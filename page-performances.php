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
				$start = $pfmr['start_time'][0]."PM";
				$end	 = $pfmr['end_time'][0]."PM";
				if(!isset($performances["{$start}_to_{$end}"])){
					$performances["{$start}_to_{$end}"] = [];
				}
				array_push($performances["{$start}_to_{$end}"], get_The_title($pfmr['performer']->ID));
				array_push($performances["{$start}_to_{$end}"], get_the_title());
			}else{break;}
		}
		break;
	}
	print_r($performances);
	wp_reset_query();
?>
<div class="pfmcs-table-container">
	<table>
		<tbody>
			<tr>
				<th>Time Slot</th>
				<th>Performer</th>
				<th>Porch</th>
			</tr>
			<tr>
				<td>1PM to 2PM</td>
				<td>This Big Giant Band</td>
				<td>This Porch</td>
			</tr>
			<tr>
				<td>1PM to 2PM</td>
				<td>This Big Giant Band</td>
				<td>That Porch</td>
			</tr>
		</tbody>
	</table>
</div>
<?php
	get_footer();