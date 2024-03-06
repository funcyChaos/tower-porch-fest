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
function updatePorchesTemp(){
	?><script>
					class FetchQueue {
						constructor() {
							this.queue = [];
							this.isProcessing = false;
						}
	
						add(fetchPromise) {
							this.queue.push(fetchPromise);
							if (!this.isProcessing) {
								this.processQueue();
							}
						}
	
						async processQueue() {
							if (this.queue.length > 0) {
								this.isProcessing = true;
								const fetchPromise = this.queue.shift();
								try {
									await fetchPromise();
								} catch (error) {
									console.error('Error in fetch:', error);
								}
								this.processQueue();
							} else {
								this.isProcessing = false;
							}
						}
					}
	
					const fetchQueue = new FetchQueue();
	
					async function updateCoords(address, id){
						const coords = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=<?=map_api_key?>`)
						const cData		= await coords.json()
						const cObject	= await cData
						const lon = cObject.results[0].geometry.location.lng
						const lat = cObject.results[0].geometry.location.lat
						const update = await fetch('/wp-json/porches/v1/adds', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/JSON',
							},
							body: JSON.stringify({
								id,
								lon,
								lat,
							}),
						})
						const uData = await update.json()
						const uObject = await uData
						console.log(uObject, cObject)
					}
				</script><?php
				while(have_posts()){
					the_post();
					?>
						<script>
							fetchQueue.add(()=>updateCoords("<?php the_field('porch_address');?>", <?=get_the_id()?>))
						</script>
					<?php
				}
}