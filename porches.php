<?php
/**
 * Template Name: Porches
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>
<div class="background">
<h2 class="porch">Porches</h2>
<p class="paragraph">Plan your day by exploring porch lineups. Listen to the bands ahead of time write down their porches. The organizers and performers are not paid, so we encourage all attendees to have cash to tip performers or purchase swag to help support the event.</p>
</div>
<section class="porchespost">

<?php 
    $args = new WP_Query (array(
        'posts_per_page' => -1,
        'post_type' => 'porch'
        
    ));
    
    
    while($args->have_posts()){
        $args->the_post();?>
        
        <div class="background">
         <div class="porchcard" id="<?php echo "card_". get_the_id();?>">
            <h2 class="porchheading" > <?php the_field('porch_name'); ?> </h2>
            <p class="porchparagraph"> <?php the_field('porch_address'); ?> </p>
            <img class="porchimage" src="<?php the_field('porch_logo'); ?>" alt="" />
            <p class="porchDescription"> <?php the_field('description'); ?> </p>
            <div class="porchlinks"> 

            <p>categories here</p>
            <p class="textoncard"><?php the_field('text_for_card'); ?></p>
            <p class="timeoncard"><?php the_field('time_1'); ?> - <?php the_field('time_2'); ?></p>
            <a href="#" class="button7">SEE LINEUP</a>

            </div>
            
            </div>
        </div>
    <?php }
?>
</section>


<?php get_footer(); ?>