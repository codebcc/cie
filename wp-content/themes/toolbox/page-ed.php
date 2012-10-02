<?php

	$fb = getEdWall();

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Template Name: Edinburgh
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); 



?>

		<div id="primary">
			<div id="content" role="main">
				<h1>Berlin Ex-pat comedy laughing show BLOG</h1>
<?php ed(); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>