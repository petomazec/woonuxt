<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="tabs-1">
    <?php
    $wpaicg_ai_model = get_option('wpaicg_ai_model','');
    ?>
    <div class="wpcgai_form_row">
        <label class="wpcgai_label" for="wpaicg_ai_model">Model:</label>
        <select class="regular-text" id="wpaicg_ai_model"  name="wpaicg_ai_model" >
            <?php
            foreach($wpaicg_custom_models as $wpaicg_custom_model){
                echo '<option'.($wpaicg_ai_model == $wpaicg_custom_model ? ' selected':'').' value="'.esc_html($wpaicg_custom_model).'">'.esc_html($wpaicg_custom_model).'</option>';
            }
            ?>
        </select>
        <a class="wpcgai_help_link" href="https://gptaipower.com/gpt-3-model-settings/" target="_blank">?</a>
        <a class="wpaicg_sync_finetune" href="javascript:void(0)">Sync</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Temperature:</label>
        <input type="text" class="regular-text" id="label_temperature" name="wpaicg_settings[temperature]" value="<?php
        echo  esc_html( $existingValue['temperature'] ) ;
        ?>">
        <a class="wpcgai_help_link" href="https://gptaipower.com/gpt-3-temperature-settings/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Max Tokens:</label>
        <input type="text" class="regular-text" id="label_max_tokens" name="wpaicg_settings[max_tokens]" value="<?php
        echo  esc_html( $existingValue['max_tokens'] ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/max-tokens/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Top P:</label>
        <input type="text" class="regular-text" id="label_top_p" name="wpaicg_settings[top_p]" value="<?php
        echo  esc_html( $existingValue['top_p'] ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/top_p/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Best Of:</label>
        <input type="text" class="regular-text" id="label_best_of" name="wpaicg_settings[best_of]" value="<?php
        echo  esc_html( $existingValue['best_of'] ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/best-of/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Frequency Penalty:</label>
        <input type="text" class="regular-text" id="label_frequency_penalty" name="wpaicg_settings[frequency_penalty]" value="<?php
        echo  esc_html( $existingValue['frequency_penalty'] ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/frequency-penalty/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Presence Penalty:</label>
        <input type="text" class="regular-text" id="label_presence_penalty" name="wpaicg_settings[presence_penalty]" value="<?php
        echo  esc_html( $existingValue['presence_penalty'] ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/presence-penalty/" target="_blank">?</a>
    </div>

    <div class="wpcgai_form_row">
        <label class="wpcgai_label">Api Key:</label>
        <input type="text" class="regular-text" id="label_api_key" name="wpaicg_settings[api_key]" value="<?php
        echo  esc_html( $existingValue['api_key'] ) ;
        ?>" >
        <a class="wpcgai_help_link" href="https://gptaipower.com/bring-your-own-key-model/" target="_blank">?</a>
        <a class="wpcgai_help_link" href="https://beta.openai.com/account/api-keys" target="_blank">Get Your Api Key</a>
    </div>
</div>
