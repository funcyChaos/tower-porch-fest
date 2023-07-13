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
				'after'	=> $after,
				'pfmr'	=> html_entity_decode(get_the_title($pfmr['performer']->ID)),
				'porch'	=> get_the_title(),
				'slot'	=> "{$start}-{$end}",
			];
		}else{break;}
	}
}
wp_reset_query();
ksort($performances);
$loggedIn = is_user_logged_in();
$itinerary = '';
if($loggedIn){
	$itinerary = get_user_meta(get_current_user_id(), 'itinerary', true);
	if($itinerary){
		ksort($itinerary);
	}
}
?>
<div class="pfmcs-table-container">
	<div class="menu">
		<button id="performances_button">Performances</button>
		<button id="itinerary_button">Itinerary</button>
	</div>

	<table class="hide" id="itinerary_table">
		<thead>
			<tr>
			<?php
				if($loggedIn){
					?>
						<th>After</th>
						<th>Performer</th>
						<th>Porch</th>
						<th>Time Slot</th>
						<th>Itinerary</th>
					<?php
				}else{
					?><th>Log In for Itinerary</th><?php
				}
			?>
			</tr>
		</thead>
		<tbody>
			<?php
			if($itinerary){
				foreach($itinerary as $start => $pfmrs){
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
						foreach($pfmr as $key => $detail){
							if($key == 'after')continue;
							if($key == 'porch'){
								?>
									<td><a href="/map#<?=$pfmr['porch'];?>"><?=$detail?></a></td>
								<?php
							}else{
								?><td><?=$detail?></td><?php
							}
						}
						?>
								<td>
									<button data-tgl="rmv" onclick='tglItn(<?=json_encode($pfmr)?>, this)'>Remove</button>
								</td>
							</tr>
						<?php
					}
				}
			}
			?>
		</tbody>
	</table>

	<table class="hide" id="performances_table">
		<thead>
			<tr>
				<th>After</th>
				<th>Performer</th>
				<th>Porch</th>
				<th>Time Slot</th>
				<th><?=$loggedIn ? 'Itinerary' : 'Log In for Itinerary'?></th>
			</tr>
		</thead>
		<tbody>
			<?php
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
						foreach($pfmr as $key => $detail){
							if($key == 'after')continue;
							if($key == 'porch'){
								?>
									<td><a href="/map#<?=$pfmr['porch'];?>"><?=$detail?></a></td>
								<?php
							}else{
								?><td><?=$detail?></td><?php
							}
						}
						$added = false;
						$loggedIn = is_user_logged_in();
						if($loggedIn){
							$itinerary = get_user_meta(get_current_user_id(), 'itinerary', true);
							$toCheck = $pfmr;
							unset($toCheck['after']);
							if($itinerary){
								foreach($itinerary as $entry){
									foreach($entry as $ePfmr){
										if($ePfmr == $toCheck){
											$added = true;
											break;
										}
									}
								}
							}
						}
						?>
								<td>
									<?php
									if($loggedIn){
										if($added){
											?>
												<button data-tgl="rmv" onclick='tglItn(<?=json_encode($toCheck)?>, this)'>Remove</button>
											<?php
										}else{
											?>
												<button data-tgl="add" onclick='tglItn(<?=json_encode($pfmr)?>, this)'>Add</button>
											<?php
										}
									}else{
										?>Log In to Add<?php
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
					// btn.dataset.tgl = 'rmv'
					// btn.innerText		= 'Remove'
					location.reload()
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
					// btn.dataset.tgl	= 'add'
					// btn.innerText		= 'Add'
					location.reload()
				}
			})
		}
	}

	const pfmrTable = document.getElementById('performances_table')
	const itnTable	= document.getElementById('itinerary_table')
	const pfmrBtn		= document.getElementById('performances_button')
	const itnBtn		= document.getElementById('itinerary_button')

	pfmrBtn.addEventListener('click', ()=>{
		itnTable.classList.add('hide')
		pfmrTable.classList.remove('hide')
		pfmrBtn.classList.add('active')
		itnBtn.classList.remove('active')
		window.location.hash = ''
	})

	itnBtn.addEventListener('click', ()=>{
		pfmrTable.classList.add('hide')
		itnTable.classList.remove('hide')
		itnBtn.classList.add('active')
		pfmrBtn.classList.remove('active')
		window.location.hash = 'itinerary'
	})

	document.addEventListener('DOMContentLoaded', ()=>{
		const hash = window.location.hash
		if(hash){
			if(hash == '#itinerary'){
				itnTable.classList.remove('hide')
				itnBtn.classList.add('active')
			}else{
				pfmrTable.classList.remove('hide')
				pfmrBtn.classList.add('active')
			}
		}else{
			pfmrTable.classList.remove('hide')
			pfmrBtn.classList.add('active')
		}
	})
</script>
<?php get_footer();