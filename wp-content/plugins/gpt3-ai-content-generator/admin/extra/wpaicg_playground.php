<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
?>
<style>
.wpaicg_notice_text_pg {
    padding: 10px;
    background-color: #F8DC6F;
    text-align: left;
    margin-bottom: 12px;
    color: #000;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
</style>
<p class="wpaicg_notice_text_pg">If you are happy with our plugin, please consider writing a review <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/reviews/#new-post" target="_blank">here</a>. Post your questions and suggestions on our <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/" target="_blank">support forum</a>. Thank you! ❤️ 😊</p>
<div class="wrap fs-section">
    <h2 class="nav-tab-wrapper">
        <a href="javascript:void(0)" class="nav-tab nav-tab-active">Playground</a>
    </h2>
    <div id="poststuff">
        <div id="fs_account">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">Enter your prompt</th>
                    <td>
                        <input type="text" class="regular-text wpaicg_prompt" value="Write a product description about: Training Socks.">
                        &nbsp;<button class="button wpaicg_generator_button"><span class="spinner"></span>Generate</button>
                        &nbsp;<button class="button button-primary wpaicg_generator_stop">Stop</button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Result</th>
                    <td>
                        <?php
                        wp_editor('','wpaicg_generator_result', array('media_buttons' => true, 'textarea_name' => 'wpaicg_generator_result'));
                        ?>
                        <p class="wpaicg-playground-buttons">
                            <button class="button button-primary wpaicg-playground-save">Save as Draft</button>
                            <button class="button wpaicg-playground-clear">Clear</button>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function ($){
        var wpaicg_generator_working = false;
        var eventGenerator = false;
        var wpaicg_limitLines = 10;
        function stopOpenAIGenerator(){
            $('.wpaicg-playground-buttons').show();
            $('.wpaicg_generator_stop').hide();
            wpaicg_generator_working = false;
            $('.wpaicg_generator_button .spinner').hide();
            $('.wpaicg_generator_button').removeAttr('disabled');
            eventGenerator.close();
        }
        $('.wpaicg_generator_button').click(function(){
            var btn = $(this);
            var title = $('.wpaicg_prompt').val();
            if(!wpaicg_generator_working && title !== ''){
                var count_line = 0;
                var wpaicg_generator_result = $('.wpaicg_generator_result');
                btn.attr('disabled','disabled');
                btn.find('.spinner').show();
                btn.find('.spinner').css('visibility','unset');
                wpaicg_generator_result.val('');
                wpaicg_generator_working = true;
                $('.wpaicg_generator_stop').show();
                eventGenerator = new EventSource('<?php echo esc_html(add_query_arg('wpaicg_stream','yes',site_url().'/index.php'));?>&title='+title);
                var editor = tinyMCE.get('wpaicg_generator_result');
                var basicEditor = true;
                if ( $('#wp-wpaicg_generator_result-wrap').hasClass('tmce-active') && editor ) {
                    basicEditor = false;
                }
                var currentContent = '';
                eventGenerator.onmessage = function (e) {
                    if(basicEditor){
                        currentContent = $('#wpaicg_generator_result').val();
                    }
                    else{
                        currentContent = editor.getContent();
                        currentContent = currentContent.replace(/<\/?p(>|$)/g, "");
                    }
                    if(e.data === "[DONE]"){
                        count_line += 1;
                        if(basicEditor) {
                            $('#wpaicg_generator_result').val(currentContent+'\n\n');
                        }
                        else{
                            editor.setContent(currentContent+'\n\n');
                        }
                    }
                    else{
                        var result = JSON.parse(e.data);
                        if(result.error !== undefined){
                            var content_generated = result.error.message;
                        }
                        else{
                            var content_generated = result.choices[0].text;
                        }
                        if(basicEditor){
                            $('#wpaicg_generator_result').val(currentContent+content_generated);
                        }
                        else{
                            editor.setContent(currentContent+content_generated);
                        }
                    }
                    if(count_line === wpaicg_limitLines){
                        stopOpenAIGenerator();
                    }
                };
                eventGenerator.onerror = function (e) {
                };
            }
        });
        $('.wpaicg_generator_stop').click(function (){
            stopOpenAIGenerator();
        });
        $('.wpaicg-playground-clear').click(function (){
            $('.wpaicg_prompt').val('');
            var editor = tinyMCE.get('wpaicg_generator_result');
            var basicEditor = true;
            if ( $('#wp-wpaicg_generator_result-wrap').hasClass('tmce-active') && editor ) {
                basicEditor = false;
            }
            if(basicEditor){
                $('#wpaicg_generator_result').val('');
            }
            else{
                editor.setContent('');
            }
        });
        $('.wpaicg-playground-save').click(function (){
            var wpaicg_draft_btn = $(this);
            var title = $('.wpaicg_prompt').val();
            var editor = tinyMCE.get('wpaicg_generator_result');
            var basicEditor = true;
            if ( $('#wp-wpaicg_generator_result-wrap').hasClass('tmce-active') && editor ) {
                basicEditor = false;
            }
            var content = '';
            if (basicEditor){
                content = $('#wpaicg_generator_result').val();
            }
            else{
                content = editor.getContent();
            }
            if(title === ''){
                alert('Please enter title');
            }
            else if(content === ''){
                alert('Please wait content generated');
            }
            else{
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php')?>',
                    data: {title: title, content: content, action: 'wpaicg_save_draft_post_extra'},
                    dataType: 'json',
                    type: 'POST',
                    beforeSend: function (){
                        wpaicg_draft_btn.attr('disabled','disabled');
                        wpaicg_draft_btn.append('<span class="spinner"></span>');
                        wpaicg_draft_btn.find('.spinner').css('visibility','unset');
                    },
                    success: function (res){
                        wpaicg_draft_btn.removeAttr('disabled');
                        wpaicg_draft_btn.find('.spinner').remove();
                        if(res.status === 'success'){
                            window.location.href = '<?php echo admin_url('post.php')?>?post='+res.id+'&action=edit';
                        }
                        else{
                            alert(res.msg);
                        }
                    },
                    error: function (){
                        wpaicg_draft_btn.removeAttr('disabled');
                        wpaicg_draft_btn.find('.spinner').remove();
                        alert('Something went wrong');
                    }
                });
            }
        })
    })
</script>
