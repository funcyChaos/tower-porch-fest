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

<div class="map-header"><h3>Advertisement and Header Area</h3></div>
<div id="map"></div>

<?php get_footer("map");?>