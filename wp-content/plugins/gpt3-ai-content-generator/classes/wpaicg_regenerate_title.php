<?php

namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('\\WPAICG\\WPAICG_Regenerate_Title')) {
    class WPAICG_Regenerate_Title
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
            add_filter('post_row_actions',[$this,'wpaicg_regenerate_action'],10,2);
            add_filter('page_row_actions',[$this,'wpaicg_regenerate_action'],10,2);
            add_action('admin_footer',[$this,'wpaicg_regenerate_footer']);
            add_action('wp_ajax_wpaicg_regenerate_title',[$this,'wpaicg_regenerate_title']);
            add_action('wp_ajax_wpaicg_regenerate_save',[$this,'wpaicg_regenerate_save']);
        }

        public function wpaicg_regenerate_save()
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['id']) && !empty($_POST['id'])){
                $id = sanitize_text_field($_POST['id']);
                $title = sanitize_text_field($_POST['title']);
                $check = wp_update_post(array(
                    'ID' => $id,
                    'post_title' => $title
                ));
                if(is_wp_error($check)){
                    $wpaicg_result['msg'] = $check->get_error_message();
                }
                else{
                    $wpaicg_result['status'] = 'success';
                }
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_regenerate_title()
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            if(isset($_POST['title']) && !empty($_POST['title'])){
                $title = sanitize_text_field($_POST['title']);
                $open_ai = WPAICG_OpenAI::get_instance()->openai();
                if(!$open_ai){
                    $wpaicg_result['error'] = 'Missing API Setting';
                }
                else{
                    $temperature = floatval( $open_ai->temperature );
                    $max_tokens = intval( $open_ai->max_tokens );
                    $top_p = floatval( $open_ai->top_p );
                    $best_of = intval( $open_ai->best_of );
                    $frequency_penalty = floatval( $open_ai->frequency_penalty );
                    $presence_penalty = floatval( $open_ai->presence_penalty );
                    $wpai_language = sanitize_text_field( $open_ai->wpai_language );
                    if ( empty($wpai_language) ) {
                        $wpai_language = "en";
                    }
                    $wpaicg_language_file = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/languages/' . $wpai_language . '.json';
                    if ( !file_exists( $wpaicg_language_file ) ) {
                        $wpaicg_language_file = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/languages/en.json';
                    }
                    $wpaicg_language_json = file_get_contents( $wpaicg_language_file );
                    $wpaicg_languages = json_decode( $wpaicg_language_json, true );
                    $prompt = isset($wpaicg_languages['regenerate_prompt']) && !empty($wpaicg_languages['regenerate_prompt']) ? $wpaicg_languages['regenerate_prompt'] : 'Suggest me 5 different title for: %s.';
                    $prompt = sprintf($prompt, $title);
                    $wpaicg_ai_model = get_option('wpaicg_ai_model','text-davinci-003');
                    $complete = $open_ai->completion( [
                        'model'             => $wpaicg_ai_model,
                        'prompt'            => $prompt,
                        'temperature'       => $temperature,
                        'max_tokens'        => $max_tokens,
                        'frequency_penalty' => $frequency_penalty,
                        'presence_penalty'  => $presence_penalty,
                        'top_p'             => $top_p,
                        'best_of'           => $best_of,
                        'stop' => '6.'
                    ] );
                    $complete = json_decode( $complete );
                    if ( isset( $complete->error ) ) {
                        $complete = $complete->error->message;
                        $wpaicg_result['msg'] = $complete;
                    }
                    else{
                        $complete = $complete->choices[0]->text;
                        $complete = trim( $complete );
                        $complete=preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$complete)));
                        $mylist = preg_split( "/\r\n|\n|\r/", $complete );
                        $mylist = preg_replace( '/^\\d+\\.\\s/', '', $mylist );
                        $mylist = preg_replace( '/\\.$/', '', $mylist );
                        if($mylist && is_array($mylist) && count($mylist)){
                            $wpaicg_result['data'] = $mylist;
                            $wpaicg_result['status'] = 'success';
                        }
                        else{
                            $wpaicg_result['msg'] = 'No title generated';
                        }
                    }

                }
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_regenerate_action($actions, $post)
        {
            $actions['wpaicg_regenerate'] = '<a class="wpaicg_regenerate_title" data-title="'.esc_html($post->post_title).'" data-id="'.esc_attr($post->ID).'" href="javascript:void(0)">'.esc_html(__('Suggest Title','wp-ai-content-generator')).'</a>';
            return $actions;
        }

        public function wpaicg_regenerate_footer()
        {
            ?>
            <script>
                jQuery(document).ready(function ($){
                    var wpaicgRegenerateRunning = false;
                    $('.wpaicg_modal_close').click(function (){
                        $('.wpaicg_modal_content').empty();
                        $('.wpaicg_modal_close').closest('.wpaicg_modal').hide();
                        $('.wpaicg_modal_close').closest('.wpaicg_modal').removeClass('wpaicg-small-modal');
                        $('.wpaicg-overlay').hide();
                        if(wpaicgRegenerateRunning){
                            wpaicgRegenerateRunning.abort();
                        }
                    })
                    function wpaicgLoading(btn){
                        btn.attr('disabled','disabled');
                        if(!btn.find('spinner').length){
                            btn.append('<span class="spinner"></span>');
                        }
                        btn.find('.spinner').css('visibility','unset');
                    }
                    function wpaicgRmLoading(btn){
                        btn.removeAttr('disabled');
                        btn.find('.spinner').remove();
                    }
                    $(document).on('click','.wpaicg_regenerate_save', function (e){
                        var btn = $(e.currentTarget);
                        var title = btn.parent().find('input').val();
                        var id = btn.attr('data-id');
                        if(title === ''){
                            alert('Please insert title');
                        }
                        else{
                            wpaicgRegenerateRunning = $.ajax({
                                url: '<?php echo admin_url('admin-ajax.php')?>',
                                data: {action: 'wpaicg_regenerate_save',title: title, id: id},
                                dataType: 'JSON',
                                type: 'POST',
                                beforeSend: function (){
                                    $('.wpaicg_regenerate_save').attr('disabled','disabled');
                                    wpaicgLoading(btn);
                                },
                                success: function(res){
                                    if(res.status === 'success'){
                                        $('#post-'+id+' .row-title').text(title);
                                        $('.wpaicg_modal_close').click();
                                    }
                                    else{
                                        wpaicgRmLoading(btn);
                                        alert(res.msg);
                                    }
                                },
                                error: function (){
                                    wpaicgRmLoading(btn);
                                    alert('Something went wrong');
                                    $('.wpaicg_regenerate_save').removeAttr('disabled');
                                }
                            })
                        }
                    })
                    $(document).on('click','.wpaicg_regenerate_title', function (e){
                        var btn = $(e.currentTarget);
                        var id = btn.attr('data-id');
                        var title = btn.attr('data-title');
                        if(title === ''){
                            alert('Please update title first');
                        }
                        else{
                            if(wpaicgRegenerateRunning){
                                wpaicgRegenerateRunning.abort();
                            }
                            $('.wpaicg_modal_content').empty();
                            $('.wpaicg-overlay').show();
                            $('.wpaicg_modal').show();
                            $('.wpaicg_modal_title').html('GPT AI Power - Title Suggestion Tool');
                            $('.wpaicg_modal_content').html('<p style="font-style: italic;margin-top: 5px;text-align: center;">Preparing suggestions...</p>');
                            wpaicgRegenerateRunning = $.ajax({
                                url: '<?php echo admin_url('admin-ajax.php')?>',
                                data: {action: 'wpaicg_regenerate_title',title: title},
                                dataType: 'JSON',
                                type: 'POST',
                                success: function (res){
                                    if(res.status === 'success'){
                                        var html = '';
                                        if(res.data.length){
                                            $.each(res.data, function (idx, item){
                                                html += '<div class="wpaicg-regenerate-title"><input type="text" value="'+item+'"><button data-id="'+id+'" class="button button-primary wpaicg_regenerate_save">Use</button></div>';
                                            })
                                            $('.wpaicg_modal_content').html(html);
                                        }
                                        else{
                                            $('.wpaicg_modal_content').html('<p style="color: #f00;margin-top: 5px;text-align: center;">No result</p>');
                                        }
                                    }
                                    else{
                                        $('.wpaicg_modal_content').html('<p style="color: #f00;margin-top: 5px;text-align: center;">'+res.msg+'</p>');
                                    }
                                },
                                error: function (){
                                    $('.wpaicg_modal_content').html('<p style="color: #f00;margin-top: 5px;text-align: center;">Something went wrong</p>');
                                }
                            })
                        }
                    })
                })
            </script>
            <?php
        }
    }
    WPAICG_Regenerate_Title::get_instance();
}
