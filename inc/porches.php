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
			'edit_published_posts'	=> 'edit_published_porches',
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

add_action('login_enqueue_scripts', function(){
	wp_dequeue_script('user-profile');
	wp_dequeue_script('password-strength-meter');
	wp_deregister_script('user-profile');

	$suffix = SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_script( 'user-profile', "/wp-admin/js/user-profile$suffix.js", array( 'jquery', 'wp-util' ), false, 1 );
});

add_action('wp_ajax_email-porch-hosts', function(){
	if(!wp_verify_nonce($_REQUEST['nonce'], 'super_secret_code')){
		wp_send_json(['response'=>'bad nonce']);
		wp_die();
	}
	ob_start();
	?>
		<p><?=$_REQUEST['body']?></p>
	<?php
	$content = ob_get_clean();
	$users = get_users(['role'=>'um_porch-operator']);
	foreach($users as $key => $value){
		$email_success[] = wp_mail($value->data->user_email,$_REQUEST['subject'], $content, array('Content-Type: text/html; charset=UTF-8'));
	}
	if($email_success){
		wp_send_json(['response'=>'success']);
	}else{
		wp_send_json(['response'=>'failed']);
	}
	wp_die();
});

add_action('wp_ajax_nopriv_email-porch-hosts', function(){
	wp_send_json(['response'=>'nopriv']);
	wp_die();
});

add_action('admin_menu', function(){
	add_menu_page(
		'Porch Admin Tools',
		'Porch Admin Tools',
		'administrator',
		'porch-admin-tools',
		function(){
			?>
				<form id="email_porch_hosts_form" style="display:flex;flex-direction:column;padding:1rem;padding-right:2rem;">
					<label for="Email Subject">Email Subject</label>
					<input type="text" name="Email Subject" id="email_subject">
					<label for="Email Body">Email Body</label>
					<textarea name="Email Body" id="email_body" cols="30" rows="10" wrap="hard"></textarea>
					<button type="submit">Send Email</button>
				</form>
				<script>
					const emailForm = document.getElementById('email_porch_hosts_form')
					emailForm.addEventListener('submit', (e)=>{
						e.preventDefault()
						const subject = document.getElementById('email_subject').value
						const body = document.getElementById('email_body').value.replace(/\n/g, "<br />");
						fetch('<?=admin_url('admin-ajax.php')?>', {
							method: 'POST',
							headers: {
								'content-Type': 'application/x-www-form-urlencoded; charset-UTF-8'
							},
							body: `action=email-porch-hosts&nonce=<?=wp_create_nonce('super_secret_code')?>&subject=${subject}&body=${body}`,
						})
						.then(heck=>heck.json())
						.then(flubber=>console.log(flubber))
					})
				</script>
			<?php
		},
		'dashicons-admin-tools
		',
		1
	);
});

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