<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $breadcrumb ) ) {

	echo trim($wrap_before);

	$end = '' ;
	$count = 1;
	foreach ( $breadcrumb as $key => $crumb ) {

		echo trim($before);
		if($count == 1){
			$icon = '<i class="fa fa-home" aria-hidden="true"></i>';
		}else{
			$icon = '';
		}
		if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
			echo '<li><a href="' . esc_url( $crumb[1] ) . '">'.$icon. esc_html( $crumb[0] ) . '</a></li>';
		} else {
			echo '<li>'.esc_html( $crumb[0] ).'</li>';
		}

		echo trim($after);

		$end = esc_html( $crumb[0] );
		$count++;
	}

	echo trim($wrap_after);

}
?>