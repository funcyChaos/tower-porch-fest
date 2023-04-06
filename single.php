<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package towerpf-site
 */

get_header();
?>
	<section class="prochesSingle">
	
		<div class="singleporchImgContainer">
		<img class="porchimage" src="<?php the_field('porch_image'); ?>" alt="" />
		</div>
			
		<div class="singleporchContentContainer">
			<h2 class="porchheading" > <?php the_field('porch_name'); ?> </h2>
			<p class="porchaddress"> <?php the_field('porch_address'); ?> </p>
			<p class="porchDescription"> <?php the_field('description'); ?> </p>
					
			<a href="#" class="singleButton">SEE LINEUP</a>
		</div>




	</section>
	<section class="categoriesBar">
			<a href=""><?php the_field('genre'); ?></a>
			<a href=""><?php the_field('tag_one'); ?></a>
			<a href=""><?php the_field('tag_two'); ?></a>

	</section>

	<section class="singleArchivesSection" style="background:linear-gradient(to right, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('<?php the_field('category_background_image'); ?>'); background-position: center; background-size: cover; background-repeat: no-repeat; ">
	<div class="cardContainer" >
			<div class="SingleCatCard">
				<div class="TagImg">
					<img class="porchimage" src="<?php the_field('tag_image'); ?>" alt="" />
					<div class="tag">
						<p><?php the_field('genre'); ?></p>
					</div>
					
				</div>
				<div class="TagContent">
					<a href="#" class="singleButton"><?php the_field('tag_time'); ?></a>
					<h2 class="tagHeading" > <?php the_field('tag_heading'); ?> </h2>
					<p class="porchDescription"> <?php the_field('tag_description'); ?> </p>
				</div>
			</div>

			<div class="SingleCatCard">
			<div class="TagImg">
					<img class="porchimage" src="<?php the_field('tag_two_image'); ?>" alt="" />
					<div class="tag">
						<p><?php the_field('tag_one'); ?></p>
					</div>
				</div>
				<div class="TagContent">
					<a href="#" class="singleButton"><?php the_field('tag_two_time'); ?></a>
					<h2 class="tagHeading" > <?php the_field('tag_two_heading'); ?> </h2>
					<p class="porchDescription"> <?php the_field('tag_two_description'); ?> </p>
				</div>
			</div>

			<div class="SingleCatCard">
			<div class="TagImg">
					<img class="porchimage" src="<?php the_field('tag_three_image'); ?>" alt="" />
					<div class="tag">
						<p><?php the_field('tag_two'); ?></p>
					</div>
				</div>
				<div class="TagContent">
					<a href="#" class="singleButton"><?php the_field('tag_three_time'); ?></a>
					<h2 class="tagHeading" > <?php the_field('tag_three_heading'); ?> </h2>
					<p class="porchDescription"> <?php the_field('tag_three_description'); ?> </p>
				</div>
			</div>

		</div>

	</section>
<?php
get_sidebar();
get_footer();
