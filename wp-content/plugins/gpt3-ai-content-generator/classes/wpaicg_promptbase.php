<?php

namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('\\WPAICG\\WPAICG_Promptbase')) {
    class WPAICG_Promptbase
    {
        private static $instance = null;
        public $wpaicg_engine = 'text-davinci-003';
        public $wpaicg_max_tokens = 100;
        public $wpaicg_temperature = 0;
        public $wpaicg_top_p = 1;
        public $wpaicg_best_of = 1;
        public $wpaicg_frequency_penalty = 0;
        public $wpaicg_presence_penalty = 0;
        public $wpaicg_stop = [];

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct()
        {
            add_action( 'admin_menu', array( $this, 'wpaicg_menu' ) );
            add_shortcode('wpaicg_prompt',[$this,'wpaicg_prompt_shortcode']);
            add_action('wp_ajax_wpaicg_update_prompt',[$this,'wpaicg_update_prompt']);
            add_action('wp_ajax_wpaicg_prompt_delete',[$this,'wpaicg_prompt_delete']);
        }

        public function wpaicg_menu()
        {
            add_submenu_page(
                'wpaicg',
                'PromptBase',
                'PromptBase',
                'manage_options',
                'wpaicg_promptbase',
                array( $this, 'wpaicg_promptbase' )
            );
        }

        public function wpaicg_update_prompt()
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            if(
                isset($_POST['title'])
                && !empty($_POST['title'])
                && isset($_POST['description'])
                && !empty($_POST['description'])
                && isset($_POST['prompt'])
                && !empty($_POST['prompt'])
            ){
                $title = sanitize_text_field($_POST['title']);
                $description = sanitize_text_field($_POST['description']);
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $wpaicg_prompt_id = sanitize_text_field($_POST['id']);
                    wp_update_post(array(
                        'ID' => $wpaicg_prompt_id,
                        'post_title' => $title,
                        'post_content' => $description
                    ));
                }
                else{
                    $wpaicg_prompt_id = wp_insert_post(array(
                        'post_title' => $title,
                        'post_type' => 'wpaicg_prompt',
                        'post_content' => $description,
                        'post_status' => 'publish'
                    ));
                }
                $prompt_fields = array('prompt','response','category','engine','max_tokens','temperature','top_p','best_of','frequency_penalty','presence_penalty','stop','color','icon','editor','bgcolor','header','dans','ddraft','dclear','dnotice');
                foreach($prompt_fields as $prompt_field){
                    if(isset($_POST[$prompt_field]) && !empty($_POST[$prompt_field])){
                        $value = wpaicg_util_core()->sanitize_text_or_array_field($_POST[$prompt_field]);
                        $key = sanitize_text_field($prompt_field);
                        update_post_meta($wpaicg_prompt_id, 'wpaicg_prompt_'.$key, $value);
                    }
                    elseif(in_array($prompt_field,array('bgcolor','header','dans','ddraft','dclear','dnotice')) && (!isset($_POST[$prompt_field]) || empty($_POST[$prompt_field]))){
                        delete_post_meta($wpaicg_prompt_id, 'wpaicg_prompt_'.$prompt_field);
                    }
                }
                $wpaicg_result['status'] = 'success';
                $wpaicg_result['id'] = $wpaicg_prompt_id;
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_prompt_delete()
        {
            $wpaicg_result = array('status' => 'success');
            if(isset($_POST['id']) && !empty($_POST['id'])){
                wp_delete_post(sanitize_text_field($_POST['id']));
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_promptbase()
        {
            include WPAICG_PLUGIN_DIR . 'admin/extra/wpaicg_promptbase.php';
        }

        public function wpaicg_prompt_shortcode($atts)
        {
            ob_start();
            include WPAICG_PLUGIN_DIR . 'admin/extra/wpaicg_prompt_shortcode.php';
            return ob_get_clean();
        }
    }
    WPAICG_Promptbase::get_instance();
}
