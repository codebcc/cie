<?php
/**
 * Template Name: Home Hero Template with 3 widget areas
 *
 *
 * @package WP-Bootstrap
 * @subpackage Default_Theme
 * @since WP-Bootstrap 0.5
 *
 * Last Revised: July 16, 2012
 */
get_header(); ?>

<div id="home-banner" class="hidden-phone">
    <div class="inner container"></div>
</div>

<div class="container">

    <div class="row">

        <div class="span6 pull-right hidden-phone">

            <div class="well">

            <?php $args = array(
            'numberposts'     => 1,
            'orderby'         => 'post_date',
            'order'           => 'DESC',
            'post_type'       => 'post',
            'post_status'     => 'publish' ); 

            $posts_array = get_posts( $args );

            $main =  get_field("main_news_story");
            $post = get_post($main->ID);

            ?>
            <h2><?php echo $post->post_title; ?></h2>
            <?php 
            $i = get_field('image');
            $i = wp_get_attachment_image( $i, "medium");
            if($i) echo $i;

            $c = $post->post_content; 

            echo make_content($c);

            ?>

                <p><strong>Get daily gig alerts on Twitter!</strong></p>
                <a href="https://twitter.com/cie_berlin" class="twitter-follow-button" data-show-count="false">Follow @cie_berlin</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

            </div><!-- /.well -->

        </div><!-- /.span6 -->

        <div class="span6 pull-left">

            <div class="well">

                <h2>Upcoming shows <a class="more" href="/event-calendar">Full calendar</a></h2>
                <?php event_list(0); ?>

            </div><!-- /.well -->

        </div><!-- /.span6 -->

        <div class="span6 pull-right hidden-phone">

            <div class="well">

                <h2>Latest blog posts <a href="/blog" class="more">All posts</a></h2>

                <?php latest_posts(true); ?>

            </div><!-- /.well -->

        </div>

    </div><!-- .row -->

</div>
<?php get_footer();?>
