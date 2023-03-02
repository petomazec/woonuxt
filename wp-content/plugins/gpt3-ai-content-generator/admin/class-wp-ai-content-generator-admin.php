<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://gptaipower.com
 * @since      1.0.0
 *
 * @package    Wp_Ai_Content_Generator
 * @subpackage Wp_Ai_Content_Generator/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Ai_Content_Generator
 * @subpackage Wp_Ai_Content_Generator/admin
 * @author     Senol Sahin <senols@gmail.com>
 */
class Wp_Ai_Content_Generator_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/wp-ai-content-generator-admin.css',
            array(),
            $this->version,
            'all'
        );
        $screen = get_current_screen();
        if(strpos($screen->id, 'wpaicg') !== false) {
            wp_enqueue_style(
                'jquery-ui',
                plugin_dir_url(__FILE__) . 'css/jquery-ui.css',
                array(),
                $this->version,
                'all'
            );
        }
        wp_enqueue_style(
            'font-awesome',
            plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/wp-ai-content-generator-admin.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-tabs' );
        wp_enqueue_script( 'jquery-ui-accordion' );
    }

    public function wpaicg_load_db_vaule()
    {
        global  $wpdb ;
        ini_set( 'max_execution_time', 1000 );
        $wpaicg_result = array(
            'status'    => 'error',
            'msg'       => 'Something went wrong',
            'data'      => '',
            'content'   => '',
            'next_step' => 'content',
            'img'       => '',
            'featured_img'       => '',
            'description'       => '',
            'tocs' => ''
        );
        if(isset($_REQUEST['generated_img']) && !empty($_REQUEST['generated_img'])){
            $wpaicg_result['img'] = sanitize_text_field($_REQUEST['generated_img']);
        }
        if(isset($_REQUEST['featured_img']) && !empty($_REQUEST['featured_img'])){
            $wpaicg_result['featured_img'] = sanitize_text_field($_REQUEST['featured_img']);
        }
        $open_ai = \WPAICG\WPAICG_OpenAI::get_instance()->openai();
        if(!$open_ai){
            $wpaicg_result['msg'] = 'Missing API Setting';
            wp_send_json($wpaicg_result);
            exit;
        }
        $temperature = floatval( $open_ai->temperature );
        $max_tokens = intval( $open_ai->max_tokens );
        $top_p = floatval( $open_ai->top_p );
        $best_of = intval( $open_ai->best_of );
        $frequency_penalty = floatval( $open_ai->frequency_penalty );
        $presence_penalty = floatval( $open_ai->presence_penalty );
        $img_size = $open_ai->img_size;
        $wpai_preview_title = sanitize_text_field( $_REQUEST["wpai_preview_title"] );
        $wpai_number_of_heading = sanitize_text_field( $_REQUEST["wpai_number_of_heading"] );
        $wpaicg_image_source = sanitize_text_field($_REQUEST['wpaicg_image_source']);
        $wpaicg_featured_image_source = sanitize_text_field($_REQUEST['wpaicg_featured_image_source']);
        $wpai_language = sanitize_text_field( $_REQUEST["wpai_language"] );
        $wpai_add_intro = intval( sanitize_text_field($_REQUEST["wpai_add_intro"] ));
        $wpai_add_conclusion = intval( sanitize_text_field($_REQUEST["wpai_add_conclusion"] ));
        $wpai_writing_style = sanitize_text_field( $_REQUEST["wpai_writing_style"] );
        $wpai_writing_tone = sanitize_text_field( $_REQUEST["wpai_writing_tone"] );
        $wpai_keywords = sanitize_text_field( $_REQUEST["wpai_keywords"] );
        $wpai_add_keywords_bold = intval( sanitize_text_field($_REQUEST["wpai_add_keywords_bold"] ));
        $wpai_heading_tag = sanitize_text_field( $_REQUEST["wpai_heading_tag"] );
        $wpai_words_to_avoid = sanitize_text_field( $_REQUEST["wpai_words_to_avoid"] );
        $wpai_add_tagline = intval( sanitize_text_field($_REQUEST["wpai_add_tagline"] ));
        $wpai_add_faq = intval( sanitize_text_field($_REQUEST["wpai_add_faq"] ));
        $wpaicg_seo_meta_desc = intval( sanitize_text_field($_REQUEST["wpaicg_seo_meta_desc"] ));
        $wpai_target_url = sanitize_text_field( $_REQUEST["wpai_target_url"] );
        $wpai_anchor_text = sanitize_text_field( $_REQUEST["wpai_anchor_text"] );
        $wpai_cta_pos = sanitize_text_field( $_REQUEST["wpai_cta_pos"] );
        $wpai_target_url_cta = sanitize_text_field( $_REQUEST["wpai_target_url_cta"] );
        $wpai_img_size = sanitize_text_field( $_REQUEST["wpai_img_size"] );
        $wpai_img_size = ( empty($wpai_img_size) ? $img_size : $wpai_img_size );
        $wpai_img_style = sanitize_text_field( $_REQUEST["wpai_img_style"] );
        $_wpaicg_image_style = get_option( '_wpaicg_image_style', '' );
        $wpai_img_style = ( empty($wpai_img_style) ? $_wpaicg_image_style : $wpai_img_style );
        $wpai_modify_headings = intval( sanitize_text_field($_REQUEST["wpai_modify_headings"] ));

        /*
         * Multi Thread Ajax Request
         * */
        $wpaicg_step = ( isset( $_REQUEST['step'] ) && !empty($_REQUEST['step']) ? sanitize_text_field( $_REQUEST['step'] ) : 'heading' );
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
        $wpaicg_language_file = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/languages/' . $wpai_language . '.json';
        if ( !file_exists( $wpaicg_language_file ) ) {
            $wpaicg_language_file = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/languages/en.json';
        }
        $wpaicg_language_json = file_get_contents( $wpaicg_language_file );
        $wpaicg_languages = json_decode( $wpaicg_language_json, true );
        $writing_style = ( isset( $wpaicg_languages['writing_style'][$wpai_writing_style] ) ? $wpaicg_languages['writing_style'][$wpai_writing_style] : 'infor' );
        $tone_text = ( isset( $wpaicg_languages['writing_tone'][$wpai_writing_tone] ) ? $wpaicg_languages['writing_tone'][$wpai_writing_tone] : 'formal' );

        if ( $wpai_number_of_heading == 1 ) {
            $prompt_text = ( isset( $wpaicg_languages['prompt_text_1'] ) ? $wpaicg_languages['prompt_text_1'] : '' );
        } else {
            $prompt_text = ( isset( $wpaicg_languages['prompt_text'] ) ? $wpaicg_languages['prompt_text'] : '' );
        }

        $intro_text = ( isset( $wpaicg_languages['intro_text'] ) ? $wpaicg_languages['intro_text'] : '' );
        $conclusion_text = ( isset( $wpaicg_languages['conclusion_text'] ) ? $wpaicg_languages['conclusion_text'] : '' );
        $tagline_text = ( isset( $wpaicg_languages['tagline_text'] ) ? $wpaicg_languages['tagline_text'] : '' );
        $introduction = ( isset( $wpaicg_languages['introduction'] ) ? $wpaicg_languages['introduction'] : '' );
        $conclusion = ( isset( $wpaicg_languages['conclusion'] ) ? $wpaicg_languages['conclusion'] : '' );

        if ( $wpai_language == 'hi' || $wpai_language == 'tr' || $wpai_language == 'ja' || $wpai_language == 'zh' || $wpai_language == 'ko' ) {
            $faq_text = ( isset( $wpaicg_languages['faq_text'] ) ? sprintf( $wpaicg_languages['faq_text'], $wpai_preview_title, strval( $wpai_number_of_heading ) ) : '' );
        } else {
            $faq_text = ( isset( $wpaicg_languages['faq_text'] ) ? sprintf( $wpaicg_languages['faq_text'], strval( $wpai_number_of_heading ), $wpai_preview_title ) : '' );
        }

        $faq_heading = ( isset( $wpaicg_languages['faq_heading'] ) ? $wpaicg_languages['faq_heading'] : '' );
        $style_text = ( isset( $wpaicg_languages['style_text'] ) ? sprintf( $wpaicg_languages['style_text'], $writing_style ) : '' );
        $of_text = ( isset( $wpaicg_languages['of_text'] ) ? $wpaicg_languages['of_text'] : '' );
        $prompt_last = ( isset( $wpaicg_languages['prompt_last'] ) ? $wpaicg_languages['prompt_last'] : '' );
        $piece_text = ( isset( $wpaicg_languages['piece_text'] ) ? $wpaicg_languages['piece_text'] : '' );

        if ( $wpai_language == 'ru' || $wpai_language == 'ko' ) {

            if ( empty($wpai_keywords) ) {
                $myprompt = $prompt_text . strval( $wpai_number_of_heading ) . $prompt_last . $wpai_preview_title . ".";
            } else {
                $keyword_text = ( isset( $wpaicg_languages['keyword_text'] ) ? sprintf( $wpaicg_languages['keyword_text'], $wpai_keywords ) : '' );
                $myprompt = $prompt_text . strval( $wpai_number_of_heading ) . $prompt_last . $wpai_preview_title . $keyword_text;
            }

        } elseif ( $wpai_language == 'zh' ) {

            if ( empty($wpai_keywords) ) {
                $myprompt = $prompt_text . $wpai_preview_title . $of_text . strval( $wpai_number_of_heading ) . $piece_text . ".";
            } else {
                $keyword_text = ( isset( $wpaicg_languages['keyword_text'] ) ? sprintf( $wpaicg_languages['keyword_text'], $wpai_keywords ) : '' );
                $myprompt = $prompt_text . $wpai_preview_title . $of_text . strval( $wpai_number_of_heading ) . $piece_text . $keyword_text;
            }

        } elseif ( $wpai_language == 'ja' || $wpai_language == 'hi' || $wpai_language == 'tr' ) {

            if ( empty($wpai_keywords) ) {
                $myprompt = $wpai_preview_title . $prompt_text . strval( $wpai_number_of_heading ) . $prompt_last . ".";
            } else {
                $keyword_text = ( isset( $wpaicg_languages['keyword_text'] ) ? sprintf( $wpaicg_languages['keyword_text'], $wpai_keywords ) : '' );
                $myprompt = $wpai_preview_title . $prompt_text . strval( $wpai_number_of_heading ) . $prompt_last . $keyword_text;
            }

        } else {

            if ( empty($wpai_keywords) ) {
                $myprompt = strval( $wpai_number_of_heading ) . $prompt_text . $wpai_preview_title . ".";
            } else {
                $keyword_text = ( isset( $wpaicg_languages['keyword_text'] ) ? sprintf( $wpaicg_languages['keyword_text'], $wpai_keywords ) : '' );
                $myprompt = strval( $wpai_number_of_heading ) . $prompt_text . $wpai_preview_title . $keyword_text;
            }

        }


        if ( !empty($wpai_words_to_avoid) ) {
            $avoid_text = ( isset( $wpaicg_languages['avoid_text'] ) ? sprintf( $wpaicg_languages['avoid_text'], $wpai_words_to_avoid ) : '' );
            $myprompt = $myprompt . $avoid_text;
        }


        if ( $wpai_language == 'ja' || $wpai_language == 'tr' ) {
            $myintro = $wpai_preview_title . $intro_text;
            $myconclusion = $wpai_preview_title . $conclusion_text;
            $mytagline = $wpai_preview_title . $tagline_text;
        } else {

            if ( $wpai_language == 'ko' || $wpai_language == 'hi' || $wpai_language == 'ar' ) {
                $myintro = $intro_text . $wpai_preview_title;
                $myconclusion = $conclusion_text . $wpai_preview_title;
                $mytagline = $wpai_preview_title . $tagline_text;
            } else {
                $myintro = $intro_text . $wpai_preview_title;
                $myconclusion = $conclusion_text . $wpai_preview_title;
                $mytagline = $tagline_text . $wpai_preview_title;
            }

        }

        $mycta = ( isset( $wpaicg_languages['mycta'] ) ? sprintf( $wpaicg_languages['mycta'], $wpai_preview_title, $wpai_target_url_cta ) : '' );
        $wpai_heading_tag = sanitize_text_field( $_REQUEST["wpai_heading_tag"] );
        $wpai_modify_headings = intval( sanitize_text_field($_REQUEST["wpai_modify_headings"] ));
        $is_generate_continue = intval( sanitize_text_field($_REQUEST["is_generate_continue"] ));
        $hfHeadings = sanitize_text_field( $_REQUEST["hfHeadings"] );
        $hfHeadings2 = explode( "||", $hfHeadings );
        $wpaicg_ai_model = get_option('wpaicg_ai_model','text-davinci-003');
        $wpaicg_toc = intval(sanitize_text_field($_REQUEST['wpaicg_toc']));
        $wpaicg_toc_title = sanitize_text_field($_REQUEST['wpaicg_toc_title']);
        $wpaicg_toc_title = empty($wpaicg_toc_title) ? 'Table of Contents' : $wpaicg_toc_title;
        $wpaicg_toc_title_tag = sanitize_text_field($_REQUEST['wpaicg_toc_title_tag']);
        $wpaicg_toc_title_tag = empty($wpaicg_toc_title_tag) ? 'h2' : $wpaicg_toc_title_tag;
        $wpaicg_intro_title_tag = sanitize_text_field($_REQUEST['wpaicg_intro_title_tag']);
        $wpaicg_intro_title_tag = empty($wpaicg_intro_title_tag) ? 'h2' : $wpaicg_intro_title_tag;
        $wpaicg_conclusion_title_tag = sanitize_text_field($_REQUEST['wpaicg_conclusion_title_tag']);
        $wpaicg_conclusion_title_tag = empty($wpaicg_conclusion_title_tag) ? 'h2' : $wpaicg_conclusion_title_tag;
        $wpaicg_toc_list = isset($_REQUEST['wpaicg_toc_list']) && !empty($_REQUEST['wpaicg_toc_list']) ? explode(',',sanitize_text_field($_REQUEST['wpaicg_toc_list'])) : array();
        /*Generate Heading*/
        if ( $wpaicg_step == 'heading' ) {

            if ( $wpai_modify_headings == 1 && $is_generate_continue == 1 ) {
                $mylist = $hfHeadings2;
                $wpaicg_result['next_step'] = 'content';
                $wpaicg_result['data'] = $hfHeadings;
                $wpaicg_result['status'] = 'success';
            } else {
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

                if ( isset( $complete->error ) ) {
                    $complete = $complete->error->message;
                    // exit
                    $wpaicg_result['msg'] = esc_html( $complete );
                } else {
                    if(isset($complete->choices) && is_array($complete->choices)) {
                        $complete = $complete->choices[0]->text;
                        $complete = trim($complete);
                        $complete = preg_replace('/\n$/', '', preg_replace('/^\n/', '', preg_replace('/[\r\n]+/', "\n", $complete)));
                        $mylist = preg_split("/\r\n|\n|\r/", $complete);
                        // delete 1. 2. 3. etc from beginning of the line
                        $mylist = preg_replace('/^\\d+\\.\\s/', '', $mylist);
                        // delete if there is a dot at the end of the line
                        $mylist = preg_replace('/\\.$/', '', $mylist);
                        $mylist = array_splice($mylist, 0, strval($wpai_number_of_heading));
                        $wpaicg_result['next_step'] = 'content';
                        $wpaicg_result['data'] = implode('||', $mylist);
                        $wpaicg_result['status'] = 'success';
                        if ($wpai_modify_headings == 1 && $is_generate_continue == 0) {
                            $wpaicg_result['next_step'] = 'modify_heading';
                        }
                    }
                    else{
                        $wpaicg_result['msg'] = 'OpenAI returned empty response for this request. Please try again.';
                        wp_send_json($wpaicg_result);
                    }
                }

            }

        }
        /*
         * Generate Content
         * */

        if ( $wpaicg_step == 'content' ) {
            $mylist = $hfHeadings2;
            $allresults = '';
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
                $complete = $complete->choices[0]->text;
                // trim the text
                $complete = trim( $complete );
                $value = str_replace( '\\/', '', $value );
                $value = str_replace( '\\', '', $value );
                // trim value
                $value = trim( $value );
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

                $allresults .= $result;
            }
            $wpaicg_result['content'] = $allresults;
            $wpaicg_result['status'] = 'success';
            $wpaicg_result['next_step'] = 'intro';
        }

        /*
         * Generato Intro
         * */
        $wpaicg_allowed_html_content_post = wp_kses_allowed_html( 'post' );

        if ( $wpaicg_step == 'intro' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );

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
                    $wpaicg_result['msg'] = esc_html( $completeintro );
                } else {
                    $completeintro = $completeintro->choices[0]->text;
                    // trim the text
                    $completeintro = trim( $completeintro );
                    // add <h1>Introuction</h1> to the beginning of the text
                    $wpaicg_toc_list_new = array($introduction);
                    foreach($wpaicg_toc_list as $wpaicg_toc_item){
                        $wpaicg_toc_list_new[] = $wpaicg_toc_item;
                    }
                    $wpaicg_toc_list = $wpaicg_toc_list_new;
                    $wpaicg_introduction_id = 'wpaicg-'.sanitize_title($introduction);
                    // $wpaicg_intro_title_tag
                    $completeintro = '<'.$wpaicg_intro_title_tag.' id="'.$wpaicg_introduction_id.'">'.$introduction.'</'.$wpaicg_intro_title_tag.'>'.$completeintro;
                    // original: $completeintro = "<h1 id=\"$wpaicg_introduction_id\">" . $introduction . "</h1>" . $completeintro;
                    // add intro to the beginning of the text
                    $wpaicg_content = $completeintro . $wpaicg_content;
                    $wpaicg_result['content'] = $wpaicg_content;
                    $wpaicg_result['status'] = 'success';
                    $wpaicg_result['next_step'] = 'faq';
                }

            } else {
                $wpaicg_result['content'] = $wpaicg_content;
                $wpaicg_result['status'] = 'success';
                $wpaicg_result['next_step'] = 'faq';
            }

        }

        /*
         * Generate FAQ
         * */

        if ( $wpaicg_step == 'faq' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );

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
                    $wpaicg_result['msg'] = esc_html( $completefaq );
                } else {
                    $completefaq = $completefaq->choices[0]->text;
                    // trim the text
                    $completefaq = trim( $completefaq );
                    // add <h1>FAQ</h1> to the beginning of the text
                    $wpaicg_toc_list[] = $faq_heading;
                    $wpaicg_faq_id = 'wpaicg-'.sanitize_title($faq_heading);
                    $completefaq = "<h2 id=\"$wpaicg_faq_id\">" . $faq_heading . "</h2>" . $completefaq;
                    // add intro to the beginning of the text
                    $wpaicg_content = $wpaicg_content . $completefaq;
                    $wpaicg_result['content'] = $wpaicg_content;
                    $wpaicg_result['status'] = 'success';
                    $wpaicg_result['next_step'] = 'conclusion';
                }

            } else {
                $wpaicg_result['content'] = $wpaicg_content;
                $wpaicg_result['status'] = 'success';
                $wpaicg_result['next_step'] = 'conclusion';
            }

        }

        /*
         * Generate Conclusion
         * */

        if ( $wpaicg_step == 'conclusion' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );

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
                    $wpaicg_result['msg'] = esc_html( $completeconclusion );
                } else {
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
                    $wpaicg_content = $wpaicg_content . $completeconclusion;
                    $wpaicg_result['content'] = $wpaicg_content;
                    $wpaicg_result['status'] = 'success';
                    $wpaicg_result['next_step'] = 'tagline';
                }

            } else {
                $wpaicg_result['content'] = $wpaicg_content;
                $wpaicg_result['status'] = 'success';
                $wpaicg_result['next_step'] = 'tagline';
            }

        }


        if ( $wpaicg_step == 'tagline' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );

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
                    $wpaicg_result['msg'] = esc_html( $completetagline );
                } else {
                    $completetagline = $completetagline->choices[0]->text;
                    // trim the text
                    $completetagline = trim( $completetagline );
                    // add <p> to the beginning of the text
                    $completetagline = "<p>" . $completetagline . "</p>";
                    // add intro to the beginning of the text
                    $wpaicg_content = $completetagline . $wpaicg_content;
                    $wpaicg_result['content'] = $wpaicg_content;
                    $wpaicg_result['status'] = 'success';
                    if ( $wpaicg_seo_meta_desc ) {
                        $wpaicg_result['next_step'] = 'seo';
                    } else {
                        $wpaicg_result['next_step'] = 'addition';
                    }
                }

            } else {
                $wpaicg_result['content'] = $wpaicg_content;
                $wpaicg_result['status'] = 'success';

                if ( $wpaicg_seo_meta_desc ) {
                    $wpaicg_result['next_step'] = 'seo';
                } else {
                    $wpaicg_result['next_step'] = 'addition';
                }

            }

        }


        if ( $wpaicg_step == 'seo' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );
            $wpaicg_result['status'] = 'success';
            if ( $wpaicg_seo_meta_desc ) {
                $meta_desc_prompt = ( isset( $wpai_languages['meta_desc_prompt'] ) && !empty($wpai_languages['meta_desc_prompt']) ? sprintf( $wpai_languages['meta_desc_prompt'], $wpai_preview_title ) : 'Write a meta description about: ' . $wpai_preview_title .'. Max: 155 characters');
                $completeseo = $open_ai->completion( [
                    'model'             => $wpaicg_ai_model,
                    'prompt'            => $meta_desc_prompt,
                    'temperature'       => $temperature,
                    'max_tokens'        => $max_tokens,
                    'frequency_penalty' => $frequency_penalty,
                    'presence_penalty'  => $presence_penalty,
                    'top_p'             => $top_p,
                    'best_of'           => $best_of,
                ] );
                $completeseo = json_decode( $completeseo );
                // we need to catch the error here

                if ( isset( $completeseo->error ) ) {
                    $completeseo = $completeseo->error->message;
                    $wpaicg_result['status'] = 'error';
                    $wpaicg_result['msg'] = esc_html( $completeseo );
                } else {
                    $completeseo = $completeseo->choices[0]->text;
                    $wpaicg_result['description'] = trim( $completeseo );
                }

            }

            $wpaicg_result['content'] = $wpaicg_content;
            $wpaicg_result['next_step'] = 'addition';
        }


        if ( $wpaicg_step == 'addition' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );
            if ( $wpai_add_keywords_bold == "1" ) {

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
                        $wpaicg_content = preg_replace( '/\\b' . $keyword . '\\b/', '<strong>' . $keyword . '</strong>', $wpaicg_content );
                    }
                }

            }
            if ( $wpai_target_url != "" && $wpai_anchor_text != "" ) {
                // create a link if anchor text found.. rules: 1. only for first occurance 2. exact match 3. case insensitive 4. if anchor text found inside any h1,h2,h3,h4,h5,h6, a then skip it. 5. use anchor text to create link dont replace it with existing text
                $wpaicg_content = preg_replace(
                    '/(?<!<h[1-6]><a href=")(?<!<a href=")(?<!<h[1-6]>)(?<!<h[1-6]><strong>)(?<!<strong>)(?<!<h[1-6]><em>)(?<!<em>)(?<!<h[1-6]><strong><em>)(?<!<strong><em>)(?<!<h[1-6]><em><strong>)(?<!<em><strong>)\\b' . $wpai_anchor_text . '\\b(?![^<]*<\\/a>)(?![^<]*<\\/h[1-6]>)(?![^<]*<\\/strong>)(?![^<]*<\\/em>)(?![^<]*<\\/strong><\\/em>)(?![^<]*<\\/em><\\/strong>)/i',
                    '<a href="' . $wpai_target_url . '">' . $wpai_anchor_text . '</a>',
                    $wpaicg_content,
                    1
                );
            }
            $wpaicg_result['status'] = 'success';

            if ( $wpai_target_url_cta != "" ) {
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
                    $wpaicg_result['status'] = 'error';
                    $wpaicg_result['msg'] = esc_html( $completecta );
                } else {
                    $completecta = $completecta->choices[0]->text;
                    // trim the text
                    $completecta = trim( $completecta );
                    // add <p> to the beginning of the text
                    $completecta = "<p>" . $completecta . "</p>";

                    if ( $wpai_cta_pos == "beg" ) {
                        $wpaicg_content = preg_replace(
                            '/(<h[1-6]>)/',
                            $completecta . ' $1',
                            $wpaicg_content,
                            1
                        );
                    } else {
                        $wpaicg_content = $wpaicg_content . $completecta;
                    }

                    $wpaicg_result['status'] = 'success';
                }

            }

            if($wpaicg_toc == '1' && is_array($wpaicg_toc_list) && count($wpaicg_toc_list)){
                $wpaicg_table_content = '<ul class="wpaicg_toc"><li>';
                if($wpaicg_toc_title !== ''){
                    $wpaicg_table_content .= '<'.$wpaicg_toc_title_tag.'>'.$wpaicg_toc_title.'</'.$wpaicg_toc_title_tag.'>';
                }
                $wpaicg_table_content .= '<ul>';
                foreach($wpaicg_toc_list as $wpaicg_toc_item){
                    $wpaicg_toc_item_id = 'wpaicg-'.sanitize_title($wpaicg_toc_item);
                    $wpaicg_table_content .= '<li><a href="#'.$wpaicg_toc_item_id.'">'.$wpaicg_toc_item.'</a></li>';
                }
                $wpaicg_table_content .= '</ul>';
                $wpaicg_table_content .= '</li></ul>';
                $wpaicg_content = $wpaicg_table_content.$wpaicg_content;
            }
            $wpaicg_result['content'] = $wpaicg_content;
            $wpaicg_result['next_step'] = 'image';
        }

        $wpaicg_pexels_api = get_option('wpaicg_pexels_api','');
        if ( $wpaicg_step == 'image' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );
            $wpaicg_result['status'] = 'success';
            if ( !empty($wpaicg_image_source) ) {
                if($wpaicg_image_source == 'dalle') {
                    if (!empty($_wpaicg_image_style)) {
                        $_wpaicg_art_style = (isset($wpaicg_languages['art_style']) && !empty($wpaicg_languages['art_style']) ? ' ' . $wpaicg_languages['art_style'] : '');
                        $_wpaicg_image_style = (isset($wpaicg_languages['img_styles'][$wpai_img_style]) && !empty($wpaicg_languages['img_styles'][$wpai_img_style]) ? ' ' . $wpaicg_languages['img_styles'][$wpai_img_style] : '');
                    }

                    $imgresult = $open_ai->image([
                        "prompt" => $wpai_preview_title . $_wpaicg_art_style . $_wpaicg_image_style,
                        "n" => 1,
                        "size" => $wpai_img_size,
                        "response_format" => "url",
                    ]);
                    $imgresult = json_decode($imgresult);

                    if (isset($imgresult->error)) {
                        $wpaicg_result['status'] = 'no_image';
                        $wpaicg_result['msg'] = esc_html($imgresult->error->message);
                    } else {
                        $imgresult = $imgresult->data[0]->url;
                        $wpaicg_result['img'] = trim($imgresult);
                        $imgresult = "__WPAICG_IMAGE__";
                        // get half of wpai_number_of_heading and insert image in the middle
                        $half = intval($wpai_number_of_heading) / 2;
                        $half = round($half);
                        $half = $half - 1;
                        // use wpai_heading_tag to add heading tag to image
                        $wpaicg_content = explode("</" . $wpai_heading_tag . ">", $wpaicg_content);
                        $wpaicg_content[$half] = $wpaicg_content[$half] . $imgresult;
                        $wpaicg_content = implode("</" . $wpai_heading_tag . ">", $wpaicg_content);
                    }
                }
                /*Pexels API*/
                if($wpaicg_image_source == 'pexels') {
                    $wpaicg_pexels_orientation = isset($_REQUEST['wpaicg_pexels_orientation']) && !empty($_REQUEST['wpaicg_pexels_orientation']) ? sanitize_text_field($_REQUEST['wpaicg_pexels_orientation']) : '';
                    $wpaicg_pexels_size = isset($_REQUEST['wpaicg_pexels_size']) && !empty($_REQUEST['wpaicg_pexels_size']) ? sanitize_text_field($_REQUEST['wpaicg_pexels_size']) : '';
                    $wpaicg_pexels_response = WPAICG\WPAICG_Functions::wpaicg_pexels_generator($wpaicg_pexels_api, $wpai_preview_title, $wpaicg_pexels_orientation, $wpaicg_pexels_size);
                    $wpaicg_result['status'] = $wpaicg_pexels_response['status'];
                    if(isset($wpaicg_pexels_response['pexels_reponse']) && !empty($wpaicg_pexels_response['pexels_reponse'])){
                        $wpaicg_result['img'] = trim($wpaicg_pexels_response['pexels_reponse']);
                        $imgresult = "__WPAICG_IMAGE__";
                        $half = intval($wpai_number_of_heading) / 2;
                        $half = round($half);
                        $half = $half - 1;
                        // use wpai_heading_tag to add heading tag to image
                        $wpaicg_content = explode("</" . $wpai_heading_tag . ">", $wpaicg_content);
                        $wpaicg_content[$half] = $wpaicg_content[$half] . $imgresult;
                        $wpaicg_content = implode("</" . $wpai_heading_tag . ">", $wpaicg_content);
                    }
                }

            }

            $wpaicg_result['next_step'] = 'featuredimage';
            $wpaicg_result['content'] = $wpaicg_content;
        }


        if ( $wpaicg_step == 'featuredimage' ) {
            $wpaicg_content = ( isset( $_REQUEST['content'] ) ? wp_kses( $_REQUEST['content'], $wpaicg_allowed_html_content_post ) : '' );
            $wpaicg_result['status'] = 'success';

            if ( !empty($wpaicg_featured_image_source) ) {
                if($wpaicg_featured_image_source == 'dalle') {
                    if (!empty($_wpaicg_image_style)) {
                        $_wpaicg_art_style = (isset($wpaicg_languages['art_style']) && !empty($wpaicg_languages['art_style']) ? ' ' . $wpaicg_languages['art_style'] : '');
                        $_wpaicg_image_style = (isset($wpaicg_languages['img_styles'][$wpai_img_style]) && !empty($wpaicg_languages['img_styles'][$wpai_img_style]) ? ' ' . $wpaicg_languages['img_styles'][$wpai_img_style] : '');
                    }

                    $imgresult = $open_ai->image([
                        "prompt" => $wpai_preview_title . $_wpaicg_art_style . $_wpaicg_image_style,
                        "n" => 1,
                        "size" => $wpai_img_size,
                        "response_format" => "url",
                    ]);
                    $imgresult = json_decode($imgresult);

                    if (isset($imgresult->error)) {
                        $wpaicg_result['status'] = 'no_image';
                        $wpaicg_result['msg'] = esc_html($imgresult->error->message);
                    } else {
                        $imgresult = $imgresult->data[0]->url;
                        $wpaicg_result['featured_img'] = trim($imgresult);
                    }
                }
                if($wpaicg_featured_image_source == 'pexels') {
                    $wpaicg_pexels_orientation = isset($_REQUEST['wpaicg_pexels_orientation']) && !empty($_REQUEST['wpaicg_pexels_orientation']) ? sanitize_text_field($_REQUEST['wpaicg_pexels_orientation']) : '';
                    $wpaicg_pexels_size = isset($_REQUEST['wpaicg_pexels_size']) && !empty($_REQUEST['wpaicg_pexels_size']) ? sanitize_text_field($_REQUEST['wpaicg_pexels_size']) : '';
                    $wpaicg_pexels_response = WPAICG\WPAICG_Functions::wpaicg_pexels_generator($wpaicg_pexels_api, $wpai_preview_title, $wpaicg_pexels_orientation, $wpaicg_pexels_size);
                    $wpaicg_result['status'] = $wpaicg_pexels_response['status'];
                    if(isset($wpaicg_pexels_response['pexels_reponse']) && !empty($wpaicg_pexels_response['pexels_reponse'])){
                        $wpaicg_result['featured_img'] = trim($wpaicg_pexels_response['pexels_reponse']);
                    }
                }
            }

            $wpaicg_result['next_step'] = 'DONE';
            $wpaicg_result['content'] = $wpaicg_content;
        }

        $wpaicg_result['tocs'] = implode(',',$wpaicg_toc_list);
        wp_send_json( $wpaicg_result );
    }

    function wpaicg_load_db_vaule_js()
    {
        global  $post ;
        include WPAICG_PLUGIN_DIR.'admin/views/scripts.php';
    }

    public function wpaicg_options_page()
    {
        add_menu_page(
            __( 'GPT AI Power', 'wp-ai-content-generator' ),
            'GPT AI Power',
            'manage_options',
            'wpaicg',
            array( $this, 'wpaicg_api_settings' ),
            'dashicons-megaphone',
            6
        );
    }

    public function wpaicg_help_menu()
    {
        add_submenu_page(
            'wpaicg',
            'Help',
            'Help',
            'manage_options',
            'wpaicg_help',
            array( $this, 'wpaicg_help_page' )
        );
    }

    public function wpaicg_logs_menu()
    {
        add_submenu_page(
            'wpaicg',
            'Logs',
            'Logs',
            'manage_options',
            'wpaicg_logs',
            array( $this, 'wpaicg_logs_page' )
        );
    }

    public function wpaicg_logs_page()
    {
        include WPAICG_PLUGIN_DIR.'admin/views/log.php';
    }

    public function wpaicg_help_page()
    {
        include WPAICG_PLUGIN_DIR.'admin/views/help/index.php';
    }

    public function wpaicg_api_settings()
    {
        include WPAICG_PLUGIN_DIR.'admin/views/settings/index.php';
    }

    public static function add_wp_ai_metabox()
    {
        $screens = [ 'post', 'page', 'wporg_cpt' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'wpaicg_preview',
                __( 'GPT-3 AI Content Writer & Generator', 'wwu-api' ),
                [ self::class, 'html' ],
                $screen,
                'advanced',
                'default'
            );
        }
    }

    public function wpaicg_set_post_content_()
    {
        wp_send_json( 'success' );
        die;
    }

    /**
     * Save the meta box selections.
     *
     * @param int $post_id  The post ID.
     */
    public static function save( int $post_id )
    {
        global  $wpdb ;
        $tablename = $wpdb->prefix . 'wpaicg_log';
        if ( array_key_exists( '_wporg_preview_title', $_POST ) ) {

            if ( !empty($_POST["_wporg_preview_title"]) && !empty($_POST["_wporg_generated_text"]) ) {
                $select_row = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpaicg_log", OBJECT );
                $insert_post_id = "";
                foreach ( $select_row as $selectData ) {
                    $insert_post_id = $selectData->post_id;
                }

                if ( $insert_post_id == $post_id ) {
                    $wpdb->update( $tablename, array(
                        'title'      => sanitize_text_field($_POST['_wporg_preview_title']),
                        'added_date' => date( 'Y-m-d H:i:s' ),
                        'post_id'    => $post_id,
                    ), array( '%s', '%s', '%s' ) );
                } else {
                    $get_post_name = get_post_type( $post_id );
                    echo  esc_html($get_post_name) ;
                    if ( $get_post_name == "post" ) {
                        $wpdb->insert( $tablename, array(
                            'title'      => sanitize_text_field($_POST['_wporg_preview_title']),
                            'added_date' => date( 'Y-m-d H:i:s' ),
                            'post_id'    => $post_id,
                        ), array( '%s', '%s', '%s' ) );
                    }
                }

            }

        }
        if ( array_key_exists( 'wpaicg_settings', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_meta_key', \WPAICG\wpaicg_util_core()->sanitize_text_or_array_field($_POST['wpaicg_settings']));
        }
        if ( array_key_exists( '_wporg_language', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_language', sanitize_text_field($_POST['_wporg_language'] ));
        }
        if ( array_key_exists( '_wporg_preview_title', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_preview_title', sanitize_text_field($_POST['_wporg_preview_title'] ));
        }
        if ( array_key_exists( '_wporg_number_of_heading', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_number_of_heading', sanitize_text_field($_POST['_wporg_number_of_heading'] ));
        }
        if ( array_key_exists( '_wporg_heading_tag', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_heading_tag', sanitize_text_field($_POST['_wporg_heading_tag'] ));
        }
        if ( array_key_exists( '_wporg_writing_style', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_writing_style', sanitize_text_field($_POST['_wporg_writing_style'] ));
        }
        if ( array_key_exists( '_wporg_writing_tone', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_writing_tone', sanitize_text_field($_POST['_wporg_writing_tone'] ));
        }
        if ( array_key_exists( '_wporg_modify_headings', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_modify_headings', sanitize_text_field($_POST['_wporg_modify_headings'] ));
        }
        if ( array_key_exists( '_wporg_add_img', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_add_img', sanitize_text_field($_POST['_wporg_add_img'] ));
        }
        if ( array_key_exists( 'wpaicg_image_featured', $_POST ) ) {
            update_post_meta( $post_id, '_wpaicg_image_featured', sanitize_text_field($_POST['wpaicg_image_featured'] ));
        }
        if ( array_key_exists( '_wporg_add_tagline', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_add_tagline', sanitize_text_field($_POST['_wporg_add_tagline'] ));
        }
        if ( array_key_exists( '_wporg_add_intro', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_add_intro', sanitize_text_field($_POST['_wporg_add_intro'] ));
        }
        if ( array_key_exists( '_wporg_add_conclusion', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_add_conclusion', sanitize_text_field($_POST['_wporg_add_conclusion'] ));
        }
        if ( array_key_exists( '_wporg_anchor_text', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_anchor_text', sanitize_text_field($_POST['_wporg_anchor_text'] ));
        }
        if ( array_key_exists( '_wporg_target_url', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_target_url', sanitize_text_field($_POST['_wporg_target_url'] ));
        }
        if ( array_key_exists( '_wporg_generated_text', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_generated_text', sanitize_text_field($_POST['_wporg_generated_text'] ));
        }
        // _wporg_cta_pos
        if ( array_key_exists( '_wporg_cta_pos', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_cta_pos', sanitize_text_field($_POST['_wporg_cta_pos'] ));
        }
        // _wporg_target_url_cta
        if ( array_key_exists( '_wporg_target_url_cta', $_POST ) ) {
            update_post_meta( $post_id, '_wporg_target_url_cta', sanitize_text_field($_POST['_wporg_target_url_cta'] ));
        }
        if ( array_key_exists( 'wpaicg_toc', $_POST ) ) {
            update_post_meta( $post_id, 'wpaicg_toc', sanitize_text_field($_POST['wpaicg_toc'] ));
        }
        else{
            delete_post_meta($post_id,'wpaicg_toc');
        }
        if ( array_key_exists( 'wpaicg_toc_title', $_POST ) ) {
            update_post_meta( $post_id, 'wpaicg_toc_title', sanitize_text_field($_POST['wpaicg_toc_title'] ));
        }
        else{
            delete_post_meta($post_id,'wpaicg_toc_title');
        }
        if ( array_key_exists( 'wpaicg_toc_title_tag', $_POST ) ) {
            update_post_meta( $post_id, 'wpaicg_toc_title_tag',sanitize_text_field( $_POST['wpaicg_toc_title_tag'] ));
        }
        else{
            delete_post_meta($post_id,'wpaicg_toc_title_tag');
        }
        // wpaicg_intro_title_tag
        if ( array_key_exists( 'wpaicg_intro_title_tag', $_POST ) ) {
            update_post_meta( $post_id, 'wpaicg_intro_title_tag', sanitize_text_field($_POST['wpaicg_intro_title_tag'] ));
        }
        else{
            delete_post_meta($post_id,'wpaicg_intro_title_tag');
        }
        // wpaicg_conclusion_title_tag
        if ( array_key_exists( 'wpaicg_conclusion_title_tag', $_POST ) ) {
            update_post_meta( $post_id, 'wpaicg_conclusion_title_tag', sanitize_text_field($_POST['wpaicg_conclusion_title_tag'] ));
        }
        else{
            delete_post_meta($post_id,'wpaicg_conclusion_title_tag');
        }
    }

    /**
     * Display the meta box HTML to the user.
     *
     * @param WP_Post $post   Post object.
     */
    public static function html( $post )
    {
        include WPAICG_PLUGIN_DIR.'admin/views/metabox.php';
    }

}
add_action( 'add_meta_boxes', [ 'Wp_Ai_Content_Generator_Admin', 'add_wp_ai_metabox' ] );
add_action( 'save_post', [ 'Wp_Ai_Content_Generator_Admin', 'save' ] );
