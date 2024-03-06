<?php

add_action('init', function(){
	register_post_type('porch', array(
		'public'        	=> true,
		'labels'					=> [
			'name'					=> 'Porches',
			'singular_name'	=> 'Porch',
			'add_new'				=> 'Add Porch!',
			'add_new_item'	=> 'Add Porch!',
		],
		'menu_icon'     	=> 'dashicons-admin-home',
		'menu_position' 	=> 2,
		'has_archive'			=> 'porches',
		'rewrite'					=> true,
		'show_in_rest'		=> true,
		'rest_base'				=> 'porches',
		'supports'				=> ['title', 'editor', 'thumbnail', 'excerpt', 'author'],
		'taxonomies' 			=> ['category'],
		'capabilities'		=> [
			'edit_post'						=> 'edit_porch',
			'edit_posts'					=> 'edit_porches',
			'edit_others_posts'		=> 'edit_others_porches',
			'publish_posts'				=> 'publish_porches',
			'read_post'						=> 'read_porch',
			'read_private_posts'	=> 'read_private_porches',
			'delete_posts'				=> 'delete_porches',
		],
		'map_meta_cap'		=> true,
	));
});

add_action('rest_api_init', function(){
	register_rest_field('porch', 'acff', [
		'get_callback' => function($object){
			return get_fields($object['id']);
		},
		'update_callback' => null,
		'schema' => null,
	]);
});

add_filter('block_editor_settings_all', function($editor_settings){
	$screen = get_current_screen();
	if('porch' == $screen->post_type){
		$editor_settings['bodyPlaceholder']='Describe your porch here, have fun with it!';
	}
	return $editor_settings;
});

add_action('wp_login', function($user_login, $user){
	if(in_array('um_porch-operator', $user->roles)){
		exit(wp_redirect('wp-admin/admin.php?page=start-here'));
	}
},10,2);

add_filter('enter_title_here', function($title){
	$screen = get_current_screen();
	if('porch' == $screen->post_type){
		$title = 'Enter your porch name here';
	}
	return $title;
});

add_action('admin_footer', function(){
	if(did_action('wp_enqueue_media')){
		?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					wp.media.controller.Library.prototype.defaults.contentUserSetting = false
					wp.media.controller.FeaturedImage.prototype.defaults.contentUserSetting = false
				})
			</script>
		<?php
	}
});

add_action('add_meta_boxes', function(){
	add_meta_box('instructionsdiv', 'Porch Instructions', function(){
		?>
			<p style="font-size: larger;">Complete this form to create/edit your porch entry. When you are ready to publish send an email to <a href="mailto:towerporchinfo@gmail.com">towerporchinfo@gmail.com</a>.</p>
			<p>Add up to 12 performers. If your performer is not in the dropdown, head to the porches pages <a href="<?=site_url();?>/wp-admin/edit.php?post_type=performer">here</a> to create a new performer.</p>
		<?php
	}, 'porch', 'normal', 'high');

	add_meta_box('postimagediv', 'Picture of your porch', function($post){
		add_filter('admin_post_thumbnail_size', function(){return 'full';}, 10, 3);		
		$thumbnail_id = get_post_meta($post->ID, '_thumbnail_id', true);
		echo nl2br("Set the Featured Image for your porch! \n \n Recommended dimensions are 728px by 90px." );
		echo _wp_post_thumbnail_html($thumbnail_id, $post->ID);
	}, 'porch', 'normal', 'high');

	add_meta_box('postimagediv', 'Featured image!', function($post){
		add_filter('admin_post_thumbnail_size', function(){return 'full';}, 10, 3);		
		$thumbnail_id = get_post_meta($post->ID, '_thumbnail_id', true);
		echo nl2br("Set a Featured Image! \n \n Recommended dimensions are 728px by 90px." );
		echo _wp_post_thumbnail_html($thumbnail_id, $post->ID);
	}, 'performer', 'normal', 'high');
}, 1);

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

add_action('save_post', function($post_id, $obj, $updating){
	if(get_post_type($post_id) == 'porch' && $updating){
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
}, 10, 3);

function updatePorchesTemp(){
	?><script>
		class FetchQueue{
			constructor(){
				this.queue = []
				this.isProcessing = false
			}

			add(fetchPromise){
				this.queue.push(fetchPromise)
				if(!this.isProcessing){
					this.processQueue()
				}
			}

			async processQueue(){
				if(this.queue.length > 0){
					this.isProcessing = true
					const fetchPromise = this.queue.shift()
					try{
						await fetchPromise()
					}catch(error){
						console.error('Error in fetch:', error)
					}
					this.processQueue()
				}else{
					this.isProcessing = false
				}
			}
		}

		const fetchQueue = new FetchQueue()

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