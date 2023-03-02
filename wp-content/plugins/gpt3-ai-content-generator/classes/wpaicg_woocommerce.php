<?php
namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( '\\WPAICG\\WPAICG_WooCommerce' ) ) {
    class WPAICG_WooCommerce
    {
        private static  $instance = null ;

        public static function get_instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct()
        {
            add_action('add_meta_boxes_product', array($this,'wpaicg_register_meta_box'));
            add_action('wp_ajax_wpaicg_product_generator',array($this,'wpaicg_product_generator'));
            add_action('wp_ajax_wpaicg_product_save',array($this,'wpaicg_product_save'));
        }

        public function wpaicg_register_meta_box()
        {
            add_meta_box('wpaicg-woocommerce-generator', esc_html__('GPT3 Product Writer','wp-ai-content-generator'),[$this,'wpaicg_meta_box']);
        }

        public function wpaicg_meta_box($post)
        {
            include WPAICG_PLUGIN_DIR.'admin/views/woocommerce/wpaicg-meta-box.php';
        }

        public function wpaicg_product_save()
        {
            $wpaicg_result = array('status' => 'error','msg' => 'Something went wrong');
            if(
                isset($_REQUEST['id'])
                && !empty($_REQUEST['id'])
                && isset($_REQUEST['mode'])
                && !empty($_REQUEST['mode'])
            ){
                $wpaicgMode = sanitize_text_field($_REQUEST['mode']);
                $wpaicgProductID = sanitize_text_field($_REQUEST['id']);
                if($wpaicgMode == 'new'){
                    $wpaicgProductData = array(
                        'post_title' => '',
                        'post_type' => 'product'
                    );
                    if(isset($_REQUEST['wpaicg_product_title']) && !empty($_REQUEST['wpaicg_product_title'])){
                        $wpaicgProductData['post_title'] = sanitize_text_field($_REQUEST['wpaicg_product_title']);
                    }
                    elseif(isset($_REQUEST['wpaicg_original_title']) && !empty($_REQUEST['wpaicg_original_title'])){
                        $wpaicgProductData['post_title'] = sanitize_text_field($_REQUEST['wpaicg_original_title']);
                    }
                    $wpaicgProductID = wp_insert_post($wpaicgProductData);
                }
                $wpaicgData = array('ID' => $wpaicgProductID);
                if(isset($_REQUEST['wpaicg_product_title']) && !empty($_REQUEST['wpaicg_product_title'])){
                    $wpaicgData['post_title'] = sanitize_text_field($_REQUEST['wpaicg_product_title']);
                    update_post_meta($wpaicgProductID,'wpaicg_product_title', sanitize_text_field($_REQUEST['wpaicg_product_title']));
                }
                if(isset($_REQUEST['wpaicg_product_short']) && !empty($_REQUEST['wpaicg_product_short'])){
                    $wpaicgData['post_excerpt'] = sanitize_text_field($_REQUEST['wpaicg_product_short']);
                    update_post_meta($wpaicgProductID,'wpaicg_product_short', sanitize_text_field($_REQUEST['wpaicg_product_short']));
                }
                if(isset($_REQUEST['wpaicg_product_description']) && !empty($_REQUEST['wpaicg_product_description'])){
                    $wpaicgData['post_content'] = wp_kses_post($_REQUEST['wpaicg_product_description']);
                    update_post_meta($wpaicgProductID,'wpaicg_product_description', wp_kses_post($_REQUEST['wpaicg_product_description']));
                }
                if(isset($_REQUEST['wpaicg_product_tags']) && !empty($_REQUEST['wpaicg_product_tags'])){
                    $wpaicgTags = sanitize_text_field($_REQUEST['wpaicg_product_tags']);
                    $wpaicgTags = array_map('trim', explode(',', $wpaicgTags));
                    wp_set_object_terms($wpaicgProductID, $wpaicgTags,'product_tag');
                    update_post_meta($wpaicgProductID,'wpaicg_product_tags', sanitize_text_field($_REQUEST['wpaicg_product_tags']));
                }
                if(isset($_REQUEST['wpaicg_generate_title']) && $_REQUEST['wpaicg_generate_title']){
                    update_post_meta($wpaicgProductID,'wpaicg_generate_title', 1);
                }
                else{
                    delete_post_meta($wpaicgProductID,'wpaicg_generate_title');
                }
                if(isset($_REQUEST['wpaicg_generate_description']) && $_REQUEST['wpaicg_generate_description']){
                    update_post_meta($wpaicgProductID,'wpaicg_generate_description', 1);
                }
                else{
                    delete_post_meta($wpaicgProductID,'wpaicg_generate_description');
                }
                if(isset($_REQUEST['wpaicg_generate_short']) && $_REQUEST['wpaicg_generate_short']){
                    update_post_meta($wpaicgProductID,'wpaicg_generate_short', 1);
                }
                else{
                    delete_post_meta($wpaicgProductID,'wpaicg_generate_short');
                }
                if(isset($_REQUEST['wpaicg_generate_tags']) && $_REQUEST['wpaicg_generate_tags']){
                    update_post_meta($wpaicgProductID,'wpaicg_generate_tags', 1);
                }
                else{
                    delete_post_meta($wpaicgProductID,'wpaicg_generate_tags');
                }
                wp_update_post($wpaicgData);
                $wpaicg_result['status'] = 'success';
                $wpaicg_result['url'] = admin_url('post.php?post='.$wpaicgProductID.'&action=edit');
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_product_generator()
        {
            global $wpdb;
            $open_ai = WPAICG_OpenAI::get_instance()->openai();
            $wpaicg_result = array('status' => 'error','msg' => 'Something went wrong','data' => '');
            if(!$open_ai){
                $wpaicg_result['msg'] = 'Missing API Setting';
                wp_send_json($wpaicg_result);
                exit;
            }
            ini_set( 'max_execution_time', 1000 );
            $temperature = floatval( $open_ai->temperature );
            $max_tokens = intval( $open_ai->max_tokens );
            $top_p = floatval( $open_ai->top_p );
            $best_of = intval( $open_ai->best_of );
            $frequency_penalty = floatval( $open_ai->frequency_penalty );
            $presence_penalty = floatval( $open_ai->presence_penalty );
            $wpai_language = sanitize_text_field( $open_ai->wpai_language );
            $wpaicg_language_file = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/languages/' . $wpai_language . '.json';
            if ( !file_exists( $wpaicg_language_file ) ) {
                $wpaicg_language_file = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/languages/en.json';
            }
            $wpaicg_language_json = file_get_contents( $wpaicg_language_file );
            $wpaicg_languages = json_decode( $wpaicg_language_json, true );
            if(isset($_REQUEST['step']) && !empty($_REQUEST['step']) && isset($_REQUEST['title']) && !empty($_REQUEST['title'])) {
                $wpaicg_step = sanitize_text_field($_REQUEST['step']);
                $wpaicg_title = sanitize_text_field($_REQUEST['title']);
                $wpaicg_language_key = isset($wpaicg_languages['woo_product_'.$wpaicg_step]) ? 'woo_product_'.$wpaicg_step : 'woo_product_title';
                $myprompt = isset($wpaicg_languages[$wpaicg_language_key]) && !empty($wpaicg_languages[$wpaicg_language_key]) ? sprintf($wpaicg_languages[$wpaicg_language_key], $wpaicg_title) : $wpaicg_title;
                $wpaicg_result['prompt'] = $myprompt;
                $wpaicg_ai_model = get_option('wpaicg_ai_model','text-davinci-003');
                $complete = $open_ai->completion([
                    'model' => $wpaicg_ai_model,
                    'prompt' => $myprompt,
                    'temperature' => $temperature,
                    'max_tokens' => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty' => $presence_penalty,
                    'top_p' => $top_p,
                    'best_of' => $best_of,
                ]);
                $complete = json_decode( $complete );
                if ( isset( $complete->error ) ) {
                    $complete = $complete->error->message;
                    $wpaicg_result['msg'] = esc_html( $complete );
                } else {
                    $complete = $complete->choices[0]->text;
                    $wpaicg_result['status'] = 'success';
                    if($wpaicg_step == 'tags'){
                        $wpaicgTags = preg_split( "/\r\n|\n|\r/", $complete );
                        $wpaicgTags = preg_replace( '/^\\d+\\.\\s/', '', $wpaicgTags );
                        foreach($wpaicgTags as $wpaicgTag){
                            if(!empty($wpaicgTag)){
                                $wpaicg_result['data'] .= (empty($wpaicg_result['data']) ? '' : ', ').trim($wpaicgTag);
                            }
                        }
                    }
                    else{
                        $wpaicg_result['data'] = trim($complete);
                        if($wpaicg_step == 'title'){
                            $wpaicg_result['data'] = str_replace('"','',$wpaicg_result['data']);
                        }
                        if(empty($wpaicg_result['data'])){
                            $wpaicg_result['data'] = 'There was no response for this product from OpenAI. Please try again';
                        }
                    }
                }
            }
            wp_send_json($wpaicg_result);
        }
    }

    WPAICG_WooCommerce::get_instance();
}
