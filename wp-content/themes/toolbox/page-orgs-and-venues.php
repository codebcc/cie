<?php
require_once("inc/fb/src/facebook.php");
require_once("inc/functions.php");
$facebook = doFacebook();

$fbID = get_field("facebook_id");
$file =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/toolbox/inc/all-json.php";

$var = $homepage = file_get_contents($file);
$wall = json_decode($var);
//print_r($var);

$nopull = get_field("nopull");

if($fbID) {
	$fbPage = getPage($fbID);
	$fbID = $fbPage["id"];
	if(!$nopull) {
		$name = $fbPage["name"];
		$username = $fbPage["username"];
		$website = $fbPage["website"];
		$description = make_content($fbPage["description"]);
		$about = make_content($fbPage["about"]);
		$location = $fbPage["location"];
		$awards = make_content($fbPage["awards"]); 
		$link = $fbPage["link"];
		$cover = $fbPage["cover"];
		if(!$cover) {
			$cover["source"] = get_fb_image($fbID);
		}
		$phone = $fbPage["phone"];
	}	
} 

if(!$fbID || $nopull) $name = get_the_title();


$content_before = get_field("content_before");
$content_before = get_field("content_after");
$links = get_field("links");
//uibutton confirm

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Template Name: Organisations and Venues
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); 
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=366597510039533";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


		<div id="primary">
			<div id="content" role="main">
<? 
echo '<h1>'.$name.'</h1>';
if($content_before) echo '<div id="content_before">'.$content_before.'</div>';
if($fbID && !$nopull) {
	if($cover) echo '<div id="image_text">';
	if($cover) if($cover["source"]) echo '<img src="'.$cover["source"].'" class="cover" width="300" alt=""/>';
	echo '<div id="small_details">';
	if($website) echo '<p>Website: <a href="'.getHref($website).'">'.$website.'</a></p>'; 
	echo '<a href="'.$link.'" class="uibutton confirm">See Facebook page</a>';
	echo '<div class="fb-like" data-href="'.$link.'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>';
	if($location) echo getMapHTML($location,$name);
	if($phone) echo '<p>Phone: '.$phone.'</p>'; 
	echo '<div class="upcoming-events page"><h2>Upcoming Events <a class="more" href="/event-calendar">Full calendar</a></h2>';
	event_list(3, false, getAssociatedProducers($fbID)); 
	echo '</div>';
	echo '</div><!-- /#small_details -->';
	if($cover) echo '</div><!-- /#image_text -->';
	if($description) echo $description;
	if($about) echo $about;
	if($awards) echo $awards;
}

if($nopull && $fbID) {
	echo '<div class="upcoming-events page"><h2>Upcoming Events <a class="more" href="/event-calendar">Full calendar</a></h2>';

	event_list(3, false, getAssociatedProducers($fbID)); 
	echo '</div>';
}

if($content_after) echo '<div id="content_after">'.$content_after.'</div>';
?>
</div></div>
<?php
get_sidebar(); ?>
<?php get_footer(); ?>