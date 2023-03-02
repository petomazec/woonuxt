<?php

if ( is_single() ) {
	// for Single Product page
	$show = ogami_get_config('show_product_recent_viewed');
	if ( !$show ) {
		return;
	}
	$columns = ogami_get_config('recent_viewed_product_columns', 4);
	$number = ogami_get_config('number_product_recent_viewed', 4);

	$args = array(
		'product_type' => 'recently_viewed',
		'post_per_page' => $number
	);
	$loop = ogami_get_products( $args );
	
	if ( !empty($loop) && $loop->have_posts() ) { ?>

		<div class="widget widget-recent_viewed">
			<h3 class="widget-title"><?php esc_html_e('Recently Viewed Products', 'ogami'); ?></h3>
			<div class="widget-content woocommerce">
				<?php wc_get_template( 'layout-products/carousel.php', array( 'loop' => $loop, 'columns' => $columns, 'rows' => 1, 'product_item' => 'inner', 'show_nav' => 1, 'slick_top' => 'slick-carousel-top' ) ); ?>
			</div>
		</div>

	<?php }
} else {
	// for Shop/Category page
	$show = ogami_get_config('show_archive_product_recent_viewed');
	if ( !$show ) {
		return;
	}
	$number = ogami_get_config('number_archive_product_recent_viewed', 4);
	$columns = ogami_get_config('recent_archive_viewed_product_columns', 4);

	$args = array(
		'product_type' => 'recently_viewed',
		'post_per_page' => $number
	);
	$loop = ogami_get_products( $args );
	if ( !empty($loop) && $loop->have_posts() ) { ?>

		<div class="widget widget-recent_viewed">
			<h3 class="widget-title"><?php esc_html_e('Recently Viewed Products', 'ogami'); ?></h3>
			<div class="widget-content woocommerce">
				<?php wc_get_template( 'layout-products/carousel.php', array( 'loop' => $loop, 'columns' => $columns, 'rows' => 1, 'product_item' => 'inner-list-small', 'show_nav' => 1, 'slick_top' => 'slick-carousel-top' ) ); ?>
			</div>
		</div>

	<?php }
}