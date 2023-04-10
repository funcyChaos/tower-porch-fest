<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package towerpf-site
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="footer-content-wrapper">

		<h2 class="heading2"> TOWER PORCHFEST </h2>
            <!-- social media icons -->
            <?php
            $args = array(
                'post_type' => 'socials',
                'post_status' => 'private',
                'posts_per_page' => -1
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="social-icons">
                        <a href="<?php echo the_field('facebook_url'); ?>" target="_blank"><i role="link"class="fab fa-facebook-f fa-xl"></i></a>
                        <a href="<?php echo the_field('instagram_url'); ?>" target="_blank"><i role="link" class="fab fa-instagram fa-xl"></i></a>
                        <!-- <a href="<?php echo the_field('twitter_url'); ?>" target="_blank"><i role="link" class="fab fa-twitter fa-xl"></i></a> -->
                        <!-- <a href="<?php echo the_field('youtube_url'); ?>" target="_blank"><i role="link" class="fab fa-youtube fa-xl"></i></a> -->
                    </div>
            <?php endwhile;
                wp_reset_postdata();
                    else :
                    echo __( 'No socials found', 'textdomain' );
                endif;
            ?>
            
            <!-- nav menu items -->
            <?php
                wp_nav_menu(array(
                    'menu' => 'Nav Menu',
                    'theme_location' => 'footer-menu',
                    'menu_class' => 'footer-menu',
                    'menu_id' => 'footer-id'
                ))
            ?>
            <!-- end -->
            <p class="copyright"> Copyright &#169; Tower Porchfest 2023 </p>
            </div>
		</div>
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
       integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
       crossorigin="anonymous"></script>
        
       <script>
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
           var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
           return new bootstrap.Tooltip(tooltipTriggerEl)})
       </script>

</body>
</html>
