<?php
/**
 * ogami functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Ogami
 * @since Ogami 1.26
 */

define( 'OGAMI_THEME_VERSION', '1.26' );
define( 'OGAMI_DEMO_MODE', false );

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

if ( ! function_exists( 'ogami_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Ogami 1.0
 */
function ogami_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ogami, use a find and replace
	 * to change 'ogami' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'ogami', get_template_directory() . '/languages' );

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
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 750, true );
	add_image_size( 'ogami-shop-mansory-large', 850, 850, true );
	add_image_size( 'ogami-shop-mansory-small', 410, 410, true );
	add_image_size( 'ogami-blog-small', 100, 100, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'ogami' ),
		'vertical-menu' => esc_html__( 'Vertical Menu', 'ogami' ),
		'my-account' => esc_html__( 'My Account Menu', 'ogami' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( "woocommerce", array('gallery_thumbnail_image_width' => 410) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = ogami_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ogami_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles.
	add_editor_style( array( 'css/style-editor.css', ogami_get_fonts_url() ) );

	ogami_get_load_plugins();
}
endif; // ogami_setup
add_action( 'after_setup_theme', 'ogami_setup' );

/**
 * Load Google Front
 */
function ogami_get_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by Montserrat, translate this to 'off'. Do not translate
    * into your own language.
    */
    $poppins = _x( 'on', 'Poppins font: on or off', 'ogami' );
    $lora = _x( 'on', 'Lora font: on or off', 'ogami' );

    if ( 'off' !== $poppins ) {
        $font_families = array();
        if ( 'off' !== $poppins ) {
            $font_families[] = 'Poppins:300,300i,400,400i,500,600,700,800';
        }
        $query_args = array(
            'family' => ( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 		
 		$protocol = is_ssl() ? 'https:' : 'http:';
        $fonts_url = add_query_arg( $query_args, $protocol .'//fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}

/**
 * Enqueue styles.
 *
 * @since Ogami 1.0
 */
function ogami_enqueue_styles() {
	
	// load font
	wp_enqueue_style( 'ogami-theme-fonts', ogami_get_fonts_url(), array(), null );

	//load font awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.5.0' );

	// load font themify icon
	wp_enqueue_style( 'font-themify', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );

	wp_enqueue_style( 'font-icomoon', get_template_directory_uri() . '/css/font-icomoon.css', array(), '1.0.0' );
	
	wp_enqueue_style( 'font-eleganticon', get_template_directory_uri() . '/css/eleganticon-style.css', array(), '1.0.0' );
	
	// load animate version 3.6.0
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '3.6.0' );

	// load bootstrap style
	if( is_rtl() ){
		wp_enqueue_style( 'bootstrap-rtl', get_template_directory_uri() . '/css/bootstrap-rtl.css', array(), '3.2.0' );
	} else {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.2.0' );
	}
	// slick
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '1.8.0' );
	// magnific-popup
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css', array(), '1.1.0' );
	// perfect scrollbar
	wp_enqueue_style( 'perfect-scrollbar', get_template_directory_uri() . '/css/perfect-scrollbar.css', array(), '0.6.12' );
	
	// main style
	wp_enqueue_style( 'ogami-template', get_template_directory_uri() . '/css/template.css', array(), '1.0' );
	
	$custom_style = ogami_custom_styles();
	if ( !empty($custom_style) ) {
		wp_add_inline_style( 'ogami-template', $custom_style );
	}
	wp_enqueue_style( 'ogami-style', get_template_directory_uri() . '/style.css', array(), '1.0' );

	wp_deregister_style('yith-wcwl-font-awesome');
}
add_action( 'wp_enqueue_scripts', 'ogami_enqueue_styles', 100 );
/**
 * Enqueue scripts.
 *
 * @since Ogami 1.0
 */
function ogami_enqueue_scripts() {
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// bootstrap
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '20150330', true );
	// slick
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
	// countdown
	wp_register_script( 'countdown', get_template_directory_uri() . '/js/countdown.js', array( 'jquery' ), '20150315', true );
	wp_localize_script( 'countdown', 'ogami_countdown_opts', array(
		'days' => esc_html__('Days', 'ogami'),
		'hours' => esc_html__('Hrs', 'ogami'),
		'mins' => esc_html__('Mins', 'ogami'),
		'secs' => esc_html__('Secs', 'ogami'),
	));
	wp_enqueue_script( 'countdown' );
	// popup
	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	// unviel
	wp_enqueue_script( 'jquery-unveil', get_template_directory_uri() . '/js/jquery.unveil.js', array( 'jquery' ), '1.1.0', true );
	// perfect scrollbar
	wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.jquery.min.js', array( 'jquery' ), '0.6.12', true );
	
	if ( ogami_get_config('enable_smooth_scroll') ) {
		wp_enqueue_script( 'SmoothScroll', get_template_directory_uri() . '/js/SmoothScroll.js', '1.3.0', true );
	}
	// main script
	wp_register_script( 'ogami-functions', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );
	wp_localize_script( 'ogami-functions', 'ogami_ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'previous' => esc_html__('Previous', 'ogami'),
		'next' => esc_html__('Next', 'ogami'),
	));
	wp_enqueue_script( 'ogami-functions' );
	
	wp_add_inline_script( 'ogami-functions', "(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);" );
}
add_action( 'wp_enqueue_scripts', 'ogami_enqueue_scripts', 1 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Ogami 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function ogami_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'ogami_search_form_modify' );

/**
 * Function get opt_name
 *
 */
function ogami_get_opt_name() {
	return 'ogami_theme_options';
}
add_filter( 'apus_framework_get_opt_name', 'ogami_get_opt_name' );


function ogami_register_demo_mode() {
	if ( defined('OGAMI_DEMO_MODE') && OGAMI_DEMO_MODE ) {
		return true;
	}
	return false;
}
add_filter( 'apus_framework_register_demo_mode', 'ogami_register_demo_mode' );

function ogami_get_demo_preset() {
	$preset = '';
    if ( defined('OGAMI_DEMO_MODE') && OGAMI_DEMO_MODE ) {
        if ( isset($_REQUEST['_preset']) && $_REQUEST['_preset'] ) {
            $presets = get_option( 'apus_framework_presets' );
            if ( is_array($presets) && isset($presets[$_REQUEST['_preset']]) ) {
                $preset = $_REQUEST['_preset'];
            }
        } else {
            $preset = get_option( 'apus_framework_preset_default' );
        }
    }
    return $preset;
}

function ogami_get_config($name, $default = '') {
	global $ogami_options;
    if ( isset($ogami_options[$name]) ) {
        return $ogami_options[$name];
    }
    return $default;
}

function ogami_set_exporter_settings_option_keys($option_keys) {
	return array_merge($option_keys, array(
		'elementor_disable_color_schemes',
		'elementor_disable_typography_schemes',
		'elementor_allow_tracking', 'elementor_cpt_support'
	));
}
add_filter( 'apus_exporter_settings_option_keys', 'ogami_set_exporter_settings_option_keys' );

function ogami_disable_one_click_import() {
	return false;
}
add_filter('apus_frammework_enable_one_click_import', 'ogami_disable_one_click_import');

function ogami_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Default', 'ogami' ),
		'id'            => 'sidebar-default',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'ogami' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Topbar Right', 'ogami' ),
		'id'            => 'sidebar-topbar-right',
		'description'   => esc_html__( 'Add widgets here to appear in your Topbar Right.', 'ogami' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Blog sidebar', 'ogami' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ogami' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name' 				=> esc_html__( 'Shop Sidebar', 'ogami' ),
		'id' 				=> 'shop-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ogami' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	));

	register_sidebar( array(
		'name' 				=> esc_html__( 'Popup Newsletter', 'ogami' ),
		'id' 				=> 'popup-newsletter',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ogami' ),
		'before_widget' => '<aside class="%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	));
}
add_action( 'widgets_init', 'ogami_widgets_init' );

function ogami_get_load_plugins() {

	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Framework For Themes', 'ogami' ),
        'slug'                     => 'apus-framework',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-framework.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Elementor Page Builder', 'ogami' ),
	    'slug'                     => 'elementor',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Revolution Slider', 'ogami' ),
        'slug'                     => 'revslider',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/revslider.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Cmb2', 'ogami' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'MailChimp for WordPress', 'ogami' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Contact Form 7', 'ogami' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	);

	// woocommerce plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'Woocommerce', 'ogami' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'WooCommerce Variation Swatches', 'ogami' ),
	    'slug'                     => 'variation-swatches-for-woocommerce',
	    'required'                 =>  false
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'YITH WooCommerce Compare', 'ogami' ),
	    'slug'                     => 'yith-woocommerce-compare',
	    'required'                 =>  false
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'YITH WooCommerce Wishlist', 'ogami' ),
	    'slug'                     => 'yith-woocommerce-wishlist',
	    'required'                 =>  false
	);
	
	$plugins[] = array(
        'name'                  => esc_html__( 'One Click Demo Import', 'ogami' ),
        'slug'                  => 'one-click-demo-import',
        'required'              => false,
    );

	tgmpa( $plugins );
}

require get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/functions-helper.php';
require get_template_directory() . '/inc/functions-frontend.php';

/**
 * Implement the Custom Header feature.
 *
 */
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/classes/megamenu.php';
require get_template_directory() . '/inc/classes/mobilemenu.php';
require get_template_directory() . '/inc/classes/mobileverticalmenu.php';

/**
 * Custom template tags for this theme.
 *
 */
require get_template_directory() . '/inc/template-tags.php';

if ( defined( 'APUS_FRAMEWORK_REDUX_ACTIVED' ) ) {
	require get_template_directory() . '/inc/vendors/redux-framework/redux-config.php';
	define( 'OGAMI_REDUX_FRAMEWORK_ACTIVED', true );
}
if( ogami_is_cmb2_activated() ) {
	require get_template_directory() . '/inc/vendors/cmb2/page.php';
	require get_template_directory() . '/inc/vendors/cmb2/product.php';
	define( 'OGAMI_CMB2_ACTIVED', true );
}
if( ogami_is_woocommerce_activated() ) {
	require get_template_directory() . '/inc/vendors/woocommerce/functions.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-search.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-redux-configs.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-swatches.php';
	define( 'OGAMI_WOOCOMMERCE_ACTIVED', true );
}
if( ogami_is_apus_framework_activated() ) {
	require get_template_directory() . '/inc/widgets/custom_menu.php';
	require get_template_directory() . '/inc/widgets/popup_newsletter.php';
	require get_template_directory() . '/inc/widgets/recent_post.php';
	require get_template_directory() . '/inc/widgets/search.php';
	require get_template_directory() . '/inc/widgets/socials.php';

	require get_template_directory() . '/inc/widgets/woo-layered-nav.php';
	define( 'OGAMI_FRAMEWORK_ACTIVED', true );
}
if( ogami_is_dokan_activated() ) {
	require get_template_directory() . '/inc/vendors/dokan/functions.php';
}

require get_template_directory() . '/inc/vendors/elementor/functions.php';
require get_template_directory() . '/inc/vendors/one-click-demo-import/functions.php';

function ogami_register_post_types($post_types) {
	foreach ($post_types as $key => $post_type) {
		if ( $post_type == 'brand' || $post_type == 'testimonial' ) {
			unset($post_types[$key]);
		}
	}
	if ( !in_array('header', $post_types) ) {
		$post_types[] = 'header';
	}
	return $post_types;
}
add_filter( 'apus_framework_register_post_types', 'ogami_register_post_types' );


/**
 * Customizer additions.
 *
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Styles
 *
 */
require get_template_directory() . '/inc/custom-styles.php';