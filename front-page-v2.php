<?php
/**
 * Template Name: Homepage v2
 *
 * @package towerpf-site
 *
 */
?>
<?php get_header(); ?>

<?php
if ( has_post_thumbnail() ) {
  /* grabs featured image */
  $image_url = get_the_post_thumbnail_url();
  echo '<div class="featured-image" style="background-image: url(' . $image_url . ');">';
  echo '</div>';
}
?>
<h1>ANTONIOOOO</h1>