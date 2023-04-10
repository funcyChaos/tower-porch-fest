<?php
/**
 * Template Name: Front Page v3
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>
<main class="front-page-styles">
  
  <!-- grabs featured image -->
  <?php
  if ( has_post_thumbnail() ) {
    $image_url = get_the_post_thumbnail_url();
    echo '<div class="featured-image" style="background-image: url(' . $image_url . ');">';
    echo '</div>';
  }
  ?>
  <!-- connects to ACF group Front Page -->
   <?php if( have_rows('front_page_group') ): ?>
    <?php while( have_rows('front_page_group') ): the_row(); ?>
    <!-- date and counter -->

      <?php if( have_rows('where_when_and_countdown_timer') ): ?>
        <?php while( have_rows('where_when_and_countdown_timer') ): the_row(); 

          // Get sub field values.
          $city_state = get_sub_field('city_state');
          $festival_date = get_sub_field('festival_date');
          $subtext = get_sub_field('subtext');
          $countdown_title = get_sub_field('countdown_title');

          // Passes the countdown to date in a variable and gives to countdown.js
          wp_localize_script( 'countdown', 'countdownData', array(
            'festivalDate' => $festival_date
          ) );
        ?>
          <div class="polygon-wrapper">
            <div class="rectangle">
              <h2><?php echo $city_state;?></h2>
              <p><?php echo $festival_date;?></p>
              <p class="subtext"><?php echo $subtext;?></p>
            </div>
            <div class="triangle"></div>
            <p class="count-down-title"><?php echo $countdown_title;?></p>
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
          <!-- polygon wrapper desktop -->
          <div class="polygon-wrapper-desktop">
            <div class="rectangle">
              <h2><?php echo $city_state;?></h2>
              <p><?php echo $festival_date;?></p>
              <p class="subtext"><?php echo $subtext;?></p>
            </div>
            <div class="triangle">
              <p class="count-down-title"><?php echo $countdown_title;?></p>
              <div class="counter-wrapper">
                <div id="counter">
                  <span id="days-desk"></span>
                  <span id="hours-desk"></span>
                  <span id="minutes-desk"></span>
                  <span id="seconds-desk"></span>
                </div>
                <ul>
                  <li>Days</li>
                  <li>Hours</li>
                  <li>Minutes</li>
                  <li>Seconds</li>
                </ul>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
  <!-- end -->
  <!-- Text and Three Up Images -->
      <?php if( have_rows('text_and_three_up_images') ): ?>
        <?php while( have_rows('text_and_three_up_images') ): the_row(); 
          // Get sub field values.
          $title = get_sub_field('title');
          $paragraph = get_sub_field('paragraph');
          $button_text = get_sub_field('button_text');
          $button_link = get_sub_field('button_link');
          $big_image = get_sub_field('big_image');
          $small_left_image = get_sub_field('small_left_image');
          $small_right_image = get_sub_field('small_right_image');
        ?>
          <section class="section-wrapper">
          <!-- light text section -->
            <div class="light-section text-and-button">
              <div class="wrapper">
                <h2><?php echo $title;?></h2>
                <p><?php echo $paragraph;?></p>
                <button><?php echo $button_text;?></button>
              </div>
            </div>
          <!-- three up images -->
            <div class="three-up">
              <div class="three-up-wrapper">
                <img src="<?php echo $big_image;?>" alt="">
                <div class="image-row">
                  <img src="<?php echo $small_left_image;?>" alt="">
                  <img src="<?php echo $small_right_image;?>" alt="">
                </div>
              </div>
            </div>
          </section>
        <?php endwhile; ?>
      <?php endif; ?>
  <!-- end -->
  <!-- Text and Carousel -->    
        <!-- dark text section -->
      <?php if( have_rows('text_and_carousel') ): ?>
        <?php while( have_rows('text_and_carousel') ): the_row(); 
          // Get sub field values.
          $title = get_sub_field('title');
          $paragraph = get_sub_field('paragraph');
          $button_text = get_sub_field('button_text');
          $button_link = get_sub_field('button_link');
        ?>  
        <section class="dark-section text-and-button">
          <div class="wrapper">
          <h2><?php echo $title;?></h2>
                <p><?php echo $paragraph;?></p>
                <button><?php echo $button_text;?></button>
          </div>
          <!-- Future Carousel -->
          <div class="wrapper">
            <h2>Placeholder for Carousel</h2>
          </div>
        </section>
        <?php endwhile; ?>
      <?php endif; ?>  
        <!-- light text section -->
        <section class="section-wrapper">
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
          <!-- <div class="row"> -->
            <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
            <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
            <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
            <? get_template_part( 'template-parts/content', 'icons-cards' ); ?>
          <!-- </div> -->
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
            <section class="logo-container">
              <img class="logo-style" src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/raging-records-logo-1.png">
              <img class="logo-style" src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/spokeasy-logo-1.png">
              <img class="logo-style" src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/neighborhood-thrift-1.png">
              <img class="logo-style" src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/the-brass-unicorn-1.png">
              <img class="logo-style" src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/blkmktplc-1.png">
              <img class="logo-style" src="http://tpf-4-6-v2.local/wp-content/uploads/2023/04/hi-top-coffee-1.png">
            </section>
      </main>


<?php endwhile; ?>
<?php endif; ?>

<!-- Footer must be included for navigation to display -->
<?php get_footer(); ?>