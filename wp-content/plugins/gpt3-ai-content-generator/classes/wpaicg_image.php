<?php

namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('\\WPAICG\\WPAICG_Image')) {
    class WPAICG_Image
    {
        private static $instance = null;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct()
        {
            add_action('wp_ajax_wpaicg_image_generator',[$this, 'wpaicg_image_generator_action']);
            add_action('wp_ajax_nopriv_wpaicg_image_generator',[$this, 'wpaicg_image_generator_action']);
            add_action('wp_ajax_wpaicg_image_stable_diffusion',[$this, 'wpaicg_image_stable_diffusion']);
            add_action('wp_ajax_nopriv_wpaicg_image_stable_diffusion',[$this, 'wpaicg_image_stable_diffusion']);
            add_action('wp_ajax_nopriv_wpaicg_save_image_media',[$this, 'wpaicg_save_image_media']);
            add_action('wp_ajax_wpaicg_save_image_media',[$this, 'wpaicg_save_image_media']);
            add_shortcode('wpcgai_img',[$this,'wpaicg_image_generator_shortcode']);
            add_action( 'admin_menu', array( $this, 'wpaicg_menu' ) );
        }

        public function wpaicg_menu()
        {
            add_submenu_page(
                'wpaicg',
                'Image Generator',
                'Image Generator',
                'manage_options',
                'wpaicg_image_generator',
                array( $this, 'wpaicg_image_generator' )
            );
        }

        public function wpaicg_admin_footer()
        {
            ?>
            <div class="wpaicg-overlay" style="display: none">
                <div class="wpaicg_modal">
                    <div class="wpaicg_modal_head">
                        <span class="wpaicg_modal_title">GPT3 Modal</span>
                        <span class="wpaicg_modal_close">&times;</span>
                    </div>
                    <div class="wpaicg_modal_content"></div>
                </div>
            </div>
            <div class="wpcgai_lds-ellipsis" style="display: none">
                <div class="wpaicg-generating-title">Generating content..</div>
                <div class="wpaicg-generating-process"></div>
                <div class="wpaicg-timer"></div>
            </div>
            <?php
        }

        public function wpaicg_image_generator_shortcode($wpaicg_shortcode_settings)
        {
            add_action('wp_footer',[$this,'wpaicg_admin_footer']);
            ob_start();
            include WPAICG_PLUGIN_DIR.'admin/extra/wpaicg_image_shortcode.php';
            return ob_get_clean();
        }

        public function wpaicg_image_generator_action()
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            $open_ai = WPAICG_OpenAI::get_instance()->openai();
            $wpaicg_nonce = sanitize_text_field($_REQUEST['_wpnonce']);
            if ( !wp_verify_nonce( $wpaicg_nonce, 'wpaicg-image-generator' ) ) {
                $wpaicg_result['msg'] = 'Security check';
            }
            else {
                if (!$open_ai) {
                    $wpaicg_result['msg'] = 'Missing API Setting';
                } else {
                    $prompt = sanitize_text_field($_POST['prompt']);
                    $prompt_title = sanitize_text_field($_POST['prompt']);
                    $img_size = sanitize_text_field($_POST['img_size']);
                    $num_images = (int)sanitize_text_field($_POST['num_images']);
                    $prompt_elements = array(
                        'artist' => 'Painter',
                        'art_style' => 'Style',
                        'photography_style' => 'Photography Style',
                        'composition' => 'Composition',
                        'resolution' => 'Resolution',
                        'color' => 'Color',
                        'special_effects' => 'Special Effects',
                        'lighting' => 'Lighting',
                        'subject' => 'Subject',
                        'camera_settings' => 'Camera Settings',
                    );
                    foreach ($prompt_elements as $key => $value) {
                        if ($_POST[$key] != "None") {
                            $prompt = $prompt . ". " . $value . ": " . sanitize_text_field($_POST[$key]);
                        }
                    }
                    $imgresult = $open_ai->image([
                        "prompt" => $prompt,
                        "n" => $num_images,
                        "size" => $img_size,
                        "response_format" => "url",
                    ]);
                    $img_result = json_decode($imgresult);
                    if (isset($img_result->error)) {
                        $wpaicg_result['msg'] = trim($img_result->error->message);
                    } else {
                        $wpaicg_result['imgs'] = array();
                        for ($i = 0; $i < $num_images; $i++) {
                            $wpaicg_result['imgs'][] = $img_result->data[$i]->url;
                        }
                        $wpaicg_result['title'] = $prompt_title;
                        $wpaicg_result['status'] = 'success';
                    }

                }
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_stable_diffusion_images($url, $headers)
        {
            $images = array();
            try {
                $response = wp_remote_get($url, array('headers' => $headers));
                if(is_wp_error($response)){
                    $images = $response->get_error_message();
                }
                else{
                    $body = json_decode($response['body'],true);
                    if($body['status'] == 'succeeded'){
                        $images = $body['output'];
                    }
                    elseif($body['status'] == 'processing' || $body['status'] == 'starting'){
                        $images = $this->wpaicg_stable_diffusion_images($url, $headers);
                    }
                    else{
                        $images = 'Something went wrong';
                    }
                }
            }
            catch (\Exception $exception){
                $images = $exception->getMessage();
            }
            return $images;
        }

        public function wpaicg_image_stable_diffusion()
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            $wpaicg_nonce = sanitize_text_field($_REQUEST['_wpnonce']);
            if ( !wp_verify_nonce( $wpaicg_nonce, 'wpaicg-image-generator' ) ) {
                $wpaicg_result['msg'] = 'Security check';
            }
            else {
                $wpaicg_sd_api_key = get_option('wpaicg_sd_api_key', '');
                $wpaicg_sd_api_version = get_option('wpaicg_sd_api_version', 'f178fa7a1ae43a9a9af01b833b9d2ecf97b1bcb0acfd2dc5dd04895e042863f1');
                if (empty($wpaicg_sd_api_key)) {
                    $wpaicg_result['msg'] = 'Missing Stable Diffusion API';
                } else {
                    if (isset($_REQUEST['prompt']) && !empty($_REQUEST['prompt'])) {
                        $headers = array(
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Token ' . $wpaicg_sd_api_key
                        );
                        $prompt_title = sanitize_text_field($_REQUEST['prompt']);
                        $prompt = sanitize_text_field($_REQUEST['prompt']);
                        $prompt_elements = array(
                            'artist' => 'Painter',
                            'art_style' => 'Style',
                            'photography_style' => 'Photography Style',
                            'composition' => 'Composition',
                            'resolution' => 'Resolution',
                            'color' => 'Color',
                            'special_effects' => 'Special Effects',
                            'lighting' => 'Lighting',
                            'subject' => 'Subject',
                            'camera_settings' => 'Camera Settings',
                        );
                        foreach ($prompt_elements as $key => $value) {
                            if ($_POST[$key] != "None") {
                                $prompt = $prompt . ". " . $value . ": " . sanitize_text_field($_POST[$key]);
                            }
                        }
                        $body = array(
                            'version' => $wpaicg_sd_api_version,
                            'input' => array(
                                'prompt' => $prompt,
                                'num_outputs' => 1,
                                'negative_prompt' => isset($_REQUEST['negative_prompt']) && !empty($_REQUEST['negative_prompt']) ? sanitize_text_field($_REQUEST['negative_prompt']) : '',
                                'width' => isset($_REQUEST['width']) && !empty($_REQUEST['width']) ? sanitize_text_field($_REQUEST['width']) : 768,
                                'height' => isset($_REQUEST['height']) && !empty($_REQUEST['height']) ? sanitize_text_field($_REQUEST['height']) : 768,
                                'prompt_strength' => isset($_REQUEST['prompt_strength']) && !empty($_REQUEST['prompt_strength']) ? sanitize_text_field($_REQUEST['prompt_strength']) : 0.8,
                                'num_inference_steps' => isset($_REQUEST['num_inference_steps']) && !empty($_REQUEST['num_inference_steps']) ? sanitize_text_field($_REQUEST['num_inference_steps']) : 50,
                                'scheduler' => isset($_REQUEST['scheduler']) && !empty($_REQUEST['scheduler']) ? sanitize_text_field($_REQUEST['scheduler']) : 'DPMSolverMultistep',
                            )
                        );

                        try {
                            $wpaicg_response = wp_remote_post('https://api.replicate.com/v1/predictions', array(
                                'headers' => $headers,
                                'body' => json_encode($body)
                            ));
                            if (is_wp_error($wpaicg_response)) {
                                $wpaicg_result['msg'] = $wpaicg_response->get_error_message();
                            } else {
                                $response_body = isset($wpaicg_response['body']) && !empty($wpaicg_response['body']) ? json_decode($wpaicg_response['body'], true) : false;
                                if (isset($response_body['detail']) && !empty($response_body['detail'])) {
                                    $wpaicg_result['msg'] = $response_body['detail'];
                                } elseif (!isset($response_body['urls']['get'])) {
                                    $wpaicg_result['msg'] = 'Empty results';
                                } else {
                                    $images = $this->wpaicg_stable_diffusion_images($response_body['urls']['get'], $headers);
                                    if (!is_array($images)) {
                                        $wpaicg_result['msg'] = $images;
                                    } else {
                                        $wpaicg_result['title'] = $prompt_title;
                                        $wpaicg_result['imgs'] = $images;
                                        $wpaicg_result['status'] = 'success';
                                        $wpaicg_result['prompt'] = $prompt;
                                    }
                                }
                            }
                        } catch (\Exception $exception) {
                            $wpaicg_result['msg'] = $exception->getMessage();
                        }
                    } else {
                        $wpaicg_result['msg'] = 'Please insert prompt';
                    }
                }
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_save_image_media()
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            if(
                isset($_POST['image_url'])
                && !empty($_POST['image_url'])
            ){
                $url = sanitize_url($_POST['image_url']);
                $image_title = isset($_POST['image_title']) && !empty($_POST['image_title']) ? sanitize_text_field($_POST['image_title']) : '';
                $image_alt = isset($_POST['image_alt']) && !empty($_POST['image_alt']) ? sanitize_text_field($_POST['image_alt']) : '';
                $image_caption = isset($_POST['image_caption']) && !empty($_POST['image_caption']) ? sanitize_text_field($_POST['image_caption']) : '';
                $image_description = isset($_POST['image_description']) && !empty($_POST['image_description']) ? sanitize_text_field($_POST['image_description']) : '';
                $wpaicg_image_attachment_id = WPAICG_Content::get_instance()->wpaicg_save_image($url, $image_title);
                if($wpaicg_image_attachment_id){
                    wp_update_post(array(
                        'ID' => $wpaicg_image_attachment_id,
                        'post_content' => $image_description,
                        'post_excerpt' => $image_caption
                    ));
                    update_post_meta($wpaicg_image_attachment_id,'_wp_attachment_image_alt', $image_alt);
                    $wpaicg_result['status'] = 'success';
                }
                else{
                    $wpaicg_result['msg'] = 'Can not save image to media library.';
                }
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_image_generator()
        {
            include WPAICG_PLUGIN_DIR . 'admin/extra/wpaicg_image_generator.php';
        }

    }
    WPAICG_Image::get_instance();
}
