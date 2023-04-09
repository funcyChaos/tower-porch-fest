<?php
/**
 * Template Name: Test
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>
<?php if( have_rows('front_page_group') ): ?>
    <?php while( have_rows('front_page_group') ): the_row(); ?>
    <?php if( have_rows('where_when_and_countdown_timer') ): ?>
      <?php while( have_rows('where_when_and_countdown_timer') ): the_row(); 

        // Get sub field values.
        $city_state = get_sub_field('city_state');
        $link = get_sub_field('link');

        ?>

      <main class="front-page-styles">

      <!-- Date and counter -->
      <div class="polygon-wrapper">
        <div class="rectangle">
          <h2><?php echo $city_state; ?></h2>
          <p>April 29th, 2023</p>
          <p class="subtext">A free music and art community festival</p>
        </div>
      </div>
      </main>

      <?php endwhile; ?>
<?php endif; ?>
<?php endwhile; ?>
<?php endif; ?>

<!-- Footer must be included for navigation to display -->
<?php get_footer(); ?>