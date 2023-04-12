<?php
/**
 * The template for displaying the porch archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package towerpf-site
 */
?>

<?php get_header(); ?>
<div class="porchesarchiveHeading">
    <h1>Porches</h1>
    <p>Plan your day by exploring porch lineups. Listen to the bands ahead of time write down their porches. The organizers and performers are not paid, so we encourage all attendees to have cash to tip performers or purchase swag to help support the event.</p>
</div>
<section class="porchesarchivesContainer">
	<section class="porchesposts">
		<?php
			$default_image = get_the_post_thumbnail(5, 'medium');
			while(have_posts()){
				the_post();
				?>
					<div class="porchcard" id="<?php echo "card_". get_the_id();?>">
						<h2 class="porchheading" ><?=the_title()?></h2>
						<p class="porchparagraph"><?php the_field('porch_address'); ?></p>
						<?php 
							if(has_post_thumbnail()){
								the_post_thumbnail('medium');
							}else{
								echo $default_image;
							}
						?>
						<div class="porchlinks"> 
							<a href=""><?php the_field('tag_one');?></a>
							<a href=""><?php the_field('tag_two');?></a>
							<a href=""><?php the_field('tag_three');?></a>
						</div>
						<p class="porchDescription"><?=get_the_excerpt()?></p>
						<a href="<?=the_permalink()?>" class="button7">SEE LINEUP</a>
					</div>
				<?php 
			}
		?>
	</section>
</section>

<?php get_footer(); ?>