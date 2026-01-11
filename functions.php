<?php
/**
 * oso_theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package oso_theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.1.6' );
}

if ( ! function_exists( 'oso_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function oso_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on oso_theme, use a find and replace
		 * to change 'oso_theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'oso_theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'oso_theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'oso_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'oso_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function oso_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'oso_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'oso_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function oso_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'oso_theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'oso_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'oso_theme' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'oso_theme' ),
			'before_sidebar' => '<section id="%1$s" class="widget %2$s">',
			'after_sidebar'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'oso_theme' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here.', 'oso_theme' ),
			'before_sidebar' => '<section id="%1$s" class="widget %2$s">',
			'after_sidebar'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'oso_theme' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here.', 'oso_theme' ),
			'before_sidebar' => '<section id="%1$s" class="widget %2$s">',
			'after_sidebar'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'oso_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function oso_theme_scripts() {
	
	// vimeo api
	wp_enqueue_script( 'vimeo-api', 'https://player.vimeo.com/api/player.js', array(), _S_VERSION, true );
	
	wp_enqueue_style( 'oso_theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'oso_theme-style', 'rtl', 'replace' );

	wp_enqueue_style( 'oso_theme-custom-style', get_template_directory_uri() . '/css/styles.css', array(), _S_VERSION );

	wp_enqueue_script( 'oso_theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'oso_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function get_soundcloud_feed_handler() {
	$feed_url = 'https://feeds.soundcloud.com/users/soundcloud:users:1117667029/sounds.rss';
	$content = file_get_contents($feed_url);
 
	$data = array();

	// Instantiate XML element
	$a = new SimpleXMLElement($content);
	foreach($a->channel->item as $entry) {
		$data[] = array( 
			'guid' => (string)$entry->guid,
			'title' => (string)$entry->title,
			'link' => (string)$entry->link
		);
		//echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
	}

	// Your response in array
	$result = array(
		'data' => $data,
		'page' => 1,
		'paging' => array(),
		'per_page' => count($data),
		'total' => count($data)
	);
    

    // Make your array as json
	wp_send_json($result);

    // Don't forget to stop execution afterward.
    wp_die();
}
add_action( 'wp_ajax_get_soundcloud_feed', 'get_soundcloud_feed_handler' );
add_action( 'wp_ajax_nopriv_get_soundcloud_feed', 'get_soundcloud_feed_handler' );

function get_soundcloud_player_handler(){
	$thisid = $_POST["id"];
	$feed_url = 'https://feeds.soundcloud.com/users/soundcloud:users:1117667029/sounds.rss';
	$content = file_get_contents($feed_url);
 
	$data = array();

	// Instantiate XML element
	$a = new SimpleXMLElement($content);
	foreach($a->channel->item as $entry) {

		$guid = (string)$entry->guid;

		if ($guid==$thisid){		
			// Your response in array
			$result = array(
				'guid' => (string)$entry->guid,
				'title' => (string)$entry->title,
				'link' => (string)$entry->link
			);
			//echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
		}
	}

	
    

    // Make your array as json
	wp_send_json($result);

    // Don't forget to stop execution afterward.
    wp_die();
}
add_action( 'wp_ajax_get_soundcloud_player', 'get_soundcloud_player_handler' );
add_action( 'wp_ajax_nopriv_get_soundcloud_player', 'get_soundcloud_player_handler' );