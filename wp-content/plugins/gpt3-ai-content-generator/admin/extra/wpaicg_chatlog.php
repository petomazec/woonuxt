<?php
global $wpdb;
$wpaicg_log_page = isset($_GET['wpage']) && !empty($_GET['wpage']) ? sanitize_text_field($_GET['wpage']) : 1;
$search = isset($_GET['wsearch']) && !empty($_GET['wsearch']) ? sanitize_text_field($_GET['wsearch']) : '';
$where = '';
if(!empty($search)) {
    $where .= " AND `data` LIKE '%".$search."%'";
}
$query = "SELECT * FROM ".$wpdb->prefix."wpaicg_chatlogs WHERE 1=1".$where;
$total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
$total = $wpdb->get_var( $total_query );
$items_per_page = 10;
$offset = ( $wpaicg_log_page * $items_per_page ) - $items_per_page;
$wpaicg_logs = $wpdb->get_results( $query . " ORDER BY created_at DESC LIMIT ${offset}, ${items_per_page}" );
$totalPage         = ceil($total / $items_per_page);
?>
<style>
    .wpaicg_modal{
        top: 5%;
        height: 90%;
        position: relative;
    }
    .wpaicg_modal_content{
        max-height: calc(100% - 103px);
        overflow-y: auto;
    }
</style>
<form action="" method="get">
    <input type="hidden" name="page" value="wpaicg_chatgpt">
    <input type="hidden" name="action" value="logs">
    <div class="wpaicg-d-flex mb-5">
        <input style="width: 100%" value="<?php echo esc_html($search)?>" class="regular-text" name="wsearch" type="text" placeholder="Type for search">
        <button class="button button-primary">Search</button>
    </div>
</form>
<table class="wp-list-table widefat fixed striped table-view-list posts">
    <thead>
    <tr>
        <th>SessionID</th>
        <th>Date</th>
        <th>User Message</th>
        <th>AI Response</th>
        <th>Page</th>
        <th>Source</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody class="wpaicg-builder-list">
    <?php
    if($wpaicg_logs && is_array($wpaicg_logs) && count($wpaicg_logs)){
        foreach ($wpaicg_logs as $wpaicg_log){
            $last_user_message = '';
            $last_ai_message = '';
            $all_messages = json_decode($wpaicg_log->data,true);
            $all_messages = $all_messages && is_array($all_messages) ? $all_messages : array();
            foreach(array_reverse($all_messages) as $item){
                if(
                    isset($item['type'])
                    && $item['type'] == 'user'
                    && empty($last_user_message)
                ){
                    $last_user_message = $item['message'];
                }
                if(
                    isset($item['type'])
                    && $item['type'] == 'ai'
                    && empty($last_ai_message)
                ){
                    $last_ai_message = $item['message'];
                }
                if(!empty($last_ai_message) && !empty($last_user_message)){
                    break;
                }
            }
            ?>
            <tr>
                <td><?php echo esc_html($wpaicg_log->id)?></td>
                <td><?php echo date('d.m.Y H:i',esc_html($wpaicg_log->created_at))?></td>
                <td><?php echo esc_html(substr($last_user_message,0,255))?></td>
                <td><?php echo esc_html(substr($last_ai_message,0,255))?></td>
                <td><?php echo esc_html($wpaicg_log->page_title)?></td>
                <td><?php echo $wpaicg_log->source == 'widget' ? 'Chat Widget' : 'Chat Shortcode'?></td>
                <td>
                    <button class="button button-primary button-small wpaicg-log-messages" data-messages="<?php echo esc_html(json_encode($all_messages))?>">View</button>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>
<div class="wpaicg-paginate">
<?php
if($totalPage > 1){
    echo paginate_links( array(
        'base'         => admin_url('admin.php?page=wpaicg_chatgpt&action=logs&wpage=%#%'),
        'total'        => $totalPage,
        'current'      => $wpaicg_log_page,
        'format'       => '?wpage=%#%',
        'show_all'     => false,
        'prev_next'    => false,
        'add_args'     => false,
    ));
}
?>
</div>
<script>
    jQuery(document).ready(function ($){
        $('.wpaicg_modal_close').click(function (){
            $('.wpaicg_modal_close').closest('.wpaicg_modal').hide();
            $('.wpaicg-overlay').hide();
        });
        $('.wpaicg-log-messages').click(function (){
            var wpaicg_messages = $(this).attr('data-messages');
            if(wpaicg_messages !== ''){
                wpaicg_messages = JSON.parse(wpaicg_messages);
                var html = '';
                $('.wpaicg_modal_title').html('View Chat Log');
                $.each(wpaicg_messages, function (idx, item){
                    html += '<p>';
                    if(item.type === 'ai'){
                        html += '<strong>AI:</strong>&nbsp;';
                    }
                    else{
                        html += '<strong>User:</strong>&nbsp;';
                    }
                    html += item.message;
                    html += '</p>';
                })
                $('.wpaicg_modal_content').html(html);
                $('.wpaicg-overlay').show();
                $('.wpaicg_modal').show();
            }
        })
    })
</script>
