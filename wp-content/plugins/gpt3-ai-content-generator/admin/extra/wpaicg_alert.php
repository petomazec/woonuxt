<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wpaicg_max_execution = ini_get('max_execution_time');
if($wpaicg_max_execution < 1000){
    ?>
    <div class="wpaicg-alert">
        <p style="color: #f00">
            It appears that your PHP INI max execution time is less than 1000 seconds. Please increase it to ensure that the plugin functions properly.
        </p>
    </div>
    <?php
}
?>
<?php
if(isset($_POST['wpaicg_delete_running'])){
    update_option('_wpaicg_crojob_bulk_last_time', time());
    @unlink(WPAICG_PLUGIN_DIR.'wpaicg_running.txt');
    echo '<script>window.location.reload()</script>';
    exit;
}
if(!empty($wpaicg_cron_job_last_time)){
    $wpaicg_timestamp_diff = time() - $wpaicg_cron_job_last_time;
    if($wpaicg_timestamp_diff > 600){
        ?>
        <div class="wpaicg-alert">
            <p style="color: #f00">
                You can use below button to restart your queue if it is stuck.
            </p>
            <form action="" method="post">
                <button name="wpaicg_delete_running" class="button button-primary">Force Refresh</button>
            </form>
        </div>
        <?php
    }
}
?>
<style>
.wpaicg_notice_text_be {
    padding: 10px;
    background-color: #F8DC6F;
    text-align: left;
    margin-bottom: 12px;
    color: #000;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
</style>
<p class="wpaicg_notice_text_be">If you are happy with our plugin, please consider writing a review <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/reviews/#new-post" target="_blank">here</a>. Post your questions and suggestions on our <a href="https://wordpress.org/support/plugin/gpt3-ai-content-generator/" target="_blank">support forum</a>. Thank you! ❤️ 😊</p>

<div class="wpaicg-alert">
    <?php
    if(empty($wpaicg_cron_added)):
    ?>
    <h4>Important</h4>
    <p>
        You must configure a <a href="https://www.hostgator.com/help/article/what-are-cron-jobs" target="_blank">Cron Job</a> on your hosting/server.
        If this is not done, the Bulk Editor feature will not be available for use.
    </p>
    <?php
    endif;
    ?>
    <?php
    if(empty($wpaicg_cron_added)){
        echo '<p style="color: #f00"><strong>It appears that you have not activated Cron Job on your server, which means you will not be able to use the Bulk Editor feature. If you have already activated Cron Job, please allow a few minutes to pass before refreshing the page.</strong></p>';
    }
    else{
        echo '<p style="color: #10922c"><strong>Great! It looks like your Cron Job is running properly. You should now be able to use the Bulk Editor.</strong></p>';
    }
    ?>
    <?php
    if(!empty($wpaicg_cron_job_last_time)):
        $wpaicg_current_timestamp = time();

        $wpaicg_time_diff = human_time_diff( $wpaicg_cron_job_last_time, $wpaicg_current_timestamp );

        if ( strpos( $wpaicg_time_diff, 'hour' ) !== false ) {
            $wpaicg_output = str_replace( 'hours', 'hours', $wpaicg_time_diff );
        } elseif ( strpos( $wpaicg_time_diff, 'day' ) !== false ) {
            $wpaicg_output = str_replace( 'days', 'days', $wpaicg_time_diff );
        } elseif ( strpos( $wpaicg_time_diff, 'min' ) !== false ) {
            $wpaicg_output = str_replace( 'minutes', 'minutes', $wpaicg_time_diff );
        } else {
            $wpaicg_output = $wpaicg_time_diff;
        }
        ?>
        <p>The last time, the Cron Job ran on your website <?php echo date('Y-m-d H:i:s',$wpaicg_cron_job_last_time)?> (<?php echo esc_html($wpaicg_output)?> ago)</p>
    <?php
    endif;
    ?>
    <?php
//    if(empty($wpaicg_cron_added)):
    ?>
    <hr>
    <p></p>
    <p><strong>Cron Job Configuration</strong></p>
    <p></p>
    <p>If you are using Linux/Unix server, copy below code and paste it into crontab. Read detailed guide <a href="https://gptaipower.com/how-to-add-cron-job/" target="_blank">here</a>.</p>
    <p><code>* * * * * php <?php echo esc_html(ABSPATH)?>index.php -- wpaicg_cron=yes</code></p>
    <?php
//    endif;
    ?>
</div>
<?php
if(!\WPAICG\wpaicg_util_core()->wpaicg_is_pro()):
?>
<div class="wpaicg-alert">
Free users can only generate 5 pieces of content at a time. Please <a href="<?php echo admin_url('admin.php?page=wpaicg-pricing')?>">click here</a> to upgrade to the Pro plan to unlock more.
</div>
<?php
endif;
?>

