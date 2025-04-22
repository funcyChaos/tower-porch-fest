<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package towerpf-site
 */

get_header();

function getPorchNumber($inPost){
	$count = 0;
	$posts = new WP_Query([
		'post_type'				=> 'porch',
		'post_status'			=> 'publish',
		'posts_per_page'	=> -1
	]);
	while($posts->have_posts()){
		$posts->the_post();
		$count++;
		if(get_the_ID() == $inPost){
			echo $count;
			break;
		}
	}
	wp_reset_query();
}

while(have_posts()){
	the_post();
	?>
		<section class="porch-content">
			<a class="go-to-map" href="/map#<?php the_title();?>">Go To Map</a>
			<div class="singleporchImgContainer">
			<?php 
				$imgURL = has_post_thumbnail() ? get_the_post_thumbnail_url() : get_the_post_thumbnail_url(5);
			?>
			<img class="porchimage" src="<?=$imgURL?>" alt="Picture of Porch"/>
			</div>
			<div class="singleporchContentContainer">
				<h2 class="porchheading"><?php the_title();?></h2>
				<p class="porchaddress"><?php the_field('porch_address');?></p>
				<?php $content = wp_strip_all_tags(get_the_content());?>
				<div class="the-content">
					<?php the_content();?>
				</div>
				<!-- <p class="porchDescription"></p> -->
				<a href="#band_lineup" class="singleButton">SEE LINEUP</a>
				<!-- <p>Porch number <?php //getPorchNumber(get_the_ID());?></p> -->
			</div>
		</section>
		<div class="lineup-container" id="band_lineup">
			<?php
				// $imgURL = get_field('category_background_image');
				// if(!$imgURL){
				// 	$imgURL = get_the_post_thumbnail_url(5);
				// }
			?>
		<div class="blurred-lineup-background" style="background-image: url('<?=get_the_post_thumbnail_url(5)?>');"></div>
		<div class="band-card-container">
			<?php
				$food = get_field('has_food');
				if($food){
					$foodDetails = get_field('food_vendor');
					?>
						<div class="band-card">
							<div class="TagContent">
								<a href="#" class="singleButton"><?=$foodDetails['start_time']?> - <?=$foodDetails['end_time']?></a>
								<h2 class="tagHeading"><?=$foodDetails['food_name']?></h2>
							</div>
							<p class="porchDescription"><?=$foodDetails['description']?></p>
						</div>
					<?php
				}
				$sponsor = get_field('sponsor_name');
				if($sponsor){
					?>
						<div class="band-card">
							<div class="TagContent">
								<h2 class="tagHeading">Sponsored by <?=$sponsor?></h2>
							</div>
						</div>
					<?php
				}

			if(have_rows("performer_lineup")){
				while(have_rows('performer_lineup')){
					the_row();
					$performer = get_sub_field("performer");
					?>
						<div class="band-card">
							<div class="TagContent">
								<?php
									$th_starts = date("h:i A", strtotime(get_sub_field("start_time")));
									$th_ends = date("h:i A", strtotime(get_sub_field("end_time")));
								?>
								<a href="#" class="singleButton"><?=$th_starts?> - <?=$th_ends?></a>
								<h2 class="tagHeading"><?=get_the_title($performer->ID);?></h2>
									<p class="tag">
										<?php
											foreach(get_field("genre", $performer->ID) as $value){
												echo $value . " ";
											}
										?>
									</p>
								</div>
							<p class="porchDescription"><?=get_the_content(null, false, $performer->ID);?></p>
						</div>
					<?php
				}
			}
			?>
		</div>
	</div>		
	<?php
}
get_sidebar();
get_footer();
