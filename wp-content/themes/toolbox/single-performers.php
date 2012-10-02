<?php
require_once("inc/fb/src/facebook.php");
require_once("inc/functions.php");
$facebook = doFacebook();

$fbID = get_field("facebook_id");

$fbPage = getPage($fbID);

$external_links = get_field("external_links");
$organisation_links = get_field("organisation_links");
$gigs = get_field("upcoming_gigs");

$images = get_field("images");
$videos = get_field("videos");

if(!$images) $fbImg = get_fb_image($fbID);

//echo getAssociatedProducers(getPageId($fbID),true);

//getProducersEvents($fbID);

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
		<div id="primary">
			<div id="content" role="main">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php 
						echo '<div id="image_text">';
						
						foreach ($images as $i => $img) {
							if($i==0) {
								if(count($images)>1) echo '<div id="images">';
								$imgObj = wp_get_attachment_image_src($img["image"], "medium");
								echo '<img src="'.$imgObj[0].'">';
								if(count($images)>1) echo '<ul id="image_nav">';
							} else {
								$imgObj = wp_get_attachment_image_src($img["image"],array(50,50));
								echo '<li><img src="'.$imgObj[0].'" width="50"></li>'; 
							}
						}

						if(!$images) echo '<img src="'.$fbImg.'" />';

						if(count($images)>1) echo '</ul></div><!-- /#images -->';
						echo '<div id="small_details">';

						echo '<h1>'.get_the_title().'</h1>';
						fbButton($fbPage["id"]);
						get_template_part( 'content', 'page' ); 
						if($gigs) {
							echo '<div class="upcoming-events pod">';
                			echo '<h2>Upcoming shows <a class="more" href="/event-calendar">Full calendar</a></h2>';
                			//print_r($gigs);
                			event_list(0,false,false, $gigs);
            				echo '</div>';
						}
						echo '</div>';
						echo '</div><!-- /#images_text -->';
						

					?>
				<?php endwhile; // end of the loop. ?>
</div></div>
<?php
get_sidebar(); ?>
<?php get_footer(); ?>