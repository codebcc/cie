<?php
/**
 * Template Name: Map
 * Description: Map page
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header();

$e = array();

$venues = $wpdb->get_results( "SELECT * FROM  wp_ai1ec_events WHERE DATE(start) >= DATE(NOW()) GROUP BY venue" );

foreach($venues as $i => $venue) {

	$v = $venue->venue;

	$myrows = $wpdb->get_results( "SELECT * FROM  wp_ai1ec_events WHERE venue = '".$v."'" );

	foreach($myrows as $j => $row) {

		$myrow = $wpdb->get_results( "SELECT * FROM  wp_posts WHERE ID = ".$row->post_id );

		$e[$i][$j] = array(
			"id" 		=> $row->post_id,
			"start"		=> $row->start,
			"end" 		=> $row->end,
			"ll" 		=> extractLL($row->venue),
			"venue"		=> extractVenue($row->venue),
			"title" 	=> $myrow[0]->post_title,
			"link"  	=> $myrow[0]->guid,
		);

	}

}

?>


<div id="map_canvas" style="width:100%; height:100%"></div>

<script type="text/javascript">

<?php if($e[0][0]["ll"][0]) { ?>

var markers = [];
var infos = [];

var myOptions = {
  zoom: 13,
  center: new google.maps.LatLng(<?php echo $e[0][0]["ll"][0].",".$e[0][0]["ll"][1]; ?>),
  mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);



<?php

	foreach($e as $i => $venue) {
		foreach($venue as $j => $event)
			if($event["ll"][0]) {
?>
	infos[<?php echo $i; ?>] = new google.maps.InfoWindow({
		content: 
			'<div class="infowindow">' + 
			'<h2><a href="<?php echo $ev["link"]?>"><?php echo $ev["title"]?></a></h2>' + 
			'<p class="date">@ <?php echo $ev["start"]?></p>' + 
			'</div>'
	});
	markers[<?php echo $i; ?>] = new google.maps.Marker({
		position: new google.maps.LatLng(<?php echo $ev["ll"][0].",".$ev["ll"][1]; ?>),
		title: "<?php echo str_replace('"', "&quot;", $ev["title"]);  ?>"
	});

	markers[<?php echo $i; ?>].setMap(map);

	google.maps.event.addListener(markers[<?php echo $i; ?>], 'click', function() {
		infos[<?php echo $i; ?>].open(map,markers[<?php echo $i; ?>])
	});

	
<?php } } } ?>


	

</script>

<?php get_footer(); ?>