<?php
/**
 * Default Footer
 *
 * @package WP-Bootstrap
 * @subpackage Default_Theme
 * @since WP-Bootstrap 0.1
 *
 * Last Revised: July 16, 2012
 */
?>

    </div> <!-- /container -->
    <div class="navbar navbar-inverse navbar-relative-top" id="footer">
           <div class="navbar-inner">
             <div class="container">

      <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; <?php bloginfo('name'); ?> <?php the_time('Y') ?></p>
          <?php
    if ( function_exists('dynamic_sidebar')) dynamic_sidebar("footer-content"); ?>

        </div>
      </div>
    </div>

<?php wp_footer(); ?>

  </body>
</html>