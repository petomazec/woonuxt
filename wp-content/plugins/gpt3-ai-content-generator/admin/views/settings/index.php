<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$flag = true;
$errors = '';
wp_enqueue_script('wp-color-picker');
wp_enqueue_style('wp-color-picker');

if ( isset( $_POST['wpaicg_submit'] ) ) {
    global  $wpdb ;
    $table = $wpdb->prefix . 'wpaicg';
    $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE name = %s", 'wpaicg_settings' ) );
    $newData = [];
    extract( \WPAICG\wpaicg_util_core()->sanitize_text_or_array_field($_POST['wpaicg_settings']) );

    if ( !is_numeric( $temperature ) || floatval( $temperature ) < 0 || floatval( $temperature ) > 1 ) {
        $errors = 'Please enter a valid temperature value between 0 and 1.';
        $flag = false;
    }


    if ( !is_numeric( $max_tokens ) || floatval( $max_tokens ) < 64 || floatval( $max_tokens ) > 1400 ) {
        $errors = 'Please enter a valid max token value between 64 and 1400.';
        $flag = false;
    }


    if ( !is_numeric( $top_p ) || floatval( $top_p ) < 0 || floatval( $top_p ) > 1 ) {
        $errors = 'Please enter a valid top p value between 0 and 1.';
        $flag = false;
    }


    if ( !is_numeric( $best_of ) || floatval( $best_of ) < 1 || floatval( $best_of ) > 20 ) {
        $errors = 'Please enter a valid best of value between 1 and 20.';
        $flag = false;
    }


    if ( !is_numeric( $frequency_penalty ) || floatval( $frequency_penalty ) < 0 || floatval( $frequency_penalty ) > 2 ) {
        $errors = 'Please enter a valid frequency penalty value between 0 and 2.';
        $flag = false;
    }


    if ( !is_numeric( $presence_penalty ) || floatval( $presence_penalty ) < 0 || floatval( $presence_penalty ) > 2 ) {
        $errors = 'Please enter a valid presence penalty value between 0 and 2.';
        $flag = false;
    }

// if api_key is empty then give error
    if ( isset($_POST['wpaicg_chat_temperature']) && (!is_numeric( $_POST['wpaicg_chat_temperature'] ) || floatval( $_POST['wpaicg_chat_temperature'] ) < 0 || floatval( $_POST['wpaicg_chat_temperature'] ) > 1 )) {
        $errors = 'Please enter a valid temperature value between 0 and 1.';
        $flag = false;
    }


    if (isset($_POST['wpaicg_chat_max_tokens']) && ( !is_numeric( $_POST['wpaicg_chat_max_tokens'] ) || floatval( $_POST['wpaicg_chat_max_tokens'] ) < 64 || floatval( $_POST['wpaicg_chat_max_tokens'] ) > 1400 )) {
        $errors = 'Please enter a valid max token value between 64 and 1400.';
        $flag = false;
    }


    if (isset($_POST['wpaicg_chat_top_p']) && (!is_numeric( $_POST['wpaicg_chat_top_p'] ) || floatval( $_POST['wpaicg_chat_top_p'] ) < 0 || floatval( $_POST['wpaicg_chat_top_p'] ) > 1 )){
        $errors = 'Please enter a valid top p value between 0 and 1.';
        $flag = false;
    }


    if (isset($_POST['wpaicg_chat_best_of']) && ( !is_numeric( $_POST['wpaicg_chat_best_of'] ) || floatval( $_POST['wpaicg_chat_best_of'] ) < 1 || floatval( $_POST['wpaicg_chat_best_of'] ) > 20 )) {
        $errors = 'Please enter a valid best of value between 1 and 20.';
        $flag = false;
    }


    if (isset($_POST['wpaicg_chat_frequency_penalty']) && ( !is_numeric( $_POST['wpaicg_chat_frequency_penalty'] ) || floatval( $_POST['wpaicg_chat_frequency_penalty'] ) < 0 || floatval( $_POST['wpaicg_chat_frequency_penalty'] ) > 2 )) {
        $errors = 'Please enter a valid frequency penalty value between 0 and 2.';
        $flag = false;
    }


    if (isset($_POST['wpaicg_chat_presence_penalty']) && ( !is_numeric( $_POST['wpaicg_chat_presence_penalty'] ) || floatval( $_POST['wpaicg_chat_presence_penalty'] ) < 0 || floatval( $_POST['wpaicg_chat_presence_penalty'] ) > 2 ) ){
        $errors = 'Please enter a valid presence penalty value between 0 and 2.';
        $flag = false;
    }

// if api_key is empty then give error

    if ( empty($api_key) ) {
        $errors = 'Please enter a valid API key.';
        $flag = false;
    }

    $data = [
        'name'                   => 'wpaicg_settings',
        'temperature'            => $temperature,
        'max_tokens'             => $max_tokens,
        'top_p'                  => $top_p,
        'best_of'                => $best_of,
        'frequency_penalty'      => $frequency_penalty,
        'presence_penalty'       => $presence_penalty,
        'img_size'               => $img_size,
        'api_key'                => $api_key,
        'wpai_language'          => $wpai_language,
        'wpai_modify_headings'   => ( isset( $wpai_modify_headings ) ? 1 : 0 ),
        'wpai_add_img'           => ( isset( $wpai_add_img ) ? 1 : 0 ),
        'wpai_add_tagline'       => ( isset( $wpai_add_tagline ) ? 1 : 0 ),
        'wpai_add_intro'         => ( isset( $wpai_add_intro ) ? 1 : 0 ),
        'wpai_add_faq'           => ( isset( $wpai_add_faq ) ? 1 : 0 ),
        'wpai_add_conclusion'    => ( isset( $wpai_add_conclusion ) ? 1 : 0 ),
        'wpai_add_keywords_bold' => ( isset( $wpai_add_keywords_bold ) ? 1 : 0 ),
        'wpai_number_of_heading' => $wpai_number_of_heading,
        'wpai_heading_tag'       => $wpai_heading_tag,
        'wpai_writing_style'     => $wpai_writing_style,
        'wpai_writing_tone'      => $wpai_writing_tone,
        'wpai_cta_pos'           => $wpai_cta_pos,
        'added_date'             => date( 'Y-m-d H:i:s' ),
        'modified_date'          => date( 'Y-m-d H:i:s' ),
    ];

    if ( $flag == true ) {

        if ( !empty($result->name) ) {
            $wpdb->update(
                $table,
                $data,
                [
                    'name' => 'wpaicg_settings',
                ],
                [
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ],
                [ '%s' ]
            );
        } else {
            $wpdb->insert( $table, $data, [
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ] );
        }

        if ( isset( $_POST['_wpaicg_chatbox_you'] ) ) {
            update_option( '_wpaicg_chatbox_you', sanitize_text_field( $_POST['_wpaicg_chatbox_you'] ) );
        }
        if ( isset( $_POST['_wpaicg_ai_thinking'] ) ) {
            update_option( '_wpaicg_ai_thinking', sanitize_text_field( $_POST['_wpaicg_ai_thinking'] ) );
        }
        if ( isset( $_POST['_wpaicg_typing_placeholder'] ) ) {
            update_option( '_wpaicg_typing_placeholder', sanitize_text_field( $_POST['_wpaicg_typing_placeholder'] ) );
        }
        if ( isset( $_POST['_wpaicg_chatbox_welcome_message'] ) ) {
            update_option( '_wpaicg_chatbox_welcome_message', sanitize_text_field( $_POST['_wpaicg_chatbox_welcome_message'] ) );
        }
        if ( isset( $_POST['_wpaicg_chatbox_ai_name'] ) ) {
            update_option( '_wpaicg_chatbox_ai_name', sanitize_text_field( $_POST['_wpaicg_chatbox_ai_name'] ) );
        }

        if ( isset( $_POST['_wpaicg_image_featured'] ) ) {
            update_option( '_wpaicg_image_featured', sanitize_text_field( $_POST['_wpaicg_image_featured'] ) );
        } else {
            delete_option( '_wpaicg_image_featured' );
        }

        if ( isset( $_POST['_wpaicg_seo_meta_desc'] ) ) {
            update_option( '_wpaicg_seo_meta_desc', sanitize_text_field( $_POST['_wpaicg_seo_meta_desc'] ) );
        }
        else {
            delete_option( '_wpaicg_seo_meta_desc' );
        }

        if ( isset( $_POST['rank_math_description'] ) ) {
            update_option( 'rank_math_description', sanitize_text_field( $_POST['rank_math_description'] ) );
        }
        else {
            delete_option( 'rank_math_description' );
        }
        if ( isset( $_POST['_wpaicg_image_style'] ) ) {
            update_option( '_wpaicg_image_style', sanitize_text_field( $_POST['_wpaicg_image_style'] ) );
        }
        if ( isset( $_POST['wpaicg_woo_generate_title'] ) ) {
            update_option( 'wpaicg_woo_generate_title', sanitize_text_field( $_POST['wpaicg_woo_generate_title'] ) );
        }
        else {
            delete_option( 'wpaicg_woo_generate_title' );
        }
        if ( isset( $_POST['wpaicg_woo_generate_description'] ) ) {
            update_option( 'wpaicg_woo_generate_description', sanitize_text_field( $_POST['wpaicg_woo_generate_description'] ) );
        }
        else {
            delete_option( 'wpaicg_woo_generate_description' );
        }
        if ( isset( $_POST['wpaicg_woo_generate_short'] ) ) {
            update_option( 'wpaicg_woo_generate_short', sanitize_text_field( $_POST['wpaicg_woo_generate_short'] ) );
        }
        else {
            delete_option( 'wpaicg_woo_generate_short' );
        }
        if ( isset( $_POST['wpaicg_woo_generate_tags'] ) ) {
            update_option( 'wpaicg_woo_generate_tags', sanitize_text_field( $_POST['wpaicg_woo_generate_tags'] ) );
        }
        else {
            delete_option( 'wpaicg_woo_generate_tags' );
        }
        if ( isset( $_POST['wpaicg_ai_model'] ) ) {
            update_option( 'wpaicg_chat_model', sanitize_text_field( $_POST['wpaicg_chat_model'] ) );
        }
        else {
            update_option( 'wpaicg_chat_model', 'text-davinci-003');
        }
        if ( isset( $_POST['wpaicg_ai_model'] ) ) {
            update_option( 'wpaicg_ai_model', sanitize_text_field( $_POST['wpaicg_ai_model'] ) );
        }
        else {
            update_option( 'wpaicg_ai_model', 'text-davinci-003');
        }

        if ( isset( $_POST['wpaicg_chat_temperature'] ) ) {
            update_option( 'wpaicg_chat_temperature', sanitize_text_field( $_POST['wpaicg_chat_temperature'] ) );
        }
        else {
            delete_option( 'wpaicg_chat_temperature' );
        }

        if ( isset( $_POST['wpaicg_chat_max_tokens'] ) ) {
            update_option( 'wpaicg_chat_max_tokens', sanitize_text_field( $_POST['wpaicg_chat_max_tokens'] ) );
        }
        else {
            delete_option( 'wpaicg_chat_max_tokens' );
        }
        if ( isset( $_POST['wpaicg_chat_top_p'] ) ) {
            update_option( 'wpaicg_chat_top_p', sanitize_text_field( $_POST['wpaicg_chat_top_p'] ) );
        }
        else {
            delete_option( 'wpaicg_chat_top_p' );
        }
        if ( isset( $_POST['wpaicg_chat_best_of'] ) ) {
            update_option( 'wpaicg_chat_best_of', sanitize_text_field( $_POST['wpaicg_chat_best_of'] ) );
        }
        else {
            delete_option( 'wpaicg_chat_best_of' );
        }
        if ( isset( $_POST['wpaicg_chat_frequency_penalty'] ) ) {
            update_option( 'wpaicg_chat_frequency_penalty', sanitize_text_field( $_POST['wpaicg_chat_frequency_penalty'] ) );
        }
        else {
            delete_option( 'wpaicg_chat_frequency_penalty' );
        }
        if ( isset( $_POST['wpaicg_chat_presence_penalty'] ) ) {
            update_option( 'wpaicg_chat_presence_penalty', sanitize_text_field( $_POST['wpaicg_chat_presence_penalty'] ) );
        }
        else {
            delete_option( 'wpaicg_chat_presence_penalty' );
        }
        if ( isset( $_POST['_wpaicg_seo_meta_tag'] ) ) {
            update_option( '_wpaicg_seo_meta_tag', sanitize_text_field( $_POST['_wpaicg_seo_meta_tag'] ) );
        }
        else {
            delete_option( '_wpaicg_seo_meta_tag' );
        }
        if(isset($_POST['wpaicg_chat_widget']) && !empty($_POST['wpaicg_chat_widget'])){
            update_option('wpaicg_chat_widget',\WPAICG\wpaicg_util_core()->sanitize_text_or_array_field($_POST['wpaicg_chat_widget']));
        }
        if(isset($_POST['wpaicg_toc'])){
            update_option('wpaicg_toc',sanitize_text_field($_POST['wpaicg_toc']));
        }
        else{
            delete_option('wpaicg_toc');
        }
        if(isset($_POST['wpaicg_toc_title']) && !empty($_POST['wpaicg_toc_title'])){
            update_option('wpaicg_toc_title',sanitize_text_field($_POST['wpaicg_toc_title']));
        }
        else{
            delete_option('wpaicg_toc_title');
        }
        if(isset($_POST['wpaicg_toc_title_tag']) && !empty($_POST['wpaicg_toc_title_tag'])){
            update_option('wpaicg_toc_title_tag',sanitize_text_field($_POST['wpaicg_toc_title_tag']));
        }
        else{
            delete_option('wpaicg_toc_title_tag');
        }
// wpaicg_intro_title_tag
        if(isset($_POST['wpaicg_intro_title_tag']) && !empty($_POST['wpaicg_intro_title_tag'])){
            update_option('wpaicg_intro_title_tag',sanitize_text_field($_POST['wpaicg_intro_title_tag']));
        }
        else{
            delete_option('wpaicg_intro_title_tag');
        }
// wpaicg_conclusion_title_tag
        if(isset($_POST['wpaicg_conclusion_title_tag']) && !empty($_POST['wpaicg_conclusion_title_tag'])){
            update_option('wpaicg_conclusion_title_tag',sanitize_text_field($_POST['wpaicg_conclusion_title_tag']));
        }
        else{
            delete_option('wpaicg_conclusion_title_tag');
        }
        if(isset($_POST['wpaicg_chat_language']) && !empty($_POST['wpaicg_chat_language'])){
            update_option('wpaicg_chat_language',sanitize_text_field($_POST['wpaicg_chat_language']));
        }
        else{
            delete_option('wpaicg_chat_language');
        }
        if(isset($_POST['wpaicg_conversation_cut']) && !empty($_POST['wpaicg_conversation_cut'])){
            update_option('wpaicg_conversation_cut',sanitize_text_field($_POST['wpaicg_conversation_cut']));
        }
        else{
            delete_option('wpaicg_conversation_cut');
        }
        if(isset($_POST['wpaicg_sd_api_key']) && !empty($_POST['wpaicg_sd_api_key'])){
            update_option('wpaicg_sd_api_key',sanitize_text_field($_POST['wpaicg_sd_api_key']));
        }
        else{
            delete_option('wpaicg_sd_api_key');
        }
        if(isset($_POST['wpaicg_sd_api_version']) && !empty($_POST['wpaicg_sd_api_version'])){
            update_option('wpaicg_sd_api_version',sanitize_text_field($_POST['wpaicg_sd_api_version']));
        }
        else{
            delete_option('wpaicg_sd_api_version');
        }
        if(isset($_POST['wpaicg_chat_embedding']) && !empty($_POST['wpaicg_chat_embedding'])){
            update_option('wpaicg_chat_embedding',sanitize_text_field($_POST['wpaicg_chat_embedding']));
        }
        else{
            delete_option('wpaicg_chat_embedding');
        }
        if(isset($_POST['wpaicg_chat_no_answer']) && !empty($_POST['wpaicg_chat_no_answer'])){
            update_option('wpaicg_chat_no_answer',sanitize_text_field($_POST['wpaicg_chat_no_answer']));
        }
        else{
            delete_option('wpaicg_chat_no_answer');
        }
        if(isset($_POST['wpaicg_chat_embedding_type']) && !empty($_POST['wpaicg_chat_embedding_type'])){
            update_option('wpaicg_chat_embedding_type',sanitize_text_field($_POST['wpaicg_chat_embedding_type']));
        }
        else{
            delete_option('wpaicg_chat_embedding_type');
        }
        if(isset($_POST['wpaicg_chat_embedding_top']) && !empty($_POST['wpaicg_chat_embedding_top'])){
            update_option('wpaicg_chat_embedding_top',sanitize_text_field($_POST['wpaicg_chat_embedding_top']));
        }
        else{
            delete_option('wpaicg_chat_embedding_top');
        }
        if ( isset( $_POST['_yoast_wpseo_metadesc'] ) ) {
            update_option( '_yoast_wpseo_metadesc', sanitize_text_field( $_POST['_yoast_wpseo_metadesc'] ) );
        }
        else {
            delete_option( '_yoast_wpseo_metadesc' );
        }
        if ( isset( $_POST['_aioseo_description'] ) ) {
            update_option( '_aioseo_description', sanitize_text_field( $_POST['_aioseo_description'] ) );
        }
        else {
            delete_option( '_aioseo_description' );
        }
        if ( isset( $_POST['wpaicg_search_language'] ) ) {
            update_option( 'wpaicg_search_language', sanitize_text_field( $_POST['wpaicg_search_language'] ) );
        }
        else {
            delete_option( 'wpaicg_search_language' );
        }
        if ( isset( $_POST['wpaicg_search_placeholder'] ) ) {
            update_option( 'wpaicg_search_placeholder', sanitize_text_field( $_POST['wpaicg_search_placeholder'] ) );
        }
        else {
            delete_option( 'wpaicg_search_placeholder' );
        }
        if ( isset( $_POST['wpaicg_search_no_result'] ) ) {
            update_option( 'wpaicg_search_no_result', sanitize_text_field( $_POST['wpaicg_search_no_result'] ) );
        }
        else {
            delete_option( 'wpaicg_search_no_result' );
        }
        if ( isset( $_POST['wpaicg_search_font_size'] ) ) {
            update_option( 'wpaicg_search_font_size', sanitize_text_field( $_POST['wpaicg_search_font_size'] ) );
        }
        else {
            delete_option( 'wpaicg_search_font_size' );
        }
        if ( isset( $_POST['wpaicg_search_font_color'] ) ) {
            update_option( 'wpaicg_search_font_color', sanitize_text_field( $_POST['wpaicg_search_font_color'] ) );
        }
        else {
            delete_option( 'wpaicg_search_font_color' );
        }
        if ( isset( $_POST['wpaicg_search_bg_color'] ) ) {
            update_option( 'wpaicg_search_bg_color', sanitize_text_field( $_POST['wpaicg_search_bg_color'] ) );
        }
        else {
            delete_option( 'wpaicg_search_bg_color' );
        }
        if ( isset( $_POST['wpaicg_search_width'] ) ) {
            update_option( 'wpaicg_search_width', sanitize_text_field( $_POST['wpaicg_search_width'] ) );
        }
        else {
            delete_option( 'wpaicg_search_width' );
        }
        if ( isset( $_POST['wpaicg_search_height'] ) ) {
            update_option( 'wpaicg_search_height', sanitize_text_field( $_POST['wpaicg_search_height'] ) );
        }
        else {
            delete_option( 'wpaicg_search_height' );
        }
        if ( isset( $_POST['wpaicg_search_border_color'] ) ) {
            update_option( 'wpaicg_search_border_color', sanitize_text_field( $_POST['wpaicg_search_border_color'] ) );
        }
        else {
            delete_option( 'wpaicg_search_border_color' );
        }
        if ( isset( $_POST['wpaicg_search_loading_color'] ) ) {
            update_option( 'wpaicg_search_loading_color', sanitize_text_field( $_POST['wpaicg_search_loading_color'] ) );
        }
        else {
            delete_option( 'wpaicg_search_loading_color' );
        }
        if ( isset( $_POST['wpaicg_search_result_font_size'] ) ) {
            update_option( 'wpaicg_search_result_font_size', sanitize_text_field( $_POST['wpaicg_search_result_font_size'] ) );
        }
        else {
            delete_option( 'wpaicg_search_result_font_size' );
        }
        if ( isset( $_POST['wpaicg_search_result_font_color'] ) ) {
            update_option( 'wpaicg_search_result_font_color', sanitize_text_field( $_POST['wpaicg_search_result_font_color'] ) );
        }
        else {
            delete_option( 'wpaicg_search_result_font_color' );
        }
        if ( isset( $_POST['wpaicg_search_result_bg_color'] ) ) {
            update_option( 'wpaicg_search_result_bg_color', sanitize_text_field( $_POST['wpaicg_search_result_bg_color'] ) );
        }
        else {
            delete_option( 'wpaicg_search_result_bg_color' );
        }
        if ( isset( $_POST['wpaicg_image_source'] ) && !empty( $_POST['wpaicg_image_source'] ) ) {
            update_option( 'wpaicg_image_source', sanitize_text_field( $_POST['wpaicg_image_source'] ) );
        }
        else {
            delete_option( 'wpaicg_image_source' );
        }
        if ( isset( $_POST['wpaicg_featured_image_source'] ) && !empty( $_POST['wpaicg_featured_image_source'] ) ) {
            update_option( 'wpaicg_featured_image_source', sanitize_text_field( $_POST['wpaicg_featured_image_source'] ) );
        }
        else {
            delete_option( 'wpaicg_featured_image_source' );
        }
        if ( isset( $_POST['wpaicg_pexels_orientation'] ) && !empty( $_POST['wpaicg_pexels_orientation'] ) ) {
            update_option( 'wpaicg_pexels_orientation', sanitize_text_field( $_POST['wpaicg_pexels_orientation'] ) );
        }
        else {
            delete_option( 'wpaicg_pexels_orientation' );
        }
        if ( isset( $_POST['wpaicg_pexels_size'] ) && !empty( $_POST['wpaicg_pexels_size'] ) ) {
            update_option( 'wpaicg_pexels_size', sanitize_text_field( $_POST['wpaicg_pexels_size'] ) );
        }
        else {
            delete_option( 'wpaicg_pexels_size' );
        }
        if ( isset( $_POST['wpaicg_pexels_api'] ) && !empty( $_POST['wpaicg_pexels_api'] ) ) {
            update_option( 'wpaicg_pexels_api', sanitize_text_field( $_POST['wpaicg_pexels_api'] ) );
        }
        else {
            delete_option( 'wpaicg_pexels_api' );
            delete_option( 'wpaicg_pexels_size' );
            delete_option( 'wpaicg_pexels_orientation' );
            if ( isset( $_POST['wpaicg_featured_image_source'] ) && !empty( $_POST['wpaicg_featured_image_source'] ) && $_POST['wpaicg_featured_image_source'] == 'pexels') {
                delete_option('wpaicg_featured_image_source');
            }
            if ( isset( $_POST['wpaicg_image_source'] ) && !empty( $_POST['wpaicg_image_source'] ) && $_POST['wpaicg_image_source'] == 'pexels') {
                delete_option('wpaicg_image_source');
            }
        }
        $message = "Records successfully updated!";
    }

}

global  $wpdb ;
$table = $wpdb->prefix . 'wpaicg';
$existingValue = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE name = %s", 'wpaicg_settings' ), ARRAY_A );
function help_img()
{
}

?>
<script>
    jQuery( function() {
        jQuery( "#wpcgai_tabs" ).tabs();
    } );
</script>

<h3>Settings</h3>
<?php

if ( !empty($message) && $flag == true ) {
    echo  "<h4 id='setting_message' style='color: green;'>" . esc_html( $message ) . "</h4>" ;
} else {
    echo  "<h4 id='setting_message' style='color: red;'>" . esc_html( $errors ) . "</h4>" ;
}
$wpaicg_custom_models = get_option('wpaicg_custom_models',array());
$wpaicg_custom_models = array_merge(array('text-davinci-003','text-curie-001','text-babbage-001','text-ada-001'),$wpaicg_custom_models);
?>
<style>
    .wp-picker-holder{
        position: absolute;
    }
    .wpaicg_chatbox_icon{
        cursor: pointer;
    }
    .wpaicg_chatbox_icon svg{
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function($)
    {
        if(jQuery("#wpcgai_setting_message").text() != '')
        {
            jQuery("#wpcgai_setting_message").delay(4000).slideUp(300);
        }
        $('.wpaicgchat_color').wpColorPicker();
        $('.wpaicg_chatbox_icon').click(function (e){
            e.preventDefault();
            $('.wpaicg_chatbox_icon_default').prop('checked',false);
            $('.wpaicg_chatbox_icon_custom').prop('checked',true);
            var button = $(e.currentTarget),
                custom_uploader = wp.media({
                    title: '<?php echo __('Insert image')?>',
                    library : {
                        type : 'image'
                    },
                    button: {
                        text: '<?php echo __('Use this image')?>'
                    },
                    multiple: false
                }).on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    button.html('<img width="75" height="75" src="'+attachment.url+'">');
                    $('.wpaicg_chat_icon_url').val(attachment.id);
                }).open();
        });
        $('.wpaicg_chatbox_avatar').click(function (e){
            e.preventDefault();
            $('.wpaicg_chatbox_avatar_default').prop('checked',false);
            $('.wpaicg_chatbox_avatar_custom').prop('checked',true);
            var button = $(e.currentTarget),
                custom_uploader = wp.media({
                    title: '<?php echo __('Insert image')?>',
                    library : {
                        type : 'image'
                    },
                    button: {
                        text: '<?php echo __('Use this image')?>'
                    },
                    multiple: false
                }).on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    button.html('<img width="40" height="40" src="'+attachment.url+'">');
                    $('.wpaicg_ai_avatar_id').val(attachment.id);
                }).open();
        });
    });
</script>
<div class="wpcgai_container">
    <div id="wpcgai_tabs">
        <form action="<?php
        echo  esc_url( $_SERVER['REQUEST_URI'] ) ;
        ?>" method="post">
            <ul>
                <li><a href="#tabs-1">AI Engine</a></li>
                <li><a href="#tabs-2">Content</a></li>
                <li><a href="#tabs-6">SEO</a></li>
                <?php
                if ( class_exists( 'woocommerce' ) ){
                    ?>
                    <li><a href="#tabs-7">WooCommerce</a></li>
                    <?php
                }
                ?>
                <li><a href="#tabs-5">Image</a></li>
                <li><a href="#tabs-4">ChatGPT</a></li>
                <li><a href="#tabs-8">SearchGPT</a></li>
                <li><a href="#tabs-3">How to Use?</a></li>
            </ul>
            <?php
            include WPAICG_PLUGIN_DIR.'admin/views/settings/ai.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/content.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/seo.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/woocommerce.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/image.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/chat.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/how-to.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/search.php';
            ?>
            <table>
                <tr>
                    <td><input type="submit" value="Save" name="wpaicg_submit" class="button button-primary button-large">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
