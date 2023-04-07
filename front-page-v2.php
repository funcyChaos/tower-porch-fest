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
  <!-- date and counter -->
  <div class="polygon-wrapper">
    <div class="rectangle">
      <h2>Fresno, CA</h2>
      <p>April 29th, 2023</p>
      <p class="subtext">A free music and art community festival</p>
    </div>
    <div class="triangle"></div>
    <p class="count-down-title">Countdown</p>
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
<!-- text section -->
  <section class="text-and-button">
    <div class="wrapper">
      <h2>Building Community Through Music and Art</h2>
      <p>Porches throughout Fresno’s historic Tower neighborhood host performances in a day of revelry celebrating local music and art! We do this through the collaboration of Tower residents who provide venue for local musicians to perform.</p>
      <button>View Porch Map</button>
    </div>
  </section>
  <section class="three-up">
    <div class="three-up-wrapper">
      <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-6-1.png" alt=""></img>
      <div class="image-row">
        <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-7-1.png" alt=""></img>
        <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-8.png" alt=""></img>
      </div>
    </div>
  </section>
</main>
<!-- Footer must be included for navigation to display -->
<?php get_footer(); ?>