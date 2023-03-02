<?php

if ( !function_exists('ogami_get_products') ) {
    function ogami_get_products( $args = array() ) {
        global $woocommerce, $wp_query;

        $args = wp_parse_args( $args, array(
            'categories' => array(),
            'product_type' => 'recent_product',
            'paged' => 1,
            'post_per_page' => -1,
            'orderby' => '',
            'order' => '',
            'includes' => array(),
            'excludes' => array(),
            'author' => '',
        ));
        extract($args);
        
        $query_args = array(
            'post_type' => 'product',
            'posts_per_page' => $post_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'orderby'   => $orderby,
            'order' => $order
        );

        if ( isset( $query_args['orderby'] ) ) {
            if ( 'price' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_price',
                    'orderby'   => 'meta_value_num'
                ) );
            }
            if ( 'featured' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_featured',
                    'orderby'   => 'meta_value'
                ) );
            }
            if ( 'sku' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_sku',
                    'orderby'   => 'meta_value'
                ) );
            }
        }

        switch ($product_type) {
            case 'best_selling':
                $query_args['meta_key']='total_sales';
                $query_args['orderby']='meta_value_num';
                $query_args['ignore_sticky_posts']   = 1;
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'featured_product':
                $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['featured'],
                );
                break;
            case 'top_rate':
                //add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recent_product':
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;
            case 'deals':
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $query_args['meta_query'][] =  array(
                    array(
                        'key'           => '_sale_price_dates_to',
                        'value'         => time(),
                        'compare'       => '>',
                        'type'          => 'numeric'
                    )
                );
                break;     
            case 'on_sale':
                $product_ids_on_sale    = wc_get_product_ids_on_sale();
                $product_ids_on_sale[]  = 0;
                $query_args['post__in'] = $product_ids_on_sale;
                break;
            case 'recent_review':
                if($post_per_page == -1) $_limit = 4;
                else $_limit = $post_per_page;
                global $wpdb;
                $query = "SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c
                        WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0
                        ORDER BY c.comment_date ASC";
                $results = $wpdb->get_results($query, OBJECT);
                $_pids = array();
                foreach ($results as $re) {
                    if(!in_array($re->comment_post_ID, $_pids))
                        $_pids[] = $re->comment_post_ID;
                    if(count($_pids) == $_limit)
                        break;
                }

                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $query_args['post__in'] = $_pids;

                break;
            case 'rand':
                $query_args['orderby'] = 'rand';
                break;
            case 'recommended':

                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = array(
                    'key' => '_apus_recommended',
                    'value' => 'yes',
                );
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recently_viewed':
                $viewed_products = ! empty( $_COOKIE['apus_woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['apus_woocommerce_recently_viewed'] ) : array();
                $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

                if ( empty( $viewed_products ) ) {
                    return false;
                }
                $query_args['post__in'] = $viewed_products;
                break;
        }

        if ( !empty($categories) && is_array($categories) ) {
            $query_args['tax_query'][] = array(
                'taxonomy'      => 'product_cat',
                'field'         => 'slug',
                'terms'         => $categories,
                'operator'      => 'IN'
            );
        }

        if (!empty($includes) && is_array($includes)) {
            $query_args['post__in'] = $includes;
        }
        
        if ( !empty($excludes) && is_array($excludes) ) {
            $query_args['post__not_in'] = $excludes;
        }

        if ( !empty($author) ) {
            $query_args['author'] = $author;
        }
        if ( $product_type == 'top_rate' && class_exists('WC_Shortcode_Products') ) {
            add_filter( 'posts_clauses', array( 'WC_Shortcode_Products', 'order_by_rating_post_clauses' ) );
            $loop = new WP_Query($query_args);
            remove_filter( 'posts_clauses', array( 'WC_Shortcode_Products', 'order_by_rating_post_clauses' ) );
        } else {
            $loop = new WP_Query($query_args);
        }
        return $loop;
    }
}

// add product viewed
function ogami_track_product_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }

    global $post;

    if ( empty( $_COOKIE['apus_woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['apus_woocommerce_recently_viewed'] );

    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }

    if ( sizeof( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }

    // Store for session only
    wc_setcookie( 'apus_woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'ogami_track_product_view', 20 );

// Style hooks
function ogami_woocommerce_enqueue_styles() {
    wp_enqueue_style( 'ogami-wc-quantity-increment', get_template_directory_uri() .'/css/wc-quantity-increment.css' );
    wp_enqueue_style( 'ogami-woocommerce', get_template_directory_uri() .'/css/woocommerce.css' , 'ogami-woocommerce-front' , OGAMI_THEME_VERSION, 'all' );
}
add_action( 'wp_enqueue_scripts', 'ogami_woocommerce_enqueue_styles', 99 );

function ogami_woocommerce_enqueue_scripts() {
    wp_enqueue_script( 'selectWoo' );
    wp_enqueue_style( 'select2' );
    
    
    wp_enqueue_script( 'ogami-number-polyfill', get_template_directory_uri() . '/js/number-polyfill.min.js', array( 'jquery' ), '20150330', true );
    wp_enqueue_script( 'ogami-quantity-increment', get_template_directory_uri() . '/js/wc-quantity-increment.js', array( 'jquery' ), '20150330', true );

    wp_register_script( 'ogami-woocommerce', get_template_directory_uri() . '/js/woocommerce.js', array( 'jquery', 'jquery-unveil', 'slick' ), '20150330', true );

    $options = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'enable_search' => (ogami_get_config('enable_autocompleate_search', true) ? '1' : '0'),
        'template' => apply_filters( 'ogami_autocompleate_search_template', '<div class="autocomplete-list-item"><a href="{{url}}" class="media autocompleate-media"><div class="media-left media-middle"><img src="{{image}}" class="media-object" height="100" width="100"></div><div class="media-body media-middle"><h3 class="name-product">{{title}}</h3><div class="price">{{price}}</div></div></a></div>' ),
        'empty_msg' => apply_filters( 'ogami_autocompleate_search_empty_msg', esc_html__( 'Unable to find any products that match the currenty query', 'ogami' ) ),
        'nonce' => wp_create_nonce( 'ajax-nonce' ),
        'view_more_text' => esc_html__('View More', 'ogami'),
        'view_less_text' => esc_html__('View Less', 'ogami'),
        '_preset' => ogami_get_demo_preset()
    );
    wp_localize_script( 'ogami-woocommerce', 'ogami_woo_options', $options );
    wp_enqueue_script( 'ogami-woocommerce' );
    
    wp_enqueue_script( 'wc-add-to-cart-variation' );

    
}
add_action( 'wp_enqueue_scripts', 'ogami_woocommerce_enqueue_scripts', 10 );

// cart
if ( !function_exists('ogami_woocommerce_header_add_to_cart_fragment') ) {
    function ogami_woocommerce_header_add_to_cart_fragment( $fragments ){
        global $woocommerce;
        $fragments['.cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        $fragments['.footer-mini-cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        $fragments['.cart .total-minicart'] = '<div class="total-minicart">'. $woocommerce->cart->get_cart_total(). '</div>';
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'ogami_woocommerce_header_add_to_cart_fragment' );

// breadcrumb for woocommerce page
if ( !function_exists('ogami_woocommerce_breadcrumb_defaults') ) {
    function ogami_woocommerce_breadcrumb_defaults( $args ) {
        $breadcrumb_img = ogami_get_config('woo_breadcrumb_image');
        $breadcrumb_color = ogami_get_config('woo_breadcrumb_color');
        $style = $classes = array();
        $show_breadcrumbs = ogami_get_config('show_product_breadcrumbs', true);

        if ( !$show_breadcrumbs ) {
            $style[] = 'display:none';
        }
        if( $breadcrumb_color  ){
            $style[] = 'background-color:'.$breadcrumb_color;
        }
        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
            $classes[] = 'has_bg';
        }
        $estyle = !empty($style) ? ' style="'.implode(";", $style).'"':"";
        if ( is_single() ) {
            $classes[] = 'woo-detail';
        }

        $full_width = apply_filters('ogami_woocommerce_content_class', 'container');
        
        $args['wrap_before'] = '<section id="apus-breadscrumb" class="apus-breadscrumb woo-breadcrumb '.esc_attr(!empty($classes) ? implode(' ', $classes) : '').'"'.$estyle.'><div class="'.$full_width.'"><div class="wrapper-breads"><div class="wrapper-breads-inner">
        <ol class="breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>';
        $args['wrap_after'] = '</ol></div></div></div></section>';

        return $args;
    }
}
add_filter( 'woocommerce_breadcrumb_defaults', 'ogami_woocommerce_breadcrumb_defaults' );
add_action( 'ogami_woo_template_main_before', 'woocommerce_breadcrumb', 30, 0 );

// display woocommerce modes
if ( !function_exists('ogami_woocommerce_display_modes') ) {
    function ogami_woocommerce_display_modes(){
        global $wp;
        $current_url = ogami_shop_page_link(true);

        $url_grid = add_query_arg( 'display_mode', 'grid', remove_query_arg( 'display_mode', $current_url ) );
        $url_list = add_query_arg( 'display_mode', 'list', remove_query_arg( 'display_mode', $current_url ) );

        $woo_mode = ogami_woocommerce_get_display_mode();

        echo '<div class="display-mode pull-right">';
        echo '<a href="'.  $url_grid  .'" class=" change-view '.($woo_mode == 'grid' ? 'active' : '').'"><i class="ti-layout-grid3"></i></a>';
        echo '<a href="'.  $url_list  .'" class=" change-view '.($woo_mode == 'list' ? 'active' : '').'"><i class="ti-view-list-alt"></i></a>';
        echo '</div>'; 
    }
}

if ( !function_exists('ogami_woocommerce_get_display_mode') ) {
    function ogami_woocommerce_get_display_mode() {
        $woo_mode = ogami_get_config('product_display_mode', 'grid');
        $args = array( 'grid', 'list' );
        if ( isset($_COOKIE['ogami_woo_mode']) && in_array($_COOKIE['ogami_woo_mode'], $args) ) {
            $woo_mode = $_COOKIE['ogami_woo_mode'];
        }
        return $woo_mode;
    }
}

if(!function_exists('ogami_shop_page_link')) {
    function ogami_shop_page_link($keep_query = false ) {
        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        } elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
            $link = get_post_type_archive_link( 'product' );
        } else {
            $link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
        }

        if( $keep_query ) {
            // Keep query string vars intact
            foreach ( $_GET as $key => $val ) {
                if ( 'orderby' === $key || 'submit' === $key ) {
                    continue;
                }
                $link = add_query_arg( $key, $val, $link );

            }
        }
        return $link;
    }
}


// add filter to top archive
add_action( 'woocommerce_top_pagination', 'woocommerce_pagination', 1 );

if ( !function_exists('ogami_before_woocommerce_init') ) {
    function ogami_before_woocommerce_init() {
        // set display mode to cookie
        if( isset($_GET['display_mode']) && ($_GET['display_mode']=='list' || $_GET['display_mode']=='grid') ){  
            setcookie( 'ogami_woo_mode', trim($_GET['display_mode']) , time()+3600*24*100,'/' );
            $_COOKIE['ogami_woo_mode'] = trim($_GET['display_mode']);
        }

        if ( ogami_get_config('show_quickview', false) ) {
            add_action( 'wp_ajax_ogami_quickview_product', 'ogami_woocommerce_quickview' );
            add_action( 'wp_ajax_nopriv_ogami_quickview_product', 'ogami_woocommerce_quickview' );
        }

        add_action( 'wp_ajax_ogami_ajax_get_products', 'ogami_woocommerce_get_ajax_products' );
        add_action( 'wp_ajax_nopriv_ogami_ajax_get_products', 'ogami_woocommerce_get_ajax_products' );
    }
}
add_action( 'init', 'ogami_before_woocommerce_init' );

function ogami_woocommerce_get_ajax_products() {
    if ( ogami_is_yith_woocompare_activated() ) {
        $compare_path = WP_PLUGIN_DIR.'/yith-woocommerce-compare/includes/class.yith-woocompare-frontend.php';
        if ( file_exists($compare_path) ) {
            require_once ($compare_path);
        }
    }

    $settings = isset($_POST['settings']) ? $_POST['settings'] : '';

    $tab = isset($_POST['tab']) ? $_POST['tab'] : '';
    
    if ( empty($settings) || empty($tab) ) {
        exit();
    }

    $slugs = !empty($tab['slugs']) ? array_map('trim', explode(',', $tab['slugs'])) : array();

    $columns = isset($settings['columns']) ? $settings['columns'] : 4;
    $rows = isset($settings['rows']) ? $settings['rows'] : 1;
    $show_nav = isset($settings['show_nav']) ? $settings['show_nav'] : false;
    $show_pagination = isset($settings['show_pagination']) ? $settings['show_pagination'] : false;
    $limit = isset($settings['limit']) ? $settings['limit'] : 4;
    $product_type = isset($tab['type']) ? $tab['type'] : 'recent_product';

    $layout_type = isset($settings['layout_type']) ? $settings['layout_type'] : 'grid';

    $args = array(
        'categories' => $slugs,
        'product_type' => $product_type,
        'paged' => 1,
        'post_per_page' => $limit,
    );

    $loop = ogami_get_products( $args );
    if ( $loop->have_posts() ) {
        $max_pages = $loop->max_num_pages;
        wc_get_template( 'layout-products/'.$layout_type.'.php' , array(
            'loop' => $loop,
            'columns' => $columns,
            'rows' => $rows,
            'show_nav' => $show_nav,
            'show_pagination' => $show_pagination,
        ) );
    }
    exit();
}

// quickview
if ( !function_exists('ogami_woocommerce_quickview') ) {
    function ogami_woocommerce_quickview() {
        if ( !empty($_GET['product_id']) ) {
            $args = array(
                'post_type' => 'product',
                'post__in' => array($_GET['product_id'])
            );
            $query = new WP_Query($args);
            if ( $query->have_posts() ) {
                while ($query->have_posts()): $query->the_post(); global $product;
                    wc_get_template_part( 'content', 'product-quickview' );
                endwhile;
            }
            wp_reset_postdata();
        }
        die;
    }
}

// Number of products per page
if ( !function_exists('ogami_woocommerce_shop_per_page') ) {
    function ogami_woocommerce_shop_per_page($number) {
        
        if ( isset( $_REQUEST['wppp_ppp'] ) ) :
            $number = intval( $_REQUEST['wppp_ppp'] );
            WC()->session->set( 'products_per_page', intval( $_REQUEST['wppp_ppp'] ) );
        elseif ( isset( $_REQUEST['ppp'] ) ) :
            $number = intval( $_REQUEST['ppp'] );
            WC()->session->set( 'products_per_page', intval( $_REQUEST['ppp'] ) );
        elseif ( WC()->session->__isset( 'products_per_page' ) ) :
            $number = intval( WC()->session->__get( 'products_per_page' ) );
        else :
            $value = ogami_get_config('number_products_per_page', 12);
            $number = intval( $value );
        endif;
        
        return $number;

    }
}
add_filter( 'loop_shop_per_page', 'ogami_woocommerce_shop_per_page', 30 );

// Number of products per row
if ( !function_exists('ogami_woocommerce_shop_columns') ) {
    function ogami_woocommerce_shop_columns($number) {
        $value = ogami_get_config('product_columns');
        if ( in_array( $value, array(1, 2, 3, 4, 5, 6, 7, 8) ) ) {
            $number = $value;
        }
        return $number;
    }
}
add_filter( 'loop_shop_columns', 'ogami_woocommerce_shop_columns' );

// share box
if ( !function_exists('ogami_woocommerce_share_box') ) {
    function ogami_woocommerce_share_box() {
        if ( ogami_get_config('show_product_social_share', false) ) {
            get_template_part( 'template-parts/sharebox' );
        }
    }
}
add_filter( 'woocommerce_single_product_summary', 'ogami_woocommerce_share_box', 100 );

// add div top infor for detail
function ogami_woo_before_detail_info() {
    ?>
    <div class="price-rating-wrapper clearfix">
    <?php
}
function ogami_woo_after_detail_info() {
    ?>
    </div>
    <?php
}
function ogami_woo_clearfix_addcart() {
    ?>
    <div class="clearfix"></div>
    <?php
}

add_filter( 'woocommerce_single_product_summary', 'ogami_woo_before_detail_info', 9 );
add_filter( 'woocommerce_single_product_summary', 'ogami_woo_after_detail_info', 12 );

add_filter( 'woocommerce_single_product_summary', 'ogami_woo_clearfix_addcart', 39 );
// shipping infomation
if ( !function_exists('ogami_woocommerce_delivery_info') ) {
    function ogami_woocommerce_delivery_info() {
        $delivery_info = ogami_get_config('product_delivery_info');
        if ( !empty($delivery_info) ) {
            echo "<div class='delivery_info'>";
            echo wp_kses_post($delivery_info);
            echo "</div>";
        }
    }
}
add_filter( 'woocommerce_single_product_summary', 'ogami_woocommerce_delivery_info', 8 );
add_filter( 'ogami_list_shipping_info', 'ogami_woocommerce_delivery_info', 10);

// shipping infomation
if ( !function_exists('ogami_woocommerce_shipping_info') ) {
    function ogami_woocommerce_shipping_info() {
        $shipping_info = ogami_get_config('product_shipping_info');
        if ( !empty($shipping_info) ) {
            echo "<div class='shipping_info'>";
            echo wp_kses_post($shipping_info);
            echo "</div>";
        }
    }
}
add_filter( 'woocommerce_single_product_summary', 'ogami_woocommerce_shipping_info', 60 );

function ogami_woo_display_product_cat($product_id) {
    $terms = get_the_terms( $product_id, 'product_cat' );
    if ( !empty($terms) ) { ?>
        <div class="product-cat">
        <?php foreach ( $terms as $term ) {
            echo '<a class="text-theme" href="' . get_term_link( $term ) . '">' . $term->name . '</a>';
            break;
        } ?>
        </div>
    <?php
    }
}


// Wishlist
add_filter( 'yith_wcwl_button_label', 'ogami_woocomerce_icon_wishlist'  );
add_filter( 'yith-wcwl-browse-wishlist-label', 'ogami_woocomerce_icon_wishlist_add' );
function ogami_woocomerce_icon_wishlist( $value='' ){
    return '<i class="icon_heart_alt"></i>';
}

function ogami_woocomerce_icon_wishlist_add(){
    return '<i class="icon_heart"></i>';
}

function ogami_yith_wcwl_positions($positions) {
    $layout = ogami_get_config('product_single_version', 'v2');
    if ( isset($positions['add-to-cart']['hook']) ) {
        if ( $layout == 'v1' ) {
            $positions['add-to-cart']['hook'] = 'ogami_woocommerce_single_product_summary';
        }
        $positions['add-to-cart']['priority'] = 6;
    }
    return $positions;
}
add_filter( 'yith_wcwl_positions', 'ogami_yith_wcwl_positions', 100 );

// countdown
function ogami_woocommerce_single_countdown() {
    if ( ogami_get_config('show_product_countdown_timer') ) {
        get_template_part( 'woocommerce/single-product/countdown' );
    }
}
add_action('woocommerce_single_product_summary', 'ogami_woocommerce_single_countdown', 15);

function ogami_woocommerce_single_title_wrapper_open() {
    echo '<div class="clearfix title-cat-wishlist-wrapper">';
}
function ogami_woocommerce_single_title_wrapper_close() {
    echo '</div>';
}




// swap effect
if ( !function_exists('ogami_swap_images') ) {
    function ogami_swap_images() {
        global $post, $product, $woocommerce;
        
        $thumb = 'woocommerce_thumbnail';
        $output = '';
        $class = "attachment-$thumb size-$thumb image-no-effect";
        if (has_post_thumbnail()) {
            $swap_image = ogami_get_config('enable_swap_image', true);
            if ( $swap_image ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = "attachment-$thumb size-$thumb image-hover";
                    $swap_class = "attachment-$thumb size-$thumb image-effect";
                    $output .= ogami_get_attachment_thumbnail( $attachment_ids[0], $thumb, false, array('class' => $swap_class), false);
                }
            }
            $output .= ogami_get_attachment_thumbnail( get_post_thumbnail_id(), $thumb , false, array('class' => $class), false);
        } else {
            $image_sizes = get_option('shop_catalog_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_attr__('Placeholder' , 'ogami').'" class="'.$class.'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
        }
        echo trim($output);
    }
}
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'ogami_swap_images', 10);

if ( !function_exists('ogami_product_image') ) {
    function ogami_product_image($thumb = 'woocommerce_thumbnail') {
        $swap_image = (bool)ogami_get_config('enable_swap_image', true);
        ?>
        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
            <?php ogami_product_get_image($thumb, $swap_image); ?>
        </a>
        <?php
    }
}
// get image
if ( !function_exists('ogami_product_get_image') ) {
    function ogami_product_get_image($thumb = 'woocommerce_thumbnail', $swap = true) {
        global $post, $product, $woocommerce;
        
        $output = '';
        $class = "attachment-$thumb size-$thumb image-no-effect";
        if (has_post_thumbnail()) {
            if ( $swap ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = "attachment-$thumb size-$thumb image-hover";
                    $swap_class = "attachment-$thumb size-$thumb image-effect";
                    $output .= ogami_get_attachment_thumbnail( $attachment_ids[0], $thumb , false, array('class' => $swap_class), false);
                }
            }
            $output .= ogami_get_attachment_thumbnail( get_post_thumbnail_id(), $thumb , false, array('class' => $class), false);
        } else {
            $image_sizes = get_option('shop_catalog_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_attr__('Placeholder' , 'ogami').'" class="'.$class.'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
        }
        echo trim($output);
    }
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


function ogami_wc_get_gallery_image_html( $attachment_id, $main_image = false ) {
    $flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
    $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
    $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
    $image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
    $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
    $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
    $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
    
    
    $img = ogami_get_attachment_thumbnail($attachment_id, $image_size);
    return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '">' . $img . '</a></div>';
}

// layout class for woo page
if ( !function_exists('ogami_woocommerce_content_class') ) {
    function ogami_woocommerce_content_class( $class ) {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        if( ogami_get_config('product_'.$page.'_fullwidth') ) {
            return 'container-fluid max-1800';
        }
        return $class;
    }
}
add_filter( 'ogami_woocommerce_content_class', 'ogami_woocommerce_content_class' );

// get layout configs
if ( !function_exists('ogami_get_woocommerce_layout_configs') ) {
    function ogami_get_woocommerce_layout_configs() {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        $left = ogami_get_config('product_'.$page.'_left_sidebar');
        $right = ogami_get_config('product_'.$page.'_right_sidebar');
        // check full width
        if( ogami_get_config('product_'.$page.'_fullwidth') ) {
            $sidebar = 'col-lg-2';
            $main_full = 'col-lg-10';
        }else{
            $sidebar = 'col-lg-3';
            $main_full = 'col-lg-9';
        }
        switch ( ogami_get_config('product_'.$page.'_layout') ) {
            case 'left-main':
                $configs['left'] = array( 'sidebar' => $left, 'class' => $sidebar.' col-md-3 col-sm-12 col-xs-12'  );
                $configs['main'] = array( 'class' => $main_full.' col-md-9 col-sm-12 col-xs-12' );
                break;
            case 'main-right':
                $configs['right'] = array( 'sidebar' => $right,  'class' => $sidebar.' col-md-3 col-sm-12 col-xs-12' ); 
                $configs['main'] = array( 'class' => $main_full.' col-md-9 col-sm-12 col-xs-12' );
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
            default:
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
        }
        return $configs; 
    }
}

if ( !function_exists( 'ogami_product_review_tab' ) ) {
    function ogami_product_review_tab($tabs) {
        global $post;
        if ( !ogami_get_config('show_product_review_tab', true) && isset($tabs['reviews']) ) {
            unset( $tabs['reviews'] ); 
        }

        if ( !ogami_get_config('hidden_product_additional_information_tab', false) && isset($tabs['additional_information']) ) {
            unset( $tabs['additional_information'] ); 
        }
        
        return $tabs;
    }
}
add_filter( 'woocommerce_product_tabs', 'ogami_product_review_tab', 90 );



// Loop
if ( ! function_exists( 'ogami_wc_products_per_page' ) ) {
    function ogami_wc_products_per_page() {
        global $wp_query;

        $action = '';
        $cat                = $wp_query->get_queried_object();
        $return_to_first    = apply_filters( 'ogami_wc_ppp_return_to_first', false );
        $total              = $wp_query->found_posts;
        $per_page           = $wp_query->get( 'posts_per_page' );
        $_per_page          = ogami_get_config('number_products_per_page', 12);

        // Generate per page options
        $products_per_page_options = array();
        while ( $_per_page < $total ) {
            $products_per_page_options[] = $_per_page;
            $_per_page = $_per_page * 2;
        }

        if ( empty( $products_per_page_options ) ) {
            return;
        }

        $products_per_page_options[] = -1;

        $query_string = ! empty( $_GET['QUERY_STRING'] ) ? '?' . add_query_arg( array( 'ppp' => false ), $_GET['QUERY_STRING'] ) : null;

        if ( isset( $cat->term_id ) && isset( $cat->taxonomy ) && $return_to_first ) {
            $action = get_term_link( $cat->term_id, $cat->taxonomy ) . $query_string;
        } elseif ( $return_to_first ) {
            $action = get_permalink( wc_get_page_id( 'shop' ) ) . $query_string;
        }

        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        ?>
        <form method="POST" action="<?php echo esc_url( $action ); ?>" class="form-ogami-ppp">
            <?php
            foreach ( $_GET as $key => $value ) {
                if ( 'ppp' === $key || 'submit' === $key ) {
                    continue;
                }
                if ( is_array( $value ) ) {
                    foreach( $value as $i_value ) {
                        ?>
                        <input type="hidden" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( $i_value ); ?>" />
                        <?php
                    }
                } else {
                    ?><input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" /><?php
                }
            }
            ?>

            <select name="ppp" onchange="this.form.submit()" class="ogami-wc-wppp-select">
                <?php foreach( $products_per_page_options as $key => $value ) { ?>
                    <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $per_page ); ?>><?php
                        $ppp_text = apply_filters( 'ogami_wc_ppp_text', esc_html__( 'Show: %s', 'ogami' ), $value );
                        esc_html( printf( $ppp_text, $value == -1 ? esc_html__( 'All', 'ogami' ) : $value ) );
                    ?></option>
                <?php } ?>
            </select>
        </form>
        <?php
    }
}

function ogami_woo_after_shop_loop_before() {
    ?>
    <div class="apus-after-loop-shop clearfix">
    <?php
}
function ogami_woo_after_shop_loop_after() {
    ?>
    </div>
    <?php
}
add_action( 'woocommerce_after_shop_loop', 'ogami_woo_after_shop_loop_before', 1 );
add_action( 'woocommerce_after_shop_loop', 'ogami_woo_after_shop_loop_after', 99999 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 30 );


function ogami_show_page_title($return) {
    return false;
}
add_filter( 'woocommerce_show_page_title', 'ogami_show_page_title', 100 );


if (!function_exists('ogami_filter_before')) {
    function ogami_filter_before() {
        echo '<div class="wrapper-fillter"><div class="apus-filter"><div class="row">';
    }
}

if (!function_exists('ogami_filter_after')) {
    function ogami_filter_after() {
        echo '</div></div></div>';
    }
}

if (!function_exists('ogami_shop_cat_title')) {
    function ogami_shop_cat_title() {
        $sidebar_configs = ogami_get_woocommerce_layout_configs();
        ?>
        <div class="col-md-3 col-xs-12 right-inner">
            <h1 class="shop-page-title pull-left"><?php woocommerce_page_title(); ?></h1>
            <?php ogami_before_content( $sidebar_configs ); ?>
        </div>
        <?php
    }
}
function ogami_filter_colmun_before() {
    ?>
    <div class="col-md-9 col-xs-12 wrapper-lert">
        <div class="left-inner clearfix">
    <?php
}
function ogami_filter_colmun_after() {
    ?>
    </div></div>
    <?php
}
add_action( 'woocommerce_before_shop_loop', 'ogami_filter_before' , 11 );
add_action( 'woocommerce_before_shop_loop', 'ogami_shop_cat_title', 20 );
add_action( 'woocommerce_before_shop_loop', 'ogami_filter_colmun_before', 25 );
add_action( 'woocommerce_before_shop_loop', 'ogami_wc_products_per_page', 40 );
add_action( 'woocommerce_before_shop_loop', 'ogami_woocommerce_display_modes', 50 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_before_shop_loop', 'ogami_filter_colmun_after' , 99 );
add_action( 'woocommerce_before_shop_loop', 'ogami_filter_after' , 100 );


function ogami_show_sale_percentage_loop() {
    global $product;
     
    if ( $product->is_on_sale() ) {
        if ( ! $product->is_type( 'variable' ) ) {
            $price = $product->get_regular_price();
            $sale = $product->get_sale_price();
            if ( $sale && $price ) {
                $max_percentage = ( ( $price - $sale ) / $price ) * 100;
            }
        } else {
            $max_percentage = 0;
            foreach ( $product->get_children() as $child_id ) {
                $variation = wc_get_product( $child_id );
                $price = $variation->get_regular_price();
                $sale = $variation->get_sale_price();
                $percentage = 0;
                if ( $price != 0 && ! empty( $sale ) ) {
                    $percentage = ( $price - $sale ) / $price * 100;
                }
                if ( $percentage > $max_percentage ) {
                    $max_percentage = $percentage;
                }
            }
        }
        if ( !empty($max_percentage) ) {
            echo "<div class='sale-perc'>-" . round($max_percentage) . "%</div>";
        }
    }
 
}
add_action( 'ogami_woocommerce_loop_sale_flash', 'ogami_show_sale_percentage_loop', 25 );

// catalog mode
add_action( 'wp', 'ogami_catalog_mode_init' );
add_action( 'wp', 'ogami_pages_redirect' );

function ogami_catalog_mode_init() {
    if( ! ogami_get_config( 'enable_shop_catalog' ) ) return false;

    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

function ogami_pages_redirect() {
    if( ! ogami_get_config( 'enable_shop_catalog' ) ) return false;

    $cart     = is_page( wc_get_page_id( 'cart' ) );
    $checkout = is_page( wc_get_page_id( 'checkout' ) );

    wp_reset_postdata();

    if ( $cart || $checkout ) {
        wp_redirect( home_url() );
        exit;
    }
}



function ogami_display_out_of_stock() {
    global $product;
    if ( ! $product->is_in_stock() ) {
        echo '<p class="stock out-of-stock">'.esc_html__('SOLD OUT', 'ogami').'</p>';
    }
}
add_action( 'ogami_woocommerce_loop_sale_flash', 'ogami_display_out_of_stock', 10 );