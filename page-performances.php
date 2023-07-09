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
				$after = $pfmr['start_time'][0];
				$start = str_replace(' pm', '', $pfmr['start_time']);
				$end	 = str_replace(' pm', '', $pfmr['end_time']);
				$performances[$after][] = [
					'pfmr'	=> get_the_title($pfmr['performer']->ID),
					'porch'	=> get_the_title(),
					'slot'	=> "{$start}-{$end}",
				];
			}else{break;}
		}
	}
	wp_reset_query();
?>
<div class="pfmcs-table-container">
	<table>
		<thead>
			<tr>
				<th>After</th>
				<th>Performer</th>
				<th>Porch</th>
				<th>Time Slot</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($performances as $start => $pfmrs){
					$index = $start;
					$count = count($pfmrs);
					$th		 = true;
					foreach($pfmrs as $pfmr){
						if($th){
							?><tr><th rowspan="<?=$count?>" scope="rowgroup"><?=$start?>PM</th><?php
							$th = false;
						}else{
							?><tr><?php
						}
						foreach($pfmr as $detail){
							?><td><?=$detail?></td><?php
						}
						$itinerary = get_user_meta(get_current_user_id(), 'itinerary', true);
						$added = false;
						if($itinerary){
							foreach($itinerary as $entry){
								if($entry == $pfmr)$added = true;
							}
						}
						?>
								<td><a href="/map#<?=$pfmr['porch'];?>">See on Map</a></td>
								<td>
									<?php
										if($added){
											?>
												<button onclick=''>Remove</button>
											<?php
										}else{
											?>
												<button onclick='add_to_itinerary(<?=json_encode($pfmr)?>)'>Add to Itinerary</button>
											<?php
										}
									?>
								</td>
							</tr>
						<?php
					}
				}
			?>
		</tbody>
	</table>
</div>
<script>
	function add_to_itinerary(performance){
		fetch('<?=home_url()?>/wp-json/itinerary/v1/add-to-itinerary',{
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce':		'<?=wp_create_nonce('wp_rest')?>',
			},
			body: JSON.stringify({'to_add': performance}),
		})
		.then(res=>res.json())
		.then(obj=>console.log(obj))
	}
</script>
<?php get_footer();