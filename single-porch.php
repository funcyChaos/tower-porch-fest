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
					$genre = get_field('genre', $band['performer']->ID);
					?>
						<div class="band-card">
							<div class="TagContent">
								<a href="#" class="singleButton"><?=$band['start_time']?> - <?=$band['end_time']?></a>
								<h2 class="tagHeading"><?=$band['performer']->post_title?></h2>
								<p class="tag"><?=$genre?></p>
							</div>
							<p class="porchDescription"><?=$band['performer']->post_content?></p>
						</div>
					<?php
				}
			}
			?>
			<?php
				$tagOne = get_field('tag_one');
				if($tagOne){
					?>
						<div class="SingleCatCard">
							<div class="TagImg">
								<img class="porchimage" src="<?php the_field('tag_image'); ?>" alt="" />
								<div class="tag">
									<p><?=$tagOne?></p>
								</div>
							</div>
							<div class="TagContent">
								<a href="#" class="singleButton"><?php the_field('tag_time'); ?></a>
								<h2 class="tagHeading" > <?php the_field('tag_heading');?> </h2>
								<p class="porchDescription"> <?php the_field('tag_description'); ?> </p>
							</div>
						</div>
					<?php
				}
				$tagTwo = get_field('tag_two');
				if($tagTwo){
					?>
						<div class="SingleCatCard">
						<div class="TagImg">
								<img class="porchimage" src="<?php the_field('tag_two_image');?>" alt="" />
								<div class="tag">
									<p><?=$tagTwo?></p>
								</div>
							</div>
							<div class="TagContent">
								<a href="#" class="singleButton"><?php the_field('tag_two_time');?></a>
								<h2 class="tagHeading"><?php the_field('tag_two_heading');?></h2>
								<p class="porchDescription"> <?php the_field('tag_two_description');?></p>
							</div>
						</div>
					<?php
				}
				$tagThree = get_field('tag_three');
				if($tagThree){
					?>
						<div class="SingleCatCard">
						<div class="TagImg">
								<img class="porchimage" src="<?php the_field('tag_three_image'); ?>" alt="" />
								<div class="tag">
									<p><?=$tagThree?></p>
								</div>
							</div>
							<div class="TagContent">
								<a href="#" class="singleButton"><?php the_field('tag_three_time'); ?></a>
								<h2 class="tagHeading" ><?php the_field('tag_three_heading'); ?></h2>
								<p class="porchDescription"><?php the_field('tag_three_description');?></p>
							</div>
						</div>
					<?php
				}
				?>
		</div>
	</div>		
	<?php
}
get_sidebar();
get_footer();
