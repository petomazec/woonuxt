<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ogami_Elementor_Search_Form extends Elementor\Widget_Base {

	public function get_name() {
        return 'ogami_search_form';
    }

	public function get_title() {
        return esc_html__( 'Apus Header Search Form', 'ogami' );
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
            'show_categories',
            [
                'label' => esc_html__( 'Show Categories', 'ogami' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Show', 'ogami' ),
                'label_off' => esc_html__( 'Hide', 'ogami' ),
            ]
        );

        $this->add_control(
            'show_auto_search',
            [
                'label' => esc_html__( 'Show Autocomplete Search', 'ogami' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Show', 'ogami' ),
                'label_off' => esc_html__( 'Hide', 'ogami' ),
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'ogami' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Show', 'ogami' ),
                'label_off' => esc_html__( 'Hide', 'ogami' ),
            ]
        );

        $this->add_control(
            'show_text',
            [
                'label' => esc_html__( 'Show text Search', 'ogami' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__( 'Show', 'ogami' ),
                'label_off' => esc_html__( 'Hide', 'ogami' ),
            ]
        );

        $this->add_responsive_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'ogami' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Style 1', 'ogami' ),
                    'style2' => esc_html__( 'Style 2', 'ogami' ),
                ],
                'default' => ''
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

        $this->end_controls_section();


        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'ogami' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__( 'Background Color Button', 'ogami' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .apus-search-form .btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_border_color',
            [
                'label' => esc_html__( 'Border Color Button', 'ogami' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .apus-search-form .btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_hover_color',
            [
                'label' => esc_html__( 'Background Hover Color Button', 'ogami' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .apus-search-form .btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_border_hover_color',
            [
                'label' => esc_html__( 'Border Hover Color Button', 'ogami' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .apus-search-form .btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        ?>
        <div class="apus-search-form <?php echo esc_attr($el_class.' '.$style); ?>">
            <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                <?php 
                    if ( $show_categories && ogami_is_woocommerce_activated() ) {
                        $args = array(
                            'show_count' => 0,
                            'hierarchical' => true,
                            'show_uncategorized' => 0
                        );
                        echo '<div class="select-category">';
                            wc_product_dropdown_categories( $args );
                        echo '</div>';
                    }
                ?>
                <div class="main-search">
                    <?php if ( $show_auto_search ) echo '<div class="twitter-typeahead">'; ?>
                        <input type="text" placeholder="<?php esc_attr_e( 'What do you need?', 'ogami' ); ?>" name="s" class="apus-search form-control <?php echo esc_attr($show_auto_search ? 'apus-autocompleate-input' : ''); ?>" autocomplete="off"/>
                    <?php if ( $show_auto_search ) echo '</div>'; ?>
                </div>
                <input type="hidden" name="post_type" value="product" class="post_type" />
                
                <button type="submit" class="btn btn-theme radius-0 <?php echo esc_attr(($show_icon && !$show_text)?'st_small':''); ?>"><?php if($show_icon){ ?><i class="fa fa-search"></i><?php } ?><?php if($show_text){ ?><span class="text"><?php esc_html_e('SEARCH', 'ogami'); ?></span><?php } ?></button>
            </form>
        </div>
        <?php
    }

}

Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Ogami_Elementor_Search_Form );