<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ogami_Elementor_Social_Links extends Widget_Base {

	public function get_name() {
        return 'ogami_social_links';
    }

	public function get_title() {
        return esc_html__( 'Apus Social Links', 'ogami' );
    }

    public function get_icon() {
        return 'fa fa-share-square-o';
    }

	public function get_categories() {
        return [ 'ogami-elements' ];
    }

	protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'ogami' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Social Title', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Social Title' , 'ogami' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Social Link', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your social link here', 'ogami' ),
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Social Icon', 'ogami' ),
                'type' => Controls_Manager::ICON,
            ]
        );

        $this->add_control(
            'socials',
            [
                'label' => esc_html__( 'Socials', 'ogami' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );
        
        $this->add_responsive_control(
            'align',
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
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'ogami' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Normal', 'ogami'),
                    'st_small' => esc_html__('Small', 'ogami'),
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
                'label' => esc_html__( 'Title', 'ogami' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Background Hover Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .social a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($socials) ) {

            ?>
            <div class="widget-social <?php echo esc_attr($el_class.' '.$align.' '.$style); ?>">
                <ul class="social list-inline">
                    <?php foreach ($socials as $social) { ?>
                        <?php if ( !empty($social['link']) && !empty($social['icon']) ) { ?>
                            <li>
                                <a href="<?php echo esc_url($social['link']);?>" <?php echo wp_kses_post(!empty($social['title']) ? 'title="'.$social['title'].'"' : ''); ?>>
                                    <i class="<?php echo esc_attr($social['icon']); ?>"></i>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <?php
        }
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Ogami_Elementor_Social_Links );