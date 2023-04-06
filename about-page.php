<?php
/**
 * Template Name: About Us
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>

<div class="aboutbackground">

    <img class="aboutbanner" src="<?php the_field('about_banner'); ?>" alt="">
    
    <img class="aboutbanner2" src="<?php the_field('about_banner_2'); ?>" alt="">

    <h2 class="aboutheading1"><?php the_field('about_heading_1'); ?></h2>

    <h4 class="aboutheading2"><?php the_field('about_heading_2'); ?></h4>
    <p class="aboutpara1"><?php the_field('about_paragraph_1'); ?></p>

    <h4 class="aboutheading3"><?php the_field('about_heading_3'); ?></h4>
    <p class="aboutpara2"><?php the_field('about_paragraph_2'); ?></p>
    <p class="aboutpara3"><?php the_field('about_paragraph_3'); ?></p>

    <h4 class="aboutheading4"><?php the_field('about_heading_4'); ?></h4>
    <p class="aboutpara4"><?php the_field('about_paragraph_4'); ?></p>

    <h4 class="aboutheading5"><?php the_field('about_heading_5'); ?></h4>
    <p class="aboutpara5"><?php the_field('about_paragraph_5'); ?></p>
    <p class="aboutpara6"><?php the_field('about_paragraph_6'); ?></p>

    <h4 class="aboutheading6"><?php the_field('about_heading_6'); ?></h4>
    <p class="aboutpara7"><?php the_field('about_paragraph_7'); ?></p>

    <h4 class="aboutheading7"><?php the_field('about_heading_7'); ?></h4>
    <p class="aboutpara8"><?php the_field('about_paragraph_8'); ?></p>

    <h4 class="aboutheading8"><?php the_field('about_heading_8'); ?></h4>
    <p class="aboutpara9"><?php the_field('about_paragraph_9'); ?></p>

</div>


<?php get_footer(); ?>