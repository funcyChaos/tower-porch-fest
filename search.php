<?php

	get_header();
	?>
		<div class="search-container">
			<?php
				get_search_form();
			?>
			<h1>Results For: <?=get_search_query()?></h1>
		</div>
		<section class="porchesarchivesContainer">
			<section class="porchescardcontainer">
				<?php
					$default_image = get_the_post_thumbnail(5, 'medium');
					while(have_posts()){
						the_post();
						?>
							<div class="porchcard" id="<?php echo "card_". get_the_id();?>">
								<div class="heading">
									<h2 class="porchheading" ><?=the_title()?></h2>
									<p class="porchparagraph"><?php the_field('porch_address');?></p>
								</div>
								<div class="content">
									<?php 
										if(has_post_thumbnail()){
											the_post_thumbnail('medium');
										}else{
											echo $default_image;
										}
									?>
									<div class="porchlinks">
										<a href=""><?php the_field('tag_one');?></a>
										<a href=""><?php the_field('tag_two');?></a>
										<a href=""><?php the_field('tag_three');?></a>
									</div>
									<p class="porchDescription"><?=get_the_excerpt()?></p>
								</div>
								<a href="<?=the_permalink()?>/#band_lineup" class="button7">SEE LINEUP</a>
							</div>
						<?php 
					}
				?>
			</section>
		</section>

	<?php
	get_footer();
?>