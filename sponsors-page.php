<?php
/**
 * Template Name: Sponsors
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>

<div class="sponbackground">

    <img class="sponbanner" src="<?php the_field('banner'); ?>" alt="">
    
    <img class="sponbanner2" src="<?php the_field('banner_2'); ?>" alt="">

    <h2 class="sponheading1"><?php the_field('sponsor_heading_1'); ?></h2>
    <p class="sponpara1"><?php the_field('sponsor_paragraph_1'); ?></p>
    <p class="sponpara2"><?php the_field('sponsor_paragraph_2'); ?></p>

    <h2 class="sponheading2"><?php the_field('sponsor_heading_2'); ?></h2>
    <p class="sponpara3"><?php the_field('sponsor_paragraph_3'); ?></p>
    <p class="sponpara4"><?php the_field('sponsor_paragraph_4'); ?></p>
    <p class="sponpara5"><?php the_field('sponsor_paragraph_5'); ?></p>
    <p class="sponpara6"><?php the_field('sponsor_paragraph_6'); ?></p>

    <img class="sponimg" src="<?php the_field('sponsor_image'); ?>" alt="">
    <a href="#" class="button9">SPONSOR SIGN-UP</a>

    <p class="sponpara7"><?php the_field('sponsor_paragraph_7'); ?></p>

</div>


<?php get_footer(); ?>