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

				// Example usage:
				const fetchQueue = new FetchQueue();

				// Function to simulate a fetch request
				const mockFetch = async (url) => {
					console.log(`Fetching from ${url}...`);
					await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate fetch delay
					console.log(`Finished fetching from ${url}`);
				};

				async function updateCoords(address){
					const response1 = await fetch(url1);
					const data1 = await response1.json();

					const response2 = await fetch(url2);
					const data2 = await response2.json();

					const coords = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=<?=map_api_key?>`)
					const cData		= await coords.json()
					const cObject	= await cData
					const update = await fetch('/wp-json/porches/v1/adds', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/JSON',
						},
						body: JSON.stringify({
							id: porch.id,
							lon,
							lat,
						}),
					})
				}
			</script><?php
			while(have_posts()){
				the_post();
				?>
					<script>
		

						// Add fetches to the queue
						// fetchQueue.add(() => mockFetch('https://jsonplaceholder.typicode.com/posts/1'));
						// fetchQueue.add(() => mockFetch('https://jsonplaceholder.typicode.com/posts/2'));
						// fetchQueue.add(() => mockFetch('https://jsonplaceholder.typicode.com/posts/3'));

						function checkPorches(){
							fetch(`${wpVars.homeURL}/wp-json/wp/v2/porches?per_page=100`)
							.then(res=>res.json())
							.then(data=>{
								let interval = 1000
								data.map(porch=>{
									fetchQueue.add(()=>{
										fetch(
										`https://maps.googleapis.com/maps/api/geocode/json?address=${porch.acf.porch_address}&key=${gApi.key}`
										)
										.then(res=>res.json())
										.then(data=>{
											const lon = data.results[0].geometry.location.lng
											const lat = data.results[0].geometry.location.lat
											fetch('/wp-json/porches/v1/adds', {
												method: 'POST',
												headers: {
													'Content-Type': 'application/JSON',
												},
												body: JSON.stringify({
													id: porch.id,
													lon,
													lat,
												}),
											})
											.then(res=>res.json())
											.then(data=>console.log(data))
										})
									})
									// setCoords(porch.id, porch.acf.porch_address, interval)
									// interval += 1000
								})
							})
						}

						// checkPorches();

						function setCoords(id, address, interval){
							setTimeout(()=>{
								fetch(
									`https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=${gApi.key}`
								)
								.then(res=>res.json())
								.then(data=>{
									const lon = data.results[0].geometry.location.lng
									const lat = data.results[0].geometry.location.lat
									fetch('/wp-json/porches/v1/adds', {
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
									.then(res=>res.json())
									.then(data=>console.log(data))
								})
							}, interval)
						}

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