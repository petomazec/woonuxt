<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(isset($_POST['save_bulk_setting'])) {
    if (isset($_POST['wpaicg_restart_queue']) && !empty($_POST['wpaicg_restart_queue'])) {
        update_option('wpaicg_restart_queue', sanitize_text_field($_POST['wpaicg_restart_queue']));
    } else {
        delete_option('wpaicg_restart_queue');
    }
    if (isset($_POST['wpaicg_try_queue']) && !empty($_POST['wpaicg_try_queue'])) {
        update_option('wpaicg_try_queue', sanitize_text_field($_POST['wpaicg_try_queue']));
    } else {
        delete_option('wpaicg_try_queue');
    }
}
$wpaicg_restart_queue = get_option('wpaicg_restart_queue', '');
$wpaicg_try_queue = get_option('wpaicg_try_queue', '');
?>
<form action="" method="post">
    <table class="form-table">
        <tr>
            <th scope="row">Restart Failed Jobs After</th>
            <td>
                <select name="wpaicg_restart_queue">
                    <option value="">Dont Restart</option>
                    <?php
                    for($i = 20; $i <=60; $i+=10){
                        echo '<option'.($wpaicg_restart_queue == $i ? ' selected':'').' value="'.esc_html($i).'">'.esc_html($i).'</option>';
                    }
                    ?>
                </select>
                minutes
            </td>
        </tr>
        <tr>
            <th scope="row">Attempt up to a maximum of</th>
            <td>
                <select name="wpaicg_try_queue">
                    <?php
                    for($i = 1; $i <=10; $i++){
                        echo '<option'.($wpaicg_try_queue == $i ? ' selected':'').' value="'.esc_html($i).'">'.esc_html($i).'</option>';
                    }
                    ?>
                </select>
                times
            </td>
        </tr>
    </table>
    <button class="button-primary button" name="save_bulk_setting">Save</button>
</form>
