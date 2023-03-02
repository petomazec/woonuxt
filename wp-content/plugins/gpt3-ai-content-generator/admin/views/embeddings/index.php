<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wpaicg_action = isset($_GET['action']) && !empty($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
$wpaicg_pinecone_api = get_option('wpaicg_pinecone_api','');
$wpaicg_pinecone_environment = get_option('wpaicg_pinecone_environment','');
if(empty($wpaicg_pinecone_api) && empty($wpaicg_pinecone_environment) && $wpaicg_action != 'settings'){
    echo '<script>window.location.href = "'.admin_url('admin.php?page=wpaicg_embeddings&action=settings').'"</script>';
    exit;
}
?>
<style>
.wpaicg_notice_text_em {
    padding: 10px;
    background-color: #F8DC6F;
    text-align: left;
    margin-bottom: 12px;
    color: #000;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
</style>
<p class="wpaicg_notice_text_em">If you are happy with our plugin, please consider writing a review <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/reviews/#new-post" target="_blank">here</a>. Post your questions and suggestions on our <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/" target="_blank">support forum</a>. Thank you! ❤️ 😊</p>
<div class="wrap fs-section">
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wpaicg_embeddings');?>" class="nav-tab<?php echo empty($wpaicg_action) ? ' nav-tab-active' : ''?>">Data Entry </a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_embeddings&action=logs');?>" class="nav-tab<?php echo $wpaicg_action == 'logs' ? ' nav-tab-active' : ''?>">Entries </a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_embeddings&action=builder');?>" class="nav-tab<?php echo $wpaicg_action == 'builder' ? ' nav-tab-active' : ''?>">Index Builder</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_embeddings&action=settings');?>" class="nav-tab<?php echo $wpaicg_action == 'settings' ? ' nav-tab-active' : ''?>">Settings</a>
    </h2>
</div>
<div id="poststuff">
<?php
if(empty($wpaicg_action)){
    include __DIR__.'/entries.php';
}
elseif($wpaicg_action == 'logs'){
    include __DIR__.'/logs.php';
}
elseif($wpaicg_action == 'settings'){
    include __DIR__.'/settings.php';
}
elseif($wpaicg_action == 'builder'){
    include __DIR__.'/builder.php';
}
?>
</div>
