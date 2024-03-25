<?php

add_action('rest_api_init', function(){
	register_rest_route('itinerary/v1', '/add-to-itinerary', [
		[
			'methods'	=> 'POST',
			'callback'	=> function (WP_REST_Request $req){
				$toAdd = $req->get_param('to_add');
				$after = $toAdd['after'];
				unset($toAdd['after']);
				$currentItinerary = get_user_meta(get_current_user_id(), 'itinerary', true);
				if($currentItinerary){
					$currentItinerary[$after][] = $toAdd;
					update_user_meta(get_current_user_id(), 'itinerary', $currentItinerary);
				}else{
					$currentItinerary = [$after => [$toAdd]];
					update_user_meta(get_current_user_id(), 'itinerary', $currentItinerary);
				}
				return [
					'res'				=> 'success',
					'add'				=> $req->get_param('to_add'),
					'current'		=> $currentItinerary,
				];
			},
			'permission_callback' => function(){
				return current_user_can('read');
			}
		]
	]);
	register_rest_route('itinerary/v1', '/remove-from-itinerary', [
		[
			'methods'	=> 'POST',
			'callback'	=> function (WP_REST_Request $req){
				$toRemove = $req->get_param('to_remove');
				$currentItinerary = get_user_meta(get_current_user_id(), 'itinerary', true);
				foreach($currentItinerary as $start => $pfmrs){
					foreach($pfmrs as $key => $pfmr){
						if($pfmr == $toRemove){
							unset($currentItinerary[$start][$key]);
						}	
					}
				}
				update_user_meta(get_current_user_id(), 'itinerary', $currentItinerary);
				return [
					'res'				=> 'success',
					'rmv'				=> $req->get_param('to_remove'),
					'current'		=> $currentItinerary,
				];
			},
			'permission_callback' => function(){
				return current_user_can('read');
			}
		]
	]);
});