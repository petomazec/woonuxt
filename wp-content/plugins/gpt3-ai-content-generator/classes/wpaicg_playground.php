<?php

namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('\\WPAICG\\WPAICG_Playground')) {
    class WPAICG_Playground
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
            add_action('init',[$this,'wpaicg_stream'],1);
            add_action( 'admin_menu', array( $this, 'wpaicg_playground_menu' ) );
        }

        public function wpaicg_stream()
        {
            if(isset($_GET['wpaicg_stream']) && sanitize_text_field($_GET['wpaicg_stream']) == 'yes'){
                global $wpdb;
                header('Content-type: text/event-stream');
                header('Cache-Control: no-cache');
                if(isset($_REQUEST['title']) && !empty($_REQUEST['title'])) {
                    $wpaicg_prompt = sanitize_text_field($_REQUEST['title']);
                    $openai = \WPAICG\WPAICG_OpenAI::get_instance()->openai();
                    if ($openai) {
                        $wpaicg_args = array(
                            'prompt' => $wpaicg_prompt,
                            'temperature' => (float)$openai->temperature,
                            "max_tokens" => (float)$openai->max_tokens,
                            "frequency_penalty" => (float)$openai->frequency_penalty,
                            "presence_penalty" => (float)$openai->presence_penalty,
                            "stream" => true
                        );
                        if(isset($_REQUEST['temperature']) && !empty($_REQUEST['temperature'])){
                            $wpaicg_args['temperature'] = (float)sanitize_text_field($_REQUEST['temperature']);
                        }
                        if(isset($_REQUEST['engine']) && !empty($_REQUEST['engine'])){
                            $wpaicg_args['model'] = sanitize_text_field($_REQUEST['engine']);
                        }
                        if(isset($_REQUEST['max_tokens']) && !empty($_REQUEST['max_tokens'])){
                            $wpaicg_args['max_tokens'] = (float)sanitize_text_field($_REQUEST['max_tokens']);
                        }
                        if(isset($_REQUEST['frequency_penalty']) && !empty($_REQUEST['frequency_penalty'])){
                            $wpaicg_args['frequency_penalty'] = (float)sanitize_text_field($_REQUEST['frequency_penalty']);
                        }
                        if(isset($_REQUEST['presence_penalty']) && !empty($_REQUEST['presence_penalty'])){
                            $wpaicg_args['presence_penalty'] = (float)sanitize_text_field($_REQUEST['presence_penalty']);
                        }
                        if(isset($_REQUEST['top_p']) && !empty($_REQUEST['top_p'])){
                            $wpaicg_args['top_p'] = (float)sanitize_text_field($_REQUEST['top_p']);
                        }
                        if(isset($_REQUEST['best_of']) && !empty($_REQUEST['best_of'])){
                            $wpaicg_args['best_of'] = (float)sanitize_text_field($_REQUEST['best_of']);
                        }
                        if(isset($_REQUEST['stop']) && !empty($_REQUEST['stop'])){
                            $wpaicg_args['stop'] = explode(',',sanitize_text_field($_REQUEST['stop']));
                        }
                        $openai->completion($wpaicg_args, function ($curl_info, $data){
                            echo _wp_specialchars($data,ENT_NOQUOTES,'UTF-8',true);
                            echo PHP_EOL;
                            ob_flush();
                            flush();
                            return strlen($data);
                        });
                    }
                }
                exit;
            }
        }

        public function wpaicg_playground_menu()
        {
            add_submenu_page(
                'wpaicg',
                'Playground',
                'Playground',
                'manage_options',
                'wpaicg_playground',
                array( $this, 'wpaicg_playground_page' )
            );
        }

        public function wpaicg_playground_page()
        {
            include WPAICG_PLUGIN_DIR . 'admin/extra/wpaicg_playground.php';
        }

    }
    WPAICG_Playground::get_instance();
}
