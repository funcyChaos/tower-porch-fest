<section class="porchesarchivesContainer">
	<section class="porchescardcontainer">
		<?php
			$default_image = get_the_post_thumbnail(5, 'medium');
			?><script>
				class FetchQueue {
					constructor() {
						this.queue = [];
						this.isProcessing = false;
					}

					add(fetchPromise) {
						this.queue.push(fetchPromise);
						if (!this.isProcessing) {
							this.processQueue();
						}
					}

					async processQueue() {
						if (this.queue.length > 0) {
							this.isProcessing = true;
							const fetchPromise = this.queue.shift();
							try {
								await fetchPromise();
							} catch (error) {
								console.error('Error in fetch:', error);
							}
							this.processQueue();
						} else {
							this.isProcessing = false;
						}
					}
				}

				const fetchQueue = new FetchQueue();

				async function updateCoords(address, id){
					const coords = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=<?=map_api_key?>`)
					const cData		= await coords.json()
					const cObject	= await cData
					const lon = cObject.results[0].geometry.location.lng
					const lat = cObject.results[0].geometry.location.lat
					const update = await fetch('/wp-json/porches/v1/adds', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/JSON',
						},
						body: JSON.stringify({
							id,
							lon,
							lat,
						}),
					})
					const uData = await update.json()
					const uObject = await uData
					console.log(uObject, cObject)
				}
			</script><?php
			while(have_posts()){
				the_post();
				?>
					<script>
						fetchQueue.add(()=>updateCoords("<?php the_field('porch_address');?>", <?=get_the_id()?>))
					</script>
					
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