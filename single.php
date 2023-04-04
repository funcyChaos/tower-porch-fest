<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package towerpf-site
 */

get_header();
?>
	<section class="prochesSingle">
		<main id="primary" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'towerpf-site' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'towerpf-site' ) . '</span> <span class="nav-title">%title</span>',
					)
				);

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

			<a href="#" class="singleButton">SEE LINEUP</a>

		</main><!-- #main -->

	</section>
	<section class="categoriesBar">
			<a href="">INDIE</a>
			<a href="">VENDORS</a>
			<a href="">FOOD</a>

	</section>

	<section class="singleArchivesSection">
			
	</section>
<?php
get_sidebar();
get_footer();
