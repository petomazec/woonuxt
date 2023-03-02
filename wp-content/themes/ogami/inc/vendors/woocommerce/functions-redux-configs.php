<?php

// Shop Archive settings
function ogami_woo_redux_config($sections, $sidebars, $columns) {
    $attributes = array();
    if ( is_admin() ) {
        $attrs = wc_get_attribute_taxonomies();
        if ( $attrs ) {
            foreach ( $attrs as $tax ) {
                $attributes[wc_attribute_taxonomy_name( $tax->attribute_name )] = $tax->attribute_label;
            }
        }
    }
    $sections[] = array(
        'icon' => 'el el-shopping-cart',
        'title' => esc_html__('Shop Settings', 'ogami'),
        'fields' => array(
            array (
                'id' => 'products_general_total_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'enable_shop_catalog',
                'type' => 'switch',
                'title' => esc_html__('Enable Shop Catalog', 'ogami'),
                'default' => 0,
                'subtitle' => esc_html__('Enable Catalog Mode for disable Add To Cart button, Cart, Checkout', 'ogami'),
            ),
            array (
                'id' => 'products_watches_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Swatches Variation Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'show_product_swatches_on_grid',
                'type' => 'switch',
                'title' => esc_html__('Show Swatches On Product Grid', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'product_swatches_attribute',
                'type' => 'select',
                'title' => esc_html__( 'Grid swatch attribute to display', 'ogami' ),
                'subtitle' => esc_html__( 'Choose attribute that will be shown on products grid', 'ogami' ),
                'options' => $attributes
            ),
            array(
                'id' => 'show_product_swatches_use_images',
                'type' => 'switch',
                'title' => esc_html__('Use images from product variations', 'ogami'),
                'subtitle' => esc_html__( 'If enabled swatches buttons will be filled with images choosed for product variations and not with images uploaded to attribute terms.', 'ogami' ),
                'default' => 1
            ),
            array(
                'id' => 'products_breadcrumb_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Breadcrumbs Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'show_product_breadcrumbs',
                'type' => 'switch',
                'title' => esc_html__('Breadcrumbs', 'ogami'),
                'default' => 1
            ),
            array (
                'title' => esc_html__('Breadcrumbs Background Color', 'ogami'),
                'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'ogami').'</em>',
                'id' => 'woo_breadcrumb_color',
                'type' => 'color',
                'transparent' => false,
            ),
            array(
                'id' => 'woo_breadcrumb_image',
                'type' => 'media',
                'title' => esc_html__('Breadcrumbs Background', 'ogami'),
                'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'ogami'),
            ),
        )
    );
    // Archive settings
    $sections[] = array(
        'title' => esc_html__('Product Archives', 'ogami'),
        'subsection' => true,
        'fields' => array(
            array(
                'id' => 'products_general_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'show_shop_cat_title',
                'type' => 'switch',
                'title' => esc_html__('Show Shop/Category Title ?', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'product_display_mode',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'ogami'),
                'subtitle' => esc_html__('Choose a default layout archive product.', 'ogami'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'ogami'),
                    'list' => esc_html__('List', 'ogami'),
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'product_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'ogami'),
                'options' => $columns,
                'default' => 4,
                'required' => array('product_display_mode', '=', array('grid'))
            ),
            array(
                'id' => 'number_products_per_page',
                'type' => 'text',
                'title' => esc_html__('Number of Products Per Page', 'ogami'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider'
            ),
            array(
                'id' => 'show_quickview',
                'type' => 'switch',
                'title' => esc_html__('Show Quick View', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'enable_swap_image',
                'type' => 'switch',
                'title' => esc_html__('Enable Swap Image', 'ogami'),
                'default' => 1
            ),

            array(
                'id' => 'show_archive_product_recent_viewed',
                'type' => 'switch',
                'title' => esc_html__('Show Products Recent Viewed', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'number_archive_product_recent_viewed',
                'title' => esc_html__('Number of Recent Viewed products to show', 'ogami'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '50',
                'type' => 'slider',
                'required' => array('show_archive_product_recent_viewed', '=', true)
            ),
            array(
                'id' => 'recent_archive_viewed_product_columns',
                'type' => 'select',
                'title' => esc_html__('Recent Viewed Products Columns', 'ogami'),
                'options' => $columns,
                'default' => 4,
                'required' => array('show_archive_product_recent_viewed', '=', true)
            ),

            array(
                'id' => 'products_sidebar_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Sidebar Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'product_archive_fullwidth',
                'type' => 'switch',
                'title' => esc_html__('Is Full Width?', 'ogami'),
                'default' => false
            ),
            array(
                'id' => 'product_archive_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Archive Product Layout', 'ogami'),
                'subtitle' => esc_html__('Select the layout you want to apply on your archive product page.', 'ogami'),
                'options' => array(
                    'main' => array(
                        'title' => esc_html__('Main Content', 'ogami'),
                        'alt' => esc_html__('Main Content', 'ogami'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                    ),
                    'left-main' => array(
                        'title' => esc_html__('Left Sidebar - Main Content', 'ogami'),
                        'alt' => esc_html__('Left Sidebar - Main Content', 'ogami'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                    ),
                    'main-right' => array(
                        'title' => esc_html__('Main Content - Right Sidebar', 'ogami'),
                        'alt' => esc_html__('Main Content - Right Sidebar', 'ogami'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                    ),
                ),
                'default' => 'left-main'
            ),
            array(
                'id' => 'product_archive_left_sidebar',
                'type' => 'select',
                'title' => esc_html__('Archive Left Sidebar', 'ogami'),
                'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'ogami'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_archive_right_sidebar',
                'type' => 'select',
                'title' => esc_html__('Archive Right Sidebar', 'ogami'),
                'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'ogami'),
                'options' => $sidebars
            ),
        )
    );
    
    
    // Product Page
    $sections[] = array(
        'title' => esc_html__('Single Product', 'ogami'),
        'subsection' => true,
        'fields' => array(
            array (
                'id' => 'product_general_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'product_single_version',
                'type' => 'select',
                'title' => esc_html__('Product Layout', 'ogami'),
                'options' => array(
                    'v1' => esc_html__('Layout 1 (3 Columns)', 'ogami'),
                    'v2' => esc_html__('Layout 2 (2 Columns)', 'ogami'),
                ),
                'default' => 'v2',
            ),
            array(
                'id' => 'product_thumbs_position',
                'type' => 'select',
                'title' => esc_html__('Thumbnails Position', 'ogami'),
                'options' => array(
                    'thumbnails-left' => esc_html__('Thumbnails Left', 'ogami'),
                    'thumbnails-right' => esc_html__('Thumbnails Right', 'ogami'),
                    'thumbnails-bottom' => esc_html__('Thumbnails Bottom', 'ogami'),
                ),
                'default' => 'thumbnails-left',
            ),
            array(
                'id' => 'number_product_thumbs',
                'title' => esc_html__('Number Thumbnails Per Row', 'ogami'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '8',
                'type' => 'slider',
            ),
            array(
                'id' => 'product_delivery_info',
                'type' => 'editor',
                'title' => esc_html__('Delivery Information', 'ogami'),
                'default' => '',
            ),
            array(
                'id' => 'product_shipping_info',
                'type' => 'editor',
                'title' => esc_html__('Shipping Information', 'ogami'),
                'default' => '',
            ),
            array(
                'id' => 'show_product_countdown_timer',
                'type' => 'switch',
                'title' => esc_html__('Show Product CountDown Timer', 'ogami'),
                'subtitle' => esc_html__('For only product deal', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_meta',
                'type' => 'switch',
                'title' => esc_html__('Show Product Meta', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_social_share',
                'type' => 'switch',
                'title' => esc_html__('Show Social Share', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_review_tab',
                'type' => 'switch',
                'title' => esc_html__('Show Product Review Tab', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'hidden_product_additional_information_tab',
                'type' => 'switch',
                'title' => esc_html__('Hidden Product Additional Information Tab', 'ogami'),
                'default' => 1
            ),

            array (
                'id' => 'product_sidebar_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Sidebar Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'product_single_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Single Product Sidebar Layout', 'ogami'),
                'subtitle' => esc_html__('Select the layout you want to apply on your Single Product Page.', 'ogami'),
                'options' => array(
                    'main' => array(
                        'title' => esc_html__('Main Only', 'ogami'),
                        'alt' => esc_html__('Main Only', 'ogami'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                    ),
                    'left-main' => array(
                        'title' => esc_html__('Left - Main Sidebar', 'ogami'),
                        'alt' => esc_html__('Left - Main Sidebar', 'ogami'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                    ),
                    'main-right' => array(
                        'title' => esc_html__('Main - Right Sidebar', 'ogami'),
                        'alt' => esc_html__('Main - Right Sidebar', 'ogami'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                    ),
                ),
                'default' => 'left-main'
            ),
            array(
                'id' => 'product_single_fullwidth',
                'type' => 'switch',
                'title' => esc_html__('Is Full Width?', 'ogami'),
                'default' => false
            ),
            array(
                'id' => 'product_single_left_sidebar',
                'type' => 'select',
                'title' => esc_html__('Single Product Left Sidebar', 'ogami'),
                'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'ogami'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_single_right_sidebar',
                'type' => 'select',
                'title' => esc_html__('Single Product Right Sidebar', 'ogami'),
                'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'ogami'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_block_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Product Block Setting', 'ogami').'</h3>',
            ),
            array(
                'id' => 'show_product_releated',
                'type' => 'switch',
                'title' => esc_html__('Show Products Releated', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'number_product_releated',
                'title' => esc_html__('Number of related products to show', 'ogami'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '50',
                'type' => 'slider',
                'required' => array('show_product_releated', '=', true)
            ),
            array(
                'id' => 'releated_product_columns',
                'type' => 'select',
                'title' => esc_html__('Releated Products Columns', 'ogami'),
                'options' => $columns,
                'default' => 4,
                'required' => array('show_product_releated', '=', true)
            ),

            array(
                'id' => 'show_product_upsells',
                'type' => 'switch',
                'title' => esc_html__('Show Products upsells', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'upsells_product_columns',
                'type' => 'select',
                'title' => esc_html__('Upsells Products Columns', 'ogami'),
                'options' => $columns,
                'default' => 4,
                'required' => array('show_product_upsells', '=', true)
            ),
            array(
                'id' => 'show_product_recent_viewed',
                'type' => 'switch',
                'title' => esc_html__('Show Products Recent Viewed', 'ogami'),
                'default' => 1
            ),
            array(
                'id' => 'number_product_recent_viewed',
                'title' => esc_html__('Number of Recent Viewed products to show', 'ogami'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '50',
                'type' => 'slider',
                'required' => array('show_product_recent_viewed', '=', true)
            ),
            array(
                'id' => 'recent_viewed_product_columns',
                'type' => 'select',
                'title' => esc_html__('Recent Viewed Products Columns', 'ogami'),
                'options' => $columns,
                'default' => 4,
                'required' => array('show_product_recent_viewed', '=', true)
            ),
        )
    );
    
    return $sections;
}
add_filter( 'ogami_redux_framwork_configs', 'ogami_woo_redux_config', 10, 3 );