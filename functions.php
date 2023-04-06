<?php
/**
 * towerpf-site functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package towerpf-site
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function towerpf_site_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on towerpf-site, use a find and replace
		* to change 'towerpf-site' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'towerpf-site', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'towerpf-site' ),
			'footer-menu' => __('Footer Menu', 'towerpf-site' ),
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
			'towerpf_site_custom_background_args',
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
add_action( 'after_setup_theme', 'towerpf_site_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function towerpf_site_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'towerpf_site_content_width', 640 );
}
add_action( 'after_setup_theme', 'towerpf_site_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function towerpf_site_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'towerpf-site' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'towerpf-site' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'towerpf_site_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function towerpf_site_scripts() {
	wp_enqueue_style( 'towerpf-site-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'towerpf-site-style', 'rtl', 'replace' );

	wp_enqueue_script( 'towerpf-site-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	
	if(is_page(54)){
		wp_enqueue_script('map-api', 'https://maps.googleapis.com/maps/api/js?key='. map_api_key . '&callback=initMap', [], false, true);
		wp_register_script( 'map-script', get_template_directory_uri().'/js/map.js', [], '1.0', true);
		wp_localize_script('map-script', 'wpVars', ['homeURL' => home_url()]); 
		wp_enqueue_script('map-script');
	} 

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if(is_front_page()){
		wp_enqueue_script('home-page', get_template_directory_uri() . '/js/home-page.js',array('jquery'), _S_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'towerpf_site_scripts' );

add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	if ( 'map-api' !== $handle ) {
		return $tag;
	}
	// return str_replace( ' src', ' defer src', $tag ); // defer the script
	return str_replace( ' src', ' async src', $tag ); // OR async the script
	//return str_replace( ' src', ' async defer src', $tag ); // OR do both!
}, 10, 2 );

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

function setup_porch_type() {
	register_post_type('porch', array(
		'label'					=> 'Porches',
		'slug'                  => 'porch',
		'singular'              => 'Porch',
		'plural'                => 'Porches',
		'menu_icon'             => 'dashicons-admin-home',
		'menu_position'         => 2,
		'public'                => true,
		'show_in_rest' 			=> true,
		'supports' 				=> ['title', 'editor', 'thumbnail'],
		'taxonomies' 			=> ['category']
	));
}
add_action('init', 'setup_porch_type');


function remove_default_post_type()
{
    remove_menu_page('edit.php');
}
add_action('admin_menu', 'remove_default_post_type');


// REMOVES TEXT EDITOR FOR PORCHES CUSTOM POST TYPE

add_action('init', 'my_remove_editor_from_post_type');
function my_remove_editor_from_post_type() {
remove_post_type_support( 'porch', 'editor' );

	}

