<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( class_exists( 'woocommerce' ) ):
    ?>
    <div id="tabs-7">
        <div class="wpcgai_form_row">
            <label class="wpcgai_label">Write a SEO friendly product title?:</label>
            <?php $wpaicg_woo_generate_title = get_option('wpaicg_woo_generate_title',false); ?>
            <input<?php echo $wpaicg_woo_generate_title ? ' checked':'';?> type="checkbox" name="wpaicg_woo_generate_title" value="1">
            <a class="wpcgai_help_link" href="https://gptaipower.com/optimize-your-woocommerce-product-listings-with-gpt-3-ai/" target="_blank">?</a>
        </div>
        <div class="wpcgai_form_row">
            <label class="wpcgai_label">Write a product description?:</label>
            <?php $wpaicg_woo_generate_description = get_option('wpaicg_woo_generate_description',false); ?>
            <input<?php echo $wpaicg_woo_generate_description ? ' checked':'';?> type="checkbox" name="wpaicg_woo_generate_description" value="1">
            <a class="wpcgai_help_link" href="https://gptaipower.com/optimize-your-woocommerce-product-listings-with-gpt-3-ai/" target="_blank">?</a>
        </div>
        <div class="wpcgai_form_row">
            <label class="wpcgai_label">Write a short product description?:</label>
            <?php $wpaicg_woo_generate_short = get_option('wpaicg_woo_generate_short',false); ?>
            <input<?php echo $wpaicg_woo_generate_short ? ' checked':'';?> type="checkbox" name="wpaicg_woo_generate_short" value="1">
            <a class="wpcgai_help_link" href="https://gptaipower.com/optimize-your-woocommerce-product-listings-with-gpt-3-ai/" target="_blank">?</a>
        </div>
        <div class="wpcgai_form_row">
            <label class="wpcgai_label">Generate product tags?:</label>
            <?php $wpaicg_woo_generate_tags = get_option('wpaicg_woo_generate_tags',false); ?>
            <input<?php echo $wpaicg_woo_generate_tags ? ' checked':'';?> type="checkbox" name="wpaicg_woo_generate_tags" value="1">
            <a class="wpcgai_help_link" href="https://gptaipower.com/optimize-your-woocommerce-product-listings-with-gpt-3-ai/" target="_blank">?</a>
        </div>
    </div>
<?php
endif;
?>
