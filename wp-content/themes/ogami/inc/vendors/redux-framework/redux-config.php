<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if (!class_exists('Ogami_Redux_Framework_Config')) {

    class Ogami_Redux_Framework_Config
    {
        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct()
        {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            add_action('init', array($this, 'initSettings'), 10);
        }

        public function initSettings()
        {
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setSections()
        {
            global $wp_registered_sidebars;
            $sidebars = array();

            if ( is_admin() && !empty($wp_registered_sidebars) ) {
                foreach ($wp_registered_sidebars as $sidebar) {
                    $sidebars[$sidebar['id']] = $sidebar['name'];
                }
            }
            $columns = array( '1' => esc_html__('1 Column', 'ogami'),
                '2' => esc_html__('2 Columns', 'ogami'),
                '3' => esc_html__('3 Columns', 'ogami'),
                '4' => esc_html__('4 Columns', 'ogami'),
                '5' => esc_html__('5 Columns', 'ogami'),
                '6' => esc_html__('6 Columns', 'ogami'),
                '7' => esc_html__('7 Columns', 'ogami'),
                '8' => esc_html__('8 Columns', 'ogami'),
            );
            
            $general_fields = array();
            $general_fields[] = array(
                'id' => 'preload',
                'type' => 'switch',
                'title' => esc_html__('Preload Website', 'ogami'),
                'default' => true,
            );
            $general_fields[] = array(
                'id' => 'media-preload-icon',
                'type' => 'media',
                'title' => esc_html__('Preload Icon', 'ogami'),
                'subtitle' => esc_html__('Upload a .png or .gif image that will be your preload icon.', 'ogami'),
                'required' => array('preload', '=', true)
            );
            $general_fields[] = array(
                'id' => 'image_lazy_loading',
                'type' => 'switch',
                'title' => esc_html__('Image Lazy Loading', 'ogami'),
                'default' => true,
            );
            $general_fields[] = array(
                'id' => 'enable_smooth_scroll',
                'type' => 'switch',
                'title' => esc_html__('Smooth Scroll', 'ogami'),
                'default' => true,
            );
            // General Settings Tab
            $this->sections[] = array(
                'icon' => 'el-icon-cogs',
                'title' => esc_html__('General', 'ogami'),
                'fields' => $general_fields
            );
            // Header
            $this->sections[] = array(
                'icon' => 'el el-website',
                'title' => esc_html__('Header', 'ogami'),
                'fields' => array(
                    array(
                        'id' => 'header_settings',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Header Settings', 'ogami').'</h3>',
                    ),
                    array(
                        'id' => 'header_type',
                        'type' => 'select',
                        'title' => esc_html__('Header Layout Type', 'ogami'),
                        'subtitle' => esc_html__('Choose a header for your website.', 'ogami'),
                        'options' => ogami_get_header_layouts(),
                        'desc' => sprintf(__('You can add or edit a header in <a href="%s" target="_blank">Headers Builder</a>', 'ogami'), esc_url( admin_url( 'edit.php?post_type=apus_megamenu') )),
                    ),
                    array(
                        'id' => 'keep_header',
                        'type' => 'switch',
                        'title' => esc_html__('Sticky Header', 'ogami'),
                        'default' => false
                    ),
                    array(
                        'id' => 'header_mobile_settings',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Header Mobile Settings', 'ogami').'</h3>',
                    ),
                    array(
                        'id' => 'media-mobile-logo',
                        'type' => 'media',
                        'title' => esc_html__('Mobile Logo Upload', 'ogami'),
                        'subtitle' => esc_html__('Upload a .png or .gif image that will be your logo.', 'ogami'),
                    ),
                    array(
                        'id' => 'show_searchform',
                        'type' => 'switch',
                        'title' => esc_html__('Search Header', 'ogami'),
                        'default' => false
                    ),
                    array(
                        'id' => 'enable_autocompleate_search',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Autocompleate Search', 'ogami'),
                        'default' => true,
                        'required' => array('show_searchform','=',true)
                    ),
                    array(
                        'id' => 'show_cartbtn',
                        'type' => 'switch',
                        'title' => esc_html__('Show Cart Button', 'ogami'),
                        'default' => true
                    ),
                    array(
                        'id' => 'show_wishlist_btn',
                        'type' => 'switch',
                        'title' => esc_html__('Show Wishlist Button', 'ogami'),
                        'default' => true
                    ),
                    array(
                        'id' => 'show_login_register',
                        'type' => 'switch',
                        'title' => esc_html__('Show Login/Register', 'ogami'),
                        'default' => true
                    ),
                    array(
                        'id' => 'show_vertical_menu',
                        'type' => 'switch',
                        'title' => esc_html__('Show Vertical Menu', 'ogami'),
                        'default' => true
                    ),
                    array(
                        'id'         => 'header_social_links',
                        'type'       => 'repeater',
                        'title'      => esc_html__( 'Social Links', 'ogami' ),
                        'fields'     => array(
                            array(
                                'id' => 'header_social_links_link',
                                'type' => 'text',
                                'title' => esc_html__('Link', 'ogami'),
                            ),
                            array(
                                'id' => 'header_social_links_icon',
                                'type' => 'text',
                                'title' => esc_html__('Font Icon', 'ogami'),
                            )
                        ),
                    ),
                )
            );
            // Footer
            $custom_menus = array();
            if ( is_admin() ) {
                $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
                if ( is_array( $menus ) && ! empty( $menus ) ) {
                    foreach ( $menus as $single_menu ) {
                        if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->slug ) ) {
                            $custom_menus[ $single_menu->slug ] = $single_menu->name;
                        }
                    }
                }
            }
            $this->sections[] = array(
                'icon' => 'el el-website',
                'title' => esc_html__('Footer', 'ogami'),
                'fields' => array(
                    array(
                        'id' => 'footer_type',
                        'type' => 'select',
                        'title' => esc_html__('Footer Layout Type', 'ogami'),
                        'subtitle' => esc_html__('Choose a footer for your website.', 'ogami'),
                        'options' => ogami_get_footer_layouts()
                    ),
                    array(
                        'id' => 'back_to_top',
                        'type' => 'switch',
                        'title' => esc_html__('Back To Top Button', 'ogami'),
                        'subtitle' => esc_html__('Toggle whether or not to enable a back to top button on your pages.', 'ogami'),
                        'default' => true,
                    ),
                    array(
                        'title' => esc_html__('Show Footer Desktop On Mobile', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('Enable to show Footer Desktop on mobile.', 'ogami').'</em>',
                        'id' => 'show_footer_desktop_mobile',
                        'type' => 'switch',
                        'default' => false,
                    ),
                    array(
                        'title' => esc_html__('Show Separate Footer On Mobile', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('Enable to show separate footer on mobile.', 'ogami').'</em>',
                        'id' => 'show_footer_mobile',
                        'type' => 'switch',
                        'default' => true,
                    ),
                    array(
                        'title' => esc_html__('Show Search Button', 'ogami'),
                        'id' => 'show_footer_search',
                        'type' => 'switch',
                        'default' => true,
                        'required' => array('show_footer_mobile', '=', true),
                        'subtitle' => '<em>'.esc_html__('Show search button on footer mobile.', 'ogami').'</em>',
                    ),
                    array(
                        'title' => esc_html__('Show Cart Button', 'ogami'),
                        'id' => 'show_footer_cartbtn',
                        'type' => 'switch',
                        'default' => true,
                        'required' => array('show_footer_mobile', '=', true),
                        'subtitle' => '<em>'.esc_html__('Show cart button on footer mobile.', 'ogami').'</em>',
                    ),
                    array(
                        'title' => esc_html__('Show My Account', 'ogami'),
                        'id' => 'show_footer_myaccount',
                        'type' => 'switch',
                        'default' => true,
                        'required' => array('show_footer_mobile', '=', true),
                        'subtitle' => '<em>'.esc_html__('Show My Account button on footer mobile.', 'ogami').'</em>',
                    ),
                    array(
                        'title' => esc_html__('Show More Link', 'ogami'),
                        'id' => 'show_footer_morelink',
                        'type' => 'switch',
                        'default' => true,
                        'required' => array('show_footer_mobile', '=', true),
                        'subtitle' => '<em>'.esc_html__('Show More Link button on footer mobile.', 'ogami').'</em>',
                    ),
                    array(
                        'id' => 'morelink_menu',
                        'type' => 'select',
                        'title' => esc_html__('More Link Menu', 'ogami'),
                        'options' => $custom_menus,
                        'required' => array('show_footer_mobile', '=', true),
                    )
                )
            );

            // Blog settings
            $this->sections[] = array(
                'icon' => 'el el-pencil',
                'title' => esc_html__('Blog', 'ogami'),
                'fields' => array(
                    array(
                        'id' => 'show_blog_breadcrumbs',
                        'type' => 'switch',
                        'title' => esc_html__('Breadcrumbs', 'ogami'),
                        'default' => 1
                    ),
                    array (
                        'title' => esc_html__('Breadcrumbs Background Color', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'ogami').'</em>',
                        'id' => 'blog_breadcrumb_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'id' => 'blog_breadcrumb_image',
                        'type' => 'media',
                        'title' => esc_html__('Breadcrumbs Background', 'ogami'),
                        'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'ogami'),
                    ),
                )
            );
            // Archive Blogs settings
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Blog & Post Archives', 'ogami'),
                'fields' => array(
                    array(
                        'id' => 'blog_archive_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Layout', 'ogami'),
                        'subtitle' => esc_html__('Select the variation you want to apply on your store.', 'ogami'),
                        'options' => array(
                            'main' => array(
                                'title' => esc_html__('Main Only', 'ogami'),
                                'alt' => esc_html__('Main Only', 'ogami'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                            ),
                            'left-main' => array(
                                'title' => esc_html__('Left - Main Sidebar', 'ogami'),
                                'alt' => esc_html__('Left - Main Sidebar', 'ogami'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                            ),
                            'main-right' => array(
                                'title' => esc_html__('Main - Right Sidebar', 'ogami'),
                                'alt' => esc_html__('Main - Right Sidebar', 'ogami'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                            ),
                        ),
                        'default' => 'left-main'
                    ),
                    array(
                        'id' => 'blog_archive_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Is Full Width?', 'ogami'),
                        'default' => false
                    ),
                    array(
                        'id' => 'blog_archive_left_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Archive Left Sidebar', 'ogami'),
                        'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'ogami'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'blog_archive_right_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Archive Right Sidebar', 'ogami'),
                        'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'ogami'),
                        'options' => $sidebars
                        
                    ),
                    array(
                        'id' => 'blog_display_mode',
                        'type' => 'select',
                        'title' => esc_html__('Display Mode', 'ogami'),
                        'options' => array(
                            'grid' => esc_html__('Grid Layout', 'ogami'),
                            'grid_2' => esc_html__('Grid 2 Layout', 'ogami'),
                            'list' => esc_html__('List Layout', 'ogami'),
                        ),
                        'default' => 'grid'
                    ),
                    array(
                        'id' => 'blog_columns',
                        'type' => 'select',
                        'title' => esc_html__('Blog Columns', 'ogami'),
                        'options' => $columns,
                        'default' => 1
                    ),
                    array(
                        'id' => 'blog_item_thumbsize',
                        'type' => 'text',
                        'title' => esc_html__('Thumbnail Size', 'ogami'),
                        'subtitle' => esc_html__('This featured for the site is using Visual Composer.', 'ogami'),
                        'desc' => esc_html__('Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) .', 'ogami'),
                    ),

                )
            );
            // Single Blogs settings
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Blog', 'ogami'),
                'fields' => array(
                    
                    array(
                        'id' => 'blog_single_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Archive Blog Layout', 'ogami'),
                        'subtitle' => esc_html__('Select the variation you want to apply on your store.', 'ogami'),
                        'options' => array(
                            'main' => array(
                                'title' => esc_html__('Main Only', 'ogami'),
                                'alt' => esc_html__('Main Only', 'ogami'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                            ),
                            'left-main' => array(
                                'title' => esc_html__('Left - Main Sidebar', 'ogami'),
                                'alt' => esc_html__('Left - Main Sidebar', 'ogami'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                            ),
                            'main-right' => array(
                                'title' => esc_html__('Main - Right Sidebar', 'ogami'),
                                'alt' => esc_html__('Main - Right Sidebar', 'ogami'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                            ),
                        ),
                        'default' => 'left-main'
                    ),
                    array(
                        'id' => 'blog_single_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Is Full Width?', 'ogami'),
                        'default' => false
                    ),
                    array(
                        'id' => 'blog_single_left_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Single Blog Left Sidebar', 'ogami'),
                        'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'ogami'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'blog_single_right_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Single Blog Right Sidebar', 'ogami'),
                        'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'ogami'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'show_blog_social_share',
                        'type' => 'switch',
                        'title' => esc_html__('Show Social Share', 'ogami'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'show_blog_releated',
                        'type' => 'switch',
                        'title' => esc_html__('Show Releated Posts', 'ogami'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'number_blog_releated',
                        'type' => 'text',
                        'title' => esc_html__('Number of related posts to show', 'ogami'),
                        'required' => array('show_blog_releated', '=', '1'),
                        'default' => 3,
                        'min' => '1',
                        'step' => '1',
                        'max' => '20',
                        'type' => 'slider'
                    ),
                    array(
                        'id' => 'releated_blog_columns',
                        'type' => 'select',
                        'title' => esc_html__('Releated Blogs Columns', 'ogami'),
                        'required' => array('show_blog_releated', '=', '1'),
                        'options' => $columns,
                        'default' => 3
                    ),

                )
            );
            
            $this->sections = apply_filters( 'ogami_redux_framwork_configs', $this->sections, $sidebars, $columns );
            
            // 404 page
            $this->sections[] = array(
                'title' => esc_html__('404 Page', 'ogami'),
                'fields' => array(
                    array (
                        'title' => esc_html__('Images Icon', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('Icon for 404 error.', 'ogami').'</em>',
                        'id' => 'icon-img',
                        'type' => 'media',
                    ),
                    array(
                        'id' => '404_title',
                        'type' => 'text',
                        'title' => esc_html__('Title', 'ogami'),
                        'default' => '404'
                    ),
                    array(
                        'id' => '404_description',
                        'type' => 'editor',
                        'title' => esc_html__('Description', 'ogami'),
                        'default' => 'Sorry but the page you are looking for does not exist, have been removed, name changed or is temporarity unavailable.'
                    )
                )
            );
            
            // Style
            $this->sections[] = array(
                'icon' => 'el el-icon-css',
                'title' => esc_html__('Custom Style', 'ogami'),
                'fields' => array(
                    array(
                        'id' => 'custom_color',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Custom Color', 'ogami').'</h3>',
                    ),
                    array(
                        'title' => esc_html__('Main Theme Color', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('The main color of the site.', 'ogami').'</em>',
                        'id' => 'main_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'title' => esc_html__('Button Background Color', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('The main color of the site.', 'ogami').'</em>',
                        'id' => 'button_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'title' => esc_html__('Button Background Hover Color', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('The main color of the site.', 'ogami').'</em>',
                        'id' => 'button_hover_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),

                    // Typography
                    array(
                        'id' => 'main_font_info',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Custom Typography', 'ogami').'</h3>',
                    ),
                    array (
                        'title' => esc_html__('Main Font Face', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('Pick the Main Font for your site.', 'ogami').'</em>',
                        'id' => 'main_font',
                        'type' => 'typography',
                        'line-height' => false,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => false,
                        'all_styles'=> true,
                        'font-size' => false,
                        'color' => false,
                        'default' => array (
                            'font-family' => '',
                            'subsets' => '',
                        )
                    ),
                    array(
                        'title' => esc_html__('Heading Font Face', 'ogami'),
                        'subtitle' => '<em>'.esc_html__('Pick the Heading Font for your site.', 'ogami').'</em>',
                        'id' => 'heading_font',
                        'type' => 'typography',
                        'line-height' => false,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => false,
                        'all_styles'=> true,
                        'font-size' => false,
                        'color' => false,
                        'default' => array (
                            'font-family' => '',
                            'subsets' => '',
                        )
                    ),
                )
            );
            
            
            // Social Media
            $this->sections[] = array(
                'icon' => 'el el-file',
                'title' => esc_html__('Social Media', 'ogami'),
                'fields' => array(
                    array(
                        'id' => 'facebook_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Facebook Share', 'ogami'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'twitter_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable twitter Share', 'ogami'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'linkedin_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable linkedin Share', 'ogami'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'tumblr_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable tumblr Share', 'ogami'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'google_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable google plus Share', 'ogami'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'pinterest_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable pinterest Share', 'ogami'),
                        'default' => 1
                    )
                )
            );
            // Custom Code
            
            $this->sections[] = array(
                'title' => esc_html__('Import / Export', 'ogami'),
                'desc' => esc_html__('Import and Export your Redux Framework settings from file, text or URL.', 'ogami'),
                'icon' => 'el-icon-refresh',
                'fields' => array(
                    array(
                        'id' => 'opt-import-export',
                        'type' => 'import_export',
                        'title' => 'Import Export',
                        'subtitle' => 'Save and restore your Redux options',
                        'full_width' => false,
                    ),
                ),
            );

            $this->sections[] = array(
                'type' => 'divide',
            );


        }
        /**
         * All the possible arguments for Redux.
         * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments()
        {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            
            $preset = ogami_get_demo_preset();
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'ogami_theme_options'.$preset,
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'),
                // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'),
                // Version that appears at the top of your panel
                'menu_type' => 'menu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true,
                // Show the sections below the admin menu item or not
                'menu_title' => esc_html__('Theme Options', 'ogami'),
                'page_title' => esc_html__('Theme Options', 'ogami'),

                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '',
                // Set it you want google fonts to update weekly. A google_api_key value is required.
                'google_update_weekly' => false,
                // Must be defined to add google fonts to the typography module
                'async_typography' => true,
                // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar' => true,
                // Show the panel pages on the admin bar
                'admin_bar_icon' => 'dashicons-portfolio',
                // Choose an icon for the admin bar menu
                'admin_bar_priority' => 50,
                // Choose an priority for the admin bar menu
                'global_variable' => 'ogami_options',
                // Set a different name for your global variable other than the opt_name
                'dev_mode' => false,
                // Show the time the page took to load, etc
                'update_notice' => false,
                // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                'customizer' => true,
                // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority' => null,
                // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php',
                // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options',
                // Permissions needed to access the options panel.
                'menu_icon' => '',
                // Specify a custom URL to an icon
                'last_tab' => '',
                // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes',
                // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options',
                // Page slug used to denote the panel
                'save_defaults' => true,
                // On load save the defaults to DB before user clicks save or not
                'default_show' => false,
                // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '',
                // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,
                // Shows the Import/Export panel when not used as a field.

                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true,
                // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true,
                // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info' => false,
                // REMOVE
                'use_cdn' => true
            );
            return $this->args;
        }

    }

    global $reduxConfig;
    $reduxConfig = new Ogami_Redux_Framework_Config();
}

if ( function_exists('apus_framework_redux_register_custom_extension_loader') ) {
    $preset = ogami_get_demo_preset();
    $opt_name = 'ogami_theme_options'.$preset;
    add_action("redux/extensions/{$opt_name}/before", 'apus_framework_redux_register_custom_extension_loader', 0);
}


function ogami_redux_remove_notice() {
    return 'bub';
}
$preset = ogami_get_demo_preset();
$opt_name = 'ogami_theme_options'.$preset;
add_action('redux/' . $opt_name . '/aNFM_filter', 'ogami_redux_remove_notice');