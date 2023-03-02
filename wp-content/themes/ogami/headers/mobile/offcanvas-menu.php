<div id="apus-mobile-menu" class="apus-offcanvas hidden-lg"> 
    <div class="apus-offcanvas-body">
        <div class="offcanvas-head bg-primary">
            <a class="btn-toggle-canvas" data-toggle="offcanvas">
                <i class="ti-close"></i> <span><?php esc_html_e( 'Close', 'ogami' ); ?></span>
            </a>
        </div>

        <nav class="navbar navbar-offcanvas navbar-static" role="navigation">
            <?php
                $args = array(
                    'theme_location' => 'primary',
                    'container_class' => 'navbar-collapse navbar-offcanvas-collapse',
                    'menu_class' => 'nav navbar-nav main-mobile-menu',
                    'fallback_cb' => '',
                    'menu_id' => '',
                    'walker' => new Ogami_Mobile_Menu()
                );
                wp_nav_menu($args);
            ?>
        </nav>
        <?php if ( ogami_get_config('show_login_register', true) ) { ?>
            <div class="top-menu-mobile">
                <?php if( !is_user_logged_in() ){ ?>
                    <div class="navbar-collapse navbar-offcanvas-collapse">
                        <h4 class="title"><?php echo esc_html__('My Account', 'ogami'); ?></h4>
                        <nav class="navbar navbar-offcanvas navbar-static" role="navigation">
                            <ul class="nav navbar-nav main-mobile-menu">
                                <li><a class="login register-login-action" data-action="#customer_login" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_attr_e('Sign in','ogami'); ?>"><?php esc_html_e('Login', 'ogami'); ?></a></li>
                                <li><a class="register register-login-action" data-action="#customer_register" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_attr_e('Register','ogami'); ?>"><?php esc_html_e('Register', 'ogami'); ?></a></li>
                            </ul>
                        </nav>
                    </div>
                <?php } else { ?>
                    <?php if ( has_nav_menu( 'my-account' ) ): ?>
                        <div class="navbar-collapse navbar-offcanvas-collapse">
                            <h4 class="title"><?php echo esc_html__('My Account', 'ogami') ?></h4>
                            <nav class="navbar navbar-offcanvas navbar-static" role="navigation">
                                <ul class="nav navbar-nav main-mobile-menu">
                                <?php
                                    $args = array(
                                        'theme_location' => 'my-account',
                                        'container' => false,
                                        'menu_class' => 'nav navbar-nav',
                                        'fallback_cb'     => false,
                                        'walker' => new Ogami_Mobile_Menu(),
                                        'items_wrap' => '%3$s',
                                    );
                                    wp_nav_menu($args);
                                ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php } ?>
            </div>
        <?php } ?>

        <?php
            $social_links = ogami_get_config('header_social_links_link');
            $social_icons = ogami_get_config('header_social_links_icon');

            if ( !empty($social_links) ) {
                ?>
                <div class="offcanvas-menu-bottom">
                    <ul class="social-top">
                        <?php foreach ($social_links as $key => $value) { ?>
                            <li class="social-item">
                                <a href="<?php echo esc_url($value); ?>">
                                    <i class="<?php echo esc_attr($social_icons[$key]); ?>"></i>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php
            }
        ?>
    </div>
</div>
<div class="over-dark"></div>