<?php
/**
 * Template Name: Porches
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>

<section class="porchespost">

<?php 
    $args = new WP_Query (array(
        'posts_per_page' => -1,
        'post_type' => 'porch'
        
    ));
    
    
    while($args->have_posts()){
        $args->the_post();?>
         <div class="porchcard" id="<?php echo "card_". get_the_id();?>">
            <h2 class="" > <?php the_field('porch_name'); ?> </h2>
            <p class=""> <?php the_field('porch_address'); ?> </p>
            <img src="<?php the_field('porch_image'); ?>" alt="" />

            <div class="porchlinks"> 

            <p><?php the_field('typegenre'); ?></p>

            </div>
            
            </div>
    <?php }
?>
</section>


<?php get_footer(); ?>