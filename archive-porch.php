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
	<section class="porchescardcontainer">
		<?php
			$default_image = get_the_post_thumbnail(5, 'medium');
			while(have_posts()){
				get_template_part('template-parts/content-porch');
			}
		?>
	</section>
</section>
<?php get_footer();?>