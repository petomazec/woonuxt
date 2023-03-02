<?php
if ( !function_exists ('ogami_custom_styles') ) {
	function ogami_custom_styles() {
		global $post;	
		
		ob_start();	
		?>
		
			<?php
				$main_font = ogami_get_config('main_font');
				$main_font = isset($main_font['font-family']) ? $main_font['font-family'] : false;
			?>
			<?php if ( $main_font ): ?>
				/* Main Font */
				body, .megamenu > li > a, .product-block.grid .product-cat
				{
					font-family: 
					<?php echo '\'' . $main_font . '\','; ?> 
					sans-serif;
				}
			<?php endif; ?>
			
			<?php
				$heading_font = ogami_get_config('heading_font');
				$heading_font = isset($heading_font['font-family']) ? $heading_font['font-family'] : false;
			?>
			<?php if ( $heading_font ): ?>
				/* Heading Font */
				h1, h2, h3, h4, h5, h6, .widget-title,.widgettitle
				{
					font-family:  <?php echo '\'' . $heading_font . '\','; ?> sans-serif;
				}			
			<?php endif; ?>


			<?php if ( ogami_get_config('main_color') != "" ) : ?>
				/* seting background main */
				.woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a::before ,
				.apus-checkout-step li.active,
				.details-product .apus-woocommerce-product-gallery-thumbs.vertical .slick-arrow:hover i, .details-product .apus-woocommerce-product-gallery-thumbs.vertical .slick-arrow:focus i,
				.tabs-v1 .nav-tabs > li.active > a,
				.product-block-list .quickview:hover, .product-block-list .quickview:focus,
				.apus-pagination .page-numbers li > span:hover, .apus-pagination .page-numbers li > span.current, .apus-pagination .page-numbers li > a:hover, .apus-pagination .page-numbers li > a.current, .apus-pagination .pagination li > span:hover, .apus-pagination .pagination li > span.current, .apus-pagination .pagination li > a:hover, .apus-pagination .pagination li > a.current,
				.wishlist-icon .count, .mini-cart .count,
				.woocommerce .widget_price_filter .price_slider_amount .button,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
				.post-layout .categories-name,
				.widget-countdown.style3 .title::before,
				.slick-carousel .slick-arrow:hover, .slick-carousel .slick-arrow:active, .slick-carousel .slick-arrow:focus,
				.product-block.grid .groups-button .add-cart .added_to_cart:hover::before,
				.product-block.grid .groups-button .add-cart .added_to_cart::before, .product-block.grid .groups-button .add-cart .button:hover::before,
				.product-block.grid .yith-wcwl-add-to-wishlist a:hover, .product-block.grid .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist), .product-block.grid .compare:hover,  .product-block.grid .compare.added:before, .product-block.grid .quickview:hover,
				.add-fix-top,
				.widget .widget-title::after, .widget .widgettitle::after, .widget .widget-heading::after,
				.slick-carousel .slick-dots li.slick-active button,
				.bg-theme,
				.vertical-wrapper .title-vertical, table.variations .tawcvs-swatches .swatch-label.selected, .widget-social .social a:hover, .widget-social .social a:focus
				{
					background-color: <?php echo esc_html( ogami_get_config('main_color') ) ?> ;
				}
				/* setting color*/
				.header-mobile .mobile-vertical-menu-title:hover, .header-mobile .mobile-vertical-menu-title.active,
				.dokan-store-menu #cat-drop-stack > ul a:hover, .dokan-store-menu #cat-drop-stack > ul:focus,
				.shopping_cart_content .cart_list .quantity,
				#order_review .order-total .amount, #order_review .cart-subtotal .amount,
				.woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link.is-active > a, .woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link:hover > a, .woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link:active > a,
				.woocommerce table.shop_table tbody .product-subtotal,
				.apus-breadscrumb .breadcrumb a:hover, .apus-breadscrumb .breadcrumb a:active,
				.details-product .title-cat-wishlist-wrapper .yith-wcwl-add-to-wishlist a:focus, .details-product .title-cat-wishlist-wrapper .yith-wcwl-add-to-wishlist a:hover,
				.details-product .title-cat-wishlist-wrapper .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				.details-product .product_meta a,
				.product-block-list .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				.product-block-list .yith-wcwl-add-to-wishlist a:hover, .product-block-list .yith-wcwl-add-to-wishlist a:focus,
				.apus-filter .change-view:hover, .apus-filter .change-view.active,
				.woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item > a:hover, .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item > a:active,
				.mobile-sidebar-btn,
				.btn-readmore:hover,
				.apus-countdown .times > div > span,
				.btn-link,
				.apus-vertical-menu > li > a > i, .apus-vertical-menu > li > a > img,
				.megamenu .dropdown-menu li > a:hover, .megamenu .dropdown-menu li > a:active,
				.apus-footer a:hover, .apus-footer a:focus, .apus-footer a:active, .megamenu .dropdown-menu li.current-menu-item > a, .megamenu .dropdown-menu li.open > a, .megamenu .dropdown-menu li.active > a, .comment-list .comment-reply-link, .comment-list .comment-edit-link, .product-categories li.current-cat-parent > a, .product-categories li.current-cat > a, .product-categories li:hover > a
				{
					color: <?php echo esc_html( ogami_get_config('main_color') ) ?>;
				}
				/* setting border color*/
				.apus-checkout-step li.active::after,
				.details-product .apus-woocommerce-product-gallery-thumbs .slick-slide:hover .thumbs-inner, .details-product .apus-woocommerce-product-gallery-thumbs .slick-slide:active .thumbs-inner, .details-product .apus-woocommerce-product-gallery-thumbs .slick-slide.slick-current .thumbs-inner,
				.product-block-list:hover,
				.woocommerce .widget_price_filter .price_slider_amount .button,
				.border-theme, .widget-social .social a:hover, .widget-social .social a:focus{
					border-color: <?php echo esc_html( ogami_get_config('main_color') ) ?> !important;
				}

				.details-product .information .price,
				.product-block-list .price,
				.text-theme{
					color: <?php echo esc_html( ogami_get_config('main_color') ) ?> !important;
				}
				.apus-checkout-step li.active .inner::after {
					border-color: #fff <?php echo esc_html( ogami_get_config('main_color') ) ?>;
				}
			<?php endif; ?>

			<?php if ( ogami_get_config('button_color') != "" ) : ?>
				/* seting background main */
				.viewmore-products-btn, .woocommerce .wishlist_table td.product-add-to-cart a, .woocommerce .return-to-shop .button, .woocommerce .track_order .button, .woocommerce #respond input#submit,
				.btn-theme
				{
					background-color: <?php echo esc_html( ogami_get_config('button_color') ) ?> ;
					border-color: <?php echo esc_html( ogami_get_config('button_color') ) ?> ;
				}
				.woocommerce div.product form.cart .button,
				.product-block-list .add-cart .added_to_cart, .product-block-list .add-cart a.button,
				.btn-theme.btn-outline{
					border-color: <?php echo esc_html( ogami_get_config('button_color') ) ?> ;
					color: <?php echo esc_html( ogami_get_config('button_color') ) ?> ;
				}
			<?php endif; ?>
			<?php if ( ogami_get_config('button_hover_color') != "" ) : ?>
				/* seting background main */
				.viewmore-products-btn:hover, .woocommerce .wishlist_table td.product-add-to-cart a:hover, .woocommerce .return-to-shop .button:hover, .woocommerce .track_order .button:hover, .woocommerce #respond input#submit:hover,
				.woocommerce div.product form.cart .button:hover, .woocommerce div.product form.cart .button:focus,
				.details-product .information .compare:hover, .details-product .information .compare:focus,
				.btn-theme:hover,
				.btn-theme:focus,
				.product-block-list .compare.added, .product-block-list .compare:hover, .product-block-list .compare:focus,
				.product-block-list .add-cart .added_to_cart:hover, .product-block-list .add-cart .added_to_cart:focus, .product-block-list .add-cart a.button:hover, .product-block-list .add-cart a.button:focus,
				.btn-theme.btn-outline:hover,
				.btn-theme.btn-outline:focus{
					border-color: <?php echo esc_html( ogami_get_config('button_hover_color') ) ?> ;
					background-color: <?php echo esc_html( ogami_get_config('button_hover_color') ) ?> ;
				}
			<?php endif; ?>
	<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}
		
		return implode($new_lines);
	}
}