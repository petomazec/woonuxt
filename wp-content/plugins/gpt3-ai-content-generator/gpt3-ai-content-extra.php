<?php
if ( ! defined( 'ABSPATH' ) ) exit;
define( 'WPAICG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPAICG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once __DIR__.'/classes/wpaicg_util.php';
require_once __DIR__.'/classes/wpaicg_content.php';
require_once __DIR__.'/classes/wpaicg_cron.php';
require_once __DIR__.'/classes/wpaicg_chat.php';
require_once __DIR__.'/classes/wpaicg_image.php';
require_once __DIR__.'/classes/wpaicg_promptbase.php';
require_once __DIR__.'/classes/wpaicg_forms.php';
require_once __DIR__.'/classes/wpaicg_playground.php';
require_once __DIR__.'/classes/wpaicg_finetune.php';
require_once __DIR__.'/classes/wpaicg_embeddings.php';
require_once __DIR__.'/classes/wpaicg_frontend.php';
require_once __DIR__.'/classes/wpaicg_woocommerce.php';
require_once __DIR__.'/classes/wpaicg_regenerate_title.php';
require_once __DIR__.'/classes/wpaicg_hook.php';
require_once __DIR__.'/classes/wpaicg_search.php';
