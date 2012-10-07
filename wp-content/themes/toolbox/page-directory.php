<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Template Name: Directory
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<h1>The Directory</h1>

				<h2>Venues</h2>

				<?php get_directory("venue"); ?>

				<h2>Performers</h2>

				<?php get_directory("Performers"); ?>

				<h2>Organisations</h2>

				<?php get_directory("organisations"); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); 
					
						if($post->post_name=="edinburgh-2012-2") {
							echo '<h2>Latest Blog Posts</h2>';
							ed();
						}

					?>
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>