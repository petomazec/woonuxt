<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
$wpaicgChatLogTable = $wpdb->prefix . 'wpaicg_chatlogs';
if($wpdb->get_var("SHOW TABLES LIKE '$wpaicgChatLogTable'") != $wpaicgChatLogTable) {
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE ".$wpaicgChatLogTable." (
    `id` mediumint(11) NOT NULL AUTO_INCREMENT,
    `log_session` VARCHAR(255) NOT NULL,
    `data` LONGTEXT NOT NULL,
    `page_title` TEXT DEFAULT NULL,
    `source` VARCHAR(255) DEFAULT NULL,
    `created_at` VARCHAR(255) NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $wpdb->query( $sql );
}
?>
<style>
    .asdisabled{
        background: #ebebeb!important;
    }
    .wpaicg_chatbox_avatar{
        cursor: pointer;
    }
</style>
<div id="tabs-4">
<p>Learn how you can teach your content to the chat bot: <u><b><a href="https://youtu.be/NPMLGwFQYrY" target="_blank">https://youtu.be/NPMLGwFQYrY</a></u></b></p>
    <?php
    $wpaicg_chat_model = get_option('wpaicg_chat_model','');
    $wpaicg_chat_language = get_option('wpaicg_chat_language','');
    ?>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Language:</label>
        <select class="regular-text" id="label_wpai_language"  name="wpaicg_chat_language" >
            <option value="en" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'en' ? 'selected' : '' ) ;
            ?>>English</option>
            <option value="ar" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'ar' ? 'selected' : '' ) ;
            ?>>Arabic</option>
            <option value="bg" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'bg' ? 'selected' : '' ) ;
            ?>>Bulgarian</option>
            <option value="zh" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'zh' ? 'selected' : '' ) ;
            ?>>Chinese</option>
            <option value="hr" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'hr' ? 'selected' : '' ) ;
            ?>>Croatian</option>
            <option value="cs" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'cs' ? 'selected' : '' ) ;
            ?>>Czech</option>
            <option value="da" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'da' ? 'selected' : '' ) ;
            ?>>Danish</option>
            <option value="nl" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'nl' ? 'selected' : '' ) ;
            ?>>Dutch</option>
            <option value="et" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'et' ? 'selected' : '' ) ;
            ?>>Estonian</option>
            <option value="fil" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'fil' ? 'selected' : '' ) ;
            ?>>Filipino</option>
            <option value="fi" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'fi' ? 'selected' : '' ) ;
            ?>>Finnish</option>
            <option value="fr" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'fr' ? 'selected' : '' ) ;
            ?>>French</option>
            <option value="de" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'de' ? 'selected' : '' ) ;
            ?>>German</option>
            <option value="el" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'el' ? 'selected' : '' ) ;
            ?>>Greek</option>
            <option value="he" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'he' ? 'selected' : '' ) ;
            ?>>Hebrew</option>
            <option value="hi" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'hi' ? 'selected' : '' ) ;
            ?>>Hindi</option>
            <option value="hu" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'hu' ? 'selected' : '' ) ;
            ?>>Hungarian</option>
            <option value="id" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'id' ? 'selected' : '' ) ;
            ?>>Indonesian</option>
            <option value="it" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'it' ? 'selected' : '' ) ;
            ?>>Italian</option>
            <option value="ja" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'ja' ? 'selected' : '' ) ;
            ?>>Japanese</option>
            <option value="ko" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'ko' ? 'selected' : '' ) ;
            ?>>Korean</option>
            <option value="lv" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'lv' ? 'selected' : '' ) ;
            ?>>Latvian</option>
            <option value="lt" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'lt' ? 'selected' : '' ) ;
            ?>>Lithuanian</option>
            <option value="ms" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'ms' ? 'selected' : '' ) ;
            ?>>Malay</option>
            <option value="no" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'no' ? 'selected' : '' ) ;
            ?>>Norwegian</option>
            <option value="pl" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'pl' ? 'selected' : '' ) ;
            ?>>Polish</option>
            <option value="pt" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'pt' ? 'selected' : '' ) ;
            ?>>Portuguese</option>
            <option value="ro" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'ro' ? 'selected' : '' ) ;
            ?>>Romanian</option>
            <option value="ru" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'ru' ? 'selected' : '' ) ;
            ?>>Russian</option>
            <option value="sr" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'sr' ? 'selected' : '' ) ;
            ?>>Serbian</option>
            <option value="sk" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'sk' ? 'selected' : '' ) ;
            ?>>Slovak</option>
            <option value="sl" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'sl' ? 'selected' : '' ) ;
            ?>>Slovenian</option>
            <option value="sv" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'sv' ? 'selected' : '' ) ;
            ?>>Swedish</option>
            <option value="es" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'es' ? 'selected' : '' ) ;
            ?>>Spanish</option>
            <option value="th" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'th' ? 'selected' : '' ) ;
            ?>>Thai</option>
            <option value="tr" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'tr' ? 'selected' : '' ) ;
            ?>>Turkish</option>
            <option value="uk" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'uk' ? 'selected' : '' ) ;
            ?>>Ukrainian</option>
            <option value="vi" <?php
            echo  ( esc_html( $wpaicg_chat_language ) == 'vi' ? 'selected' : '' ) ;
            ?>>Vietnamese</option>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/supported-languages/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label" for="wpaicg_chat_model">Model:</label>
        <select class="regular-text" id="wpaicg_chat_model"  name="wpaicg_chat_model" >
            <?php
            foreach($wpaicg_custom_models as $wpaicg_custom_model){
                echo '<option'.($wpaicg_chat_model == $wpaicg_custom_model ? ' selected':'').' value="'.esc_html($wpaicg_custom_model).'">'.esc_html($wpaicg_custom_model).'</option>';
            }
            ?>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/gpt-3-model-settings/" target="_blank">?</a>
        <a class="wpaicg_sync_finetune" href="javascript:void(0)">Sync</a>
    </div>
    <!--More fields-->
    <?php
    $wpaicg_chat_temperature = get_option('wpaicg_chat_temperature',$existingValue['temperature']);
    $wpaicg_chat_max_tokens = get_option('wpaicg_chat_max_tokens',$existingValue['max_tokens']);
    $wpaicg_chat_top_p = get_option('wpaicg_chat_top_p',$existingValue['top_p']);
    $wpaicg_chat_best_of = get_option('wpaicg_chat_best_of',$existingValue['best_of']);
    $wpaicg_chat_frequency_penalty = get_option('wpaicg_chat_frequency_penalty',$existingValue['frequency_penalty']);
    $wpaicg_chat_presence_penalty = get_option('wpaicg_chat_presence_penalty',$existingValue['presence_penalty']);
    ?>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Temperature:</label>
        <input type="text" class="regular-text" id="label_temperature" name="wpaicg_chat_temperature" value="<?php
        echo  esc_html( $wpaicg_chat_temperature ) ;
        ?>">
        <a class="wpcgai_help_link" href="https://gptaipower.com/gpt-3-temperature-settings/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Max Tokens:</label>
        <input type="text" class="regular-text" id="label_max_tokens" name="wpaicg_chat_max_tokens" value="<?php
        echo  esc_html( $wpaicg_chat_max_tokens ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/max-tokens/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Top P:</label>
        <input type="text" class="regular-text" id="label_top_p" name="wpaicg_chat_top_p" value="<?php
        echo  esc_html( $wpaicg_chat_top_p ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/top_p/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Best Of:</label>
        <input type="text" class="regular-text" id="label_best_of" name="wpaicg_chat_best_of" value="<?php
        echo  esc_html( $wpaicg_chat_best_of ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/best-of/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Frequency Penalty:</label>
        <input type="text" class="regular-text" id="label_frequency_penalty" name="wpaicg_chat_frequency_penalty" value="<?php
        echo  esc_html( $wpaicg_chat_frequency_penalty ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/frequency-penalty/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Presence Penalty:</label>
        <input type="text" class="regular-text" id="label_presence_penalty" name="wpaicg_chat_presence_penalty" value="<?php
        echo  esc_html( $wpaicg_chat_presence_penalty ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/presence-penalty/" target="_blank">?</a>
    </div>
    <p>You can customize your chat box below.</p>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">AI Name:</label>
        <input type="text" class="regular-text" name="_wpaicg_chatbox_ai_name" value="<?php
        echo  esc_html( get_option( '_wpaicg_chatbox_ai_name', 'AI' ) ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">You:</label>
        <input type="text" class="regular-text" name="_wpaicg_chatbox_you" value="<?php
        echo  esc_html( get_option( '_wpaicg_chatbox_you', 'You' ) ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">AI Thinking:</label>
        <input type="text" class="regular-text" name="_wpaicg_ai_thinking" value="<?php
        echo  esc_html( get_option( '_wpaicg_ai_thinking', 'AI thinking' ) ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Placeholder:</label>
        <input type="text" class="regular-text" name="_wpaicg_typing_placeholder" value="<?php
        echo  esc_html( get_option( '_wpaicg_typing_placeholder', 'Type a message' ) ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Welcome Message:</label>
        <input type="text" class="regular-text" name="_wpaicg_chatbox_welcome_message" value="<?php
        echo  esc_html( get_option( '_wpaicg_chatbox_welcome_message', 'Hello human, I am a GPT3 powered AI chat bot. Ask me anything!' ) ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <h3>Context</h3>
    <?php
    $wpaicg_chat_widget = get_option('wpaicg_chat_widget',[]);
    $wpaicg_chat_icon = isset($wpaicg_chat_widget['icon']) && !empty($wpaicg_chat_widget['icon']) ? $wpaicg_chat_widget['icon'] : 'default';
    $wpaicg_chat_icon_url = isset($wpaicg_chat_widget['icon_url']) && !empty($wpaicg_chat_widget['icon_url']) ? $wpaicg_chat_widget['icon_url'] : '';
    $wpaicg_chat_status = isset($wpaicg_chat_widget['status']) && !empty($wpaicg_chat_widget['status']) ? $wpaicg_chat_widget['status'] : '';
    $wpaicg_chat_fontsize = isset($wpaicg_chat_widget['fontsize']) && !empty($wpaicg_chat_widget['fontsize']) ? $wpaicg_chat_widget['fontsize'] : '13';
    $wpaicg_chat_fontcolor = isset($wpaicg_chat_widget['fontcolor']) && !empty($wpaicg_chat_widget['fontcolor']) ? $wpaicg_chat_widget['fontcolor'] : '#fff';
    $wpaicg_chat_bgcolor = isset($wpaicg_chat_widget['bgcolor']) && !empty($wpaicg_chat_widget['bgcolor']) ? $wpaicg_chat_widget['bgcolor'] : '#222222';
    $wpaicg_user_bg_color = isset($wpaicg_chat_widget['user_bg_color']) && !empty($wpaicg_chat_widget['user_bg_color']) ? $wpaicg_chat_widget['user_bg_color'] : '#444654';
    $wpaicg_ai_bg_color = isset($wpaicg_chat_widget['ai_bg_color']) && !empty($wpaicg_chat_widget['ai_bg_color']) ? $wpaicg_chat_widget['ai_bg_color'] : '#343541';
    $wpaicg_use_avatar = isset($wpaicg_chat_widget['use_avatar']) && !empty($wpaicg_chat_widget['use_avatar']) ? $wpaicg_chat_widget['use_avatar'] : false;
    $wpaicg_ai_avatar = isset($wpaicg_chat_widget['ai_avatar']) && !empty($wpaicg_chat_widget['ai_avatar']) ? $wpaicg_chat_widget['ai_avatar'] : 'default';
    $wpaicg_ai_avatar_id = isset($wpaicg_chat_widget['ai_avatar_id']) && !empty($wpaicg_chat_widget['ai_avatar_id']) ? $wpaicg_chat_widget['ai_avatar_id'] : '';
    $wpaicg_chat_width = isset($wpaicg_chat_widget['width']) && !empty($wpaicg_chat_widget['width']) ? $wpaicg_chat_widget['width'] : '350';
    $wpaicg_chat_height = isset($wpaicg_chat_widget['height']) && !empty($wpaicg_chat_widget['height']) ? $wpaicg_chat_widget['height'] : '400';
    $wpaicg_chat_position = isset($wpaicg_chat_widget['position']) && !empty($wpaicg_chat_widget['position']) ? $wpaicg_chat_widget['position'] : 'left';
    $wpaicg_chat_tone = isset($wpaicg_chat_widget['tone']) && !empty($wpaicg_chat_widget['tone']) ? $wpaicg_chat_widget['tone'] : 'friendly';
    $wpaicg_chat_proffesion = isset($wpaicg_chat_widget['proffesion']) && !empty($wpaicg_chat_widget['proffesion']) ? $wpaicg_chat_widget['proffesion'] : 'none';
    $wpaicg_chat_remember_conversation = isset($wpaicg_chat_widget['remember_conversation']) && !empty($wpaicg_chat_widget['remember_conversation']) ? $wpaicg_chat_widget['remember_conversation'] : 'yes';
    $wpaicg_chat_content_aware = isset($wpaicg_chat_widget['content_aware']) && !empty($wpaicg_chat_widget['content_aware']) ? $wpaicg_chat_widget['content_aware'] : 'yes';
    $wpaicg_pinecone_api = get_option('wpaicg_pinecone_api','');
    $wpaicg_pinecone_environment = get_option('wpaicg_pinecone_environment','');
    $wpaicg_save_logs = isset($wpaicg_chat_widget['save_logs']) && !empty($wpaicg_chat_widget['save_logs']) ? $wpaicg_chat_widget['save_logs'] : false;
    $wpaicg_log_notice = isset($wpaicg_chat_widget['log_notice']) && !empty($wpaicg_chat_widget['log_notice']) ? $wpaicg_chat_widget['log_notice'] : false;
    $wpaicg_log_notice_message = isset($wpaicg_chat_widget['log_notice_message']) && !empty($wpaicg_chat_widget['log_notice_message']) ? $wpaicg_chat_widget['log_notice_message'] : 'Please note that your conversations will be recorded.';
    ?>
    <input value="<?php echo esc_html($wpaicg_chat_icon_url)?>" type="hidden" name="wpaicg_chat_widget[icon_url]" class="wpaicg_chat_icon_url">
    <input value="<?php echo esc_html($wpaicg_ai_avatar_id)?>" type="hidden" name="wpaicg_chat_widget[ai_avatar_id]" class="wpaicg_ai_avatar_id">
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Show Widget:</label>
        <select name="wpaicg_chat_widget[status]">
            <option value="">Disable</option>
            <option<?php echo $wpaicg_chat_status == 'active' ? ' selected': ''?> value="active">Active</option>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <!-- wpaicg_chat_remember_conversation -->
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Remember Conversation?:</label>
        <select name="wpaicg_chat_widget[remember_conversation]">
            <option<?php echo $wpaicg_chat_remember_conversation == 'yes' ? ' selected': ''?> value="yes">Yes</option>
            <option<?php echo $wpaicg_chat_remember_conversation == 'no' ? ' selected': ''?> value="no">No</option>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <?php
    $wpaicg_conversation_cut = get_option('wpaicg_conversation_cut',10);
    ?>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Remember Conversation Up To:</label>
        <select name="wpaicg_conversation_cut">
            <?php
            for($i=3;$i<=20;$i++){
                echo '<option'.($wpaicg_conversation_cut == $i ? ' selected':'').' value="'.esc_html($i).'">'.esc_html($i).'</option>';
            }
            ?>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <!-- $wpaicg_chat_content_aware -->
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Content Aware?:</label>
        <select name="wpaicg_chat_widget[content_aware]" id="wpaicg_chat_content_aware">
            <option<?php echo $wpaicg_chat_content_aware == 'yes' ? ' selected': ''?> value="yes">Yes</option>
            <option<?php echo $wpaicg_chat_content_aware == 'no' ? ' selected': ''?> value="no">No</option>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <?php
    $wpaicg_embedding_field_disabled = empty($wpaicg_pinecone_api) || empty($wpaicg_pinecone_environment) ? true : false;
    $wpaicg_chat_embedding = get_option('wpaicg_chat_embedding',false);
    $wpaicg_chat_embedding_type = get_option('wpaicg_chat_embedding_type',false);
    $wpaicg_chat_embedding_top = get_option('wpaicg_chat_embedding_top',false);
    ?>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Use Excerpt:</label>
        <input<?php echo !$wpaicg_chat_embedding && $wpaicg_chat_content_aware == 'yes' ? ' checked': ''?><?php echo $wpaicg_chat_content_aware == 'no' ? ' disabled':''?> type="checkbox" id="wpaicg_chat_excerpt" class="<?php echo $wpaicg_chat_embedding && $wpaicg_chat_content_aware == 'yes' ? 'asdisabled' : ''?>">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Use Embeddings:</label>
        <input<?php echo $wpaicg_chat_embedding && $wpaicg_chat_content_aware == 'yes' ? ' checked': ''?><?php echo $wpaicg_embedding_field_disabled || $wpaicg_chat_content_aware == 'no' ? ' disabled':''?> type="checkbox" value="1" name="wpaicg_chat_embedding" id="wpaicg_chat_embedding" class="<?php echo !$wpaicg_chat_embedding && $wpaicg_chat_content_aware == 'yes' ? 'asdisabled' : ''?>">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Method:</label>
        <select<?php echo $wpaicg_embedding_field_disabled || empty($wpaicg_chat_embedding) || $wpaicg_chat_content_aware == 'no' ? ' disabled':''?> name="wpaicg_chat_embedding_type" id="wpaicg_chat_embedding_type" class="<?php echo !$wpaicg_chat_embedding && $wpaicg_chat_content_aware == 'yes' ? 'asdisabled' : ''?>">
            <option<?php echo $wpaicg_chat_embedding_type ? ' selected':'';?> value="openai">Embeddings + Completion</option>
            <option<?php echo empty($wpaicg_chat_embedding_type) ? ' selected':''?> value="">Embeddings only</option>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Nearest Answers up to:</label>
        <select<?php echo $wpaicg_embedding_field_disabled || empty($wpaicg_chat_embedding) || $wpaicg_chat_content_aware == 'no' ? ' disabled':''?> name="wpaicg_chat_embedding_top" id="wpaicg_chat_embedding_top" class="<?php echo !$wpaicg_chat_embedding && $wpaicg_chat_content_aware == 'yes' ? 'asdisabled' : ''?>">
            <?php
            for($i = 1; $i <=5;$i++){
                echo '<option'.($wpaicg_chat_embedding_top == $i ? ' selected':'').' value="'.esc_html($i).'">'.esc_html($i).'</option>';
            }
            ?>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <?php $wpaicg_chat_no_answer = get_option('wpaicg_chat_no_answer','')?>
        <label class="wpcgai_label">No Answer Message:</label>
        <input class="regular-text" type="text" value="<?php echo esc_html($wpaicg_chat_no_answer)?>" name="wpaicg_chat_no_answer">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Tone:</label>
        <select name="wpaicg_chat_widget[tone]">
            <option<?php echo $wpaicg_chat_tone == 'friendly' ? ' selected': ''?> value="friendly">Friendly</option>
            <option<?php echo $wpaicg_chat_tone == 'professional' ? ' selected': ''?> value="professional">Professional</option>
            <option<?php echo $wpaicg_chat_tone == 'sarcastic' ? ' selected': ''?> value="sarcastic">Sarcastic</option>
            <option<?php echo $wpaicg_chat_tone == 'humorous' ? ' selected': ''?> value="humorous">Humorous</option>
            <option<?php echo $wpaicg_chat_tone == 'cheerful' ? ' selected': ''?> value="cheerful">Cheerful</option>
            <option<?php echo $wpaicg_chat_tone == 'anecdotal' ? ' selected': ''?> value="anecdotal">Anecdotal</option>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Act As:</label>
        <select name="wpaicg_chat_widget[proffesion]">
            <option<?php echo $wpaicg_chat_proffesion == 'none' ? ' selected': ''?> value="none">None</option>
            <option<?php echo $wpaicg_chat_proffesion == 'accountant' ? ' selected': ''?> value="accountant">Accountant</option>
            <option<?php echo $wpaicg_chat_proffesion == 'advertisingspecialist' ? ' selected': ''?> value="advertisingspecialist">Advertising Specialist</option>
            <option<?php echo $wpaicg_chat_proffesion == 'architect' ? ' selected': ''?> value="architect">Architect</option>
            <option<?php echo $wpaicg_chat_proffesion == 'artist' ? ' selected': ''?> value="artist">Artist</option>
            <option<?php echo $wpaicg_chat_proffesion == 'blogger' ? ' selected': ''?> value="blogger">Blogger</option>
            <option<?php echo $wpaicg_chat_proffesion == 'businessanalyst' ? ' selected': ''?> value="businessanalyst">Business Analyst</option>
            <option<?php echo $wpaicg_chat_proffesion == 'businessowner' ? ' selected': ''?> value="businessowner">Business Owner</option>
            <option<?php echo $wpaicg_chat_proffesion == 'carexpert' ? ' selected': ''?> value="carexpert">Car Expert</option>
            <option<?php echo $wpaicg_chat_proffesion == 'consultant' ? ' selected': ''?> value="consultant">Consultant</option>
            <option<?php echo $wpaicg_chat_proffesion == 'counselor' ? ' selected': ''?> value="counselor">Counselor</option>
            <option<?php echo $wpaicg_chat_proffesion == 'cryptocurrencytrader' ? ' selected': ''?> value="cryptocurrencytrader">Cryptocurrency Trader</option>
            <option<?php echo $wpaicg_chat_proffesion == 'cryptocurrencyexpert' ? ' selected': ''?> value="cryptocurrencyexpert">Cryptocurrency Expert</option>
            <option<?php echo $wpaicg_chat_proffesion == 'customersupport' ? ' selected': ''?> value="customersupport">Customer Support</option>
            <option<?php echo $wpaicg_chat_proffesion == 'designer' ? ' selected': ''?> value="designer">Designer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'digitalmarketinagency' ? ' selected': ''?> value="digitalmarketinagency">Digital Marketing Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'editor' ? ' selected': ''?> value="editor">Editor</option>
            <option<?php echo $wpaicg_chat_proffesion == 'engineer' ? ' selected': ''?> value="engineer">Engineer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'eventplanner' ? ' selected': ''?> value="eventplanner">Event Planner</option>
            <option<?php echo $wpaicg_chat_proffesion == 'freelancer' ? ' selected': ''?> value="freelancer">Freelancer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'insuranceagent' ? ' selected': ''?> value="insuranceagent">Insurance Agent</option>
            <option<?php echo $wpaicg_chat_proffesion == 'insurancebroker' ? ' selected': ''?> value="insurancebroker">Insurance Broker</option>
            <option<?php echo $wpaicg_chat_proffesion == 'interiordesigner' ? ' selected': ''?> value="interiordesigner">Interior Designer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'journalist' ? ' selected': ''?> value="journalist">Journalist</option>
            <option<?php echo $wpaicg_chat_proffesion == 'marketingagency' ? ' selected': ''?> value="marketingagency">Marketing Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'marketingexpert' ? ' selected': ''?> value="marketingexpert">Marketing Expert</option>
            <option<?php echo $wpaicg_chat_proffesion == 'marketingspecialist' ? ' selected': ''?> value="marketingspecialist">Marketing Specialist</option>
            <option<?php echo $wpaicg_chat_proffesion == 'photographer' ? ' selected': ''?> value="photographer">Photographer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'programmer' ? ' selected': ''?> value="programmer">Programmer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'publicrelationsagency' ? ' selected': ''?> value="publicrelationsagency">Public Relations Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'publisher' ? ' selected': ''?> value="publisher">Publisher</option>
            <option<?php echo $wpaicg_chat_proffesion == 'realestateagent' ? ' selected': ''?> value="realestateagent">Real Estate Agent</option>
            <option<?php echo $wpaicg_chat_proffesion == 'recruiter' ? ' selected': ''?> value="recruiter">Recruiter</option>
            <option<?php echo $wpaicg_chat_proffesion == 'reporter' ? ' selected': ''?> value="reporter">Reporter</option>
            <option<?php echo $wpaicg_chat_proffesion == 'salesperson' ? ' selected': ''?> value="salesperson">Sales Person</option>
            <option<?php echo $wpaicg_chat_proffesion == 'salerep' ? ' selected': ''?> value="salerep">Sales Representative</option>
            <option<?php echo $wpaicg_chat_proffesion == 'seoagency' ? ' selected': ''?> value="seoagency">SEO Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'seoexpert' ? ' selected': ''?> value="seoexpert">SEO Expert</option>
            <option<?php echo $wpaicg_chat_proffesion == 'socialmediaagency' ? ' selected': ''?> value="socialmediaagency">Social Media Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'student' ? ' selected': ''?> value="student">Student</option>
            <option<?php echo $wpaicg_chat_proffesion == 'teacher' ? ' selected': ''?> value="teacher">Teacher</option>
            <option<?php echo $wpaicg_chat_proffesion == 'technicalsupport' ? ' selected': ''?> value="technicalsupport">Technical Support</option>
            <option<?php echo $wpaicg_chat_proffesion == 'trainer' ? ' selected': ''?> value="trainer">Trainer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'travelagency' ? ' selected': ''?> value="travelagency">Travel Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'videographer' ? ' selected': ''?> value="videographer">Videographer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'webdesignagency' ? ' selected': ''?> value="webdesignagency">Web Design Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'webdesignexpert' ? ' selected': ''?> value="webdesignexpert">Web Design Expert</option>
            <option<?php echo $wpaicg_chat_proffesion == 'webdevelopmentagency' ? ' selected': ''?> value="webdevelopmentagency">Web Development Agency</option>
            <option<?php echo $wpaicg_chat_proffesion == 'webdevelopmentexpert' ? ' selected': ''?> value="webdevelopmentexpert">Web Development Expert</option>
            <option<?php echo $wpaicg_chat_proffesion == 'webdesigner' ? ' selected': ''?> value="webdesigner">Web Designer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'webdeveloper' ? ' selected': ''?> value="webdeveloper">Web Developer</option>
            <option<?php echo $wpaicg_chat_proffesion == 'writer' ? ' selected': ''?> value="writer">Writer</option>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/customizing-chatgpt/" target="_blank">?</a>
    </div>
    <h3>Logs</h3>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Save Chat Logs:</label>
        <input<?php echo $wpaicg_save_logs ? ' checked':''?> value="1" type="checkbox" name="wpaicg_chat_widget[save_logs]">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Display Notice:</label>
        <input<?php echo $wpaicg_log_notice ? ' checked':''?> value="1" type="checkbox" name="wpaicg_chat_widget[log_notice]">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Notice Text:</label>
        <input class="regular-text" value="<?php echo esc_html($wpaicg_log_notice_message)?>" type="text" name="wpaicg_chat_widget[log_notice_message]">
    </div>
    <h3>Widget Style</h3>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Icon (75x75):</label>
        <div style="display: inline-flex; align-items: center">
            <input<?php echo $wpaicg_chat_icon == 'default' ? ' checked': ''?> class="wpaicg_chatbox_icon_default" type="radio" value="default" name="wpaicg_chat_widget[icon]">
            <div style="text-align: center">
                <img style="display: block" src="<?php echo WPAICG_PLUGIN_URL.'admin/images/chatbot.png'?>"<br>
                <strong>Default</strong>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input<?php echo $wpaicg_chat_icon == 'custom' ? ' checked': ''?> type="radio" class="wpaicg_chatbox_icon_custom" value="custom" name="wpaicg_chat_widget[icon]">
            <div style="text-align: center">
                <div class="wpaicg_chatbox_icon">
                    <?php
                    if(!empty($wpaicg_chat_icon_url) && $wpaicg_chat_icon == 'custom'):
                        $wpaicg_chatbox_icon_url = wp_get_attachment_url($wpaicg_chat_icon_url);
                        ?>
                        <img src="<?php echo esc_html($wpaicg_chatbox_icon_url)?>" width="75" height="75">
                    <?php
                    else:
                        ?>
                        <svg width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3V320c0 17.7 14.3 32 32 32s32-14.3 32-32V109.3l73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 53 43 96 96 96H352c53 0 96-43 96-96V352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V352z"/></svg><br>
                    <?php
                    endif;
                    ?>
                </div>
                <strong>Custom</strong>
            </div>
        </div>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Font Size:</label>
        <select name="wpaicg_chat_widget[fontsize]">
            <?php
            for($i = 10; $i <= 30; $i++){
                echo '<option'.($wpaicg_chat_fontsize == $i ? ' selected': '').' value="'.esc_html($i).'">'.esc_html($i).'px</option>';
            }
            ?>
        </select>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Font Color:</label>
        <input value="<?php echo esc_html($wpaicg_chat_fontcolor)?>" type="text" class="wpaicgchat_color" name="wpaicg_chat_widget[fontcolor]">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Background Color:</label>
        <input value="<?php echo esc_html($wpaicg_chat_bgcolor)?>" type="text" class="wpaicgchat_color" name="wpaicg_chat_widget[bgcolor]">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">User Background Color:</label>
        <input value="<?php echo esc_html($wpaicg_user_bg_color)?>" type="text" class="wpaicgchat_color" name="wpaicg_chat_widget[user_bg_color]">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">AI Background Color:</label>
        <input value="<?php echo esc_html($wpaicg_ai_bg_color)?>" type="text" class="wpaicgchat_color" name="wpaicg_chat_widget[ai_bg_color]">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Use Avatars:</label>
        <input<?php echo $wpaicg_use_avatar ? ' checked':''?> value="1" type="checkbox" name="wpaicg_chat_widget[use_avatar]">
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">AI Avatar (40x40):</label>
        <div style="display: inline-flex; align-items: center">
            <input<?php echo $wpaicg_ai_avatar == 'default' ? ' checked': ''?> class="wpaicg_chatbox_avatar_default" type="radio" value="default" name="wpaicg_chat_widget[ai_avatar]">
            <div style="text-align: center">
                <img style="display: block;width: 40px; height: 40px" src="<?php echo WPAICG_PLUGIN_URL.'admin/images/chatbot.png'?>"<br>
                <strong>Default</strong>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input<?php echo $wpaicg_ai_avatar == 'custom' ? ' checked': ''?> type="radio" class="wpaicg_chatbox_avatar_custom" value="custom" name="wpaicg_chat_widget[ai_avatar]">
            <div style="text-align: center">
                <div class="wpaicg_chatbox_avatar">
                    <?php
                    if(!empty($wpaicg_ai_avatar_id) && $wpaicg_ai_avatar == 'custom'):
                        $wpaicg_ai_avatar_url = wp_get_attachment_url($wpaicg_ai_avatar_id);
                        ?>
                        <img src="<?php echo esc_html($wpaicg_ai_avatar_url)?>" width="40" height="40">
                    <?php
                    else:
                        ?>
                        <svg width="40px" height="40px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3V320c0 17.7 14.3 32 32 32s32-14.3 32-32V109.3l73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 53 43 96 96 96H352c53 0 96-43 96-96V352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V352z"/></svg><br>
                    <?php
                    endif;
                    ?>
                </div>
                <strong>Custom</strong>
            </div>
        </div>
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Width:</label>
        <input value="<?php echo esc_html($wpaicg_chat_width)?>" style="width: 100px;" min="100" type="number" name="wpaicg_chat_widget[width]">px
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Height:</label>
        <input value="<?php echo esc_html($wpaicg_chat_height)?>" style="width: 100px;" min="100" type="number" name="wpaicg_chat_widget[height]">px
    </div>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Position:</label>
        <input<?php echo $wpaicg_chat_position == 'left' ? ' checked': ''?> type="radio" value="left" name="wpaicg_chat_widget[position]"> Bottom Left&nbsp;&nbsp;&nbsp;&nbsp;
        <input<?php echo $wpaicg_chat_position == 'right' ? ' checked': ''?> type="radio" value="right" name="wpaicg_chat_widget[position]"> Bottom Right
    </div>
    <p>To add the chat bot to a specific page or post in your website, please include the shortcode <code>[wpaicg_chatgpt]</code> in the desired location on your site.</p>
</div>
<script>
    jQuery(document).ready(function ($){
        $('#wpaicg_chat_excerpt').on('click', function (){
            if($(this).prop('checked')){
                $('#wpaicg_chat_excerpt').removeClass('asdisabled');
                $('#wpaicg_chat_embedding').prop('checked',false);
                $('#wpaicg_chat_embedding').addClass('asdisabled');
                $('#wpaicg_chat_embedding_type').val('openai');
                $('#wpaicg_chat_embedding_type').addClass('asdisabled');
                $('#wpaicg_chat_embedding_type').attr('disabled','disabled');
                $('#wpaicg_chat_embedding_top').attr('disabled','disabled');
                $('#wpaicg_chat_embedding_top').val(1);
            }
            else{
                $(this).prop('checked',true);
            }
        });
        $('#wpaicg_chat_embedding').on('click', function (){
            if($(this).prop('checked')){
                $('#wpaicg_chat_excerpt').prop('checked',false);
                $('#wpaicg_chat_excerpt').addClass('asdisabled');
                $('#wpaicg_chat_embedding').removeClass('asdisabled');
                $('#wpaicg_chat_embedding_type').val('openai');
                $('#wpaicg_chat_embedding_type').removeClass('asdisabled');
                $('#wpaicg_chat_embedding_type').removeAttr('disabled');
                $('#wpaicg_chat_embedding_top').val(1);
                $('#wpaicg_chat_embedding_top').removeClass('asdisabled');
                $('#wpaicg_chat_embedding_top').removeAttr('disabled');
            }
            else{
                $(this).prop('checked',true);
            }
        });
        <?php
        if(!$wpaicg_embedding_field_disabled):
        ?>
        $('#wpaicg_chat_content_aware').on('change', function (){
            if($(this).val() === 'yes'){
                $('#wpaicg_chat_excerpt').removeAttr('disabled');
                $('#wpaicg_chat_excerpt').prop('checked',true);
                $('#wpaicg_chat_embedding').removeAttr('disabled');
                $('#wpaicg_chat_embedding_type').removeAttr('disabled');
                $('#wpaicg_chat_embedding').addClass('asdisabled');
                $('#wpaicg_chat_embedding_type').val('openai');
                $('#wpaicg_chat_embedding_type').addClass('asdisabled');
                $('#wpaicg_chat_embedding_top').val(1);
                $('#wpaicg_chat_embedding_top').addClass('asdisabled');
            }
            else{
                $('#wpaicg_chat_embedding_type').removeClass('asdisabled');
                $('#wpaicg_chat_excerpt').removeClass('asdisabled');
                $('#wpaicg_chat_embedding').removeClass('asdisabled');
                $('#wpaicg_chat_excerpt').prop('checked',false);
                $('#wpaicg_chat_embedding').prop('checked',false);
                $('#wpaicg_chat_excerpt').attr('disabled','disabled');
                $('#wpaicg_chat_embedding').attr('disabled','disabled');
                $('#wpaicg_chat_embedding_type').attr('disabled','disabled');
                $('#wpaicg_chat_embedding_top').attr('disabled','disabled');
                $('#wpaicg_chat_embedding_top').removeClass('asdisabled');
            }
        })
        <?php
        else:
        ?>
        $('#wpaicg_chat_content_aware').on('change', function (){
            if($(this).val() === 'yes'){
                $('#wpaicg_chat_excerpt').removeAttr('disabled');
                $('#wpaicg_chat_excerpt').prop('checked',true);
            }
            else{
                $('#wpaicg_chat_excerpt').removeClass('asdisabled');
                $('#wpaicg_chat_excerpt').prop('checked',false);
                $('#wpaicg_chat_excerpt').attr('disabled','disabled');
            }
        })
        <?php
        endif;
        ?>
    })
</script>
