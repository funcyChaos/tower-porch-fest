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
								$categories = '';
								if(get_field('sponsored')){
									$sponsor_name = get_field('sponsor');
									$categories .= "<a href=''>{$sponsor_name}</a>";
								}
								if(get_field('has_food')){
									$food_name = get_field('food_vendor');
									$categories .= "<i class='fas fa-hamburger'></i>
									<a href=''>{$food_name}</a>";
								}
							?>
							<div class="porchlinks">
								<?=$categories?>
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