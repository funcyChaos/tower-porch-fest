<?php
/**
 * Template Name: Front Page v2
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>
<main class="front-page-styles">
  <?php
  if ( has_post_thumbnail() ) {
    /* grabs featured image */
    $image_url = get_the_post_thumbnail_url();
    echo '<div class="featured-image" style="background-image: url(' . $image_url . ');">';
    echo '</div>';
  }
  ?>
  <div class="polygon-wrapper">
    <div class="rectangle"></div>
    <div class="triangle"></div>
  </div>

  <!-- <div class="house-shape"></div> -->

</main>
<!-- Footer must be included for navigation to display -->
<?php get_footer(); ?>