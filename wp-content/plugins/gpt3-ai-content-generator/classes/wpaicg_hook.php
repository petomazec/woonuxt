<?php
namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('\\WPAICG\\WPAICG_Hook')) {
    class WPAICG_Hook
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
            add_action( 'admin_menu', array( $this, 'wpaicg_change_menu_name' ) );
            add_action( 'admin_head', array( $this, 'wpaicg_hooks_admin_header' ) );
            add_action('wp_footer',[$this,'wpaicg_footer'],1);
            add_action('wp_head',[$this,'wpaicg_head_seo'],1);
            add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
            add_action('admin_footer',array($this,'wpaicg_admin_footer'));
            add_editor_style(WPAICG_PLUGIN_URL.'admin/css/editor.css');
            add_action( 'admin_enqueue_scripts', [$this,'wpaicg_enqueue_scripts'] );
        }

        public function wpaicg_enqueue_scripts()
        {
            wp_enqueue_script('wpaicg-jquery-datepicker',WPAICG_PLUGIN_URL.'admin/js/jquery.datetimepicker.full.min.js',array(),null);
            wp_enqueue_style('wpaicg-extra-css',WPAICG_PLUGIN_URL.'admin/css/wpaicg_extra.css',array(),null);
            wp_enqueue_style('wpaicg-jquery-datepicker-css',WPAICG_PLUGIN_URL.'admin/css/jquery.datetimepicker.min.css',array(),null);
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

        public function wpaicg_head_seo()
        {
            $wpaicg_chat_widget = get_option('wpaicg_chat_widget',[]);
            $wpaicg_chat_icon = isset($wpaicg_chat_widget['icon']) && !empty($wpaicg_chat_widget['icon']) ? $wpaicg_chat_widget['icon'] : 'default';
            $wpaicg_chat_icon_url = isset($wpaicg_chat_widget['icon_url']) && !empty($wpaicg_chat_widget['icon_url']) ? $wpaicg_chat_widget['icon_url'] : '';
            $wpaicg_chat_status = isset($wpaicg_chat_widget['status']) && !empty($wpaicg_chat_widget['status']) ? $wpaicg_chat_widget['status'] : '';
            $wpaicg_chat_fontsize = isset($wpaicg_chat_widget['fontsize']) && !empty($wpaicg_chat_widget['fontsize']) ? $wpaicg_chat_widget['fontsize'] : '13';
            $wpaicg_chat_fontcolor = isset($wpaicg_chat_widget['fontcolor']) && !empty($wpaicg_chat_widget['fontcolor']) ? $wpaicg_chat_widget['fontcolor'] : '#90EE90';
            $wpaicg_chat_bgcolor = isset($wpaicg_chat_widget['bgcolor']) && !empty($wpaicg_chat_widget['bgcolor']) ? $wpaicg_chat_widget['bgcolor'] : '#222222';
            $wpaicg_chat_width = isset($wpaicg_chat_widget['width']) && !empty($wpaicg_chat_widget['width']) ? $wpaicg_chat_widget['width'] : '350';
            $wpaicg_chat_height = isset($wpaicg_chat_widget['height']) && !empty($wpaicg_chat_widget['height']) ? $wpaicg_chat_widget['height'] : '400';
            $wpaicg_chat_position = isset($wpaicg_chat_widget['position']) && !empty($wpaicg_chat_widget['position']) ? $wpaicg_chat_widget['position'] : 'left';
            $wpaicg_chat_tone = isset($wpaicg_chat_widget['tone']) && !empty($wpaicg_chat_widget['tone']) ? $wpaicg_chat_widget['tone'] : 'friendly';
            $wpaicg_chat_proffesion = isset($wpaicg_chat_widget['proffesion']) && !empty($wpaicg_chat_widget['proffesion']) ? $wpaicg_chat_widget['proffesion'] : 'none';
            $wpaicg_chat_remember_conversation = isset($wpaicg_chat_widget['remember_conversation']) && !empty($wpaicg_chat_widget['remember_conversation']) ? $wpaicg_chat_widget['remember_conversation'] : 'yes';
            $wpaicg_chat_content_aware = isset($wpaicg_chat_widget['content_aware']) && !empty($wpaicg_chat_widget['content_aware']) ? $wpaicg_chat_widget['content_aware'] : 'yes';
            ?>
            <style>
                .wpaicg_toc h2{
                    margin-bottom: 20px;
                }
                .wpaicg_toc{
                    list-style: none;
                    margin: 0 0 30px 0!important;
                    padding: 0!important;
                }
                .wpaicg_toc li{}
                .wpaicg_toc li ul{
                    list-style: decimal;
                }
                .wpaicg_toc a{}
                .wpaicg_chat_widget{
                    position: fixed;
                }
                .wpaicg_widget_left{
                    bottom: 15px;
                    left: 15px;
                }
                .wpaicg_widget_right{
                    bottom: 15px;
                    right: 15px;
                }
                .wpaicg_widget_right .wpaicg_chat_widget_content{
                    right: 0;
                }
                .wpaicg_widget_left .wpaicg_chat_widget_content{
                    left: 0;
                }
                .wpaicg_chat_widget_content .wpaicg-chatbox{
                    height: 100%;
                }
                .wpaicg_widget_open .wpaicg_chat_widget_content{
                    height: <?php echo esc_html($wpaicg_chat_height)?>px;
                }
                .wpaicg_chat_widget_content{
                    position: absolute;
                    bottom: calc(100% + 15px);
                    width: <?php echo esc_html($wpaicg_chat_width)?>px;
                    overflow: hidden;

                }
                .wpaicg_widget_open .wpaicg_chat_widget_content .wpaicg-chatbox{
                    top: 0;
                }
                .wpaicg_chat_widget_content .wpaicg-chatbox{
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: <?php echo esc_html($wpaicg_chat_width)?>px;
                    height: <?php echo esc_html($wpaicg_chat_height)?>px;
                    transition: top 300ms cubic-bezier(0.17, 0.04, 0.03, 0.94);
                }
                .wpaicg_chat_widget_content .wpaicg-chatbox-content{
                    height: <?php echo esc_html($wpaicg_chat_height)-45?>px;
                }
                .wpaicg_chat_widget_content .wpaicg-chatbox-content ul{
                    box-sizing: border-box;
                    height: <?php echo esc_html($wpaicg_chat_height)-45?>px;
                    background: <?php echo esc_html($wpaicg_chat_bgcolor)?>;
                }
                .wpaicg_chat_widget_content .wpaicg-chatbox-content ul li{
                    color: <?php echo esc_html($wpaicg_chat_fontcolor)?>;
                    font-size: <?php echo esc_html($wpaicg_chat_fontsize)?>px;
                }
                .wpaicg_chat_widget_content .wpaicg-bot-thinking{
                    color: <?php echo esc_html($wpaicg_chat_fontcolor)?>;
                }
                .wpaicg_chat_widget_content .wpaicg-chatbox-type{
                    border-top: 0;
                    background: <?php echo esc_html($wpaicg_chat_bgcolor)?>;
                }
                .wpaicg_chat_widget_content .wpaicg-chat-message{
                    color: <?php echo esc_html($wpaicg_chat_fontcolor)?>;
                }
                .wpaicg_chat_widget_content input.wpaicg-chatbox-typing{}
                .wpaicg_chat_widget_content input.wpaicg-chatbox-typing:focus{
                    outline: none;
                }
                .wpaicg_chat_widget .wpaicg_toggle{
                    cursor: pointer;
                }
                .wpaicg_chat_widget .wpaicg_toggle img{
                    width: 75px;
                    height: 75px;
                }
            </style>
            <?php
            if(is_single()){
                $wpaicg_meta_description = get_post_meta(get_the_ID(),'_wpaicg_meta_description',true);
                $_wpaicg_seo_meta_tag = get_option('_wpaicg_seo_meta_tag',false);
                $wpaicg_seo_option = false;
                $wpaicg_seo_plugin = wpaicg_util_core()->seo_plugin_activated();
                if($wpaicg_seo_plugin) {
                    $wpaicg_seo_option = get_option($wpaicg_seo_plugin, false);
                }
                if(!empty($wpaicg_meta_description) && $_wpaicg_seo_meta_tag && !$wpaicg_seo_option){
                    ?>
                    <!--- This meta description generated by GPT AI Power Plugin --->
                    <meta name="description" content="<?php echo esc_html($wpaicg_meta_description)?>">
                    <meta name="og:description" content="<?php echo esc_html($wpaicg_meta_description)?>">
                    <?php
                }
            }
        }

        public function wpaicg_footer()
        {
            include WPAICG_PLUGIN_DIR.'admin/extra/wpaicg_chat_widget.php';
        }

        public function wpaicg_hooks_admin_header()
        {
            ?>
            <style>
                .wp-block .wpaicg_toc h2{
                    margin-bottom: 20px;
                }
                .wp-block .wpaicg_toc{
                    list-style: none;
                    margin: 0 0 30px 0!important;
                    padding: 0!important;
                }
                .wp-block .wpaicg_toc li{}
                .wp-block .wpaicg_toc li ul{
                    list-style: decimal;
                }
                .wp-block .wpaicg_toc a{}

            </style>
            <?php
        }

        public function wpaicg_change_menu_name()
        {
            global  $menu ;
            global  $submenu ;
            $submenu['wpaicg'][0][0] = 'Settings';
            $wpaicg_arr = array();
            $wpaicg_next = array();
            foreach ( $submenu['wpaicg'] as $key => $wpaicg_sub ) {

                if ( $key == 1 || $key == 2 ) {
                    $wpaicg_next[] = $wpaicg_sub;
                } else {
                    $wpaicg_arr[] = $wpaicg_sub;
                }

            }
            $submenu['wpaicg'] = array_merge( $wpaicg_arr, $wpaicg_next );
        }
    }
    WPAICG_Hook::get_instance();
}
