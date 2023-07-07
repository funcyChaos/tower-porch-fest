<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package towerpf-site
 */

get_header();
function clean($string) {
	$string = str_replace('%E2%80%99', ' ', $string); // Replaces all spaces with hyphens.
 
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 }

while(have_posts()){
	the_post();
	?>
		<section class="prochesSingle">
			<a href="/map#<?php the_title();?>" style="color: white; ">Go To Map</a>
			<div class="singleporchImgContainer">
			<?php 
				$imgURL = has_post_thumbnail() ? get_the_post_thumbnail_url() : get_the_post_thumbnail_url(5);
			?>
				<img class="porchimage" src="<?=$imgURL?>" alt="Picture of Porch"/>
			</div>
			<div class="singleporchContentContainer">
				<h2 class="porchheading"><?=the_title()?></h2>
				<p class="porchaddress"><?=the_field('porch_address')?></p>
				<?php $content = wp_strip_all_tags(get_the_content());?>
				<div class="the-content">
					<?php the_content();?>
				</div>
				<!-- <p class="porchDescription"></p> -->
				<a href="#band_lineup" class="singleButton">SEE LINEUP</a>
			</div>
		</section>
		<?php
			$categories = '';
			if(get_field('sponsored')){
				$sponsor_name = get_field('sponsor');
				$categories .= "<a href=''>{$sponsor_name}</a>";
			}
			if(get_field('has_food')){
				$food_name = get_field('food_vendor');
				$categories .= "<i class='fas fa-hamburger'></i>
				<a href=''>{$food_name}</a>";
			}
		?>
		<section class="categoriesBar">
			<?=$categories?>
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
