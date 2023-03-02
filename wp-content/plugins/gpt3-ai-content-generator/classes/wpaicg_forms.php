<?php

namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('\\WPAICG\\WPAICG_Forms')) {
    class WPAICG_Forms
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
            add_action('wp_ajax_wpaicg_update_template',[$this,'wpaicg_update_template']);
            add_action('wp_ajax_wpaicg_template_delete',[$this,'wpaicg_template_delete']);
            add_shortcode('wpaicg_form',[$this,'wpaicg_form_shortcode']);
            add_action( 'admin_menu', array( $this, 'wpaicg_menu' ) );
        }

        public function wpaicg_menu()
        {
            add_submenu_page(
                'wpaicg',
                'GPT Forms',
                'GPT Forms',
                'manage_options',
                'wpaicg_forms',
                array( $this, 'wpaicg_forms' )
            );
        }

        public function wpaicg_form_shortcode($atts)
        {
            ob_start();
            include WPAICG_PLUGIN_DIR . 'admin/extra/wpaicg_form_shortcode.php';
            return ob_get_clean();
        }

        public function wpaicg_template_delete()
        {
            $wpaicg_result = array('status' => 'success');
            if(isset($_POST['id']) && !empty($_POST['id'])){
                wp_delete_post(sanitize_text_field($_POST['id']));
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_update_template()
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
                        'post_type' => 'wpaicg_form',
                        'post_content' => $description,
                        'post_status' => 'publish'
                    ));
                }
                $template_fields = array('prompt','fields','response','category','engine','max_tokens','temperature','top_p','best_of','frequency_penalty','presence_penalty','stop','color','icon','editor','bgcolor','header','dans','ddraft','dclear','dnotice');
                foreach($template_fields as $template_field){
                    if(isset($_POST[$template_field]) && !empty($_POST[$template_field])){
                        $value = wpaicg_util_core()->sanitize_text_or_array_field($_POST[$template_field]);
                        $key = sanitize_text_field($template_field);
                        if($key == 'fields'){
                            $value = json_encode($value);
                        }
                        update_post_meta($wpaicg_prompt_id, 'wpaicg_form_'.$key, $value);
                    }
                    elseif(in_array($template_field,array('bgcolor','header','dans','ddraft','dclear','dnotice')) && (!isset($_POST[$template_field]) || empty($_POST[$template_field]))){
                        delete_post_meta($wpaicg_prompt_id, 'wpaicg_form_'.$template_field);
                    }
                }
                $wpaicg_result['status'] = 'success';
                $wpaicg_result['id'] = $wpaicg_prompt_id;
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_forms()
        {
            include WPAICG_PLUGIN_DIR . 'admin/extra/wpaicg_forms.php';
        }
    }
    WPAICG_Forms::get_instance();
}
