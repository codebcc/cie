<?php
/**
 * Bootstrap functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 *
 * @package WordPress
 * @subpackage WP-Bootstrap
 * @since WP-Bootstrap 0.1
 *
 * Last Updated: September 9, 2012
 */

if (!defined('BOOTSTRAPWP_VERSION'))
define('BOOTSTRAPWP_VERSION', '.90');

 /**
 * Declaring the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 770; /* pixels */

/**
 * Declaring the theme language domain
 */
load_theme_textdomain('bootstrapwp');

/*
| -------------------------------------------------------------------
| Setup Theme
| -------------------------------------------------------------------
|
| */
add_action( 'after_setup_theme', 'bootstrapwp_theme_setup' );
if ( ! function_exists( 'bootstrapwp_theme_setup' ) ):
function bootstrapwp_theme_setup() {
  add_theme_support( 'automatic-feed-links' );
  /**
   * Adds custom menu with wp_page_menu fallback
   */
  register_nav_menus( array(
    'main-menu' => __( 'Main Menu', 'bootstrapwp' ),
  ) );
  add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery', 'link', 'quote', 'status', 'video', 'audio', 'chat' ) );
}
endif;

################################################################################
// Loading All CSS Stylesheets
################################################################################
  function bootstrapwp_css_loader() {
    wp_enqueue_style('bootstrapwp', get_template_directory_uri().'/css/bootstrapwp.css', false ,'0.90', 'all' );
    wp_enqueue_style('prettify', get_template_directory_uri().'/js/google-code-prettify/prettify.css', false ,'1.0', 'all' );
  }
add_action('wp_enqueue_scripts', 'bootstrapwp_css_loader');


################################################################################
// Loading all JS Script Files.  Remove any files you are not using!
################################################################################
  function bootstrapwp_js_loader() {
       wp_enqueue_script('bootstrapjs', get_template_directory_uri().'/js/bootstrap.min.js', array('jquery'),'0.90', true );
       wp_enqueue_script('prettifyjs', get_template_directory_uri().'/js/google-code-prettify/prettify.js', array('jquery'),'1.0', true );
       wp_enqueue_script('demojs', get_template_directory_uri().'/js/bootstrapwp.demo.js', array('jquery'),'0.90', true );
  }
add_action('wp_enqueue_scripts', 'bootstrapwp_js_loader');


/*
| -------------------------------------------------------------------
| Top Navigation Bar Customization
| -------------------------------------------------------------------

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function bootstrapwp_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
add_filter( 'wp_page_menu_args', 'bootstrapwp_page_menu_args' );

/**
 * Get file 'includes/class-bootstrap_walker_nav_menu.php' with Custom Walker class methods
 * */

include 'includes/class-bootstrapwp_walker_nav_menu.php';

/*
| -------------------------------------------------------------------
| Registering Widget Sections
| -------------------------------------------------------------------
| */
function bootstrapwp_widgets_init() {
  register_sidebar( array(
    'name' => 'Page Sidebar',
    'id' => 'sidebar-page',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => "</div>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );

  register_sidebar( array(
    'name' => 'Posts Sidebar',
    'id' => 'sidebar-posts',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => "</div>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );

  register_sidebar(array(
    'name' => 'Home Left',
    'id'   => 'home-left',
    'description'   => 'Left textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Home Middle',
    'id'   => 'home-middle',
    'description'   => 'Middle textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Home Right',
    'id'   => 'home-right',
    'description'   => 'Right textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Footer Content',
    'id'   => 'footer-content',
    'description'   => 'Footer text or acknowledgements',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
  ));
}
add_action( 'init', 'bootstrapwp_widgets_init' );


/*
| -------------------------------------------------------------------
| Adding Post Thumbnails and Image Sizes
| -------------------------------------------------------------------
| */
if ( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 160, 120 ); // 160 pixels wide by 120 pixels high
}

if ( function_exists( 'add_image_size' ) ) {
  add_image_size( 'bootstrap-small', 260, 180 ); // 260 pixels wide by 180 pixels high
  add_image_size( 'bootstrap-medium', 360, 268 ); // 360 pixels wide by 268 pixels high
}
/*
| -------------------------------------------------------------------
| Revising Default Excerpt
| -------------------------------------------------------------------
| Adding filter to post excerpts to contain ...Continue Reading link
| */
function bootstrapwp_excerpt($more) {
       global $post;
  return '&nbsp; &nbsp;<a href="'. get_permalink($post->ID) . '">...Continue Reading</a>';
}
add_filter('excerpt_more', 'bootstrapwp_excerpt');



if ( ! function_exists( 'bootstrapwp_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 */
function bootstrapwp_content_nav( $nav_id ) {
	global $wp_query;

	?>

	<?php if ( is_single() ) : // navigation links for single posts ?>
<ul class="pager">
		<?php previous_post_link( '<li class="previous">%link</li>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'bootstrapwp' ) . '</span> %title' ); ?>
		<?php next_post_link( '<li class="next">%link</li>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'bootstrapwp' ) . '</span>' ); ?>
</ul>
	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
<ul class="pager">
		<?php if ( get_next_posts_link() ) : ?>
		<li class="next"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'bootstrapwp' ) ); ?></li>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<li class="previous"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'bootstrapwp' ) ); ?></li>
		<?php endif; ?>
</ul>
	<?php endif; ?>

	<?php
}
endif; // bootstrapwp_content_nav


if ( ! function_exists( 'bootstrapwp_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own bootstrap_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since WP-Bootstrap .5
 */
function bootstrapwp_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'bootstrap' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'bootstrap' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'bootstrap' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'bootstrap' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'bootstrap' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'bootstrap' ), ' ' );
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
endif; // ends check for bootstrapwp_comment()

if ( ! function_exists( 'bootstrapwp_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own bootstrap_posted_on to override in a child theme
 *
 * @since WP-Bootstrap .5
 */
function bootstrapwp_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'bootstrap' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'bootstrap' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @since WP-Bootstrap .5
 */
function bootstrapwp_body_classes( $classes ) {
	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	return $classes;
}
add_filter( 'body_class', 'bootstrapwp_body_classes' );

/**
 * Returns true if a blog has more than 1 category
 *
 * @since WP-Bootstrap .5
 */
function bootstrapwp_categorized_blog() {
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
		// This blog has more than 1 category so bootstrap_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so bootstrap_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in bootstrapwp_categorized_blog
 *
 * @since bootstrap 1.2
 */
function bootstrapwp_category_transient_flusher() {
  // Like, beat it. Dig?
  delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'bootstrapwp_category_transient_flusher' );
add_action( 'save_post', 'bootstrapwp_category_transient_flusher' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function bootstrapwp_enhanced_image_navigation( $url ) {
	global $post;

	if ( wp_attachment_is_image( $post->ID ) )
		$url = $url . '#main';

	return $url;
}
add_filter( 'attachment_link', 'bootstrapwp_enhanced_image_navigation' );


/*
| -------------------------------------------------------------------
| Checking for Post Thumbnail
| -------------------------------------------------------------------
|
| */
function bootstrapwp_post_thumbnail_check() {
    global $post;
    if (get_the_post_thumbnail()) {
          return true; }
          else { return false; }
}

/*
| -------------------------------------------------------------------
| Setting Featured Image (Post Thumbnail)
| -------------------------------------------------------------------
| Will automatically add the first image attached to a post as the Featured Image if post does not have a featured image previously set.
| */
function bootstrapwp_autoset_featured_img() {

  $post_thumbnail = bootstrapwp_post_thumbnail_check();
  if ($post_thumbnail == true ){
    return the_post_thumbnail();
  }
    if ($post_thumbnail == false ){
      $image_args = array(
                'post_type' => 'attachment',
                'numberposts' => 1,
                'post_mime_type' => 'image',
                'post_parent' => $post->ID,
                'order' => 'desc'
          );
      $attached_image = get_children( $image_args );
             if ($attached_image) {
                                foreach ($attached_image as $attachment_id => $attachment) {
                                set_post_thumbnail($post->ID, $attachment_id);
                                }
            return the_post_thumbnail();
          } else { return " ";}
        }
      }  //end function


/*
| -------------------------------------------------------------------
| Adding Breadcrumbs
| -------------------------------------------------------------------
|
| */
 function bootstrapwp_breadcrumbs() {

  $delimiter = '<span class="divider">/</span>';
  $home = 'Home'; // text for the 'Home' link
  $before = '<li class="active">'; // tag before the current crumb
  $after = '</li>'; // tag after the current crumb

  if ( !is_home() && !is_front_page() || is_paged() ) {

    echo '<ul class="breadcrumb">';

    global $post;
    $homeLink = home_url();
    echo '<li><a href="' . $homeLink . '">' . $home . '</a></li> ' . $delimiter . ' ';

    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;

    } elseif ( is_day() ) {
      echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
      echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo $before . get_the_title() . $after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;

    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;

    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;

    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;

    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;

    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;

    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page', 'bootstrapwp') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }

    echo '</ul>';

  }
} // end bootstrapwp_breadcrumbs()


/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a bootstrap.
 */

show_admin_bar( false );

function video_gallery($size = "small") {
  $args = array(
      'numberposts'     => 1,
      'offset'      => 0,
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
      'offset'      => 1,
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
    echo '<ul class="unstyled event-list">';
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
    
        $today = ($start==date('D M d')) ? "today" : "";
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
        echo '<li class="'.$today.'"><small class="date muted">'.$start.'</small><a href="'.$gig->guid.'"><span>'.$gig->post_title.'</span>';
        if($today!="") echo ' <span class="today">Today!</span>';
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
      'offset'      => 0,
      'category'      =>  get_category_id('Edinburgh 2012'),
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
  echo '<ul class="latest_posts unstyled">'; 
  $recent_posts = wp_get_recent_posts(array('post_status' => 'publish'));
  foreach( $recent_posts as $i => $recent ){
    //print_r($recent);
    $author = get_userdata($recent["post_author"]);
    $author = $author->user_login;
    $content = my_excerpt($recent["post_content"], false);
    echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].' <span> - '.$author.'</span></a>';
    if($summary && ($i==0)) echo '<p>'.$content.'</p>';
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

function doFacebook() {
  $config = array();
  $config['appId'] = '366597510039533';
  $config['secret'] = 'd510cdd653447ac40dfd2a0540e2dca4';
  $config['fileUpload'] = false; // optional

  $facebook = new Facebook($config);

  return $facebook;
}

function getWall($id) {
  global $facebook;
  $access_token = $facebook->getAccessToken();
  //return $facebook->api('/'.$id.'/feed?access_token=".$access_token','GET');
  //'select post_id from stream where source_id = 40796308305 and created_time <'.time().' LIMIT 1000000 ;'
  $fql='/fql?q=SELECT+post_id,message,attachment+FROM+stream+WHERE+source_id='.$id.'+LIMIT+100000&access_token='.$access_token;
  return $facebook->api($fql,'GET');
  //echo $fql;

}

function getPageId($id) {
  global $facebook;
  $access_token = $facebook->getAccessToken();
  $fb = $facebook->api('/'.$id.'/?access_token='.$access_token,'GET');  
  return $fb["id"];
}

function getPage($id) {
  global $facebook;
  $access_token = $facebook->getAccessToken();
  return $facebook->api('/'.$id.'/?access_token=".$access_token','GET');  
}
 
function cleanDate($d) {
  global $e;
  if(isset($e["timezone"])) {
    //echo $e["name"];
    $d = str_replace("T", " ", $d);
    //echo $d."\n";
    $myDateTime = new DateTime(date($d));
    //print_r($myDateTime);
    $d = date('Y-m-d H:i:00', $myDateTime->format('U') + 32400); //add 9 hours because of shitty facebook
    //echo "\n".$d."\n\n";
  } 
  $dd = str_replace("-", "", $d);
  $dd = str_replace(" " , "T", $dd);
  $dd = str_replace(":", "", $dd);
  return $dd;
}

function vcalAdd($key,$e,$prefix) {
  global $vcal;
  if(isset($e[$key])) array_push($vcal,$prefix.$e[$key]);
}

function vcalAddDate($key,$prefix) {
  global $vcal, $e;
  if(isset($e[$key])) {
    $d = cleanDate($e[$key]);
    array_push($vcal,$prefix.$d);
  }
}

function ifAdd($key,$e, $sep="") {
  return (isset($e[$key])) ? $e[$key].$sep : "";
}


function getArea() {

  global $area, $areas, $e, $v;

  if($area==0) return true;

  if(($area==7) && !$v) {
    return true;
  } else {

    //no venue data + categorgy specified?
    if(!$v) return false;

    $vl = $v["location"];

    $distances = array();

    foreach($areas as $a) {

      array_push($distances, array(distance($a[0], $a[1], $vl["latitude"], $vl["longitude"]), $a[2]));

    }

    $index = -1;

    $dd = 100;

    foreach($distances as $key => $d) {
      if($d[0]<$dd) {
        $dd = $d[0];
        $index = $key;
      }
    }

    return ($distances[$index][1]==$area);

  }
  return true;
}

function distance($lat1, $lng1, $lat2, $lng2, $miles = true)
{
  $pi80 = M_PI / 180;
  $lat1 *= $pi80;
  $lng1 *= $pi80;
  $lat2 *= $pi80;
  $lng2 *= $pi80;

  $r = 6372.797; // mean radius of Earth in km
  $dlat = $lat2 - $lat1;
  $dlng = $lng2 - $lng1;
  $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
  $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
  $km = $r * $c;

  return ($miles ? ($km * 0.621371192) : $km);
}

function auto_link_text($text)
{
   $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
   $callback = create_function('$matches', '
       $url       = array_shift($matches);
       $url_parts = parse_url($url);

       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
       $text = preg_replace("/^www./", "", $text);

       $last = -(strlen(strrchr($text, "/"))) + 1;
       if ($last < 0) {
           $text = substr($text, 0, $last) . "&hellip;";
       }

       return sprintf(\'<a rel="nowfollow" href="%s">%s</a>\', $url, $text);
   ');

   return preg_replace_callback($pattern, $callback, $text);
}

function extractLL($ll) {

  //echo $ll;

  $pos = (strpos($ll, "q=")+2);
  $pos2 = (strpos($ll,"(")-1-$pos);
  $ll = substr($ll,$pos,$pos2);

  $pos = (strpos($ll,","));
  $lat = substr($ll,0,$pos);
  $lon = substr($ll,($pos+1),strlen($ll));

  return array($lat,$lon);

}

function extractVenue($v) {
  $v = explode("</a>", $v);
  return $v[0]."</a>";
}

function get_fb_venue($e) {
  if($e["venue"]) if($e["venue"]["id"]) {
    global $facebook;
    $v = $facebook->api('/'.$e["venue"]["id"],'GET'); 
    return $v;
  } else {
    return false;
  }
}

function get_fb_event($id) {
  global $facebook;
  $e = $facebook->api('/'.$id,'GET');
  return $e;
}

function get_fb_image($id, $size = "normal") {
  $headers = get_headers('http://graph.facebook.com/'.$id.'/picture?type=normal',1);
  $l = $headers["Location"];
  return str_replace("_s.jpg", "_n.jpg", $l);
}

function make_content($content) {
  return "<p>".str_replace("\n", "<br>", $content)."</p>";
}

function print_r_reverse($in) { 
    $lines = explode("\n", trim($in)); 
    if (trim($lines[0]) != 'Array') { 
        // bottomed out to something that isn't an array 
        return $in; 
    } else { 
        // this is an array, lets parse it 
        if (preg_match("/(\s{5,})\(/", $lines[1], $match)) { 
            // this is a tested array/recursive call to this function 
            // take a set of spaces off the beginning 
            $spaces = $match[1]; 
            $spaces_length = strlen($spaces); 
            $lines_total = count($lines); 
            for ($i = 0; $i < $lines_total; $i++) { 
                if (substr($lines[$i], 0, $spaces_length) == $spaces) { 
                    $lines[$i] = substr($lines[$i], $spaces_length); 
                } 
            } 
        } 
        array_shift($lines); // Array 
        array_shift($lines); // ( 
        array_pop($lines); // ) 
        $in = implode("\n", $lines); 
        // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one) 
        preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER); 
        $pos = array(); 
        $previous_key = ''; 
        $in_length = strlen($in); 
        // store the following in $pos: 
        // array with key = key of the parsed array's item 
        // value = array(start position in $in, $end position in $in) 
        foreach ($matches as $match) { 
            $key = $match[1][0]; 
            $start = $match[0][1] + strlen($match[0][0]); 
            $pos[$key] = array($start, $in_length); 
            if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1; 
            $previous_key = $key; 
        } 
        $ret = array(); 
        foreach ($pos as $key => $where) { 
            // recursively see if the parsed out value is an array too 
            $ret[$key] = print_r_reverse(substr($in, $where[0], $where[1] - $where[0])); 
        } 
        return $ret; 
    } 
} 

function safe_unserialize($serialized) {
    // unserialize will return false for object declared with small cap o
    // as well as if there is any ws between O and :
    if (is_string($serialized) && strpos($serialized, "\0") === false) {
        if (strpos($serialized, 'O:') === false) {
            // the easy case, nothing to worry about
            // let unserialize do the job
            return @unserialize($serialized);
        } else if (!preg_match('/(^|;|{|})O:[0-9]+:"/', $serialized)) {
            // in case we did have a string with O: in it,
            // but it was not a true serialized object
            return @unserialize($serialized);
        }
    }
    return false;
}

function getHref($href) {
  return (strpos($href, "http") > -1) ? $href : "http://".$href;
}

function getMapLink($loc, $name) {
  $street = $loc["street"];
  $city = $loc["city"];
  $country = $loc["country"];
  return "http://maps.google.de?q=".$name."+".$street."+".$city."+".$city."+".$country;


}

function getAddress($loc) {
  $t = "";
  $street = $loc["street"];
  $city = $loc["city"];
  if($street) $t.=$street.", ";
  if($city) $t.=$city;
  return $t;
}

function getMapHTML($loc,$name) {
  if(!$loc["street"]) return false;
  return '<p class="map">Map: <a href="'.getMapLink($loc,$name).'">'.getAddress($loc).'</a></p>';
}

function getProducersEvents($id) {

  global $wpdb;
  global $post;

  $wall = getWallVar();

  print_r($wall);

  $id = getPageId($id);

  echo $id.'<br><br>';

  foreach ($wall->data as $e) {
    echo $e->from->id."<br>";
    if($e->from->id==$id) print_r($e);
  }

}

function getAssociatedProducers($id) {

    global $wpdb;
    global $post;

    $ids = $id.",";

    $myrows = $wpdb->get_results( "SELECT * FROM  wp_posts WHERE post_type = 'performers'");
    foreach ($myrows as $row) {
       $orgs = get_field("organisation_links", $row->ID);
       if($orgs) {
         foreach ($orgs as $o) {
          if( (strpos($o["association"], "Produces")>-1) &&($o["organisation"]->ID==$post->ID) ) {
             $fbID = get_field("facebook_id", $row->ID);
             if(!$fbID) continue;
             if(!is_numeric($fbID)) {
              $fbID = getPageId($fbID);
              $ids .= $fbID.",";
             } else {
              $ids .= $fbID.",";
             }
          }
         }
      }
       
    }
    
  return $ids;
}

function getProducedGigs($id) {

    $wall = getWallVar();

    foreach ($wall->data as $w) {
    
      echo ($w->from->id).",";
    }

}

function getWallVar() {
  $file =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/toolbox/inc/all-json.php";
  $var = file_get_contents($file);
  return json_decode($var); 
}

function getEventsVar() {
  $file =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/toolbox/inc/all-events.php";
  $var = file_get_contents($file);
  json_decode($var);  
  echo json_last_error();
}

function fbButton($id) {
  echo '<a href="http://www.facebook.com/'.$id.'" class="uibutton confirm">See Facebook page</a>';
}

function get_directory($type) {

    global $wpdb;

  $h = '<ul class="directory">';


    $myrows = $wpdb->get_results( "SELECT * FROM  wp_posts WHERE post_type = '".$type."' AND post_status = 'publish' ORDER BY post_title");
    foreach ($myrows as $row) {
      $h .= '<li><a href="'.$row->guid.'">'.$row->post_title.'</a></li>';
    }
    $h .= '</ul>';
    echo $h;

}
