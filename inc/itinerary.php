<?php

add_action('rest_api_init', function(){
	register_rest_route('itinerary/v1', '/add-to-itinerary', [
		[
			'methods'	=> 'POST',
			'callback'	=> function (WP_REST_Request $req){
				$toAdd = $req->get_param('to_add');
				$currentItinerary = get_user_meta(get_current_user_id(), 'itinerary', false);
				$currentItinerary[] = $toAdd;
				update_user_meta(get_current_user_id(), 'itinerary', $currentItinerary);
				return [
					'response'	=> 'POST successful',
					'request'		=> $req['param'],
					'param'			=> $req->get_param('to_add'),
					'nonce'			=> $req->get_header('X-WP-Nonce'),
					'current'		=> $currentItinerary,
				];
			},
			'permission_callback' => function(){
				return current_user_can('subscriber');
			}
		]
	]);
});