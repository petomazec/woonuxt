<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ogami_Elementor_Woo_Category_Banner extends Widget_Base {

	public function get_name() {
        return 'ogami_woo_category_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Category Banner', 'ogami' );
    }

    public function get_icon() {
        return 'fa fa-shopping-bag';
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

        $this->add_control(
            'title', [
                'label' => esc_html__( 'Category Title', 'ogami' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'slug', [
                'label' => esc_html__( 'Category Slug', 'ogami' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Category Image', 'ogami' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Category Image', 'ogami' ),
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
            'border_color',
            [
                'label' => esc_html__( 'Border Hover Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .category-item:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .cat-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Hover Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-category-banner:hover .cat-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'ogami' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cat-title',
            ]
        );

        $this->add_control(
            'nb_color',
            [
                'label' => esc_html__( 'Number item Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-nb' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Number item Typography', 'ogami' ),
                'name' => 'nb_typography',
                'selector' => '{{WRAPPER}} .product-nb',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget-category-banner <?php echo esc_attr($el_class); ?>">
            
            <?php
                if ( !empty($slug) ) {
                    $term = get_term_by('slug', $slug, 'product_cat');
                    if ( $term ) {
                        $term_title = !empty($title) ? $title : $term->name;
                        ?>
                        <div class="category-item updow">

                            <a href="<?php echo esc_url(get_term_link($term)); ?>" title="<?php echo esc_attr($term->name); ?>">
                                <?php if ( !empty($img_src['id']) ) { ?>
                                    <?php echo wp_kses_post(ogami_get_attachment_thumbnail($img_src['id'], 'full')); ?>
                                <?php } ?>

                                <h3 class="cat-title"><?php echo wp_kses_post($term_title); ?></h3>
                                <div class="product-nb"><?php echo sprintf(_n('%d Item', '%d Items', $term->count, 'ogami'), $term->count); ?></div>
                            </a>
                        </div>
                        <?php
                    }
                }
            ?>
                
        </div>
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Ogami_Elementor_Woo_Category_Banner );