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
  <section class="section 1">
  <!-- light text section -->
    <div class="light-section text-and-button">
      <div class="wrapper">
        <h2>Building Community Through Music and Art</h2>
        <p>Porches throughout Fresno’s historic Tower neighborhood host performances in a day of revelry celebrating local music and art! We do this through the collaboration of Tower residents who provide venue for local musicians to perform.</p>
        <button>View Porch Map</button>
      </div>
    </div>
  <!-- three up images -->
    <div class="three-up">
      <div class="three-up-wrapper">
        <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-6-1.png" alt="">
        <div class="image-row">
          <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-7-1.png" alt="">
          <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-8.png" alt="">
        </div>
      </div>
    </div>
  </section>
  <!-- dark text section -->
  <section class="dark-section text-and-button">
    <div class="wrapper">
      <h2>Call for Porches</h2>
      <p>Do you have a porch in the Tower that would make a sweet stage? Tower Porchfest 2023 will happen on Saturday, April 29 from noon to 8 PM in Fresno’s Tower District neighborhood! Porch Host signups are open now through March 6, 2023.</p>
      <button>View All Porches</button>
    </div>
    <div class="wrapper">
      <h2>Call for Porches</h2>
      <p>Do you have a porch in the Tower that would make a sweet stage? Tower Porchfest 2023 will happen on Saturday, April 29 from noon to 8 PM in Fresno’s Tower District neighborhood! Porch Host signups are open now through March 6, 2023.</p>
      <button>View All Porches</button>
    </div>
  </section>
  <!-- Future Carousel -->
  <!-- light text section -->
  <section>
    <div class="light-section text-and-button">
      <div class="wrapper">
        <h2>Tower Porchfest Map</h2>
        <p>Tower Porchfest takes place in the Historic Tower District neighborhood in Fresno, California. Made up of an incredible array of classic housing types – ranging from granny flats, townhouses, and apartments to craftsman bungalows and mansions.</p>
        <button>Visit Map</button>
      </div>
    </div>
    <div class="map-wrapper">
      <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-6.jpg" alt="">
    </div>
  </section>
  <!-- icons + cards section -->
  <section class="container icons-cards-container">
    <div class="row">
      <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
      <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
      <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
      <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
    </div>
  </section>
<!-- Sponsors Section -->
  <section>
    <div class="light-section text-and-button">
      <div class="wrapper">
        <h2>Thank You Sponsors</h2>
        <p> We thank our sponsors who help us be the best we can be! Last year we had 60 porches with over 130 performances. There were over 5,000 views of the interactive map and our Social Media grew to 2,000+ followers (and counting). On behalf of the Tower Porchfest Committee, we ask you to consider sponsoring this awesome event!</p>
        <button>Become a Sponsor</button>
      </div>
    </div>
    <div class="sponsors">
      <div class="container">
        <div class="row">
          <div class="col-6 col-md-3 center-logo">
            <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/raging-records-logo-1.png">
          </div>
          <div class="col-6 col-md-3 center-logo">
            <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/spokeasy-logo-1.png">
          </div>
          <div class="col-6 col-md-3 center-logo">
            <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/neighborhood-thrift-1.png">
          </div>
          <div class="col-6 col-md-3 center-logo">
            <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/hi-top-coffee-1.png">
          </div>
          <div class="col-6 col-md-3 center-logo">
            <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/the-brass-unicorn-1.png">
          </div>
          <div class="col-6 col-md-3 center-logo">
            <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/blkmktplc-1.png">
          </div>
        </div>
      </div>
    </div>
  <section>
</main>
<!-- Footer must be included for navigation to display -->
<?php get_footer(); ?>