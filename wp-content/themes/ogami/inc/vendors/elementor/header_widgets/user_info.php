<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ogami_Elementor_User_Info extends Elementor\Widget_Base {

	public function get_name() {
        return 'ogami_user_info';
    }

	public function get_title() {
        return esc_html__( 'Apus Header User Info', 'ogami' );
    }
    
	public function get_categories() {
        return [ 'ogami-header-elements' ];
    }

	protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'ogami' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'ogami' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'ogami' ),
            ]
        );

        $this->add_control(
            'login_layout',
            [
                'label' => esc_html__( 'Login Form', 'ogami' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    'link_to_page' => esc_html__( 'Link To Page', 'ogami' ),
                    'popup' => esc_html__( 'Popup', 'ogami' ),
                    'dropdown_box' => esc_html__( 'Dropdown Box', 'ogami' ),
                ],
                'default' => 'link_to_page'
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'ogami' ),
                'type' => Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'ogami' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ogami' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ogami' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'ogami' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color Icon', 'ogami' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .top-wrapper-menu .drop-dow,{{WRAPPER}} .top-wrapper-menu a.login' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        if ( is_user_logged_in() ) { ?>
            <?php if( has_nav_menu( 'my-account' ) ) { ?>
                <div class="top-wrapper-menu <?php echo esc_attr($el_class); ?>">
                    <a class="drop-dow" href=""><i class="fa fa-user"></i></a>
                    <?php
                    if ( has_nav_menu( 'my-account' ) ) {
                        $args = array(
                            'theme_location' => 'my-account',
                            'container_class' => 'inner-top-menu',
                            'menu_class' => 'nav navbar-nav topmenu-menu',
                            'fallback_cb' => '',
                            'menu_id' => '',
                            'walker' => new Ogami_Nav_Menu()
                        );
                        wp_nav_menu($args);
                    }
                    ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="top-wrapper-menu <?php echo esc_attr($el_class); ?>">
                <a class="login <?php echo esc_attr($login_layout); ?>" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Sign in','ogami'); ?>"><i class="fa fa-lock"></i><?php esc_html_e('Login', 'ogami'); ?>
                </a>
                <?php
                    if ( $login_layout == 'dropdown_box' ) {
                        get_template_part( 'template-parts/login' );
                    } elseif ( $login_layout == 'popup' ) {
                        ?>
                        <div class="header-customer-login-wrapper hidden">
                            <button title="<?php echo esc_html('Close (Esc)', 'yozi'); ?>" type="button" class="mfp-close apus-mfp-close"> <i class="fa fa-close"></i> </button>
                            <?php get_template_part( 'template-parts/login' ); ?>
                        </div>
                        <?php
                    }
                ?>
            </div>
        <?php }
    }

}

Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Ogami_Elementor_User_Info );