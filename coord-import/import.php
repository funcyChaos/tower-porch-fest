<?php 

// add_action('admin_bar_menu', function(){

// });

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