<?php

namespace AppSynergy;

class DatabaseProvider
{

	public function install()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "star_ratings";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
				ID mediumint(9) NOT NULL AUTO_INCREMENT,
				user_ID bigint(20) UNSIGNED NOT NULL,
				post_ID bigint(20) UNSIGNED NOT NULL,
				rating tinyint(1) UNSIGNED NOT NULL,
				PRIMARY KEY  (ID),
				FOREIGN KEY (user_ID) REFERENCES {$wpdb->prefix}users (ID),
				FOREIGN KEY (post_ID) REFERENCES {$wpdb->prefix}posts (ID),
				UNIQUE KEY one_per_user (user_ID, post_ID)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	public static function updateRating($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "star_ratings";
		$result = $wpdb->update($table_name, [
			'rating' => $data['rating']
		], [
			'user_ID' => $data['user_ID'],
			'post_ID' => $data['post_ID'],
		]);
		if ($result == 0) {
			$result = $wpdb->insert($table_name, $data);
		}
		return $result;
	}

	public static function getRatings($postId)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "star_ratings";
		$result = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT rating FROM {$table_name} WHERE post_ID = %d",
				$postId
			)
		);
		return array_map('intval', $result);
	}

	public static function getRatingsSummary($postId)
	{
		$ratings = self::getRatings($postId);
		$results = [
			'ratings' => $ratings,
			'number' => count($ratings),
		];
		if (!empty($ratings)) {
			$results['average'] = array_sum($ratings) / count($ratings);
		} else {
			$results['average'] = 0;
		}
		return $results;
	}

}
