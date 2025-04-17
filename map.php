<?php  
/**  
* Template Name: Map Page
*  
* @package Tower-Porchfest  
*  
*/  
?>  
<?php
	get_header("map");
	$genres	= get_field_object('field_6491fdd624af4')['choices'];	
?>

<div class="map-menu" id="map_menu">
	<div class="map-menu-header">
		<a href="/"><i class="fas fa-home"></i></a>
		<i class="fas fa-times-circle" id="close_menu"></i>
	</div>
	<form action="" id="map_filter">
		<div class="search-and-time">
			<div class="filter-search">
				<label for="search">Search: </label>
				<input type="text" name="search" id="filter_search">
			</div>
			<div class="filter-time">
				<label for="time">Time</label>
				<input type="time" name="time" id="filter_time">
			</div>
		</div>
		<div class="other-options">
			<div class="filter-vendor">
				<label for="vendor">Vendor</label>
				<input type="checkbox" name="vendor" id="filter_vendor">
			</div>
			<div class="filter-porta">
				<label for="porta">Bathroom</label>
				<input type="checkbox" name="porta" id="filter_porta">
			</div>
			<div class="filter-sponsored">
				<label for="sponsor">Sponsored</label>
				<input type="checkbox" name="sponsor" id="filter_sponsor">
			</div>
			<div class="filter-genre">
				<label for="genre">genre</label>
				<select name="genre" id="filter_genre">
					<option selected value="none">none</option>
					<?php
						foreach($genres as $genre){
							?><option value="<?=$genre?>"><?=$genre?></option><?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="filter-submit">
			<input type="button" value="Reset" id="reset_filter">
			<input type="submit" value="Filter">
		</div>
	</form>
</div>

<div class="map-height-wrapper">
	<div class="map-header"><img src="<?=get_template_directory_uri()?>/img/map/fresno-teachers.png" alt=""></div>
	<div class="menu-btn-wrap"><i class="fas fa-bars" id="map_menu_btn"></i></div>
	<div id="map"></div>
</div>

<?php get_footer("map");?>