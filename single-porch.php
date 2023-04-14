<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package towerpf-site
 */

get_header();

while(have_posts()){
	the_post();
	?>
		<section class="prochesSingle">
			<div class="singleporchImgContainer">
			<?php 
				$imgURL = has_post_thumbnail() ? get_the_post_thumbnail_url() : get_the_post_thumbnail_url(5);
			?>
				<img class="porchimage" src="<?=$imgURL?>" alt="Picture of Porch"/>
			</div>
			<div class="singleporchContentContainer">
				<h2 class="porchheading"><?=the_title()?></h2>
				<p class="porchaddress"><?=the_field('porch_address')?></p>
				<?php $content = wp_strip_all_tags(get_the_content());?>
				<p class="porchDescription"><?=$content?></p>
				<a href="#" class="singleButton">SEE LINEUP</a>
			</div>
		</section>
		<section class="categoriesBar">
			<a href=""><?php the_field('tag_one'); ?></a>
			<a href=""><?php the_field('tag_two'); ?></a>
			<a href=""><?php the_field('tag_three'); ?></a>
		</section>
		<div class="lineup-container" id="lineup_container">
		<div class="blurred-lineup-background"></div>
		<div class="band-card-container">
			<?php
				for($i=1; $i < 9; $i++){
					$band = get_field("performer_{$i}_name");
					if(!$band)continue;
					?>
						<div class="band-card">
							<div class="TagContent">
								<h2 class="tagHeading"><?=$band?></h2>
								<a href="#" class="singleButton"><?=get_field("performer_{$i}_start_time")?> - <?=get_field("performer_{$i}_end_time")?></a>
								<p class="tag"><?=get_field("performer_{$i}_tags")?></p>
							</div>
							<p class="porchDescription"><?=get_field("performer_{$i}_description")?></p>
						</div>
					<?php
				}
			?>
			<?php
				$tagOne = get_field('tag_one');
				if($tagOne){
					?>
						<div class="SingleCatCard">
							<div class="TagImg">
								<img class="porchimage" src="<?php the_field('tag_image'); ?>" alt="" />
								<div class="tag">
									<p><?=$tagOne?></p>
								</div>
							</div>
							<div class="TagContent">
								<a href="#" class="singleButton"><?php the_field('tag_time'); ?></a>
								<h2 class="tagHeading" > <?php the_field('tag_heading');?> </h2>
								<p class="porchDescription"> <?php the_field('tag_description'); ?> </p>
							</div>
						</div>
					<?php
				}
				$tagTwo = get_field('tag_two');
				if($tagTwo){
					?>
						<div class="SingleCatCard">
						<div class="TagImg">
								<img class="porchimage" src="<?php the_field('tag_two_image');?>" alt="" />
								<div class="tag">
									<p><?=$tagTwo?></p>
								</div>
							</div>
							<div class="TagContent">
								<a href="#" class="singleButton"><?php the_field('tag_two_time');?></a>
								<h2 class="tagHeading"><?php the_field('tag_two_heading');?></h2>
								<p class="porchDescription"> <?php the_field('tag_two_description');?></p>
							</div>
						</div>
					<?php
				}
				$tagThree = get_field('tag_three');
				if($tagThree){
					?>
						<div class="SingleCatCard">
						<div class="TagImg">
								<img class="porchimage" src="<?php the_field('tag_three_image'); ?>" alt="" />
								<div class="tag">
									<p><?=$tagThree?></p>
								</div>
							</div>
							<div class="TagContent">
								<a href="#" class="singleButton"><?php the_field('tag_three_time'); ?></a>
								<h2 class="tagHeading" ><?php the_field('tag_three_heading'); ?></h2>
								<p class="porchDescription"><?php the_field('tag_three_description');?></p>
							</div>
						</div>
					<?php
				}
				?>
		</div>
	</div>		
	<?php
}
get_sidebar();
get_footer();
