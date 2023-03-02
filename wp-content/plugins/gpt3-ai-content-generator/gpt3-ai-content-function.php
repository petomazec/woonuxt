<?php
namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;

if(!class_exists('WPAICG_Functions')) {
    class WPAICG_Functions
    {
        public static function wpaicg_count_words($text)
        {
            $text = trim(strip_tags(html_entity_decode($text,ENT_QUOTES)));
            $text = preg_replace("/[\n]+/", " ", $text);
            $text = preg_replace("/[\s]+/", "@SEPARATOR@", $text);
            $text_array = explode('@SEPARATOR@', $text);
            $count = count($text_array);
            $last_key = end($text_array);
            if (empty($last_key)) {
                $count--;
            }
            return $count;
        }

        public static function wpaicg_pexels_generator($wpaicg_pexels_api, $wpai_preview_title, $wpaicg_pexels_orientation, $wpaicg_pexels_size)
        {
            $wpaicg_result = array('status' => 'success');
            if(!empty($wpaicg_pexels_api)) {
                $wpaicg_pexels_url = 'https://api.pexels.com/v1/search?query='.$wpai_preview_title.'&per_page=1';
                if(!empty($wpaicg_pexels_orientation)){
                    $wpaicg_pexels_orientation = strtolower($wpaicg_pexels_orientation);
                    $wpaicg_pexels_url .= '&orientation='.$wpaicg_pexels_orientation;
                }
                $response = wp_remote_get($wpaicg_pexels_url,array(
                    'headers' => array(
                        'Authorization' => $wpaicg_pexels_api
                    )
                ));
                if(is_wp_error($response)){
                    $wpaicg_result['status'] = 'success';
                    $wpaicg_result['msg'] = $response->get_error_message();
                }
                else{
                    $body = json_decode($response['body'],true);
                    if($body && is_array($body) && isset($body['photos']) && is_array($body['photos']) && count($body['photos'])){
                        $wpaicg_pexels_key = 'medium';
                        if(!empty($wpaicg_pexels_size)){
                            $wpaicg_pexels_size = strtolower($wpaicg_pexels_size);
                            if(in_array($wpaicg_pexels_size,array('large','medium','small'))){
                                $wpaicg_pexels_key = $wpaicg_pexels_size;
                            }
                        }
                        if(isset($body['photos'][0]['src'][$wpaicg_pexels_key]) && !empty($body['photos'][0]['src'][$wpaicg_pexels_key])){
                            $wpaicg_result['pexels_reponse'] = trim($body['photos'][0]['src'][$wpaicg_pexels_key]);

                        }
                        else{
                            $wpaicg_result['status'] = 'no_image';
                            $wpaicg_result['msg'] = 'No image generated';
                        }
                    }
                    else{
                        $wpaicg_result['status'] = 'no_image';
                        $wpaicg_result['msg'] = 'No image generated';
                    }
                }

            }
            else{
                $wpaicg_result['status'] = 'error';
                $wpaicg_result['msg'] = 'Missing Pexels API Setting';
            }
            return $wpaicg_result;
        }

        public static function wpaicg_load_db_vaule($wpaicg_preview_title, $post_id)
        {
            global  $wpdb ;
            $wpaicg_result = array('tokens' => 0, 'length' => 0, 'error' => '', 'content' => '','img' => '','description' => '','featured_img'       => '',);
            $open_ai = WPAICG_OpenAI::get_instance()->openai();
            if(!$open_ai){
                $wpaicg_result['error'] = 'Missing API Setting';
                return $wpaicg_result;
            }
            $temperature = floatval( $open_ai->temperature );
            $max_tokens = intval( $open_ai->max_tokens );
            $top_p = floatval( $open_ai->top_p );
            $best_of = intval( $open_ai->best_of );
            $frequency_penalty = floatval( $open_ai->frequency_penalty );
            $presence_penalty = floatval( $open_ai->presence_penalty );
            $img_size = $open_ai->img_size;
            $wpai_preview_title = sanitize_text_field($wpaicg_preview_title);
            $wpai_number_of_heading = $open_ai->wpai_number_of_heading;
            $wpai_add_img = intval( $open_ai->wpai_add_img );
            $wpai_language = sanitize_text_field( $open_ai->wpai_language );
            $wpai_add_intro = intval( $open_ai->wpai_add_intro );
            $wpai_add_conclusion = intval( $open_ai->wpai_add_conclusion );
            $wpai_writing_style = sanitize_text_field( $open_ai->wpai_writing_style );
            $wpai_writing_tone = sanitize_text_field( $open_ai->wpai_writing_tone );
            $wpai_keywords = get_post_meta($post_id, '_wpaicg_keywords', true);
            $wpai_add_keywords_bold = intval($open_ai->wpai_add_keywords_bold);
            $wpai_heading_tag = sanitize_text_field( $open_ai->wpai_heading_tag );
            $wpai_words_to_avoid = get_post_meta($post_id,'_wpaicg_avoid',true);
            $wpai_add_tagline = intval( $open_ai->wpai_add_tagline );
            $wpai_add_faq = intval( $open_ai->wpai_add_faq );
            $wpai_target_url = get_post_meta($post_id,'_wpaicg_target',true);
            $wpai_anchor_text = get_post_meta($post_id,'_wpaicg_anchor',true);
            $wpai_cta_pos = sanitize_text_field( $open_ai->wpai_cta_pos );
            $wpai_target_url_cta = get_post_meta($post_id,'_wpaicg_cta',true);
            $wpai_modify_headings = intval( $open_ai->wpai_modify_headings);
            $wpaicg_toc = get_option('wpaicg_toc',false);
            $wpaicg_toc_title = get_option('wpaicg_toc_title','Table of Contents');
            $wpaicg_toc_title_tag = get_option('wpaicg_toc_title_tag','h2');
            $wpaicg_intro_title_tag = get_option('wpaicg_intro_title_tag','h2');
            $wpaicg_conclusion_title_tag = get_option('wpaicg_conclusion_title_tag','h2');
            $wpaicg_pexels_api = get_option('wpaicg_pexels_api','');
            $wpaicg_image_source = get_option('wpaicg_image_source','');
            $wpaicg_featured_image_source = get_option('wpaicg_featured_image_source','');
            $wpaicg_pexels_orientation = get_option('wpaicg_pexels_orientation','');
            $wpaicg_pexels_size = get_option('wpaicg_pexels_size','');

            $wpaicg_toc_list = array();
            // if language is not set, set it to english
            if ( empty($wpai_language) ) {
                $wpai_language = "en";
            }
            // if number of heading is not set, set it to 5
            if ( empty($wpai_number_of_heading) ) {
                $wpai_number_of_heading = 5;
            }
            // if writing style is not set, set it to descriptive
            if ( empty($wpai_writing_style) ) {
                $wpai_writing_style = "infor";
            }
            // if writing tone is not set, set it to assertive
            if ( empty($wpai_writing_tone) ) {
                $wpai_writing_tone = "formal";
            }
            // if heading tag is not set, set it to h2
            if ( empty($wpai_heading_tag) ) {
                $wpai_heading_tag = "h2";
            }
            // Analytical, Critical, Evaluative, Journalistic
            // if writing style is descriptive, set the prompt text

            $wpaicg_language_file = WPAICG_PLUGIN_DIR.'admin/languages/'.$wpai_language.'.json';
            if(!file_exists($wpaicg_language_file)){
                $wpaicg_language_file = WPAICG_PLUGIN_DIR.'admin/languages/en.json';
            }
            $wpaicg_language_json = file_get_contents($wpaicg_language_file);
            $wpaicg_languages = json_decode($wpaicg_language_json,true);
            $writing_style = isset($wpaicg_languages['writing_style'][$wpai_writing_style]) ? $wpaicg_languages['writing_style'][$wpai_writing_style] : 'infor';
            $tone_text = isset($wpaicg_languages['writing_tone'][$wpai_writing_tone]) ? $wpaicg_languages['writing_tone'][$wpai_writing_tone] : 'formal';
            if ($wpai_number_of_heading == 1) {
                $prompt_text = isset($wpaicg_languages['prompt_text_1']) ? $wpaicg_languages['prompt_text_1'] : '';
            }
            else {

                $prompt_text = isset($wpaicg_languages['prompt_text']) ? $wpaicg_languages['prompt_text'] : '';
            }
            $prompt_last = isset($wpaicg_languages['prompt_last']) ? $wpaicg_languages['prompt_last'] : '';
            $intro_text = isset($wpaicg_languages['intro_text']) ? $wpaicg_languages['intro_text'] : '';
            $conclusion_text = isset($wpaicg_languages['conclusion_text']) ? $wpaicg_languages['conclusion_text'] : '';
            $tagline_text = isset($wpaicg_languages['tagline_text']) ? $wpaicg_languages['tagline_text'] : '';
            $introduction = isset($wpaicg_languages['introduction']) ? $wpaicg_languages['introduction'] : '';
            $conclusion = isset($wpaicg_languages['conclusion']) ? $wpaicg_languages['conclusion'] : '';
            if($wpai_language == 'hi' || $wpai_language == 'tr' || $wpai_language == 'ja' || $wpai_language == 'zh' || $wpai_language == 'ko'){
                $faq_text = isset($wpaicg_languages['faq_text']) ? sprintf($wpaicg_languages['faq_text'], $wpai_preview_title, strval($wpai_number_of_heading)) : '';
            }
            else{
                $faq_text = isset($wpaicg_languages['faq_text']) ? sprintf($wpaicg_languages['faq_text'], strval($wpai_number_of_heading),$wpai_preview_title) : '';
            }
            $faq_heading = isset($wpaicg_languages['faq_heading']) ? $wpaicg_languages['faq_heading'] : '';
            $style_text = isset($wpaicg_languages['style_text']) ? sprintf($wpaicg_languages['style_text'], $writing_style) : '';
            $of_text = isset($wpaicg_languages['of_text']) ? $wpaicg_languages['of_text'] : '';
            $prompt_last = isset($wpaicg_languages['prompt_last']) ? $wpaicg_languages['prompt_last'] : '';
            $piece_text = isset($wpaicg_languages['piece_text']) ? $wpaicg_languages['piece_text'] : '';
            if(
                $wpai_language == 'ru'
                || $wpai_language == 'ko'
            ){
                if (empty($wpai_keywords)) {
                    $myprompt = $prompt_text . strval($wpai_number_of_heading) . $prompt_last . $wpai_preview_title . ".";
                } else {
                    $keyword_text = isset($wpaicg_languages['keyword_text']) ? sprintf($wpaicg_languages['keyword_text'], $wpai_keywords) : '';
                    $myprompt = $prompt_text . strval($wpai_number_of_heading) . $prompt_last . $wpai_preview_title . $keyword_text;
                }
            }
            elseif($wpai_language == 'zh'){
                if (empty($wpai_keywords)) {
                    $myprompt = $prompt_text . $wpai_preview_title . $of_text . strval($wpai_number_of_heading) . $piece_text . ".";
                } else {
                    $keyword_text = isset($wpaicg_languages['keyword_text']) ? sprintf($wpaicg_languages['keyword_text'], $wpai_keywords) : '';
                    $myprompt = $prompt_text . $wpai_preview_title . $of_text . strval($wpai_number_of_heading) . $piece_text . $keyword_text;
                }
            }
            elseif($wpai_language == 'ja' || $wpai_language == 'hi' || $wpai_language == 'tr'){
                if (empty($wpai_keywords)) {
                    $myprompt = $wpai_preview_title . $prompt_text . strval($wpai_number_of_heading) . $prompt_last . ".";
                } else {
                    $keyword_text = isset($wpaicg_languages['keyword_text']) ? sprintf($wpaicg_languages['keyword_text'], $wpai_keywords) : '';
                    $myprompt = $wpai_preview_title . $prompt_text . strval($wpai_number_of_heading) . $prompt_last . $keyword_text;
                }
            }
            else{
                if (empty($wpai_keywords)) {
                    $myprompt = strval($wpai_number_of_heading) . $prompt_text . $wpai_preview_title . ".";
                } else {
                    $keyword_text = isset($wpaicg_languages['keyword_text']) ? sprintf($wpaicg_languages['keyword_text'], $wpai_keywords) : '';
                    $myprompt = strval($wpai_number_of_heading) . $prompt_text . $wpai_preview_title . $keyword_text;
                }
            }
            if (!empty($wpai_words_to_avoid)) {
                $avoid_text = isset($wpaicg_languages['avoid_text']) ? sprintf($wpaicg_languages['avoid_text'], $wpai_words_to_avoid) : '';
                $myprompt = $myprompt . $avoid_text;
            }
            if($wpai_language == 'ja' || $wpai_language == 'tr'){
                $myintro = $wpai_preview_title.$intro_text;
                $myconclusion = $wpai_preview_title.$conclusion_text;
                $mytagline = $wpai_preview_title.$tagline_text;
            }
            else if($wpai_language == 'ko' || $wpai_language == 'hi' || $wpai_language == 'ar'){
                $myintro = $intro_text . $wpai_preview_title;
                $myconclusion = $conclusion_text . $wpai_preview_title;
                $mytagline = $wpai_preview_title.$tagline_text;
            }
            else{
                $myintro = $intro_text . $wpai_preview_title;
                $myconclusion = $conclusion_text . $wpai_preview_title;
                $mytagline = $tagline_text . $wpai_preview_title;
            }
            $mycta = isset($wpaicg_languages['mycta']) ? sprintf($wpaicg_languages['mycta'], $wpai_preview_title,$wpai_target_url_cta) : '';
            $wpaicg_ai_model = get_option('wpaicg_ai_model','text-davinci-003');
            $complete = $open_ai->completion( [
                'model'             => $wpaicg_ai_model,
                'prompt'            => $myprompt,
                'temperature'       => $temperature,
                'max_tokens'        => $max_tokens,
                'frequency_penalty' => $frequency_penalty,
                'presence_penalty'  => $presence_penalty,
                'top_p'             => $top_p,
                'best_of'           => $best_of,
            ] );
            $complete = json_decode( $complete );
            // we need to catch the error here

            if ( isset( $complete->error ) ) {
                $complete = $complete->error->message;
                $wpaicg_result['error'] = $complete;
                return $wpaicg_result;
            } else {
                $wpaicg_result['tokens'] += $complete->usage->total_tokens;
                $wpaicg_result['length'] += self::wpaicg_count_words($complete->choices[0]->text);
                $complete = $complete->choices[0]->text;
            }

            // trim the text
            $complete = trim( $complete );
            $complete=preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$complete)));
            $mylist = preg_split( "/\r\n|\n|\r/", $complete );
            // delete 1. 2. 3. etc from beginning of the line
            $mylist = preg_replace( '/^\\d+\\.\\s/', '', $mylist );
            // delete if there is a dot at the end of the line
            $mylist = preg_replace( '/\\.$/', '', $mylist );
            $allresults = "";
            $wpai_heading_tag = sanitize_text_field( $open_ai->wpai_heading_tag );
            $wpai_modify_headings = 0;
            $is_generate_continue = intval( sanitize_text_field(@$_REQUEST["is_generate_continue"]) );
            $hfHeadings = sanitize_text_field( @$_REQUEST["hfHeadings"] );
            $hfHeadings2 = explode( ",", $hfHeadings );

            if ( $wpai_modify_headings == 1 && $is_generate_continue == 0 ) {
                $content_headings = '';
                foreach ( $mylist as $key => $value ) {
                    $content_headings .=  '<' . $wpai_heading_tag . '>' . $value . '</' . $wpai_heading_tag . '>' ;
                }
                return $content_headings;
            } else {

                if ( $wpai_modify_headings == 1 && $is_generate_continue == 1 ) {
                    $mylist = $hfHeadings2;
                } else {
                }

            }

            foreach ( $mylist as $key => $value ) {
                $withstyle = $value . '. ' . $style_text . ', ' . $tone_text . '.';
                // if avoid is not empty add it to the prompt
                if ( !empty(${$wpai_words_to_avoid}) ) {
                    $withstyle = $value . '. ' . $style_text . ', ' . $tone_text . ', ' . $avoid_text . '.';
                }
                $complete = $open_ai->completion( [
                    'model'             => $wpaicg_ai_model,
                    'prompt'            => $withstyle,
                    'temperature'       => $temperature,
                    'max_tokens'        => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty'  => $presence_penalty,
                    'top_p'             => $top_p,
                    'best_of'           => $best_of,
                ] );
                $complete = json_decode( $complete );
                if ( isset( $complete->error ) ) {
                    $wpaicg_result['error'] = trim($complete->error->message);
                    return $wpaicg_result;
                }
                else {
                    $wpaicg_result['tokens'] += $complete->usage->total_tokens;
                    $wpaicg_result['length'] += self::wpaicg_count_words($complete->choices[0]->text);
                    $complete = $complete->choices[0]->text;
                    // trim the text
                    $complete = trim($complete);
                    $value = str_replace('\\/', '', $value);
                    $value = str_replace('\\', '', $value);
                    // trim value
                    $value = trim($value);
                    // we will add h tag if the user wants to
                    $wpaicg_heading_id = 'wpaicg-'.sanitize_title($value);
                    $wpaicg_toc_list[] = $value;
                    if ( $wpai_heading_tag == "h1" ) {
                        $result = "<h1 id=\"$wpaicg_heading_id\">" . $value . "</h1>" . $complete;
                    } elseif ( $wpai_heading_tag == "h2" ) {
                        $result = "<h2 id=\"$wpaicg_heading_id\">" . $value . "</h2>" . $complete;
                    } elseif ( $wpai_heading_tag == "h3" ) {
                        $result = "<h3 id=\"$wpaicg_heading_id\">" . $value . "</h3>" . $complete;
                    } elseif ( $wpai_heading_tag == "h4" ) {
                        $result = "<h4 id=\"$wpaicg_heading_id\">" . $value . "</h4>" . $complete;
                    } elseif ( $wpai_heading_tag == "h5" ) {
                        $result = "<h5 id=\"$wpaicg_heading_id\">" . $value . "</h5>" . $complete;
                    } elseif ( $wpai_heading_tag == "h6" ) {
                        $result = "<h6 id=\"$wpaicg_heading_id\">" . $value . "</h6>" . $complete;
                    } else {
                        $result = "<h2 id=\"$wpaicg_heading_id\">" . $value . "</h2>" . $complete;
                    }

                    $allresults = $allresults . $result;
                }
            }
            //if myintro is not empty,calls the openai api

            if ( $wpai_add_intro == "1" ) {
                $completeintro = $open_ai->completion( [
                    'model'             => $wpaicg_ai_model,
                    'prompt'            => $myintro,
                    'temperature'       => $temperature,
                    'max_tokens'        => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty'  => $presence_penalty,
                    'top_p'             => $top_p,
                    'best_of'           => $best_of,
                ] );
                $completeintro = json_decode( $completeintro );
                // we need to catch the error here

                if ( isset( $completeintro->error ) ) {
                    $completeintro = $completeintro->error->message;
                    // exit
                    $wpaicg_result['error'] = $completeintro;
                    return $wpaicg_result;
                } else {
                    $wpaicg_result['tokens'] += $completeintro->usage->total_tokens;
                    $wpaicg_result['length'] += self::wpaicg_count_words($completeintro->choices[0]->text);
                    $completeintro = $completeintro->choices[0]->text;
                    // trim the text
                    $completeintro = trim( $completeintro );
                    $wpaicg_toc_list_new = array($introduction);
                    foreach($wpaicg_toc_list as $wpaicg_toc_item){
                        $wpaicg_toc_list_new[] = $wpaicg_toc_item;
                    }
                    $wpaicg_toc_list = $wpaicg_toc_list_new;
                    $wpaicg_introduction_id = 'wpaicg-'.sanitize_title($introduction);
                    // add wpaicg_intro_title_tag to the intro
                    $completeintro = '<'.$wpaicg_intro_title_tag.' id="'.$wpaicg_introduction_id.'">'.$introduction.'</'.$wpaicg_intro_title_tag.'>'.$completeintro;
                    // original: $completeintro = "<h1 id=\"$wpaicg_introduction_id\">" . $introduction . "</h1>" . $completeintro;
                    // add intro to the beginning of the text
                    $allresults = $completeintro . $allresults;
                }

            }

            // if wpai_add_faq is checked then call api with faq prompt

            if ( $wpai_add_faq == "1" ) {
                $completefaq = $open_ai->completion( [
                    'model'             => $wpaicg_ai_model,
                    'prompt'            => $faq_text,
                    'temperature'       => $temperature,
                    'max_tokens'        => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty'  => $presence_penalty,
                    'top_p'             => $top_p,
                    'best_of'           => $best_of,
                ] );
                $completefaq = json_decode( $completefaq );
                // we need to catch the error here

                if ( isset( $completefaq->error ) ) {
                    $completefaq = $completefaq->error->message;
                    // exit
                    $wpaicg_result['error'] = $completefaq;
                    return $wpaicg_result;
                } else {
                    $wpaicg_result['tokens'] += $completefaq->usage->total_tokens;
                    $wpaicg_result['length'] += self::wpaicg_count_words($completefaq->choices[0]->text);
                    $completefaq = $completefaq->choices[0]->text;
                    // trim the text
                    $completefaq = trim( $completefaq );
                    // add <h1>FAQ</h1> to the beginning of the text
                    $wpaicg_toc_list[] = $faq_heading;
                    $wpaicg_faq_id = 'wpaicg-'.sanitize_title($faq_heading);
                    $completefaq = "<h2 id=\"$wpaicg_faq_id\">" . $faq_heading . "</h2>" . $completefaq;
                    // add intro to the beginning of the text
                    $allresults = $allresults . $completefaq;
                }

            }

            //if myconclusion is not empty,calls the openai api

            if ( $wpai_add_conclusion == "1" ) {
                $completeconclusion = $open_ai->completion( [
                    'model'             => $wpaicg_ai_model,
                    'prompt'            => $myconclusion,
                    'temperature'       => $temperature,
                    'max_tokens'        => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty'  => $presence_penalty,
                    'top_p'             => $top_p,
                    'best_of'           => $best_of,
                ] );
                $completeconclusion = json_decode( $completeconclusion );
                // we need to catch the error here

                if ( isset( $completeconclusion->error ) ) {
                    $completeconclusion = $completeconclusion->error->message;
                    // exit
                    $wpaicg_result['error'] = $completeconclusion;
                    return $wpaicg_result;
                } else {
                    $wpaicg_result['tokens'] += $completeconclusion->usage->total_tokens;
                    $wpaicg_result['length'] += self::wpaicg_count_words($completeconclusion->choices[0]->text);
                    $completeconclusion = $completeconclusion->choices[0]->text;
                    // trim the text
                    $completeconclusion = trim( $completeconclusion );
                    // add <h1>Conclusion</h1> to the beginning of the text
                    $wpaicg_toc_list[] = $conclusion;
                    $wpaicg_conclusion_id = 'wpaicg-'.sanitize_title($conclusion);
                    // wpaicg_conclusion_title_tag
                    $completeconclusion = '<'.$wpaicg_conclusion_title_tag.' id="'.$wpaicg_conclusion_id.'">'.$conclusion.'</'.$wpaicg_conclusion_title_tag.'>'.$completeconclusion;
                    // original $completeconclusion = "<h1 id=\"$wpaicg_conclusion_id\">" . $conclusion . "</h1>" . $completeconclusion;
                    // add intro to the beginning of the text
                    $allresults = $allresults . $completeconclusion;
                }

            }

            // wpai_add_tagline is checked then call the openai api

            if ( $wpai_add_tagline == "1" ) {
                $completetagline = $open_ai->completion( [
                    'model'             => $wpaicg_ai_model,
                    'prompt'            => $mytagline,
                    'temperature'       => $temperature,
                    'max_tokens'        => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty'  => $presence_penalty,
                    'top_p'             => $top_p,
                    'best_of'           => $best_of,
                ] );
                $completetagline = json_decode( $completetagline );
                // we need to catch the error here

                if ( isset( $completetagline->error ) ) {
                    $completetagline = $completetagline->error->message;
                    // exit
                    $wpaicg_result['error'] = $completetagline;
                    return $wpaicg_result;
                } else {
                    $wpaicg_result['tokens'] += $completetagline->usage->total_tokens;
                    $wpaicg_result['length'] += self::wpaicg_count_words($completetagline->choices[0]->text);
                    $completetagline = $completetagline->choices[0]->text;
                    // trim the text
                    $completetagline = trim( $completetagline );
                    // add <p> to the beginning of the text
                    $completetagline = "<p>" . $completetagline . "</p>";
                    // add intro to the beginning of the text
                    $allresults = $completetagline . $allresults;
                }

            }

            // if wpai_add_keywords_bold is checked then then find all keywords and bold them. keywords are separated by comma
            if ( $wpai_add_keywords_bold == "1" ) {
                // check to see at least one keyword is entered

                if ( $wpai_keywords != "" ) {
                    // split keywords by comma if there are more than one but if there is only one then it will not split

                    if ( strpos( $wpai_keywords, ',' ) !== false ) {
                        $keywords = explode( ",", $wpai_keywords );
                    } else {
                        $keywords = array( $wpai_keywords );
                    }

                    // loop through keywords and bold them
                    foreach ( $keywords as $keyword ) {
                        $keyword = trim( $keyword );
                        // replace keyword with bold keyword but make sure exact match is found. for example if the keyword is "the" then it should not replace "there" with "there".. capital dont matter
                        $allresults = preg_replace( '/\\b' . $keyword . '\\b/', '<strong>' . $keyword . '</strong>', $allresults );
                    }
                }

            }
            // if wpai_target_url and wpai_anchor_text is not empty then find wpai_anchor_text in the text and create a link using wpai_target_url
            if ( $wpai_target_url != "" && $wpai_anchor_text != "" ) {
                // create a link if anchor text found.. rules: 1. only for first occurance 2. exact match 3. case insensitive 4. if anchor text found inside any h1,h2,h3,h4,h5,h6, a then skip it. 5. use anchor text to create link dont replace it with existing text
                $allresults = preg_replace(
                    '/(?<!<h[1-6]><a href=")(?<!<a href=")(?<!<h[1-6]>)(?<!<h[1-6]><strong>)(?<!<strong>)(?<!<h[1-6]><em>)(?<!<em>)(?<!<h[1-6]><strong><em>)(?<!<strong><em>)(?<!<h[1-6]><em><strong>)(?<!<em><strong>)\\b' . $wpai_anchor_text . '\\b(?![^<]*<\\/a>)(?![^<]*<\\/h[1-6]>)(?![^<]*<\\/strong>)(?![^<]*<\\/em>)(?![^<]*<\\/strong><\\/em>)(?![^<]*<\\/em><\\/strong>)/i',
                    '<a href="' . $wpai_target_url . '">' . $wpai_anchor_text . '</a>',
                    $allresults,
                    1
                );
            }
            // if wpai_target_url_cta is not empty then call api to get cta text and create a link using wpai_target_url_cta

            if ( $wpai_target_url_cta != "" ) {
                // call api to get cta text
                $completecta = $open_ai->completion( [
                    'model'             => $wpaicg_ai_model,
                    'prompt'            => $mycta,
                    'temperature'       => $temperature,
                    'max_tokens'        => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty'  => $presence_penalty,
                    'top_p'             => $top_p,
                    'best_of'           => $best_of,
                ] );
                $completecta = json_decode( $completecta );
                // we need to catch the error here

                if ( isset( $completecta->error ) ) {
                    $completecta = $completecta->error->message;
                    // exit
                    $wpaicg_result['error'] = $completecta;
                    return $wpaicg_result;
                } else {
                    $wpaicg_result['tokens'] += $completecta->usage->total_tokens;
                    $wpaicg_result['length'] += self::wpaicg_count_words($completecta->choices[0]->text);
                    $completecta = $completecta->choices[0]->text;
                    // trim the text
                    $completecta = trim( $completecta );
                    // add <p> to the beginning of the text
                    $completecta = "<p>" . $completecta . "</p>";

                    if ( $wpai_cta_pos == "beg" ) {
                        $allresults = preg_replace(
                            '/(<h[1-6]>)/',
                            $completecta . ' $1',
                            $allresults,
                            1
                        );
                    } else {
                        $allresults = $allresults . $completecta;
                    }

                }

            }
            // Generate Meta description
            $_wpaicg_seo_meta_desc = get_option('_wpaicg_seo_meta_desc',false);
            if($_wpaicg_seo_meta_desc) {
                $meta_desc_prompt = isset($wpai_languages['meta_desc_prompt']) && !empty($wpai_languages['meta_desc_prompt']) ? sprintf($wpai_languages['meta_desc_prompt'], $wpai_preview_title) : 'Write a meta description about: ' . $wpai_preview_title.'. Max: 155 characters.';
                $completeseo = $open_ai->completion([
                    'model' => $wpaicg_ai_model,
                    'prompt' => $meta_desc_prompt,
                    'temperature' => $temperature,
                    'max_tokens' => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty' => $presence_penalty,
                    'top_p' => $top_p,
                    'best_of' => $best_of,
                ]);
                $completeseo = json_decode($completeseo);
                if (isset($completeseo->error)) {
                    $completeseo = $completeseo->error->message;
                    $wpaicg_result['error'] = esc_html($completeseo);
                } else {
                    $completeseo = $completeseo->choices[0]->text;
                    $wpaicg_result['description'] = trim( $completeseo );
                }
            }
            // if add image is checked then we should send api request to get image
            if ( !empty($wpaicg_image_source)) {
                $imgresult_url = false;
                if($wpaicg_image_source == 'dalle') {
                    $_wpaicg_image_style = get_option('_wpaicg_image_style', '');
                    if (!empty($_wpaicg_image_style)) {
                        $_wpaicg_art_style = isset($wpaicg_languages['art_style']) && !empty($wpaicg_languages['art_style']) ? ' ' . $wpaicg_languages['art_style'] : '';
                        $_wpaicg_image_style = isset($wpaicg_languages['img_styles'][$_wpaicg_image_style]) && !empty($wpaicg_languages['img_styles'][$_wpaicg_image_style]) ? ' ' . $wpaicg_languages['img_styles'][$_wpaicg_image_style] : '';
                    }
                    $imgresult = $open_ai->image([
                        "prompt" => $wpai_preview_title . $_wpaicg_art_style . $_wpaicg_image_style,
                        "n" => 1,
                        "size" => $img_size,
                        "response_format" => "url",
                    ]);
                    // we need to get url from above string.
                    $imgresult = json_decode($imgresult);
                    if (isset($imgresult->error)) {

                    } else {
                        $imgresult_url = $imgresult->data[0]->url;
                    }
                }
                if($wpaicg_image_source == 'pexels' && !empty($wpaicg_pexels_api)){
                    $wpaicg_pexels_response = self::wpaicg_pexels_generator($wpaicg_pexels_api,$wpai_preview_title, $wpaicg_pexels_orientation, $wpaicg_pexels_size);
                    $wpaicg_result['status'] = $wpaicg_pexels_response['status'];
                    if($wpaicg_pexels_response['status'] == 'error'){
                        $wpaicg_result['msg'] = $wpaicg_pexels_response['msg'];
                    }
                    else{
                        $imgresult_url = trim($wpaicg_pexels_response['pexels_reponse']);
                    }
                }
                if($imgresult_url){
                    $wpaicg_result['img'] = trim($imgresult_url);
                    $imgresult = "__WPAICG_IMAGE__";
                    // get half of wpai_number_of_heading and insert image in the middle
                    $half = intval($wpai_number_of_heading) / 2;
                    $half = round($half);
                    $half = $half - 1;
                    // use wpai_heading_tag to add heading tag to image
                    $allresults = explode("</" . $wpai_heading_tag . ">", $allresults);
                    $allresults[$half] = $allresults[$half] . $imgresult;
                    $allresults = implode("</" . $wpai_heading_tag . ">", $allresults);
                }
            }
            if(!empty($wpaicg_featured_image_source)){
                $imgresult_featured_url = false;
                if($wpaicg_featured_image_source == 'dalle') {
                    $_wpaicg_image_style = get_option('_wpaicg_image_style', '');
                    if (!empty($_wpaicg_image_style)) {
                        $_wpaicg_art_style = isset($wpaicg_languages['art_style']) && !empty($wpaicg_languages['art_style']) ? ' ' . $wpaicg_languages['art_style'] : '';
                        $_wpaicg_image_style = isset($wpaicg_languages['img_styles'][$_wpaicg_image_style]) && !empty($wpaicg_languages['img_styles'][$_wpaicg_image_style]) ? ' ' . $wpaicg_languages['img_styles'][$_wpaicg_image_style] : '';
                    }
                    $imgresult = $open_ai->image([
                        "prompt" => $wpai_preview_title . $_wpaicg_art_style . $_wpaicg_image_style,
                        "n" => 1,
                        "size" => $img_size,
                        "response_format" => "url",
                    ]);
                    // we need to get url from above string.
                    $imgresult = json_decode($imgresult);
                    if (isset($imgresult->error)) {

                    } else {
                        $imgresult_featured_url = $imgresult->data[0]->url;
                    }
                }
                if($wpaicg_featured_image_source == 'pexels' && !empty($wpaicg_pexels_api)){
                    $wpaicg_pexels_response = self::wpaicg_pexels_generator($wpaicg_pexels_api,$wpai_preview_title, $wpaicg_pexels_orientation, $wpaicg_pexels_size);
                    $wpaicg_result['status'] = $wpaicg_pexels_response['status'];
                    if($wpaicg_pexels_response['status'] == 'error'){
                        $wpaicg_result['msg'] = $wpaicg_pexels_response['msg'];
                    }
                    else{
                        $imgresult_featured_url = trim($wpaicg_pexels_response['pexels_reponse']);
                    }
                }
                if($imgresult_featured_url){
                    $wpaicg_result['featured_img'] = $imgresult_featured_url;
                }
            }
            if($wpaicg_toc == '1' && is_array($wpaicg_toc_list) && count($wpaicg_toc_list)){
                $wpaicg_table_content = '<ul class="wpaicg_toc"><li>';
                if($wpaicg_toc_title != ''){
                    $wpaicg_table_content .= '<'.$wpaicg_toc_title_tag.'>'.$wpaicg_toc_title.'</'.$wpaicg_toc_title_tag.'>';
                }
                $wpaicg_table_content .= '<ul>';
                foreach($wpaicg_toc_list as $wpaicg_toc_item){
                    $wpaicg_toc_item_id = 'wpaicg-'.sanitize_title($wpaicg_toc_item);
                    $wpaicg_table_content .= '<li><a href="#'.$wpaicg_toc_item_id.'">'.$wpaicg_toc_item.'</a></li>';
                }
                $wpaicg_table_content .= '</ul>';
                $wpaicg_table_content .= '</li></ul>';
                $allresults = $wpaicg_table_content.$allresults;
            }

            $wpaicg_result['content'] = $allresults;
//            var_dump($wpaicg_result);
//            var_dump($wpaicg_image_source);
//            var_dump($wpaicg_featured_image_source);
            return $wpaicg_result;

        }
    }
}
