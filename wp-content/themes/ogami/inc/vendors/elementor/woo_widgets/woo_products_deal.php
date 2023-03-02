<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ogami_Elementor_Woo_Products_Deal extends Widget_Base {

	public function get_name() {
        return 'ogami_woo_products_deal';
    }

	public function get_title() {
        return esc_html__( 'Apus Products Deal', 'ogami' );
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
                'label' => esc_html__( 'Widget Title', 'ogami' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'product_id', [
                'label' => esc_html__( 'Product ID', 'ogami' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'end_date', [
                'label' => esc_html__( 'End Date', 'ogami' ),
                'type' => Controls_Manager::DATE_TIME,
                'picker_options' => [
                    'enableTime' => false
                ]
            ]
        );

        $this->add_control(
            'products',
            [
                'label' => esc_html__( 'Products Deal', 'ogami' ),
                'type' => Controls_Manager::REPEATER,
                'placeholder' => esc_html__( 'Enter your product tabs here', 'ogami' ),
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'ogami' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'ogami'),
                    'carousel' => esc_html__('Carousel', 'ogami'),
                ),
                'default' => 'grid'
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your column number here', 'ogami' ),
                'default' => 4
            ]
        );

        $this->add_control(
            'columns_tablet',
            [
                'label' => esc_html__( 'Columns Tablet', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your column number here', 'ogami' ),
                'default' => 3,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'ogami' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your rows number here', 'ogami' ),
                'default' => 1,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label'         => esc_html__( 'Show Navigation', 'ogami' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'ogami' ),
                'label_off'     => esc_html__( 'Hide', 'ogami' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'         => esc_html__( 'Show Pagination', 'ogami' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'ogami' ),
                'label_off'     => esc_html__( 'Hide', 'ogami' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
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


        // Style
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Widget Style', 'ogami' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_title_color',
            [
                'label' => esc_html__( 'Widget Title Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Widget Title Typography', 'ogami' ),
                'name' => 'widget_title_typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );

        $this->end_controls_section();

        
        $this->start_controls_section(
            'section_product_style',
            [
                'label' => esc_html__( 'Product Style', 'ogami' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

                $this->add_control(
            'border_color',
            [
                'label' => esc_html__( 'Border Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__( 'Button Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .groups-button .add-cart .button::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .groups-button a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => esc_html__( 'Button Background Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .quickview, {{WRAPPER}} .product-block .compare, {{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .groups-button .add-cart .button::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__( 'Button Hover Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .groups-button .add-cart .added_to_cart::before, {{WRAPPER}} .product-block .groups-button .add-cart .button:hover::before ' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist a:hover, {{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist), {{WRAPPER}} .product-block .compare:hover, {{WRAPPER}} .product-block .compare.added:before, {{WRAPPER}} .product-block .quickview:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => esc_html__( 'Button Hover Background Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .groups-button .add-cart .added_to_cart::before, {{WRAPPER}} .product-block .groups-button .add-cart .button:hover::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist a:hover, {{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist), {{WRAPPER}} .product-block .compare:hover, {{WRAPPER}} .product-block .compare.added:before, {{WRAPPER}} .product-block .quickview:hover' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} h3.name a' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-block:hover h3.name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'ogami' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} h3.name a',
            ]
        );

        $this->add_control(
            'cat_color',
            [
                'label' => esc_html__( 'Category Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-cat' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Category Typography', 'ogami' ),
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} .product-cat',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Price Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Price Typography', 'ogami' ),
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .price',
            ]
        );

        $this->add_control(
            'old_price_color',
            [
                'label' => esc_html__( 'Old Price Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .price del' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Old Price Typography', 'ogami' ),
                'name' => 'old_price_typography',
                'selector' => '{{WRAPPER}} .price del',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        if ( $products ) {
            if ( empty($columns) ) {
                $columns = 4;
            }
            $bcol = 12/$columns;
        ?>
            <div class="widget widget-products-deal <?php echo esc_attr($el_class); ?>">
                <?php if ( !empty($title) ): ?>
                    <h3 class="widget-title">
                        <?php echo esc_attr( $title ); ?>
                    </h3>
                <?php endif; ?>
                <div class="widget-content woocommerce <?php echo esc_attr($layout_type); ?>">
                    <?php if ( $layout_type == 'carousel' ) { ?>
                        <div class="slick-carousel products slick-carousel-top" data-items="<?php echo esc_attr($columns); ?>" data-medium="<?php echo esc_attr($columns_tablet); ?>" data-smalldesktop="<?php echo esc_attr($columns_tablet); ?>"  data-smallmedium="<?php echo esc_attr(($columns_tablet > 1)?2:1); ?>" data-extrasmall="1" data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>" data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>" data-rows="<?php echo esc_attr( $rows ); ?>">
                            <?php
                            foreach ($products as $data) {
                                if ( !empty($data['product_id']) ) {
                                    $post_object = get_post( $data['product_id'] );
                                    if ( $post_object ) {
                                        setup_postdata( $GLOBALS['post'] =& $post_object );

                                        ?>
                                            <div class="products-grid product">
                                                <?php wc_get_template( 'item-product/inner-deal.php' , array(
                                                    'end_date' => !empty($data['end_date']) ? $data['end_date'] : ''
                                                ) ); ?>
                                            </div>
                                        <?php
                                    }
                                }
                            }
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <?php
                            foreach ($products as $data) {
                                
                                if ( !empty($data['product_id']) ) {
                                    $post_object = get_post( $data['product_id'] );
                                    if ( $post_object ) {
                                        setup_postdata( $GLOBALS['post'] =& $post_object );

                                        ?>
                                            <div class="products-grid product col-md-<?php echo esc_attr($bcol); ?>">
                                                <?php wc_get_template( 'item-product/inner-deal.php' , array(
                                                    'end_date' => !empty($data['end_date']) ? $data['end_date'] : ''
                                                ) ); ?>
                                            </div>
                                        <?php 
                                    }
                                }
                            }
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php } ?>

                </div>
            </div>
            <?php
        }
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Ogami_Elementor_Woo_Products_Deal );