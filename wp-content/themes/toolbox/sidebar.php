<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */
?>


		<div id="secondary" class="widget-area" role="complementary">

<?php
	if(comments_open() && !is_page() && (get_post_type() != 'performers') && (get_post_type() != 'venue') && (get_post_type() != 'organisations')) {
?>
	<aside id="archives">
		<h2>Latest posts</h2>
		<?php latest_posts(); ?>
		<h2>Archives</h2>
		<ul>
		<?php 
			$args = array(
			    'type'            => 'monthly',
			    'format'          => 'html', 
			    'show_post_count' => false,
			    'echo'            => 1
			    );

		wp_get_archives( $args ); ?> 
		</ul>
	</aside>
<?php } ?>

			        <p style="padding-bottom: 5px"><strong>Get daily gig alerts on Twitter!</strong></p>
        <a href="https://twitter.com/cie_berlin" class="twitter-follow-button" data-show-count="false">Follow @cie_berlin</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


			<?php 

				$cat = get_the_category();
				if($cat[0]->slug=="edinburgh-2012") { 
			?>
			<aside id="ed">
				<h1>Latest Posts</h1>
				<?php ed(); ?>
				<br>
			</aside>

			<?php } ?>

			<aside class="upcoming-events page">

				<h2>Upcoming Events</h2>
				<?php event_list(); ?>

			</aside>