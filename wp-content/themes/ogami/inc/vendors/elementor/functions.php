<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'Ogami_Elementor_Extensions' ) ) {
    final class Ogami_Elementor_Extensions {

        private static $_instance = null;

        
        public function __construct() {
            add_action( 'elementor/elements/categories_registered', array( $this, 'add_widget_categories' ) );
            add_action( 'init', array( $this, 'elementor_widgets' ),  100 );
            add_filter( 'ogami_generate_post_builder', array( $this, 'render_post_builder' ), 10, 2 );
        }

        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        public function add_widget_categories( $elements_manager ) {
            $elements_manager->add_category(
                'ogami-elements',
                [
                    'title' => esc_html__( 'Ogami Elements', 'ogami' ),
                    'icon' => 'fa fa-shopping-bag',
                ]
            );

            $elements_manager->add_category(
                'ogami-header-elements',
                [
                    'title' => esc_html__( 'Ogami Header Elements', 'ogami' ),
                    'icon' => 'fa fa-shopping-bag',
                ]
            );

        }

        public function elementor_widgets() {
            // general elements
            get_template_part( 'inc/vendors/elementor/widgets/heading' );
            get_template_part( 'inc/vendors/elementor/widgets/posts' );
            get_template_part( 'inc/vendors/elementor/widgets/call_to_action' );
            get_template_part( 'inc/vendors/elementor/widgets/features_box' );
            get_template_part( 'inc/vendors/elementor/widgets/social_links' );
            get_template_part( 'inc/vendors/elementor/widgets/testimonials' );
            get_template_part( 'inc/vendors/elementor/widgets/brands' );
            get_template_part( 'inc/vendors/elementor/widgets/process' );
            get_template_part( 'inc/vendors/elementor/widgets/popup_video' );
            get_template_part( 'inc/vendors/elementor/widgets/instagram' );
            get_template_part( 'inc/vendors/elementor/widgets/banner' );
            get_template_part( 'inc/vendors/elementor/widgets/countdown' );
            get_template_part( 'inc/vendors/elementor/widgets/nav_menu' );
            get_template_part( 'inc/vendors/elementor/widgets/team' );

            // header elements
            get_template_part( 'inc/vendors/elementor/header_widgets/logo' );
            get_template_part( 'inc/vendors/elementor/header_widgets/primary_menu' );
            get_template_part( 'inc/vendors/elementor/header_widgets/vertical_menu' );
            get_template_part( 'inc/vendors/elementor/header_widgets/search_form' );
            get_template_part( 'inc/vendors/elementor/header_widgets/user_info' );

            if ( ogami_is_mailchimp_activated() ) {
                get_template_part( 'inc/vendors/elementor/widgets/mailchimp' );
            }

            if ( ogami_is_woocommerce_activated() ) {
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_product_tabs' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_products' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_products_deal' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_category_banner' );


                get_template_part( 'inc/vendors/elementor/woo_header_widgets/woo_header_info' );
            }

            if ( ogami_is_revslider_activated() ) {
                get_template_part( 'inc/vendors/elementor/widgets/revslider' );
            }
        }

        public function render_page_content($post_id) {
            if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new Elementor\Core\Files\CSS\Post( $post_id );
                $css_file->enqueue();
            }

            return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
        }

        public function render_post_builder($html, $post) {
            if ( !empty($post) && !empty($post->ID) ) {
                return $this->render_page_content($post->ID);
            }
            return $html;
        }
    }
}

if ( did_action( 'elementor/loaded' ) ) {
    // Finally initialize code
    Ogami_Elementor_Extensions::instance();
}