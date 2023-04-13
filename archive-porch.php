<?php
/**
 * The template for displaying the porch archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package towerpf-site
 */
?>

<?php get_header(); ?>
<div class="porchesarchiveHeading">
    <h1>Porches</h1>
    <p>Plan your day by exploring porch lineups. Listen to the bands ahead of time write down their porches. The organizers and performers are not paid, so we encourage all attendees to have cash to tip performers or purchase swag to help support the event.</p>
</div>
<section class="porchesarchivesContainer">
    <section class="porchesposts">
        <?php
            while(have_posts()){
                the_post();?>

                <div class="porchcard" id="<?php echo "card_". get_the_id();?>">
                    <h2 class="porchheading" > <?php the_field('porch_name'); ?> </h2>
                    <p class="porchparagraph"> <?php the_field('porch_address'); ?> </p>
                    <img class="porchimage" src="<?php the_field('porch_image'); ?>" alt="" />
                    <div class="porchlinks"> 
                        <a href=""><?php the_field('genre'); ?></a>
                        <a href=""><?php the_field('tag_one'); ?></a>
                        <a href=""><?php the_field('tag_two'); ?></a>
                    </div>
                    <p class="porchDescription"> <?php the_field('description'); ?> </p>
                
                    <p class="timeoncard"><?php the_field('performance_time'); ?> <?php the_field('time_2'); ?></p>
                    <a href="#" class="button7">SEE LINEUP</a>
                    
                </div>

                
            
            <?php }
        ?>
        </section>
</section>


<?php get_footer(); ?>