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
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		]
	]);
});

add_action('save_post', function($post_id){
	if(get_post_type($post_id) == 'porch'){
		$key = map_api_key;
		$address = urlencode(get_field('porch_address', $post_id));
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$key}";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($response, true);
		$latitude = $data['results'][0]['geometry']['location']['lat'];
		$longitude = $data['results'][0]['geometry']['location']['lng'];
		update_field('latitude', $latitude, $post_id);
		update_field('longitude', $longitude, $post_id);
	}
}, 10, 1);