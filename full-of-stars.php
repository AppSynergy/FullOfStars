<?php
/*
Plugin Name: Full Of Stars
Plugin URL: http://appsynergy.net/
Description: Allows theme developers to implement a star rating system.
Author: Adam Marshall <adam@appsynergy.net>
Version: 0.1
Author URI: http://appsynergy.net/
*/
require dirname(__FILE__) . '/src/AppSynergy/ApiProvider.php';
require dirname(__FILE__) . '/src/AppSynergy/DatabaseProvider.php';

$db = new AppSynergy\DatabaseProvider;
$api = new AppSynergy\ApiProvider($db);

register_activation_hook( __FILE__, [$db, 'install'] );

add_action('rest_api_init', function () use ($api) {
	$api->register_routes();
});

function get_ratings_summary() {
	return AppSynergy\DatabaseProvider::getRatingsSummary(get_the_ID());
}
