<?php
require_once("inc/fb/src/facebook.php");
require_once("inc/functions.php");
$facebook = doFacebook();
$fbPage = getPage("CSzBerlin");

$info = {
	name: $fbPage["name"],
	website: $fbPage["website"]
}

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

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
				<div id="videos" class="large">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); 
					//video_gallery();
 
					?>

				<?php endwhile; // end of the loop. ?>

			</div>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>