<?php 
global $product;
$product_id = $product->get_id();
?>
<div class="product-block grid" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="grid-inner">
        <?php 
            do_action( 'ogami_woocommerce_loop_sale_flash' );
        ?>
        <div class="block-inner">
            <figure class="image">
                <?php
                    $image_size = isset($image_size) ? $image_size : 'woocommerce_thumbnail';
                    ogami_product_image($image_size);
                ?>

                <?php
                    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                    remove_action('woocommerce_before_shop_loop_item_title', 'ogami_swap_images', 10);
                    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
                    do_action( 'woocommerce_before_shop_loop_item_title' );
                ?>
            </figure>
            <?php Ogami_Woo_Swatches::swatches_list( $image_size ); ?>
        </div>
        <div class="metas clearfix">
            <div class="title-wrapper">
                <div class="pull-right hidden">
                    <?php
                        $rating_html = wc_get_rating_html( $product->get_average_rating() );
                        if ( $rating_html ) {
                            ?>
                            <div class="rating clearfix">
                                <?php echo trim( $rating_html ); ?>
                            </div>
                            <?php
                        }
                    ?>
                </div>
                <div class="clearfix">
                    <?php ogami_woo_display_product_cat($product_id); ?>
                    <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php
                        /**
                        * woocommerce_after_shop_loop_item_title hook
                        *
                        * @hooked woocommerce_template_loop_rating - 5
                        * @hooked woocommerce_template_loop_price - 10
                        */
                        remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5);
                        do_action( 'woocommerce_after_shop_loop_item_title');
                    ?>    
                </div>
                <div class="groups-button clearfix">
                    <?php
                        if ( class_exists( 'YITH_WCWL' ) ) {
                            echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                        }
                    ?>
                    <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
                    
                    <?php if( class_exists( 'YITH_Woocompare_Frontend' ) ) { ?>
                        <?php
                            $obj = new YITH_Woocompare_Frontend();
                            $url = $obj->add_product_url($product_id);
                            $compare_class = '';
                            if ( isset($_COOKIE['yith_woocompare_list']) ) {
                                $compare_ids = json_decode( $_COOKIE['yith_woocompare_list'] );
                                if ( in_array($product_id, $compare_ids) ) {
                                    $compare_class = 'added';
                                    $url = $obj->view_table_url($product_id);
                                }
                            }
                        ?>
                        <div class="yith-compare">
                            <a title="<?php esc_attr_e('compare','ogami') ?>" href="<?php echo esc_url( $url ); ?>" class="compare <?php echo esc_attr($compare_class); ?>" data-product_id="<?php echo esc_attr($product_id); ?>">
                                <i class="fa fa-random" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if (ogami_get_config('show_quickview', false)) { ?>
                        <div class="view">
                            <a href="#" class="quickview" data-product_id="<?php echo esc_attr($product_id); ?>" data-toggle="modal" data-target="#apus-quickview-modal">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    <?php } ?>
                </div> 
            </div>
        </div>
    </div>
</div>