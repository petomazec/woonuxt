<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ogami_Elementor_Woo_Product_Tabs extends Widget_Base {

	public function get_name() {
        return 'ogami_woo_product_tabs';
    }

	public function get_title() {
        return esc_html__( 'Apus Product Tabs', 'ogami' );
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

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Tab Title', 'ogami' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'type',
            [
                'label' => esc_html__( 'Get Products By', 'ogami' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'recent_product' => esc_html__('Recent Products', 'ogami' ),
                    'best_selling' => esc_html__('Best Selling', 'ogami' ),
                    'featured_product' => esc_html__('Featured Products', 'ogami' ),
                    'top_rate' => esc_html__('Top Rate', 'ogami' ),
                    'on_sale' => esc_html__('On Sale', 'ogami' ),
                    'recent_review' => esc_html__('Recent Review', 'ogami' ),
                ),
                'default' => 'recent_product'
            ]
        );

        $repeater->add_control(
            'slugs',
            [
                'label' => esc_html__( 'Category Slug', 'ogami' ),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter id spearate by comma(,)', 'ogami' ),
            ]
        );

        $this->add_control(
            'title', [
                'label' => esc_html__( 'Widget Title', 'ogami' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => esc_html__( 'Tabs', 'ogami' ),
                'type' => Controls_Manager::REPEATER,
                'placeholder' => esc_html__( 'Enter your product tabs here', 'ogami' ),
                'fields' => $repeater->get_controls(),
            ]
        );
        
        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Limit', 'ogami' ),
                'type' => Controls_Manager::NUMBER,
                'placeholder' => esc_html__( 'Enter number products to display', 'ogami' ),
                'default' => 4
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
            'product_item',
            [
                'label' => esc_html__( 'Product Item', 'ogami' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'inner' => esc_html__('Grid Item 1', 'ogami'),
                    'inner-noborder' => esc_html__('Grid Item 2(No Border)', 'ogami'),
                ),
                'default' => 'inner',
            ]
        );
        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'ogami' ),
                'type' => Controls_Manager::NUMBER,
                'placeholder' => esc_html__( 'Enter your column number here', 'ogami' ),
                'default' => 4
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'ogami' ),
                'type' => Controls_Manager::NUMBER,
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
            'tab_type',
            [
                'label' => esc_html__( 'Position Tab', 'ogami' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'right' => esc_html__('Right', 'ogami'),
                    'center' => esc_html__('Center', 'ogami'),
                ),
                'default' => 'center'
            ]
        );
        $this->add_control(
            'show_border',
            [
                'label'         => esc_html__( 'Show Border for Tab', 'ogami' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'ogami' ),
                'label_off'     => esc_html__( 'Hide', 'ogami' ),
                'return_value'  => true,
                'default'       => true,
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
            'section_tab_style',
            [
                'label' => esc_html__( 'Tabs Style', 'ogami' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => esc_html__( 'Tab Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav-tabs > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_color',
            [
                'label' => esc_html__( 'Tab Hover/Active Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav-tabs > li.active > a, {{WRAPPER}} .nav-tabs > li.active > a:hover, {{WRAPPER}} .nav-tabs > li.active > a:focus, {{WRAPPER}} .nav-tabs > li > a:hover, {{WRAPPER}} .nav-tabs > li > a:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_active_color',
            [
                'label' => esc_html__( 'Border Active Color', 'ogami' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product > li > a::before, {{WRAPPER}} .widget-title:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Tab Typography', 'ogami' ),
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .nav-tabs > li > a',
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

        if ( !empty($tabs) ) {
            $_id = ogami_random_key();
            ?>
            <div class="widget widget-products-tabs <?php echo esc_attr($el_class); ?>">
                
                <div class="widget-content woocommerce <?php echo esc_attr($layout_type); ?>">
                    <div class="top-info <?php echo esc_attr(($tab_type != 'center')?'flex-bottom':'text-center'); ?>">
                        <?php if ( !empty($title) ): ?>
                            <h3 class="widget-title <?php echo esc_attr($tab_type); ?>">
                                <?php echo esc_attr( $title ); ?>
                            </h3>
                        <?php endif; ?>
                        <ul role="tablist" class="nav nav-tabs tabs-product <?php echo esc_attr($tab_type.(!empty($show_border)?' has-border':'')); ?>" data-load="ajax">
                            <?php $i = 0; foreach ($tabs as $tab) : ?>
                                <li class="<?php echo esc_attr($i == 0 ? 'active' : '');?>">
                                    <a href="#tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>">
                                        <?php if ( !empty($tab['title']) ) { ?>
                                            <?php echo trim($tab['title']); ?>
                                        <?php } ?>
                                    </a>
                                </li>
                            <?php $i++; endforeach; ?>
                        </ul>
                    </div>
                    <div class="widget-inner">
                        <div class="tab-content">
                            <?php $i = 0; foreach ($tabs as $tab) : 
                                $encoded_atts = json_encode( $settings );
                                $encoded_tab = json_encode( $tab );
                            ?>
                                <div id="tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>" class="tab-pane <?php echo esc_attr($i == 0 ? 'active' : ''); ?>" data-loaded="<?php echo esc_attr($i == 0 ? 'true' : 'false'); ?>" data-settings="<?php echo esc_attr($encoded_atts); ?>" data-tab="<?php echo esc_attr($encoded_tab); ?>">

                                    <div class="tab-content-products">
                                        <?php if ( $i == 0 ): ?>
                                            <?php
                                                $slugs = !empty($tab['slugs']) ? array_map('trim', explode(',', $tab['slugs'])) : array();
                                                $type = isset($tab['type']) ? $tab['type'] : 'recent_product';
                                                $args = array(
                                                    'categories' => $slugs,
                                                    'product_type' => $type,
                                                    'post_per_page' => $limit,
                                                );
                                                $loop = ogami_get_products( $args );
                                            ?>

                                            <?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array(
                                                'loop' => $loop,
                                                'columns' => $columns,
                                                'product_item' => 'inner',
                                                'show_nav' => $show_nav,
                                                'show_pagination' => $show_pagination,
                                                'rows' => $rows,
                                                'product_item' => $product_item,
                                                'elementor_element' => true,
                                            ) ); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php $i++; endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Ogami_Elementor_Woo_Product_Tabs );