<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ogami_ocdi_import_files() {
    $demos = array();
    $demos[] = array(
        'import_file_name'             => 'Home',
        //'categories'                   => array( 'Property Board' ),
        'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/dummy-data.xml',
        'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/widgets.wie',
        'local_import_redux'           => array(
            array(
                'file_path'   => trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/redux-options.json',
                'option_name' => 'ogami_theme_options',
            ),
        ),
        'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'screenshot.png',
        'import_notice'                => esc_html__( 'Import process may take 5-10 minutes. If you facing any issues please contact our support.', 'ogami' ),
        'preview_url'                  => 'https://www.demoapus-wp1.com/ogami/',
    );
    return apply_filters( 'ogami_ocdi_files_args', $demos );
}
add_filter( 'pt-ocdi/import_files', 'ogami_ocdi_import_files' );

function ogami_ocdi_after_import_setup( $selected_import ) {
    // Assign menus to their locations.
    $main_menu       = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id
        )
    );

    // Assign front page and posts page (blog page) and other WooCommerce pages
    $blog_page_id       = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    
    update_option( 'page_for_posts', $blog_page_id->ID );
    
    // elementor
    update_option( 'elementor_global_image_lightbox', 'yes' );
    update_option( 'elementor_disable_color_schemes', 'yes' );
    update_option( 'elementor_disable_typography_schemes', 'yes' );
    update_option( 'elementor_container_width', 1190 );
    update_option( 'elementor_viewport_lg', 1200 );

    
    $front_page_id = get_page_by_title( 'Home 1' );
    update_option( 'page_on_front', $front_page_id->ID );

    $file = trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/settings.json';
    if ( file_exists($file) ) {
        ogami_ocdi_import_settings($file);
    }

    if ( ogami_is_revslider_activated() ) {
        require_once( ABSPATH . 'wp-load.php' );
        require_once( ABSPATH . 'wp-includes/functions.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );

        $slider_array = array(
            trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/slider_1.zip',
            trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/slider-2.zip',
            trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/slider_3.zip',
            trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/slider-4.zip',
            trailingslashit( get_template_directory() ) . 'inc/vendors/one-click-demo-import/data/slider-5.zip',
        );
        $slider = new RevSlider();

        foreach( $slider_array as $filepath ) {
            $slider->importSliderFromPost( true, true, $filepath );
        }
    }
}
add_action( 'pt-ocdi/after_import', 'ogami_ocdi_after_import_setup' );

function ogami_ocdi_import_settings($file) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    $file_obj = new WP_Filesystem_Direct( array() );
    $datas = $file_obj->get_contents($file);
    $datas = json_decode( $datas, true );

    if ( count( array_filter( $datas ) ) < 1 ) {
        return;
    }

    if ( !empty($datas['page_options']) ) {
        ogami_ocdi_import_page_options($datas['page_options']);
    }
    if ( !empty($datas['metadata']) ) {
        ogami_ocdi_import_some_metadatas($datas['metadata']);
    }
}

function ogami_ocdi_import_page_options($datas) {
    if ( $datas ) {
        foreach ($datas as $option_name => $page_id) {
            update_option( $option_name, $page_id);
        }
    }
}

function ogami_ocdi_import_some_metadatas($datas) {
    if ( $datas ) {
        foreach ($datas as $slug => $post_types) {
            if ( $post_types ) {
                foreach ($post_types as $post_type => $metas) {
                    if ( $metas ) {
                        $args = array(
                            'name'        => $slug,
                            'post_type'   => $post_type,
                            'post_status' => 'publish',
                            'numberposts' => 1
                        );
                        $posts = get_posts($args);
                        if ( $posts && isset($posts[0]) ) {
                            foreach ($metas as $meta) {
                                update_post_meta( $posts[0]->ID, $meta['meta_key'], $meta['meta_value'] );
                                if ( $meta['meta_key'] == '_mc4wp_settings' ) {
                                    update_option( 'mc4wp_default_form_id', $posts[0]->ID );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

function ogami_ocdi_before_widgets_import() {

    $sidebars_widgets = get_option('sidebars_widgets');
    $all_widgets = array();

    array_walk_recursive( $sidebars_widgets, function ($item, $key) use ( &$all_widgets ) {
        if( ! isset( $all_widgets[$key] ) ) {
            $all_widgets[$key] = $item;
        } else {
            $all_widgets[] = $item;
        }
    } );

    if( isset( $all_widgets['array_version'] ) ) {
        $array_version = $all_widgets['array_version'];
        unset( $all_widgets['array_version'] );
    }

    $new_sidebars_widgets = array_fill_keys( array_keys( $sidebars_widgets ), array() );

    $new_sidebars_widgets['wp_inactive_widgets'] = $all_widgets;
    if( isset( $array_version ) ) {
        $new_sidebars_widgets['array_version'] = $array_version;
    }

    update_option( 'sidebars_widgets', $new_sidebars_widgets );
}
add_action( 'pt-ocdi/before_widgets_import', 'ogami_ocdi_before_widgets_import' );