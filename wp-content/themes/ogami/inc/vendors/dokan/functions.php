<?php

if ( ! function_exists( 'ogami_dokan_sidebars' ) ) {
	
	function ogami_dokan_sidebars() {
		register_sidebar( array(
			'name' 				=> esc_html__( 'Store Sidebar', 'ogami' ),
			'id' 				=> 'store-sidebar',
			'before_widget'		=> '<aside class="widget %2$s">',
			'after_widget' 		=> '</aside>',
			'before_title' 		=> '<h2 class="widget-title">',
			'after_title' 		=> '</h2>'
		));
	}

}

add_action( 'widgets_init', 'ogami_dokan_sidebars' );


function ogami_dokan_redux_config( $sections, $sidebars, $columns ) {
	// Dokan Store Sidebar
    $dokan_fields = array(
        array(
            'id' => 'dokan_sidebar_layout',
            'type' => 'image_select',
            'compiler' => true,
            'title' => esc_html__('Dokan Store Layout', 'ogami'),
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
            'id' => 'dokan_product_columns',
            'type' => 'select',
            'title' => esc_html__('Product Columns', 'ogami'),
            'options' => $columns,
            'default' => 4,
        ),
        array(
            'id' => 'dokan_sidebar_fullwidth',
            'type' => 'switch',
            'title' => esc_html__('Is Full Width?', 'ogami'),
            'default' => false
        ),
    );

    if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) !== 'off' ) {
    	
    	$dokan_fields[] = array(
            'id' => 'dokan_left_sidebar',
            'type' => 'select',
            'title' => esc_html__('Dokan Store Left Sidebar', 'ogami'),
            'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'ogami'),
            'options' => $sidebars
        );

        $dokan_fields[] = array(
            'id' => 'dokan_right_sidebar',
            'type' => 'select',
            'title' => esc_html__('Dokan Store Right Sidebar', 'ogami'),
            'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'ogami'),
            'options' => $sidebars
        );
    }
    $sections[] = array(
        'title' => esc_html__('Dokan Store Sidebar', 'ogami'),
        'fields' => $dokan_fields
    );

    return $sections;
}
add_filter( 'ogami_redux_framwork_configs', 'ogami_dokan_redux_config', 20, 3 );



// layout class for woo page
if ( !function_exists('ogami_dokan_content_class') ) {
    function ogami_dokan_content_class( $class ) {
        if( ogami_get_config('dokan_sidebar_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'ogami_dokan_content_class', 'ogami_dokan_content_class' );

// get layout configs
if ( !function_exists('ogami_get_dokan_layout_configs') ) {
    function ogami_get_dokan_layout_configs() {
        
                // lg and md for fullwidth
        if( ogami_get_config('dokan_sidebar_fullwidth') ) {
            $sidebar_width = 'col-lg-2 col-md-3 ';
            $main_width = 'col-lg-10 col-md-9';
        }else{
            $sidebar_width = 'col-lg-3 col-md-3 ';
            $main_width = 'col-lg-9 col-md-9 ';
        }

        $left = ogami_get_config('dokan_left_sidebar');
        $right = ogami_get_config('dokan_right_sidebar');

        switch ( ogami_get_config('dokan_sidebar_layout') ) {
            case 'left-main':
                $configs['left'] = array( 'sidebar' => $left, 'class' => $sidebar_width.' col-sm-12 col-xs-12'  );
                $configs['main'] = array( 'class' => $main_width.' col-sm-12 col-xs-12' );
                break;
            case 'main-right':
                $configs['right'] = array( 'sidebar' => $right,  'class' => $sidebar_width.' col-sm-12 col-xs-12' ); 
                $configs['main'] = array( 'class' => $main_width.' col-sm-12 col-xs-12' );
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
            case 'left-main-right':
                $configs['left'] = array( 'sidebar' => $left,  'class' => 'col-md-3 col-sm-12 col-xs-12'  );
                $configs['right'] = array( 'sidebar' => $right, 'class' => 'col-md-3 col-sm-12 col-xs-12' ); 
                $configs['main'] = array( 'class' => 'col-md-6 col-sm-12 col-xs-12' );
                break;
            default:
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
        }

        return $configs; 
    }
}