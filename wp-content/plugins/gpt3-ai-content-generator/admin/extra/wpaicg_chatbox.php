<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $wp,$wpdb;
$table = $wpdb->prefix . 'wpaicg';
$existingValue = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE name = %s", 'wpaicg_settings' ), ARRAY_A );
$wpaicg_chat_shortcode_options = get_option('wpaicg_chat_shortcode_options',[]);
$default_setting = array(
    'language' => 'en',
    'tone' => 'friendly',
    'profession' => 'none',
    'model' => 'text-davinci-003',
    'temperature' => $existingValue['temperature'],
    'max_tokens' => $existingValue['max_tokens'],
    'top_p' => $existingValue['top_p'],
    'best_of' => $existingValue['best_of'],
    'frequency_penalty' => $existingValue['frequency_penalty'],
    'presence_penalty' => $existingValue['presence_penalty'],
    'ai_name' => 'AI',
    'you' => 'You',
    'ai_thinking' => 'AI Thinking',
    'placeholder' => 'Type a message',
    'welcome' => 'Hello human, I am a GPT3 powered AI chat bot. Ask me anything!',
    'remember_conversation' => 'yes',
    'conversation_cut' => 10,
    'content_aware' => 'yes',
    'embedding' =>  false,
    'embedding_type' =>  false,
    'embedding_top' =>  false,
    'no_answer' => '',
    'fontsize' => 13,
    'fontcolor' => '#fff',
    'user_bg_color' => '#444654',
    'ai_bg_color' => '#343541',
    'ai_icon_url' => '',
    'ai_icon' => 'default',
    'use_avatar' => false,
    'width' => '100%',
    'height' => '445px',
    'save_logs' => false,
    'log_notice' => false,
    'log_notice_message' => 'Please note that your conversations will be recorded.',
);
$wpaicg_settings = shortcode_atts($default_setting, $wpaicg_chat_shortcode_options);
$wpaicg_ai_thinking = $wpaicg_settings['ai_thinking'];
$wpaicg_you = $wpaicg_settings['you'];
$wpaicg_typing_placeholder = $wpaicg_settings['placeholder'];
$wpaicg_welcome_message = $wpaicg_settings['welcome'];
$wpaicg_ai_name = $wpaicg_settings['ai_name'];
$wpaicg_chat_content_aware = $wpaicg_settings['content_aware'];
$wpaicg_font_color = $wpaicg_settings['fontcolor'];
$wpaicg_font_size = $wpaicg_settings['fontsize'];
$wpaicg_user_bg_color = $wpaicg_settings['user_bg_color'];
$wpaicg_ai_bg_color = $wpaicg_settings['ai_bg_color'];
$wpaicg_save_logs = isset($wpaicg_settings['save_logs']) && !empty($wpaicg_settings['save_logs']) ? $wpaicg_settings['save_logs'] : false;
$wpaicg_log_notice = isset($wpaicg_settings['log_notice']) && !empty($wpaicg_settings['log_notice']) ? $wpaicg_settings['log_notice'] : false;
$wpaicg_log_notice_message = isset($wpaicg_settings['log_notice_message']) && !empty($wpaicg_settings['log_notice_message']) ? $wpaicg_settings['log_notice_message'] : 'Please note that your conversations will be recorded.';
?>
<style>
    .wpaicg-chat-shortcode{
        width: <?php echo esc_html($wpaicg_settings['width'])?>;
        overflow: hidden;
    }
    .wpaicg-chat-shortcode-content{
        position: relative;
    }
    .wpaicg-chat-shortcode-content ul{
        height: calc(<?php echo esc_html($wpaicg_settings['height'])?> - 45px);
        margin: 0;
        overflow-y: auto;
        background: #222;
    }
    .wpaicg-chat-shortcode-content ul li{
        display: flex;
        margin-bottom: 0;
        padding: 10px;
        color: <?php echo esc_html($wpaicg_font_color)?>;
    }
    .wpaicg-chat-shortcode-content ul li .wpaicg-chat-message{
        color: <?php echo esc_html($wpaicg_font_color)?>;
    }
    .wpaicg-chat-shortcode-content ul li strong{
        font-weight: bold;
        margin-right: 5px;
        float: left;
    }
    .wpaicg-chat-shortcode-content ul li p{
        font-size: inherit;
    }
    .wpaicg-chat-shortcode-content ul li strong img{

    }
    .wpaicg-chat-shortcode-content ul li p{
        margin: 0;
        padding: 0;
    }
    .wpaicg-chat-shortcode-content ul li p:after{
        clear: both;
        display: block;
    }
    .wpaicg-bot-thinking{
        position: absolute;
        bottom: 0;
        font-size: 11px;
        color: #90EE90;
        padding: 2px 6px;
        display: none;
    }
    .wpaicg-chat-message{
        text-align: justify;
    }
    .wpaicg-ai-message .wpaicg-chat-message,.wpaicg-user-message .wpaicg-chat-message{
        color: inherit;
    }
    .wpaicg-jumping-dots span {
        position: relative;
        bottom: 0px;
        -webkit-animation: wpaicg-jump 1500ms infinite;
        animation: wpaicg-jump 2s infinite;
    }
    .wpaicg-jumping-dots .wpaicg-dot-1{
        -webkit-animation-delay: 200ms;
        animation-delay: 200ms;
    }
    .wpaicg-jumping-dots .wpaicg-dot-2{
        -webkit-animation-delay: 400ms;
        animation-delay: 400ms;
    }
    .wpaicg-jumping-dots .wpaicg-dot-3{
        -webkit-animation-delay: 600ms;
        animation-delay: 600ms;
    }
    .wpaicg-chat-shortcode-send{
        display: flex;
        align-items: center;
        color: #fff;
        padding: 2px 3px;
        cursor: pointer;
    }
    .wpaicg-chat-shortcode-type{
        display: flex;
        align-items: center;
        padding: 5px;
        background: #141414;
        border-top: 1px solid #3e3e3e;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    input.wpaicg-chat-shortcode-typing{
        flex: 1;
        border: 1px solid #ccc;
        border-radius: 3px;
        background: #fff;
        padding: 0 8px;
        min-height: 30px;
        line-height: 2;
        box-shadow: 0 0 0 transparent;
        color: #2c3338;
        margin: 0;
    }
    .wpaicg-chat-shortcode-send svg{
        width: 30px;
        height: 30px;
        fill: currentColor;
        stroke: currentColor;
    }
    .wpaicg-chat-message-error{
        color: #f00;
    }

    @-webkit-keyframes wpaicg-jump {
        0%   {bottom: 0px;}
        20%  {bottom: 5px;}
        40%  {bottom: 0px;}
    }

    @keyframes wpaicg-jump {
        0%   {bottom: 0px;}
        20%  {bottom: 5px;}
        40%  {bottom: 0px;}
    }
    @media (max-width: 599px){
        .wpaicg_chat_widget_content .wpaicg-chat-shortcode{
            width: 100%;
        }
        .wpaicg_widget_left .wpaicg_chat_widget_content{
            left: -15px!important;
            right: auto;
        }
        .wpaicg_widget_right .wpaicg_chat_widget_content{
            right: -15px!important;
            left: auto;
        }
    }
</style>
<?php
if(isset($wpaicg_settings['use_avatar']) && $wpaicg_settings['use_avatar']) {
    $wpaicg_ai_name = isset($wpaicg_settings['ai_icon_url']) && isset($wpaicg_settings['ai_icon']) && $wpaicg_settings['ai_icon'] == 'custom' && !empty($wpaicg_settings['ai_icon_url']) ? wp_get_attachment_url(esc_html($wpaicg_settings['ai_icon_url'])) : WPAICG_PLUGIN_URL . 'admin/images/chatbot.png';
    $wpaicg_ai_name = '<img src="'.$wpaicg_ai_name.'" height="40" width="40">';
}
else{
    $wpaicg_ai_name .= ':';
}
?>
<div class="wpaicg-chat-shortcode">
    <div class="wpaicg-chat-shortcode-content">
        <ul class="wpaicg-chat-shortcode-messages">
            <?php
            if($wpaicg_save_logs && $wpaicg_log_notice && !empty($wpaicg_log_notice_message)):
                ?>
                <li style="background: rgb(0 0 0 / 32%); padding: 10px;margin-bottom: 0">
                    <p>
                    <span class="wpaicg-chat-message">
                        <?php echo esc_html($wpaicg_log_notice_message)?>
                    </span>
                    </p>
                </li>
            <?php
            endif;
            ?>
            <li class="wpaicg-ai-message" style="color: <?php echo esc_html($wpaicg_font_color)?>; font-size: <?php echo esc_html($wpaicg_font_size)?>px; background-color: <?php echo esc_html($wpaicg_ai_bg_color);?>">
                <p>
                    <strong style="float: left" class="wpaicg-chat-avatar">
                        <?php echo wp_kses_post($wpaicg_ai_name)?></strong>
                    <span class="wpaicg-chat-message">
                        <?php echo esc_html($wpaicg_welcome_message)?>
                    </span>
                </p>
            </li>
        </ul>
        <span class="wpaicg-bot-thinking"><?php echo esc_html($wpaicg_ai_thinking)?>&nbsp;<span class="wpaicg-jumping-dots"><span class="wpaicg-dot-1">.</span><span class="wpaicg-dot-2">.</span><span class="wpaicg-dot-3">.</span></span></span>
    </div>
    <div class="wpaicg-chat-shortcode-type">
        <input type="text" class="wpaicg-chat-shortcode-typing" placeholder="<?php echo esc_html($wpaicg_typing_placeholder)?>">
        <span class="wpaicg-chat-shortcode-send">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5004 11.9998H5.00043M4.91577 12.2913L2.58085 19.266C2.39742 19.8139 2.3057 20.0879 2.37152 20.2566C2.42868 20.4031 2.55144 20.5142 2.70292 20.5565C2.87736 20.6052 3.14083 20.4866 3.66776 20.2495L20.3792 12.7293C20.8936 12.4979 21.1507 12.3822 21.2302 12.2214C21.2993 12.0817 21.2993 11.9179 21.2302 11.7782C21.1507 11.6174 20.8936 11.5017 20.3792 11.2703L3.66193 3.74751C3.13659 3.51111 2.87392 3.39291 2.69966 3.4414C2.54832 3.48351 2.42556 3.59429 2.36821 3.74054C2.30216 3.90893 2.3929 4.18231 2.57437 4.72906L4.91642 11.7853C4.94759 11.8792 4.96317 11.9262 4.96933 11.9742C4.97479 12.0168 4.97473 12.0599 4.96916 12.1025C4.96289 12.1506 4.94718 12.1975 4.91577 12.2913Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
    </div>
</div>
<script>
    var wpaicg_shortcode_user_avatar = '<?php echo is_user_logged_in() ? get_avatar_url(get_current_user_id()) : get_avatar_url('')?>';
    var wpaicg_shortcode_ai_avatar = '<?php echo isset($wpaicg_settings['use_avatar']) && $wpaicg_settings['use_avatar'] && isset($wpaicg_settings['ai_icon_url']) && !empty($wpaicg_settings['ai_icon_url']) && isset($wpaicg_settings['ai_icon']) && $wpaicg_settings['ai_icon'] == 'custom' ? wp_get_attachment_url(esc_html($wpaicg_settings['ai_icon_url'])) : WPAICG_PLUGIN_URL.'admin/images/chatbot.png'?>';
    var wpaicg_shortcode_use_avatar = <?php echo isset($wpaicg_settings['use_avatar']) && $wpaicg_settings['use_avatar'] ? 'true' : 'false'?>;
    var wpaicg_shortcode_user_bg_color = '<?php echo esc_html($wpaicg_user_bg_color)?>';
    var wpaicg_shortcode_ai_bg_color = '<?php echo esc_html($wpaicg_ai_bg_color)?>';
    var wpaicg_shortcode_font_size = '<?php echo esc_html($wpaicg_font_size)?>';
    var wpaicg_shortcode_font_color = '<?php echo esc_html($wpaicg_font_color)?>';
    var wpaicg_shortcode_you = '<?php echo esc_html($wpaicg_you)?>';
    var wpaicg_shortcode_ai_name = '<?php echo esc_html($wpaicg_ai_name)?>';
    var wpaicg_shortcode_typing_message = document.getElementsByClassName('wpaicg-chat-shortcode-typing');
    var wpaicg_shortcode_chatbox_send = document.getElementsByClassName('wpaicg-chat-shortcode-send');
    var wpaicg_shortcode_nonce = '<?php echo esc_html(wp_create_nonce( 'wpaicg-chatbox' ))?>';
    function wpaicggetSetting(){
        if(document.getElementById('form-chatbox-setting') && document.getElementById('form-chatbox-setting').length){
            var formSetting = document.getElementById('form-chatbox-setting');
            var wpaicg_chat_shortcode_ai_name = formSetting.getElementsByClassName('wpaicg_chat_shortcode_ai_name')[0].value;
            var wpaicg_chat_shortcode_you = formSetting.getElementsByClassName('wpaicg_chat_shortcode_you')[0].value;
            var wpaicg_chat_shortcode_font_size = formSetting.getElementsByClassName('wpaicg_chat_shortcode_font_size')[0].value;
            var wpaicg_font_color_setting = formSetting.getElementsByClassName('wpaicg_font_color')[0].value;
            var wpaicg_user_bg_color_setting = formSetting.getElementsByClassName('wpaicg_user_bg_color')[0].value;
            var wpaicg_ai_bg_color_setting = formSetting.getElementsByClassName('wpaicg_ai_bg_color')[0].value;
            wpaicg_shortcode_ai_name = wpaicg_chat_shortcode_ai_name === '' ? wpaicg_shortcode_ai_name : wpaicg_chat_shortcode_ai_name;
            wpaicg_shortcode_you = wpaicg_chat_shortcode_you === '' ? wpaicg_shortcode_you : wpaicg_chat_shortcode_you;
            wpaicg_shortcode_font_size = wpaicg_chat_shortcode_font_size === '' ? wpaicg_shortcode_font_size : wpaicg_chat_shortcode_font_size;
            wpaicg_shortcode_font_color = wpaicg_font_color_setting === '' ? wpaicg_shortcode_font_color : wpaicg_font_color_setting;
            wpaicg_shortcode_user_bg_color = wpaicg_user_bg_color_setting === '' ? wpaicg_shortcode_user_bg_color : wpaicg_user_bg_color_setting;
            wpaicg_shortcode_ai_bg_color = wpaicg_ai_bg_color_setting === '' ? wpaicg_shortcode_ai_bg_color : wpaicg_ai_bg_color_setting;
            wpaicg_shortcode_use_avatar = formSetting.querySelector('.wpaicg_chat_shortcode_use_avatar:checked') ? true : false;
            if(wpaicg_shortcode_use_avatar){
                wpaicg_shortcode_ai_avatar = '<?php echo WPAICG_PLUGIN_URL.'admin/images/chatbot.png';?>';
                var wpaicg_ai_avatar_img = formSetting.querySelector('.wpaicg_chatbox_icon img');
                if(
                    formSetting.querySelector('.wpaicg_chatbox_icon_custom:checked')
                    && wpaicg_ai_avatar_img
                ){
                    wpaicg_shortcode_ai_avatar = wpaicg_ai_avatar_img.src;
                }
            }
        }
    }
    for(var i=0;i<wpaicg_shortcode_typing_message.length;i++){
        wpaicg_shortcode_typing_message[i].addEventListener('keyup', function(event){
            if (event.which === 13 || event.keyCode === 13) {
                wpaicgShortcodeSendingMessage(event.target.closest('.wpaicg-chat-shortcode'));
            }
        })
    }

    for(var i=0;i<wpaicg_shortcode_chatbox_send.length;i++){
        wpaicg_shortcode_chatbox_send[i].addEventListener('click', function(event){
            wpaicgShortcodeSendingMessage(event.target.closest('.wpaicg-chat-shortcode'));
        })
    }

    function wpaicgescapeHtml(unsafe)
    {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    function wpaicgShortcodeSendingMessage(element){
        var wpaicg_box_typing = element.getElementsByClassName('wpaicg-chat-shortcode-typing')[0];
        var wpaicg_ai_thinking = element.getElementsByClassName('wpaicg-bot-thinking')[0];
        var wpaicg_messages_box = element.getElementsByClassName('wpaicg-chat-shortcode-messages')[0];
        var wpaicg_question = wpaicgescapeHtml(wpaicg_box_typing.value);
        if(wpaicg_question !== ''){
            wpaicg_box_typing.value = '';
            wpaicg_ai_thinking.style.display = 'block';
            var wpaicg_ai_name_message = wpaicg_shortcode_ai_name+':';
            var wpaicg_you_message = wpaicg_shortcode_you+':';
            wpaicggetSetting();
            wpaicg_ai_name_message = wpaicg_shortcode_ai_name+':';
            wpaicg_you_message = wpaicg_shortcode_you+':';
            if(wpaicg_shortcode_use_avatar){
                wpaicg_ai_name_message = '<img src="'+wpaicg_shortcode_ai_avatar+'" height="40" width="40">';
                wpaicg_you_message = wpaicg_shortcode_user_avatar !== '' ? '<img src="'+wpaicg_shortcode_user_avatar+'" height="40" width="40">' : wpaicg_you_message;
            }
            var wpaicg_message = '<li class="wpaicg-user-message" style="background-color:'+wpaicg_shortcode_user_bg_color+';font-size: '+wpaicg_shortcode_font_size+'px;color: '+wpaicg_shortcode_font_color+'"><strong class="wpaicg-chat-avatar">'+wpaicg_you_message+'</strong><div class="wpaicg-chat-message">'+wpaicg_question+'</div></li>';
            wpaicg_messages_box.innerHTML += wpaicg_message;
            wpaicg_messages_box.scrollTop = wpaicg_messages_box.scrollHeight;
            const xhttp = new XMLHttpRequest();
            xhttp.open('POST', '<?php echo admin_url('admin-ajax.php')?>');
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var wpaicg_data = 'action=wpaicg_chat_shortcode_message&_wpnonce='+wpaicg_shortcode_nonce+'&message='+encodeURIComponent(wpaicg_question)+'&post_id=<?php echo get_the_ID()?>&url=<?php echo home_url( $wp->request )?>';
            if(document.getElementById('form-chatbox-setting') && document.getElementById('form-chatbox-setting').length) {
                wpaicg_data += '&'+(new URLSearchParams(new FormData(document.getElementById('form-chatbox-setting'))).toString());
            }
            xhttp.send(wpaicg_data);
            xhttp.onreadystatechange = function(oEvent) {
                if (xhttp.readyState === 4) {
                    var wpaicg_message = '';
                    var wpaicg_response_text = '';
                    var wpaicg_randomnum = Math.floor((Math.random() * 100000) + 1);
                    if (xhttp.status === 200) {
                        var wpaicg_response = this.responseText;
                        if (wpaicg_response !== '') {
                            wpaicg_response = JSON.parse(wpaicg_response);
                            wpaicg_ai_thinking.style.display = 'none'
                            if (wpaicg_response.status === 'success') {
                                wpaicg_response_text = wpaicg_response.data;
                                wpaicg_message = '<li class="wpaicg-ai-message" style="background-color:'+wpaicg_shortcode_ai_bg_color+';font-size: '+wpaicg_shortcode_font_size+'px;color: '+wpaicg_shortcode_font_color+'"><p><strong class="wpaicg-chat-avatar">'+wpaicg_ai_name_message+'</strong><span class="wpaicg-chat-message" id="wpaicg-chat-message-'+wpaicg_randomnum+'"></span></p></li>';
                            } else {
                                wpaicg_response_text = wpaicg_response.msg;
                                wpaicg_message = '<li class="wpaicg-ai-message" style="background-color:'+wpaicg_shortcode_ai_bg_color+';font-size: '+wpaicg_shortcode_font_size+'px;color: '+wpaicg_shortcode_font_color+'"><p><strong class="wpaicg-chat-avatar">'+wpaicg_ai_name_message+'</strong><span class="wpaicg-chat-message wpaicg-chat-message-error" id="wpaicg-chat-message-'+wpaicg_randomnum+'"></span></p></li>';
                            }
                        }
                    }
                    else{
                        wpaicg_message = '<li class="wpaicg-ai-message" style="background-color:'+wpaicg_shortcode_ai_bg_color+';font-size: '+wpaicg_shortcode_font_size+'px;color: '+wpaicg_shortcode_font_color+'"><p><strong class="wpaicg-chat-avatar">'+wpaicg_ai_name_message+'</strong><span class="wpaicg-chat-message wpaicg-chat-message-error" id="wpaicg-chat-message-'+wpaicg_randomnum+'"></span></p></li>';
                        wpaicg_response_text = 'Something went wrong';
                    }
                    if(wpaicg_response_text === 'null' || wpaicg_response_text === null){
                        wpaicg_response_text = 'OpenAI returned empty response for this request. Please try again.';
                    }
                    if(wpaicg_response_text !== '' && wpaicg_message !== ''){
                        wpaicg_messages_box.innerHTML += wpaicg_message;
                        var wpaicg_current_message = document.getElementById('wpaicg-chat-message-'+wpaicg_randomnum);
                        var i = 0;
                        var wpaicg_speed = 20;
                        function wpaicg_typeWriter() {
                            if (i < wpaicg_response_text.length) {
                                wpaicg_current_message.innerHTML += wpaicg_response_text.charAt(i);
                                i++;
                                setTimeout(wpaicg_typeWriter, wpaicg_speed);
                                wpaicg_messages_box.scrollTop = wpaicg_messages_box.scrollHeight;
                            }
                        }
                        wpaicg_typeWriter();
                    }
                }
            }
        }
    }

</script>
