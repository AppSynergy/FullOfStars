<?php

namespace AppSynergy;

use AppSynergy\DatabaseProvider;

class ApiProvider extends \WP_REST_Controller
{
	public function __construct(DatabaseProvider $db)
	{
		$this->db = $db;
	}

	public function register_routes()
	{
		register_rest_route('stars', 'rating', [
			'methods' => \WP_REST_Server::READABLE,
			'callback' => [$this, 'getRatings'],

		]);
		register_rest_route('stars', 'rating', [
			'methods' => \WP_REST_Server::CREATABLE,
			'callback' => [$this, 'submitRating'],
		]);
	}

	/**
	 * Get Ratings Summary
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function getRatings($request)
	{
		$params = $request->get_params();
		if (array_key_exists('post_id', $params)) {
			$postId = (int) $params['post_id'];
			$rating = $this->db::getRatingsSummary($postId);
			return new \WP_REST_Response($rating, 200);
		}
		return new \WP_Error('api-error', 'Bad Request', ['status' => 400]);
	}

	/**
	 * Submit a new Rating
	 *
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_REST_Response
	 */
	public function submitRating($request)
	{
		$params = $request->get_params();
		$current_user = wp_get_current_user();
		if (array_key_exists('post_id', $params) && array_key_exists('rating', $params)) {
			$data = [
				'user_ID' => (int) wp_get_current_user(),
				'post_ID' => (int) $params['post_id'],
				'rating' => (int) $params['rating'],
			];
			$result = $this->db::updateRating($data);
			return new \WP_REST_Response($result, 200);
		}
		return new \WP_Error('api-error', 'Bad Request', ['status' => 400]);
	}

}
