<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(isset($_POST['wpaicg_confirmed_cron'])){
    update_option('_wpaicg_crojob_bulk_confirm','true');
}
$wpaicg_track_id = isset($_GET['wpaicg_track']) && !empty($_GET['wpaicg_track']) ? sanitize_text_field($_GET['wpaicg_track']) : false;
$wpaicg_bulk_action = isset($_GET['wpaicg_action']) && !empty($_GET['wpaicg_action']) ? sanitize_text_field($_GET['wpaicg_action']) : false;
$wpaicg_track = false;
if($wpaicg_track_id){
    $wpaicg_track = get_post($wpaicg_track_id);
}
$wpaicg_cron_job_last_time = get_option('_wpaicg_crojob_bulk_last_time','');
$wpaicg_cron_job_confirm = get_option('_wpaicg_crojob_bulk_confirm','');
$wpaicg_number_title = $this->wpaicg_limit_titles;
$wpaicg_cron_added = get_option('_wpaicg_cron_added','');
?>
<div class="wrap fs-section">
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wpaicg_bulk_content')?>" class="nav-tab<?php echo !$wpaicg_track && !$wpaicg_bulk_action ? ' nav-tab-active' : ''?>">Bulk Editor</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_bulk_content&wpaicg_action=csv')?>" class="nav-tab<?php echo $wpaicg_bulk_action == 'csv' ? ' nav-tab-active' : ''?>">CSV</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_bulk_content&wpaicg_action=copy-paste')?>" class="nav-tab<?php echo $wpaicg_bulk_action == 'copy-paste' ? ' nav-tab-active' : ''?>">Copy & Paste</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_bulk_content&wpaicg_action=tracking')?>" class="nav-tab<?php echo $wpaicg_track || $wpaicg_bulk_action == 'tracking' ? ' nav-tab-active' : ''?>">Queue</a>
        <a href="<?php echo admin_url('admin.php?page=wpaicg_bulk_content&wpaicg_action=setting')?>" class="nav-tab<?php echo $wpaicg_bulk_action == 'setting' ? ' nav-tab-active' : ''?>">Settings</a>
    </h2>
    <div id="poststuff">
        <?php
        if(!$wpaicg_bulk_action && !$wpaicg_track):
            include __DIR__.'/wpaicg_bulk_index.php';
        elseif($wpaicg_bulk_action == 'tracking'):
            include __DIR__.'/wpaicg_bulk_queue.php';
        elseif($wpaicg_bulk_action == 'csv'):
            include __DIR__.'/wpaicg_bulk_csv.php';
        elseif($wpaicg_bulk_action == 'copy-paste'):
            include __DIR__.'/wpaicg_bulk_copy_paste.php';
        elseif($wpaicg_bulk_action == 'setting'):
            include __DIR__.'/wpaicg_bulk_setting.php';
        elseif($wpaicg_track):
            include __DIR__.'/wpaicg_bulk_tracking.php';
        endif;
        ?>
    </div>
</div>
