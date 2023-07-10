<?php
	get_header();
	$posts = new WP_Query([
		'post_type'				=> 'porch',
		'post_status'			=> 'publish',
		'posts_per_page'	=> -1
	]);
	$performances = [];
	while($posts->have_posts()){
		$posts->the_post();
		for($i = 1; $i < 13; $i++){
			$pfmr = get_field("performer_{$i}");
			if(!empty($pfmr['performer'])){
				$after = strtotime($pfmr['start_time']);
				$start = str_replace(' ', '', $pfmr['start_time']);
				$end	 = str_replace(' ', '', $pfmr['end_time']);
				$performances[$after][] = [
					'pfmr'	=> get_the_title($pfmr['performer']->ID),
					'porch'	=> get_the_title(),
					'slot'	=> "{$start}-{$end}",
				];
			}else{break;}
		}
	}
	wp_reset_query();
	ksort($performances);
	?>
<div class="pfmcs-table-container">
	<table>
		<thead>
			<tr>
				<th>After</th>
				<th>Performer</th>
				<th>Porch</th>
				<th>Time Slot</th>
				<th>Adjust Itinerary</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$btn_id = 0;
				foreach($performances as $start => $pfmrs){
					$time = date('ga', $start);
					$count = count($pfmrs);
					$th		 = true;
					foreach($pfmrs as $pfmr){
						if($th){
							?><tr><th rowspan="<?=$count?>" scope="rowgroup"><?=$time?></th><?php
							$th = false;
						}else{
							?><tr><?php
						}
						foreach($pfmr as $detail){
							?><td><?=$detail?></td><?php
						}
						$added = false;
						$loggedIn = is_user_logged_in();
						if($loggedIn){
							$itinerary = get_user_meta(get_current_user_id(), 'itinerary', true);
							if($itinerary){
								foreach($itinerary as $entry){
									if($entry == $pfmr)$added = true;
								}
							}
						}
						?>
								<td>
									<?php
									if($loggedIn){
										if($added){
											?>
												<button id="btn_<?=$btn_id++?>" data-tgl="rmv" onclick='tglItn(<?=json_encode($pfmr)?>, this)'>Remove</button>
												<script>

												</script>
											<?php
										}else{
											?>
												<button id="btn_<?=$btn_id++?>" data-tgl="add" onclick='tglItn(<?=json_encode($pfmr)?>, this)'>Add</button>
											<?php
										}
									}else{
										?>Log In<?php
									}
									?>
								</td>
								<td><a href="/map#<?=$pfmr['porch'];?>">See on Map</a></td>
							</tr>
						<?php
					}
				}
			?>
		</tbody>
	</table>
</div>
<script>
	console.log("User ID: ", <?=get_current_user_id()?>)
	console.log("User Meta: ", <?=json_encode(get_user_meta(get_current_user_id(), 'itinerary', true))?>)
	function tglItn(performance, btn){
		const tgl = btn.dataset.tgl
		if(tgl == 'add'){
			fetch('<?=home_url()?>/wp-json/itinerary/v1/add-to-itinerary',{
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce':		'<?=wp_create_nonce('wp_rest')?>',
				},
				body: JSON.stringify({'to_add': performance}),
			})
			.then(res=>res.json())
			.then(obj=>{
				console.log(obj)
				if(obj.res == 'success'){
					btn.dataset.tgl = 'rmv'
					btn.innerText		= 'Remove'
				}
			})
		}else{
			fetch('<?=home_url()?>/wp-json/itinerary/v1/remove-from-itinerary',{
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce':		'<?=wp_create_nonce('wp_rest')?>',
				},
				body: JSON.stringify({'to_remove': performance}),
			})
			.then(res=>res.json())
			.then(obj=>{
				console.log(obj)
				if(obj.res == 'success'){
					btn.dataset.tgl	= 'add'
					btn.innerText		= 'Add'
				}
			})
		}
	}
</script>
<?php get_footer();