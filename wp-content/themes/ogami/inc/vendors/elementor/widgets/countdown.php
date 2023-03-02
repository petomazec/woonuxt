<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ogami_Elementor_Countdown extends Widget_Base {

	public function get_name() {
        return 'ogami_countdown';
    }

	public function get_title() {
        return esc_html__( 'Apus Countdown', 'ogami' );
    }
    
	public function get_categories() {
        return [ 'ogami-elements' ];
    }

	protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Countdown', 'ogami' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title here', 'ogami' ),
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => esc_html__( 'Price', 'ogami' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your Price here', 'ogami' ),
            ]
        );
        $this->add_control(
            'des',
            [
                'label' => esc_html__( 'Content', 'ogami' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your content here', 'ogami' ),
            ]
        );
        $this->add_control(
            'end_date', [
                'label' => esc_html__( 'End Date', 'ogami' ),
                'type' => Controls_Manager::DATE_TIME,
                'picker_options' => [
                    'enableTime' => false
                ]
            ]
        );
        
        $this->add_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'ogami' ),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'ogami' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget-countdown' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'URL', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Button Link here', 'ogami' ),
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your button text here', 'ogami' ),
            ]
        );

        $this->add_control(
            'btn_style',
            [
                'label' => esc_html__( 'Button Style', 'ogami' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'btn-theme' => esc_html__('Theme Color', 'ogami'),
                    'btn-theme btn-outline' => esc_html__('Theme Outline Color', 'ogami'),
                    'btn-default' => esc_html__('Default ', 'ogami'),
                    'btn-primary' => esc_html__('Primary ', 'ogami'),
                    'btn-success' => esc_html__('Success ', 'ogami'),
                    'btn-info' => esc_html__('Info ', 'ogami'),
                    'btn-warning' => esc_html__('Warning ', 'ogami'),
                    'btn-danger' => esc_html__('Danger ', 'ogami'),
                    'btn-pink' => esc_html__('Pink ', 'ogami'),
                    'btn-white' => esc_html__('White ', 'ogami'),
                ),
                'default' => 'btn-default'
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'ogami' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'ogami'),
                    'style2' => esc_html__('Style 2(showdow)', 'ogami'),
                    'style3' => esc_html__('Style 3(circle)', 'ogami'),
                ),
                'default' => 'style1'
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'ogami' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'ogami' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style', 'ogami' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'ogami' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Description Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .des' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Description Typography', 'ogami' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .des',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        $end_date = !empty($end_date) ? strtotime($end_date) : '';
        if ( $end_date ) {
            ?>
            <div class="widget-countdown <?php echo esc_attr($el_class.' '.$style); ?>">
                <?php if ( !empty($title) ) { ?>
                    <h2 class="title"><?php echo wp_kses_post($title); ?></h2>
                <?php } ?>
                <?php if ( !empty($price) ) { ?>
                    <div class="price"><?php echo wp_kses_post($price); ?></div>
                <?php } ?>
                <?php if ( !empty($des) ) { ?>
                    <div class="des"><?php echo wp_kses_post($des); ?></div>
                <?php } ?>
                <div class="time-wrapper">
                    <div class="apus-countdown clearfix" data-time="timmer"
                        data-date="<?php echo date('m', $end_date).'-'.date('d', $end_date).'-'.date('Y', $end_date).'-'. date('H', $end_date) . '-' . date('i', $end_date) . '-' .  date('s', $end_date) ; ?>">
                    </div>
                </div>
                <?php if ( !empty($btn_text) && !empty($link) ) { ?>
                    <div class="url-bottom">
                        <a href="<?php echo esc_url($link); ?>" class="btn <?php echo esc_attr(!empty($btn_style) ? $btn_style : ''); ?>"><?php echo wp_kses_post($btn_text); ?></a>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
    }

}
Plugin::instance()->widgets_manager->register_widget_type( new Ogami_Elementor_Countdown );