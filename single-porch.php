<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package towerpf-site
 */

get_header();

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
				if(get_field('performer_1')){
					for($i=1; $i < 13; $i++){
						$band = get_field("performer_{$i}");
						if(!$band['performer'])break;
						$genres = get_field('genre', $band['performer']);
						?>
							<div class="band-card">
								<div class="TagContent">
									<a href="#" class="singleButton"><?=$band['start_time']?> - <?=$band['end_time']?></a>
									<h2 class="tagHeading"><?=get_the_title($band['performer']);?></h2>
									<p class="tag">
										<?php
											if(is_array($genres)){
												$genreCount = count($genres);
												for($g=0; $g < $genreCount; $g++){ 
													if($g == $genreCount - 1){
														echo $genres[$g];
													}else{
														echo $genres[$g] . ', ';
													}
												}
											}
										?>
									</p>
								</div>
								<p class="porchDescription"><?=get_the_content(null, false, $band['performer']);?></p>
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
