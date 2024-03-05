<?php

add_action('rest_api_init', function(){
	register_rest_route('porches/v1', '/adds', [
		[
			'methods'	=> 'POST',
			'callback'	=> function(WP_REST_Request $req){
				update_field('longitude', $req->get_param('lon'), $req->get_param('id'));
				update_field('latitude', $req->get_param('lat'), $req->get_param('id'));
				return [
					'response'		=> 'POST successful',
					'id'			=> $req->get_param('id'),
				];
			},
			'permission_callback' => '__return_true',	
		]
	]);
});

add_action('save_post_porch', function($post_id, $post, $updating){
	$key = map_api_key;
	$addy = get_field('porch_address', $post_id);
	$url = urlencode("https://maps.googleapis.com/maps/api/geocode/json?key={$key}");
	$geocode = json_decode(file_get_contents($url));
	// $geocode = json_decode(file_get_contents(
	// 	"https://maps.googleapis.com",
	// ));
	update_option('test', $geocode);
}, 10, 3);