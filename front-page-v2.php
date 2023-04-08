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
  <section class="light-section text-and-button">
    <div class="wrapper">
      <h2>Building Community Through Music and Art</h2>
      <p>Porches throughout Fresno’s historic Tower neighborhood host performances in a day of revelry celebrating local music and art! We do this through the collaboration of Tower residents who provide venue for local musicians to perform.</p>
      <button>View Porch Map</button>
    </div>
  </section>
<!-- three up images -->
  <section class="three-up">
    <div class="three-up-wrapper">
      <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-6-1.png" alt=""></img>
      <div class="image-row">
        <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-7-1.png" alt=""></img>
        <img src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/Rectangle-8.png" alt=""></img>
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
    <!-- Porch Card for Carousel -->
    <section id="carousel-section">
  <div class="container">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <ol class="carousel-indicators">
        <?php 
          $args = array(
            'posts_per_page' => 6,
            'post_type' => 'porch',
            'post_status' => 'publish',
            'orderby' => 'rand'
          );
          $query = new WP_Query($args);
          $i = 0;
          while($query->have_posts()){
            $query->the_post(); ?>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i; ?>" <?php if($i == 0){ echo 'class="active"'; } ?>></li>
          <?php 
            $i++;
          }
          wp_reset_query();
        ?>
      </ol>
      <div class="carousel-inner">
        <?php 
          $args = array(
            'posts_per_page' => 6,
            'post_type' => 'porch',
            'post_status' => 'publish',
            'orderby' => 'rand'
          );
          $query = new WP_Query($args);
          $i = 0;
          while($query->have_posts()){
            $query->the_post(); ?>
            <div class="carousel-item <?php if($i == 0){ echo 'active'; } ?>">
              <?php the_post_thumbnail('large'); ?>
              <div class="carousel-caption d-none d-md-block">
                <h5><?php the_title(); ?></h5>
                <p><?php the_excerpt(); ?></p>
              </div>
            </div>
          <?php 
            $i++;
          }
          wp_reset_query();
        ?>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </a>
    </div>
  </div>
</section>

</main>
<!-- Footer must be included for navigation to display -->
<?php get_footer(); ?>