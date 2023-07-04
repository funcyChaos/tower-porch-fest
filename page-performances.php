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
							?><th rowspan="<?=$count?>" scope="rowgroup"><?=$start?>PM</th><?php
						}else{
							?><tr><?php
						}
						foreach($pfmr as $detail){
							?><td><?=$detail?></td><?php
						}
						?>
							<td>See on Map</td>
							<td>Add to Itinerary</td>
						<?php
						if($th){
							?></th><?php
							$th = false;
						}else{
							?></tr><?php
						}
					}
				}
			?>
		</tbody>
	</table>
</div>
<?php get_footer();