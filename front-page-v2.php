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
    <div class="rectangle">
      <h2>Fresno, CA</h2>
      <p>April 29th, 2023</p>
      <p class="subtext">A free music and art community festival</p>
    </div>
    <div class="triangle"></div>
    <p class="count-down-title">Countdown</p>
    <!-- <div class="counter-wrapper">
      <p id="counter"></p>
      <ul>
        <li>Days</li>
        <li>Hours</li>
        <li>Minutes</li>
        <li>Seconds</li>
      </ul>
    </div> -->
    <div class="counter-wrapper">
      <div id="counter">
        <span id="days"></span>
        <span id="hours"></span>
        <span id="minutes"></span>
        <span id="seconds"></span>
      </div>
      <ul>
        <li>Days</li>
        <li>Hours</li>
        <li>Minutes</li>
        <li>Seconds</li>
      </ul>
    </div>
  </div>

  <!-- <div class="house-shape"></div> -->

</main>
<!-- Footer must be included for navigation to display -->
<?php get_footer(); ?>