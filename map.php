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
	if(!empty($_REQUEST['srch'])){
		$s = ['s'=>$_REQUEST['srch']];
		$query = new WP_Query($s);
		if($query->have_posts()){
			$query_results = [];
			while($query->have_posts()){
				$query->the_post();
				$query_results[] = get_the_ID();
			}
			?><script>const searchResults = <?=json_encode($query_results)?></script><?php
		}
	}
?>

<div class="map-menu" id="map_menu">
	<div class="map-menu-header">
		<i class="fas fa-home"></i>
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
			<div class="filter-genre">
				<label for="genre">genre</label>
				<select name="genre" id="filter_genre">
					<option selected value="none">none</option>
					<option value="something">something</option>
				</select>
			</div>
		</div>
		<div class="filter-submit">
			<input type="button" value="Reset" id="reset_filter">
			<input type="submit" value="Filter">
		</div>
	</form>
</div>

<div class="map-header"><h3>Advertisement and Header Area</h3></div><div class="menu-btn-wrap"><i class="fas fa-bars" id="map_menu_btn"></i></div>
<div id="map"></div>

<?php get_footer("map");?>