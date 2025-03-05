<?php  
/**  
* Template Name: Map Page
*  
* @package Tower-Porchfest  
*  
*/  
?>  
<?php
	get_header();
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

<div class="hidden" id="content">
	<img src="https://towerporchfest.org/wp-content/uploads/2025/01/Untitled-1803-x-670-px1.png" alt="Default">
	<div class="header">
		<h3>Ziggy and Dirty Franks Space Jam</h3>
		<p>112 E University Ave, Fresno, CA 93704</p>
	</div>
	<div class="content">
		<p>The Universe is Expanding</p>
	</div>
	<div class="lineup">
		<table class="lineup-table"><tbody><tr><th>START TIME</th><th>PERFORMER</th></tr><tr><td>11:00AM</td><td>The Jazz Cru</td></tr><tr><td>4:00PM</td><td>Ryan Gregory Tallman</td></tr></tbody></table>
	</div>
</div>

<div id="map"></div>

<?php get_footer();?>