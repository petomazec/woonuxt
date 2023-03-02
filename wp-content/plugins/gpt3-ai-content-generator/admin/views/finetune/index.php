<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wpaicg_action = isset($_GET['action']) && !empty($_GET['action']) && in_array(sanitize_text_field($_GET['action']), array('embeddings','fine-tunes','files','data','manual','upload')) ? sanitize_text_field($_GET['action']) : 'help';
?>
<style>
.wpaicg_notice_text_tr {
    padding: 10px;
    background-color: #F8DC6F;
    text-align: left;
    margin-bottom: 12px;
    color: #000;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
</style>
<p class="wpaicg_notice_text_tr">If you are happy with our plugin, please consider writing a review <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/reviews/#new-post" target="_blank">here</a>. Post your questions and suggestions on our <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/" target="_blank">support forum</a>. Thank you! ❤️ 😊</p>
<div class="wrap fs-section">
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wpaicg_finetune')?>" class="nav-tab<?php echo $wpaicg_action == 'help' ? ' nav-tab-active' : ''?>">Preparation</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_finetune&action=upload')?>" class="nav-tab<?php echo $wpaicg_action == 'upload' ? ' nav-tab-active' : ''?>">Upload</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_finetune&action=manual')?>" class="nav-tab<?php echo $wpaicg_action == 'manual' ? ' nav-tab-active' : ''?>">Manual Entry</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_finetune&action=data')?>" class="nav-tab<?php echo $wpaicg_action == 'data' ? ' nav-tab-active' : ''?>">Data Converter</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_finetune&action=files')?>" class="nav-tab<?php echo $wpaicg_action == 'files' ? ' nav-tab-active' : ''?>">Datasets</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_finetune&action=fine-tunes')?>" class="nav-tab<?php echo $wpaicg_action == 'fine-tunes' ? ' nav-tab-active' : ''?>">Trainings</a>
    </h2>
    <div id="poststuff">
        <?php
        include(WPAICG_PLUGIN_DIR.'admin/views/finetune/'.$wpaicg_action.'.php');
        ?>
    </div>
</div>
