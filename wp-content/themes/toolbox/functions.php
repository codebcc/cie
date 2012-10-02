<?php
ini_set('display_errors','On');
error_reporting(E_ERROR);
date_default_timezone_set('Europe/Berlin');
/**
 * Toolbox functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'toolbox_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override toolbox_setup() in a child theme, add your own toolbox_setup to your child theme's
 * functions.php file.
 */
function toolbox_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on toolbox, use a find and replace
	 * to change 'toolbox' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'toolbox', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'toolbox' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery' ) );
}
endif; // toolbox_setup

/**
 * Tell WordPress to run toolbox_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'toolbox_setup' );

/**
 * Set a default theme color array for WP.com.
 */
$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'eeeeee',
	'text' => '444444',
);

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function toolbox_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'toolbox_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function toolbox_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Header quote', 'toolbox' ),
		'id' => 'header-quote',
		'before_widget' => '',
		'after_widget' => "",
		'before_title' => '',
		'after_title' => '',
	) );
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'toolbox' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

}
add_action( 'init', 'toolbox_widgets_init' );

if ( ! function_exists( 'toolbox_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Toolbox 1.2
 */
function toolbox_content_nav( $nav_id ) {
	global $wp_query;

	?>
	<nav id="<?php echo $nav_id; ?>">
		<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'toolbox' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'toolbox' ) . '</span> %title',true ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'toolbox' ) . '</span>',true ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'toolbox' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'toolbox' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // toolbox_content_nav


if ( ! function_exists( 'toolbox_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own toolbox_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Toolbox 0.4
 */
function toolbox_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'toolbox' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'toolbox' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'toolbox' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'toolbox' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'toolbox' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'toolbox' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for toolbox_comment()

if ( ! function_exists( 'toolbox_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own toolbox_posted_on to override in a child theme
 *
 * @since Toolbox 1.2
 */
function toolbox_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'toolbox' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'toolbox' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Toolbox 1.2
 */
function toolbox_body_classes( $classes ) {
	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	return $classes;
}
add_filter( 'body_class', 'toolbox_body_classes' );

/**
 * Returns true if a blog has more than 1 category
 *
 * @since Toolbox 1.2
 */
function toolbox_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so toolbox_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so toolbox_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in toolbox_categorized_blog
 *
 * @since Toolbox 1.2
 */
function toolbox_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'toolbox_category_transient_flusher' );
add_action( 'save_post', 'toolbox_category_transient_flusher' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function toolbox_enhanced_image_navigation( $url ) {
	global $post, $wp_rewrite;

	$id = (int) $post->ID;
	$object = get_post( $id );
	if ( wp_attachment_is_image( $post->ID ) && ( $wp_rewrite->using_permalinks() && ( $object->post_parent > 0 ) && ( $object->post_parent != $id ) ) )
		$url = $url . '#main';

	return $url;
}
add_filter( 'attachment_link', 'toolbox_enhanced_image_navigation' );


/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */

function ml_clean_siteurl($url) {
	$url="http://".$_SERVER[HTTP_HOST];
	return $url;
}
add_filter('option_siteurl', 'ml_clean_siteurl');

/* Taxonomies */

show_admin_bar( false );

function video_gallery($size = "small") {
	$args = array(
	    'numberposts'     => 1,
	    'offset'		  => 0,
	    'orderby'         => 'post_date',
	    'order'           => 'DESC',
	    'post_type'       => 'videos',
	    'post_status'     => 'publish' 
    ); 

    $videos = get_posts( $args );

    foreach ($videos as $video) {
        $url = get_post_meta($video->ID, "video_id", true);
        $audio = get_post_meta($video->ID, "audio_url", true);
		echo '<div id="youtube" rel="'.$url.'" audio="'.$audio.'" title="'.$video->post_title.'"></div>';
    }

	$args = array(
	    'offset'		  => 1,
	    'orderby'         => 'post_date',
	    'order'           => 'DESC',
	    'post_type'       => 'videos',
	    'post_status'     => 'publish' 
    ); 

	$videos = get_posts( $args );

	echo '<ul>';
    foreach ($videos as $video) {
        $url = get_post_meta($video->ID, "video_id", true);
        $audio = get_post_meta($video->ID, "audio_url", true);
        echo '<li><a href="#" rel="'.$url.'" audio="'.$audio.'"><span>'.$video->post_title.'</span></a>';
        if($audio) {
        	echo '<img src="images/ico-audio.png" width="120" height="90" alt="" />';
        } else {
        	echo '<img src="http://img.youtube.com/vi/'.$url.'/3.jpg" alt=""/>';
        }
        echo '</li>';
    }
    echo '</ul>';
}

function image_gallery($size = "small") {
    echo '<ul>';
    $gallery = get_field('gallery_image', 626);
    foreach($gallery as $g) {
        $upload = $g["gallery_image_upload"];
        if($upload) $upload = wp_get_attachment_image_src($upload, 'full');

        $url = $g["gallery_url"];
        $image = $upload ? $upload[0] : $url;
        //print_r($g);
        if($image) {
        	echo '<li><div class="wrap">';
        	if($size=="large") {
        		$title = $g["gallery_description"];
        		if($g["photographer"]) $title .= ', Photographer:'.$g["photographer"];
        		echo '<a class="group1" href="'.$image.'" rel="lightbox" title="'.$title.'">';
        	}
        	echo '<img src="'.$image.'" alt="" />';
        	if($size=="large") echo '</a>';
        	if($size!="large") {
	           	if($g["gallery_description"]) echo '<div class="desc"><p class="desc">'.$g["gallery_description"].'</p>';
	            if($g["photographer"]) {
	            	echo "<p class='photographer'>Photographer: ";
	                if($g["photographer_url"]) echo '<a class="photographer" href="'.$g["photographer_url"].'">';
	                echo "<strong>".$g["photographer"].'</strong></p>';
	                if($g["photographer_url"]) echo '</a>';
	                if($g["event"]) echo '<a class="event" href="'.$g["event"]->guid.'">See event</a>';
	            }
	            if($g["gallery_description"]) echo '</div>';
        	}
            echo '</div></li>';
    	}
    }
    echo '</ul>';
}

function event_list($amount = 0, $schedule = false, $filter = false, $ids = false) {
    echo '<ul>';
    global $wpdb;
    $firstOfMonth = date('Y-m-')."01 00:00:00";
    //2012-03-14 21:00:00
    $from = $schedule ? $firstOfMonth : "NOW()";

    $myrows = $wpdb->get_results( "SELECT * FROM  wp_ai1ec_events WHERE DATE(start) >= DATE(".$from.") ORDER BY DATE(start)");
    $count=0;

    if($ids) {
    	$idsFlat = "";
    	for($a=0; $a<=count($ids); $a++) {
    		$idsFlat .= $ids[$a]["gig"];
    		if($a < count($ids)-1) $idsFlat .= ",";
    	}
    	$myrows = $wpdb->get_results( "SELECT * FROM  wp_ai1ec_events WHERE DATE(start) >= DATE(NOW()) AND post_id IN (".$idsFlat.") ORDER BY DATE(start)");
    }
    foreach($myrows as $row) {
        $start = $s = new DateTime(($row->start));
        date_add($start, date_interval_create_from_date_string('2 hours'));
        $start = $start->format('D M d');
		
        $today = ($start==date('D M d')) ? " today!" : "";
        $start = $s->format('D M d @ H:i');
        
        $gig = get_post($row->post_id);
    	if($filter) {
    		$ids = explode(",", $filter);
    		$flag = false;

    		foreach ($ids as $id) {
    			if(!is_numeric($id)) continue;
    			if(!strpos($gig->post_content, $id)) {
    				$flag=true;
    			} else {
    				break;
    			}
    		}
    		if($flag) continue;
    	}
        echo '<li class="'.$today.'"><span class="date">'.$start.'</span><a href="'.$gig->guid.'"><span>'.$gig->post_title.'</span>';
        if($today!="") echo '<span class="today"> Today!</span>;';
        echo '</a></li>';
        if( ($count>=$amount-1) && ($amount!=0)) break;
    }
    $count++;
	
    if ($count==0) echo '<li>No upcoming events :(</li>'; 
    echo '</ul>';
}

add_filter('cron_schedules','custom_cron_schedules');

function custom_cron_schedules($schedules){
$schedules['tenminutes'] = array(
'interval'   => '600',
'display'   => __('Every 10 Minutes'),
);

return $schedules;
}

function edd($num = 0) {

	$args = array(
	    'numberposts'     => $num,
	    'offset'		  => 0,
	    'category'		  =>  get_category_id('Edinburgh 2012'),
	    'orderby'         => 'post_date',
	    'order'           => 'DESC',
	    'post_type'       => 'post',
	    'post_status'     => 'publish' 
    ); 

    $posts = get_posts( $args );

    echo '<ul>';

    foreach ($posts as $post) {
    	$a = ($post->post_author);
    	$au = (get_userdata($a));
        echo '<li><a href="'.$post->guid.'">'.$post->post_title.'</a> - '.$au->user_login.'</li>';
    }

    echo '</ul>';

}

function getEdWall() {
  require_once("php-sdk/src/facebook.php");

  $config = array();
  $config['appId'] = '366597510039533';
  $config['secret'] = 'd510cdd653447ac40dfd2a0540e2dca4';
  $config['fileUpload'] = false; // optional

  $facebook = new Facebook($config);

  $access_token = $facebook->getAccessToken();

  $fb["wall"] = $facebook->api("/BerlinExpatComedyFunShow/feed?access_token=".$access_token);
  $fb["fb"] = $facebook;
  $fb["access"] = $access_token;
  return $fb;
}

function ed($num=null) {
 
	global $fb;

	$class = ($num==1) ? ' class="small"' : '';
	$picType = ($num==1) ? 'small' : 'large';
	$fbo = $fb["fb"];
	$access_token = $fb["access"];
	echo '<ul id="ed"'.$class.'>';
	$count=0;
	foreach($fb["wall"]["data"] as $post) {
		//print_r($post["comments"]);
		if( ($post["from"]["id"]=="409877995714974") && $post["message"]) {
			echo '<li id="'.$post["id"].'">';
			$img = $post["picture"];
			if($img && ($num!=1)) {
				$img = str_replace("_s.", "_n.", $img);
				$img = str_replace("_t.", "_n.", $img);
			}
			$date = strtotime ($post["created_time"]);
			echo '<p class="date"><a href="/edinburgh-2012-2/">'.date('l jS \of F Y h:i:s A', $date).'</a></p>';
			$msg = $post["message"];
			$msg = str_replace("\n", "<br>", $msg);
			echo '<p>'.$msg.'</p>';
			if($img) {
				echo '<img src="'.$img.'">';
			}

			if($post["comments"]["count"]>0) {
				echo '<div class="comments"><h2>Comments</h2>';
				echo '<ul>';
				foreach ($post["comments"]["data"] as $i => $comment) {
					echo '<li>';
					echo '<h3>'.$comment["from"]["name"].'</h3>';
					echo '<p>'.$comment["message"].'</p>';
					echo '</li>';
				}
				echo '</ul></div>';
			}
			echo '</li>';
		}
		if($num!=null) {
			$count++;
			if($count>$num) break;
		}

	}
	echo '</ul>';
}

function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}

wp_schedule_event(time(),'tenminutes','our_cron_hook');
add_action('our_cron_hook','execute_cron_jobs');

//call a function just before the query runs to fetch posts
//add_action('pre_get_posts','change_post_type');
 
function change_post_type($var) {
    if(is_category()) {
                $var->query_vars['post_type'] = 'any';
                //it will change the value to 'any' from the default value of ;post';
                //can be any, attachment,  page, post, or revision.
                //'any' retrieves any type except revisions.
    }
}

function get_post_list($posts) {
	//print_r($posts);
	$h = "<ul>";
	foreach ($posts as $p) {
		$p2 = $p["gigs"];
		$h .= '<li><a href="'.$p2->guid.'">'.$p2->post_title.'</a></li>';
	}
	$h .= '</ul>';
	echo $h;
}

function latest_posts($summary = false) {
	echo '<ul class="latest_posts">'; 
	$recent_posts = wp_get_recent_posts(array('post_status' => 'publish'));
	foreach( $recent_posts as $i => $recent ){
		//print_r($recent);
		$author = get_userdata($recent["post_author"]);
		$author = $author->user_login;
		$content = my_excerpt($recent["post_content"], false);
		echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].' <span> - '.$author.'</span></a>';
		if($summary) echo '<p>'.$content.'</p>';
		echo '</li>';
	}
	echo '</ul>';
}

function my_excerpt($text, $excerpt)
{
    if ($excerpt) return $excerpt;

    $text = strip_shortcodes( $text );

    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $text = strip_tags($text);
    $excerpt_length = apply_filters('excerpt_length', 55);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
    } else {
            $text = implode(' ', $words);
    }

    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

function external_posts() {
	echo '<ul>';

	echo '</ul>';
}

register_field('event_listing', dirname(__FILE__) . '/acf_event_list.php'); 
  

