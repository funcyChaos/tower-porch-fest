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
		<section class="porch-content">
			<h2 class="porchheading"><?php the_title();?></h2>
			<div class="singleporchImgContainer">
			<?php 
				$imgURL = has_post_thumbnail() ? get_the_post_thumbnail_url() : get_the_post_thumbnail_url(5);
			?>
				<img class="porchimage" src="<?=$imgURL?>" alt="Picture of Porch"/>
			</div>
			<div class="singleporchContentContainer">
				<p class="porchaddress"><?php the_field('genre');?></p>
				<?php $content = wp_strip_all_tags(get_the_content());?>
				<div class="the-content">
					<?php the_content();?>
					<a target="_blank" href="<?php the_field('social_media');?>">Social Link!</a>
				</div>
			</div>
		</section>
	</div>		
	<?php
}
get_sidebar();
get_footer();
