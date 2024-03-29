<?php
$fb = getEdWall();
/**
 * Template Name: Hompage
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>

		<div id="content" role="main">
            <div id="left-col"/>
                <div id="featured" class="pod">

<?php $args = array(
    'numberposts'     => 1,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_type'       => 'post',
    'post_status'     => 'publish' ); 

    $posts_array = get_posts( $args );

    $main =  get_field("main_news_story");
    $post = get_post($main->ID);

    //foreach ($posts_array as $post) {
?>
        <h2><!-- a href="<?php echo $post->guid; ?>" --><?php echo $post->post_title; ?><!-- /a --></h2>
        <?php 
            $i = get_field('image');
            $i = wp_get_attachment_image( $i, "medium");
            if($i) echo $i;

            $c = $post->post_content; 
            /* if(strlen($c) > 400) {
                $c = substr ( $c , 0, 400 );
                $more = true;
            } */
            echo make_content($c);
            /* if($more) echo '... [<a href="<?php echo $post->guid; ?>">more</a>]'; */
        ?>


<?php
    //}

?>
        <!-- h3><a href="/news">More news</a></h3>
        <ul id="more-news">
<?php $args = array(
    'numberposts'     => 4,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'offset'          => 1,
    'post_type'       => 'post',
    'post_status'     => 'publish' ); 

    $posts_array = get_posts( $args );

    foreach ($posts_array as $post) {
?>
    <li>
        <h4><a href="<?php echo $post->guid; ?>"><?php echo $post->post_title; ?></a></h4>
    </li>

<?php } ?>
        </ul -->

                

                </div><!-- /#featured -->

                <div id="videos" class="pod">

                    <h2>Media <a href="/videos">Media gallery</a></h2>


                    <div id="featured-video">
                        <?php video_gallery() ?>
                    </div><!-- /#featured-videos -->

                </div><!-- /#videos -->

            </div><!-- /#left-col -->
            <div id="right-col">

            <!-- div id="ed-pod" class="pod">
                <h2>Edinburgh Festival BLOG <a class="more" href="/edinburgh-2012-2/">All posts</a></h2>
                <a class="ed-link" href="/edinburgh-2012-2/"><img src="/wp-content/themes/toolbox/images/433_BerlinExpatComedy_CarolineClifford-300x300.jpg" width="100" height="100" alt=""/></a>
                <?php ed(1); ?>

            </div --><!-- /#ed -->

            <a href="/gemused" id="gemused"><img src="/wp-content/themes/toolbox/images/gemused-thumb.jpg" alt=""/></a>
            <div id="upcoming-events" class="pod">
                <h2>Upcoming shows <a class="more" href="/event-calendar">Full calendar</a></h2>
                <?php event_list(0); ?>
            </div>
            <div id="gallery" class="pod">
                <h2>Berlin comedy in pictures <a href="/gallery">Full gallery</a></h2>
                <?php image_gallery(); ?>
                <script>
                    imgHeight();
                </script>
            </div>

		</div><!-- #content -->

<?php get_footer(); ?>