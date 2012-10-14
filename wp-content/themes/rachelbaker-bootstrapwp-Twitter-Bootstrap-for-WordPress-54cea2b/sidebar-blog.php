<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage WP-Bootstrap
 * @since WP-Bootstrap 0.1
 */
?>
<div class="span4">
		<div class="well sidebar-nav">
            <?php
    if ( function_exists('dynamic_sidebar')) dynamic_sidebar("sidebar-posts");
?>
	</div><!--/.well .sidebar-nav -->

	<div class="well">

        <h2>Upcoming shows <a class="more" href="/event-calendar">Full calendar</a></h2>
        <?php event_list(0); ?>

	</div>
         </div><!-- /.span4 -->
          </div><!-- /.row .content -->

