<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wpaicg_items = array();
$wpaicg_icons = array();
$wpaicg_models = array();
if(file_exists(WPAICG_PLUGIN_DIR.'admin/data/gptcategories.json')){
    $wpaicg_file_content = file_get_contents(WPAICG_PLUGIN_DIR.'admin/data/gptcategories.json');
    $wpaicg_file_content = json_decode($wpaicg_file_content, true);
    if($wpaicg_file_content && is_array($wpaicg_file_content) && count($wpaicg_file_content)){
        foreach($wpaicg_file_content as $key=>$item){
            $wpaicg_categories[$key] = trim($item);
        }
    }
}
if(file_exists(WPAICG_PLUGIN_DIR.'admin/data/icons.json')){
    $wpaicg_file_content = file_get_contents(WPAICG_PLUGIN_DIR.'admin/data/icons.json');
    $wpaicg_file_content = json_decode($wpaicg_file_content, true);
    if($wpaicg_file_content && is_array($wpaicg_file_content) && count($wpaicg_file_content)){
        foreach($wpaicg_file_content as $key=>$item){
            $wpaicg_icons[$key] = trim($item);
        }
    }
}
if(file_exists(WPAICG_PLUGIN_DIR.'admin/data/gptforms.json')){
    $wpaicg_file_content = file_get_contents(WPAICG_PLUGIN_DIR.'admin/data/gptforms.json');
    $wpaicg_file_content = json_decode($wpaicg_file_content, true);
    if($wpaicg_file_content && is_array($wpaicg_file_content) && count($wpaicg_file_content)){
        foreach($wpaicg_file_content as $item){
            $wpaicg_items[] = $item;
        }
    }
}
if(file_exists(WPAICG_PLUGIN_DIR.'admin/data/models.json')){
    $wpaicg_file_content = file_get_contents(WPAICG_PLUGIN_DIR.'admin/data/models.json');
    $wpaicg_file_content = json_decode($wpaicg_file_content, true);
    if($wpaicg_file_content && is_array($wpaicg_file_content) && isset($wpaicg_file_content['models']) && is_array($wpaicg_file_content['models']) && count($wpaicg_file_content['models'])){
        foreach($wpaicg_file_content['models'] as $item){
            $wpaicg_models[] = $item['name'];
        }
    }
}
global $wpdb;
if(isset($atts) && is_array($atts) && isset($atts['id']) && !empty($atts['id'])){
    $wpaicg_item_id = sanitize_text_field($atts['id']);
    $wpaicg_item = false;
    $wpaicg_custom = isset($atts['custom']) && $atts['custom'] == 'yes' ? true : false;
    if(count($wpaicg_items) && !$wpaicg_custom){
        foreach ($wpaicg_items as $wpaicg_prompt){
            if(isset($wpaicg_prompt['id']) && $wpaicg_prompt['id'] == $wpaicg_item_id){
                $wpaicg_item = $wpaicg_prompt;
                $wpaicg_item['type'] = 'json';
            }
        }
    }
    if($wpaicg_custom){
        $sql = "SELECT p.ID as id,p.post_title as title, p.post_content as description";
        $wpaicg_meta_keys = array('prompt','editor','fields','response','category','engine','max_tokens','temperature','top_p','best_of','frequency_penalty','presence_penalty','stop','color','icon','bgcolor','header','dans','ddraft','dclear','dnotice');
        foreach($wpaicg_meta_keys as $wpaicg_meta_key){
            $sql .= ",(SELECT ".$wpaicg_meta_key.".meta_value FROM ".$wpdb->postmeta." ".$wpaicg_meta_key." WHERE ".$wpaicg_meta_key.".meta_key='wpaicg_form_".$wpaicg_meta_key."' AND p.ID=".$wpaicg_meta_key.".post_id LIMIT 1) as ".$wpaicg_meta_key;
        }
        $sql .= " FROM ".$wpdb->posts." p WHERE p.post_type = 'wpaicg_form' AND p.post_status='publish' AND p.ID=".$wpaicg_item_id." ORDER BY p.post_date DESC";
        $wpaicg_item = $wpdb->get_row($sql, ARRAY_A);
        if($wpaicg_item){
            $wpaicg_item['type'] = 'custom';
        }
    }
    if($wpaicg_item){
        $wpaicg_item_categories = array();
        $wpaicg_item_categories_name = array();
        if(isset($wpaicg_item['category']) && !empty($wpaicg_item['category'])){
            $wpaicg_item_categories = array_map('trim', explode(',', $wpaicg_item['category']));
        }
        $wpaicg_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M320 0c17.7 0 32 14.3 32 32V96H480c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H160c-35.3 0-64-28.7-64-64V160c0-35.3 28.7-64 64-64H288V32c0-17.7 14.3-32 32-32zM208 384c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H208zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H304zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H400zM264 256c0-22.1-17.9-40-40-40s-40 17.9-40 40s17.9 40 40 40s40-17.9 40-40zm152 40c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40s17.9 40 40 40zM48 224H64V416H48c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48zm544 0c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H576V224h16z"/></svg>';
        if(isset($wpaicg_item['icon']) && !empty($wpaicg_item['icon']) && isset($wpaicg_icons[$wpaicg_item['icon']]) && !empty($wpaicg_icons[$wpaicg_item['icon']])){
            $wpaicg_icon = $wpaicg_icons[$wpaicg_item['icon']];
        }
        $wpaicg_icon_color = isset($wpaicg_item['color']) && !empty($wpaicg_item['color']) ? $wpaicg_item['color'] : '#19c37d';
        $wpaicg_engine = isset($wpaicg_item['engine']) && !empty($wpaicg_item['engine']) ? $wpaicg_item['engine'] : $this->wpaicg_engine;
        $wpaicg_max_tokens = isset($wpaicg_item['max_tokens']) && !empty($wpaicg_item['max_tokens']) ? $wpaicg_item['max_tokens'] : $this->wpaicg_max_tokens;
        $wpaicg_temperature = isset($wpaicg_item['temperature']) && !empty($wpaicg_item['temperature']) ? $wpaicg_item['temperature'] : $this->wpaicg_temperature;
        $wpaicg_top_p = isset($wpaicg_item['top_p']) && !empty($wpaicg_item['top_p']) ? $wpaicg_item['top_p'] : $this->wpaicg_top_p;
        $wpaicg_best_of = isset($wpaicg_item['best_of']) && !empty($wpaicg_item['best_of']) ? $wpaicg_item['best_of'] : $this->wpaicg_best_of;
        $wpaicg_frequency_penalty = isset($wpaicg_item['frequency_penalty']) && !empty($wpaicg_item['frequency_penalty']) ? $wpaicg_item['frequency_penalty'] : $this->wpaicg_frequency_penalty;
        $wpaicg_presence_penalty = isset($wpaicg_item['presence_penalty']) && !empty($wpaicg_item['presence_penalty']) ? $wpaicg_item['presence_penalty'] : $this->wpaicg_presence_penalty;
        $wpaicg_stop = isset($wpaicg_item['stop']) && !empty($wpaicg_item['stop']) ? $wpaicg_item['stop'] : $this->wpaicg_stop;
        $wpaicg_stop_lists = '';
        if(is_array($wpaicg_stop) && count($wpaicg_stop)){
            foreach($wpaicg_stop as $item_stop){
                if($item_stop === "\n"){
                    $item_stop = '\n';
                }
                $wpaicg_stop_lists = empty($wpaicg_stop_lists) ? $item_stop : ','.$item_stop;
            }
        }
        if(count($wpaicg_item_categories)){
            foreach($wpaicg_item_categories as $wpaicg_item_category){
                if(isset($wpaicg_categories[$wpaicg_item_category]) && !empty($wpaicg_categories[$wpaicg_item_category])){
                    $wpaicg_item_categories_name[] = $wpaicg_categories[$wpaicg_item_category];
                }
            }
        }
        if(is_user_logged_in()){
            wp_enqueue_editor();
        }
        $wpaicg_show_setting = false;
        if(isset($atts['settings']) && $atts['settings'] == 'yes'){
            $wpaicg_show_setting = true;
        }
        ?>
        <style>
            .wpaicg-prompt-item{

            }
            .wpaicg-prompt-head{
                display: flex;
                align-items: center;
                padding-bottom: 10px;
                border-bottom: 1px solid #b1b1b1;
            }
            .wpaicg-prompt-icon{
                color: #fff;
                width: 100px;
                height: 100px;
                margin-right: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 5px;
            }
            .wpaicg-prompt-icon svg{
                fill: currentColor;
                width: 50px;
                height: 50px;
            }
            .wpaicg-prompt-head p{
                margin: 5px 0;
            }
            .wpaicg-prompt-head strong{
                font-size: 20px;
                display: block;
            }
            .wpaicg-prompt-content{
                padding: 10px 0;
            }
            .wpaicg-grid-three{
                display: grid;
                grid-template-columns: repeat(3,1fr);
                grid-column-gap: 20px;
                grid-row-gap: 20px;
                grid-template-rows: auto auto;
            }
            .wpaicg-grid-2{
                grid-column: span 2/span 1;
            }
            .wpaicg-grid-1{
                grid-column: span 1/span 1;
            }
            .wpaicg-prompt-item .wpaicg-prompt-sample{
                display: block;
                position: relative;
                font-size: 13px;
            }
            .wpaicg-prompt-item .wpaicg-prompt-sample:hover .wpaicg-prompt-response{
                display: block;
            }
            .wpaicg-prompt-title{
                display: block;
                width: 100%;
                margin-bottom: 20px;
            }
            .wpaicg-prompt-result{
                width: 100%;
            }
            .wpaicg-prompt-max-lines{
                display: inline-block;
                width: auto;
                border: 1px solid #8f8f8f;
                margin-left: 10px;
                padding: 5px 10px;
                border-radius: 3px;
                font-size: 15px;
            }
            .wpaicg-generate-button{
                margin-left: 10px;
            }
            .wpaicg-button{
                padding: 5px 10px;
                background: #424242;
                border: 1px solid #343434;
                border-radius: 4px;
                color: #fff;
                font-size: 15px;
                position: relative;
                display: inline-flex;
                align-items: center;
            }
            .wpaicg-button:disabled{
                background: #505050;
                border-color: #999;
            }
            .wpaicg-button:hover:not(:disabled),.wpaicg-button:focus:not(:disabled){
                color: #fff;
                background-color: #171717;
                text-decoration: none;
            }
            .wpaicg-prompt-item .wpaicg-prompt-response{
                background: #333;
                border: 1px solid #444;
                position: absolute;
                border-radius: 3px;
                color: #fff;
                padding: 5px;
                width: 320px;
                bottom: calc(100% + 5px);
                left: -100px;
                z-index: 99;
                display: none;
                font-size: 13px;
            }
            .wpaicg-prompt-item h3{
                font-size: 25px;
                margin: 0 0 20px 0px;
            }
            .wpaicg-prompt-item .wpaicg-prompt-response:after,.wpaicg-prompt-item .wpaicg-prompt-response:before{
                top: 100%;
                left: 50%;
                border: solid transparent;
                content: "";
                height: 0;
                width: 0;
                position: absolute;
                pointer-events: none;
            }
            .wpaicg-prompt-item .wpaicg-prompt-response:before{
                border-color: rgba(68, 68, 68, 0);
                border-top-color: #444;
                border-width: 7px;
                margin-left: -7px;
            }
            .wpaicg-prompt-item .wpaicg-prompt-response:after{
                border-color: rgba(51, 51, 51, 0);
                border-top-color: #333;
                border-width: 6px;
                margin-left: -6px;
            }
            .wpaicg-prompt-item .wpaicg-prompt-field > strong{
                display: inline-flex;
                width: 50%;
                font-size: 13px;
                align-items: center;
                flex-wrap: wrap;
            }
            .wpaicg-prompt-item .wpaicg-prompt-field > strong > small{
                font-size: 12px;
                font-weight: normal;
                display: block;
            }
            .wpaicg-prompt-item .wpaicg-prompt-field > input,.wpaicg-prompt-item .wpaicg-prompt-field > select{
                border: 1px solid #8f8f8f;
                padding: 5px 10px;
                border-radius: 3px;
                font-size: 15px;
                display: inline-block;
                width: 50%;
            }
            .wpaicg-prompt-flex-center{
                display: flex;
                align-items: center;
            }
            .wpaicg-prompt-field{
                margin-bottom: 10px;
                display: flex;
            }
            .wpaicg-mb-10{
                margin-bottom: 10px;
            }
            .wpaicg-loader{
                width: 20px;
                height: 20px;
                border: 2px solid #FFF;
                border-bottom-color: transparent;
                border-radius: 50%;
                display: inline-block;
                box-sizing: border-box;
                animation: wpaicg_rotation 1s linear infinite;
            }
            .wpaicg-button .wpaicg-loader{
                float: right;
                margin-left: 5px;
                margin-top: 2px;
            }
            .wpaicg-form-field{
                margin-bottom: 10px;
            }
            @keyframes wpaicg_rotation {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
        <?php
        $wpaicg_fields = [];
        if($wpaicg_item['fields'] !== '') {
            $wpaicg_fields = $wpaicg_item['type'] == 'custom' ? json_decode($wpaicg_item['fields'],true) : $wpaicg_item['fields'];
        }
        $wpaicg_response_type = isset($wpaicg_item['editor']) && $wpaicg_item['editor'] == 'div' ? 'div' : 'textarea';
        $kses_defaults = wp_kses_allowed_html( 'post' );
        $svg_args = array(
            'svg'   => array(
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true // <= Must be lower case!
            ),
            'g'     => array( 'fill' => true ),
            'title' => array( 'title' => true ),
            'path'  => array(
                'd'               => true,
                'fill'            => true
            )
        );
        $allowed_tags = array_merge( $kses_defaults, $svg_args );
        ?>
        <div class="wpaicg-prompt-item" style="<?php echo isset($wpaicg_item['bgcolor']) && !empty($wpaicg_item['bgcolor']) ? 'background-color:'.esc_html($wpaicg_item['bgcolor']):'';?>">
            <div class="wpaicg-prompt-head" style="<?php echo isset($wpaicg_item['header']) && $wpaicg_item['header'] == 'no' ? 'display: none;':'';?>">
                <div class="wpaicg-prompt-icon" style="background: <?php echo esc_html($wpaicg_icon_color)?>"><?php echo wp_kses($wpaicg_icon,$allowed_tags)?></div>
                <div class="">
                    <strong><?php echo isset($wpaicg_item['title']) && !empty($wpaicg_item['title']) ? esc_html($wpaicg_item['title']) : ''?></strong>
                    <?php
                    if(isset($wpaicg_item['description']) && !empty($wpaicg_item['description'])){
                        echo '<p>'.esc_html($wpaicg_item['description']).'</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="wpaicg-prompt-content">
                <form method="post" action="" class="wpaicg-prompt-form" id="wpaicg-prompt-form">
                    <?php
                    if($wpaicg_show_setting):
                    ?>
                    <div class="wpaicg-grid-three">
                        <div class="wpaicg-grid-2">
                            <?php
                            endif;
                            ?>
                            <div class="wpaicg-mb-10">
                                <textarea style="display: none" class="wpaicg-prompt-title" id="wpaicg-prompt-title" rows="8"><?php echo esc_html($wpaicg_item['prompt'])?></textarea>
                                <textarea style="display: none" name="title" class="wpaicg-prompt-title" id="wpaicg-prompt-title-filled" rows="8"><?php echo esc_html($wpaicg_item['prompt'])?></textarea>
                                <?php
                                if(count($wpaicg_fields)){
                                    foreach($wpaicg_fields as $key=>$wpaicg_field){
                                    ?>
                                        <div class="wpaicg-form-field">
                                            <label><strong><?php echo esc_html(@$wpaicg_field['label'])?></strong></label><br>
                                            <?php
                                            if($wpaicg_field['type'] == 'select'){
                                                $wpaicg_field_options = [];
                                                if(isset($wpaicg_field['options'])){
                                                    if($wpaicg_item['type'] == 'custom'){
                                                        $wpaicg_field_options = explode("|", $wpaicg_field['options']);
                                                    }
                                                    else{
                                                        $wpaicg_field_options = $wpaicg_field['options'];
                                                    }
                                                }
                                                ?>
                                                <select required id="wpaicg-form-field-<?php echo esc_html($key)?>" name="<?php echo esc_html($wpaicg_field['id'])?>" data-label="<?php echo esc_html(@$wpaicg_field['label'])?>" data-type="<?php echo esc_html(@$wpaicg_field['type'])?>" data-min="<?php echo isset($wpaicg_field['min']) ? esc_html($wpaicg_field['min']) : ''?>" data-max="<?php echo isset($wpaicg_field['max']) ? esc_html($wpaicg_field['max']) : ''?>">
                                                    <?php
                                                    foreach($wpaicg_field_options as $wpaicg_field_option){
                                                        echo '<option value="'.esc_html($wpaicg_field_option).'">'.esc_html($wpaicg_field_option).'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            }
                                            else{
                                                ?>
                                                <input required id="wpaicg-form-field-<?php echo esc_html($key)?>" name="<?php echo esc_html($wpaicg_field['id'])?>" data-label="<?php echo esc_html(@$wpaicg_field['label'])?>" data-type="<?php echo esc_html(@$wpaicg_field['type'])?>" type="<?php echo esc_html(@$wpaicg_field['type'])?>" data-min="<?php echo isset($wpaicg_field['min']) ? esc_html($wpaicg_field['min']) : ''?>" data-max="<?php echo isset($wpaicg_field['max']) ? esc_html($wpaicg_field['max']) : ''?>">
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                }
                                ?>
                                <div class="wpaicg-prompt-flex-center">
                                    <div style="<?php echo isset($wpaicg_item['dans']) && $wpaicg_item['dans'] == 'no' ? 'display:none':''?>">
                                        <strong>Number of Answers</strong>
                                        <select class="wpaicg-prompt-max-lines" id="wpaicg-prompt-max-lines">
                                            <?php
                                            for($i=1;$i<=10;$i++){
                                                echo '<option value="'.esc_html($i).'">'.esc_html($i).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button style="<?php echo isset($wpaicg_item['dans']) && $wpaicg_item['dans'] == 'no' ? 'margin-left:0':''?>" class="wpaicg-button wpaicg-generate-button" id="wpaicg-generate-button">Generate</button>
                                    &nbsp;<button type="button" class="wpaicg-button wpaicg-prompt-stop-generate" id="wpaicg-prompt-stop-generate" style="display: none">Stop</button>
                                </div>
                            </div>
                            <div class="mb-5">
                                <?php
                                if($wpaicg_response_type == 'textarea'):
                                    if(is_user_logged_in()){
                                        wp_editor('','wpaicg-prompt-result', array('media_buttons' => true, 'textarea_name' => 'wpaicg-prompt-result'));
                                    }
                                    else{
                                        ?>
                                        <textarea class="wpaicg-prompt-result" id="wpaicg-prompt-result" rows="12"></textarea>
                                        <?php
                                        if(isset($wpaicg_item['dnotice']) && $wpaicg_item['dnotice'] == 'no'):
                                        else:
                                            ?>
                                        <a style="font-size: 13px;" href="<?php echo site_url('wp-login.php?action=register')?>">Please register to save your result</a>
                                        <?php
                                        endif;
                                        ?>
                                    <?php
                                    }
                                else:
                                    echo '<div id="wpaicg-prompt-result"></div>';
                                    if(!is_user_logged_in()){
                                        if(isset($wpaicg_item['dnotice']) && $wpaicg_item['dnotice'] == 'no'){

                                        }
                                        else{
                                            ?>
                                            <a style="font-size: 13px;" href="<?php echo site_url('wp-login.php?action=register')?>">Please register to save your result</a>
                                            <?php
                                        }
                                    }
                                endif;
                                ?>
                            </div>
                            <div class="wpaicg-prompt-save-result" id="wpaicg-prompt-save-result" style="display: none;margin-top: 10px;">
                                <?php
                                if(is_user_logged_in()):
                                if(isset($wpaicg_item['ddraft']) && $wpaicg_item['ddraft'] == 'no'):
                                else:
                                ?>
                                <button type="button" class="wpaicg-button wpaicg-prompt-save-draft" id="wpaicg-prompt-save-draft">Save Draft</button>
                                <?php
                                    endif;
                                endif;
                                if(isset($wpaicg_item['dclear']) && $wpaicg_item['dclear'] == 'no'):
                                else:
                                ?>
                                <button type="button" class="wpaicg-button wpaicg-prompt-clear" id="wpaicg-prompt-clear">Clear</button>
                                <?php
                                endif;
                                ?>
                            </div>
                            <?php
                            if($wpaicg_show_setting):
                            ?>
                        </div>
                        <div class="wpaicg-grid-1">
                            <?php
                            endif;
                            ?>
                            <div class="wpaicg-mb-10 wpaicg-prompt-item" style="<?php echo !$wpaicg_show_setting ? 'display:none': ''?>">
                                <h3>Settings</h3>
                                <div class="wpaicg-prompt-field wpaicg-prompt-engine">
                                    <strong>Engine: </strong>
                                    <select name="engine">
                                        <?php
                                        foreach($wpaicg_models as $wpaicg_model){
                                            echo '<option'.($wpaicg_model == $wpaicg_engine ? ' selected':'').' value="'.esc_html($wpaicg_model).'">'.esc_html($wpaicg_model).'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="wpaicg-prompt-field"><strong>Token: </strong><input id="wpaicg-prompt-max_tokens" name="max_tokens" type="text" value="<?php echo esc_html($wpaicg_max_tokens);?>"></div>
                                <div class="wpaicg-prompt-field"><strong>Temp: </strong><input id="wpaicg-prompt-temperature" name="temperature" type="text" value="<?php echo esc_html($wpaicg_temperature)?>"></div>
                                <div class="wpaicg-prompt-field"><strong>TP: </strong><input id="wpaicg-prompt-top_p" type="text" name="top_p" value="<?php echo esc_html($wpaicg_top_p)?>"></div>
                                <div class="wpaicg-prompt-field"><strong>BO: </strong><input id="wpaicg-prompt-best_of" name="best_of" type="text" value="<?php echo esc_html($wpaicg_best_of)?>"></div>
                                <div class="wpaicg-prompt-field"><strong>FP: </strong><input id="wpaicg-prompt-frequency_penalty" name="frequency_penalty" type="text" value="<?php echo esc_html($wpaicg_frequency_penalty)?>"></div>
                                <div class="wpaicg-prompt-field"><strong>PP: </strong><input id="wpaicg-prompt-presence_penalty" name="presence_penalty" type="text" value="<?php echo esc_html($wpaicg_presence_penalty)?>"></div>
                                <div class="wpaicg-prompt-field"><strong>Stop:<small>separate by commas</small></strong><input id="wpaicg-prompt-stop" type="text" name="stop" type="text" value="<?php echo esc_html($wpaicg_stop_lists)?>"></div>
                                <div class="wpaicg-prompt-field"><input id="wpaicg-prompt-post_title" type="hidden" name="post_title" value="<?php echo esc_html($wpaicg_item['title'])?>"></div>
                                <div class="wpaicg-prompt-field wpaicg-prompt-sample">Sample Response?<div class="wpaicg-prompt-response"><?php echo esc_html(@$wpaicg_item['response'])?></div></div>
                            </div>
                            <?php
                            if($wpaicg_show_setting):
                            ?>
                        </div>
                    </div>
                    <?php
                    endif;
                    ?>
                </form>
            </div>
        </div>
        <script>
            var wpaicg_prompt_logged = <?php echo is_user_logged_in() ? 'true' : 'false'?>;
            var wpaicgForm = document.getElementById('wpaicg-prompt-form');
            var wpaicgMaxToken = document.getElementById('wpaicg-prompt-max_tokens');
            var wpaicgTemperature = document.getElementById('wpaicg-prompt-temperature');
            var wpaicgTopP = document.getElementById('wpaicg-prompt-top_p');
            var wpaicgBestOf = document.getElementById('wpaicg-prompt-best_of');
            var wpaicgFP = document.getElementById('wpaicg-prompt-frequency_penalty');
            var wpaicgPP = document.getElementById('wpaicg-prompt-presence_penalty');
            var wpaicgStop = document.getElementById('wpaicg-prompt-stop-generate');
            var wpaicgMaxLines = document.getElementById('wpaicg-prompt-max-lines');
            var wpaicgPromptTitle = document.getElementById('wpaicg-prompt-title');
            var wpaicgPromptTitleFilled = document.getElementById('wpaicg-prompt-title-filled');
            var wpaicgGenerateBtn = document.getElementById('wpaicg-generate-button');
            var wpaicgFormFields = <?php echo json_encode($wpaicg_fields)?>;
            var wpaicgItemType = '<?php echo esc_html($wpaicg_item['type'])?>';
            var wpaicgResponseType = '<?php echo esc_html($wpaicg_response_type)?>';
            <?php
            if(is_user_logged_in()):
            ?>
            var wpaicgSaveDraftBtn = document.getElementById('wpaicg-prompt-save-draft');
            <?php
            endif;
            ?>
            var wpaicgClearBtn = document.getElementById('wpaicg-prompt-clear');
            var wpaicgSaveResult = document.getElementById('wpaicg-prompt-save-result');
            var eventGenerator = false;
            function wpaicgBasicEditor(){
                var basicEditor = true;
                if(wpaicg_prompt_logged){
                    var editor = tinyMCE.get('wpaicg-prompt-result');
                    if ( document.getElementById('wp-wpaicg-prompt-result-wrap').classList.contains('tmce-active') && editor ) {
                        basicEditor = false;
                    }
                }
                return basicEditor;
            }
            function wpaicgSetContent(value){
                if(wpaicgResponseType === 'textarea') {
                    if (wpaicgBasicEditor()) {
                        document.getElementById('wpaicg-prompt-result').value = value;
                    } else {
                        var editor = tinyMCE.get('wpaicg-prompt-result');
                        editor.setContent(value);
                    }
                }
                else{
                    document.getElementById('wpaicg-prompt-result').innerHTML = value;
                }
            }
            function wpaicgGetContent(){
                if(wpaicgResponseType === 'textarea') {
                    if (wpaicgBasicEditor()) {
                        return document.getElementById('wpaicg-prompt-result').value
                    } else {
                        var editor = tinyMCE.get('wpaicg-prompt-result');
                        var content = editor.getContent();
                        content = content.replace(/<\/?p(>|$)/g, "");
                        return content;
                    }
                }
                else return document.getElementById('wpaicg-prompt-result').innerHTML;
            }
            function wpaicgLoadingBtn(btn){
                btn.setAttribute('disabled','disabled');
                btn.innerHTML += '<span class="wpaicg-loader"></span>';
            }
            function wpaicgRmLoading(btn){
                btn.removeAttribute('disabled');
                btn.removeChild(btn.getElementsByTagName('span')[0]);
            }
            function wpaicgEventClose(){
                wpaicgStop.style.display = 'none';
                wpaicgSaveResult.style.display = 'block';
                wpaicgRmLoading(wpaicgGenerateBtn);
                eventGenerator.close();
            }
            function wpaicgValidEmail(email){
                return String(email)
                    .toLowerCase()
                    .match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
            }
            function wpaicgValidUrl(string) {
                try {
                    new URL(string);
                    return true;
                } catch (err) {
                    return false;
                }
            }
            var wpaicg_break_newline = "<?php echo is_user_logged_in() ? '<br/><br />': '\n\n'?>";
            wpaicgForm.addEventListener('submit', function(e){
                e.preventDefault();
                var max_tokens = wpaicgMaxToken.value;
                var temperature = wpaicgTemperature.value;
                var top_p = wpaicgTopP.value;
                var best_of = wpaicgBestOf.value;
                var frequency_penalty = wpaicgFP.value;
                var presence_penalty = wpaicgPP.value;
                var error_message = false;
                var title = wpaicgPromptTitle.value;
                if(title === ''){
                    error_message = 'Please insert prompt';
                }
                else if(max_tokens === ''){
                    error_message = 'Please enter max tokens';
                }
                else if(parseFloat(max_tokens) < 1 || parseFloat(max_tokens) > 2048){
                    error_message = 'Please enter a valid max tokens value between 1 and 2048';
                }
                else if(temperature === ''){
                    error_message = 'Please enter temperature';
                }
                else if(parseFloat(temperature) < 0 || parseFloat(temperature) > 1){
                    error_message = 'Please enter a valid temperature value between 0 and 1';
                }
                else if(top_p === ''){
                    error_message = 'Please enter Top P';
                }
                else if(parseFloat(top_p) < 0 || parseFloat(top_p) > 1){
                    error_message = 'Please enter a valid Top P value between 0 and 1';
                }
                else if(best_of === ''){
                    error_message = 'Please enter best of';
                }
                else if(parseFloat(best_of) < 1 || parseFloat(best_of) > 20){
                    error_message = 'Please enter a valid best of value between 0 and 1';
                }
                else if(frequency_penalty === ''){
                    error_message = 'Please enter frequency penalty';
                }
                else if(parseFloat(frequency_penalty) < 0 || parseFloat(frequency_penalty) > 2){
                    error_message = 'Please enter a valid frequency penalty value between 0 and 2';
                }
                else if(presence_penalty === ''){
                    error_message = 'Please enter presence penalty';
                }
                else if(parseFloat(presence_penalty) < 0 || parseFloat(presence_penalty) > 2){
                    error_message = 'Please enter a valid presence penalty value between 0 and 2';
                }
                if(error_message){
                    alert(error_message);
                }
                else{
                    if(typeof wpaicgFormFields === 'object'){
                        for(var i = 0; i < wpaicgFormFields.length; i++){
                            var form_field = wpaicgFormFields[i];
                            var field = document.getElementById('wpaicg-form-field-'+i);
                            var field_type = form_field['type'] !== undefined ? form_field['type'] : 'text' ;
                            var field_label = form_field['label'] !== undefined ? form_field['label'] : '' ;
                            var field_min = form_field['min'] !== undefined ? form_field['min'] : '' ;
                            var field_max = form_field['max'] !== undefined ? form_field['max'] : '' ;
                            var field_value = field.value;
                            if(field_type === 'text' || field_type === 'email' || field_type === 'url'){
                                if(field_min !== '' && field_value.length < parseInt(field_min)){
                                    error_message = field_label+' minimum '+field_min+' characters';
                                }
                                else if(field_max !== '' && field_value.length > parseInt(field_max)){
                                    error_message = field_label+' maximum '+field_max+' characters';
                                }
                                else if(field_type === 'email' && !wpaicgValidEmail(field_value)){
                                    error_message = field_label+' must be email address';
                                }
                                else if(field_type === 'url' && !wpaicgValidUrl(field_value)){
                                    error_message = field_label+' must be url';
                                }
                            }
                            else if(field_type === 'number'){
                                if(field_min !== '' && parseFloat(field_value) < parseInt(field_min)){
                                    error_message = field_label+' minimum '+field_min;
                                }
                                else if(field_max !== '' && parseFloat(field_value) > parseInt(field_max)){
                                    error_message = field_label+' maximum '+field_max;
                                }
                            }
                        }
                    }
                    if(error_message){
                        alert(error_message);
                    }
                    else {
                        if(typeof wpaicgFormFields === 'object') {
                            for (var i = 0; i < wpaicgFormFields.length; i++) {
                                var form_field = wpaicgFormFields[i];
                                var field = document.getElementById('wpaicg-form-field-'+i);
                                var field_name = form_field['id'] !== undefined ? form_field['id'] : '' ;
                                var field_value = field.value;
                                var sRegExInput = new RegExp('{' + field_name + '}', 'g');
                                title = title.replace(sRegExInput, field_value);
                            }
                        }
                        wpaicgPromptTitleFilled.value = title+".\n\n";
                        const queryString = new URLSearchParams(new FormData(wpaicgForm)).toString();
                        wpaicgLoadingBtn(wpaicgGenerateBtn);
                        wpaicgSaveResult.style.display = 'none';
                        wpaicgStop.style.display = 'inline';
                        wpaicgSetContent('');
                        var wpaicg_limitLines = parseFloat(wpaicgMaxLines.value);
                        var count_line = 0;
                        var currentContent = '';
                        eventGenerator = new EventSource('<?php echo esc_html(add_query_arg('wpaicg_stream','yes',site_url().'/index.php'));?>&' + queryString);
                        var wpaicg_response_events = 0;
                        var wpaicg_newline_before = false;
                        eventGenerator.onmessage = function (e) {
                            currentContent = wpaicgGetContent();
                            if (e.data === "[DONE]") {
                                count_line += 1;
                                wpaicgSetContent(currentContent + wpaicg_break_newline);
                                wpaicg_response_events = 0;
                            } else {
                                var result = JSON.parse(e.data);
                                var content_generated = '';
                                if (result.error !== undefined) {
                                    content_generated = result.error.message;
                                } else {
                                    content_generated = result.choices[0].text;
                                }
                                if (content_generated === '\n' && wpaicg_response_events > 0 && currentContent !== '') {
                                    console.log(currentContent);
                                    if(!wpaicg_newline_before) {
                                        wpaicg_newline_before = true;
                                        wpaicgSetContent(currentContent + wpaicg_break_newline);
                                    }
                                }
                                else if(content_generated === '\n' && wpaicg_response_events === 0 &&  currentContent === ''){

                                } else {
                                    wpaicg_newline_before = false;
                                    wpaicg_response_events += 1;
                                    wpaicgSetContent(currentContent + content_generated);
                                }
                            }
                            if (count_line === wpaicg_limitLines) {
                                wpaicgEventClose();
                            }
                        }
                    }
                }
                return false;
            });
            wpaicgStop.addEventListener('click', function (e){
                e.preventDefault();
                wpaicgEventClose();
            });
            if(wpaicgClearBtn) {
                wpaicgClearBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    wpaicgSetContent('');
                    wpaicgSaveResult.style.display = 'none';
                });
            }
            <?php
            if(is_user_logged_in()):
            ?>
            if(wpaicgSaveDraftBtn) {
                wpaicgSaveDraftBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var title = document.getElementById('wpaicg-prompt-post_title').value;
                    var content = wpaicgGetContent();
                    if (title === '') {
                        alert('Please insert title');
                    } else if (content === '') {
                        alert('Please wait generate content')
                    } else {
                        const xhttp = new XMLHttpRequest();
                        xhttp.open('POST', '<?php echo admin_url('admin-ajax.php')?>');
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhttp.send('action=wpaicg_save_draft_post_extra&title=' + title + '&content=' + content);
                        wpaicgLoadingBtn(wpaicgSaveDraftBtn);
                        xhttp.onreadystatechange = function (oEvent) {
                            if (xhttp.readyState === 4) {
                                wpaicgRmLoading(wpaicgSaveDraftBtn);
                                if (xhttp.status === 200) {
                                    var wpaicg_response = this.responseText;
                                    wpaicg_response = JSON.parse(wpaicg_response);
                                    if (wpaicg_response.status === 'success') {
                                        window.location.href = '<?php echo admin_url('post.php')?>?post=' + wpaicg_response.id + '&action=edit';
                                    } else {
                                        alert(wpaicg_response.msg);
                                    }
                                } else {
                                    alert('Something went wrong');
                                }
                            }
                        }
                    }
                })
            }
            <?php
            endif;
            ?>
        </script>
        <?php
    }
}
