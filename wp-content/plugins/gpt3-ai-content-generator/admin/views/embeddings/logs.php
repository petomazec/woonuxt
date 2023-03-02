<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wpaicg_embedding_page = isset($_GET['wpage']) && !empty($_GET['wpage']) ? sanitize_text_field($_GET['wpage']) : 1;
$wpaicg_embeddings = new WP_Query(array(
    'post_type' => 'wpaicg_embeddings',
    'posts_per_page' => 40,
    'paged' => $wpaicg_embedding_page,
    'order' => 'DESC',
    'orderby' => 'date'
));
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
<table class="wp-list-table widefat fixed striped table-view-list posts">
    <thead>
    <tr>
        <th scope="col">Content</th>
        <th scope="col">Token</th>
        <th scope="col">Estimated</th>
        <th scope="col">Date</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($wpaicg_embeddings->have_posts()){
        foreach ($wpaicg_embeddings->posts as $wpaicg_embedding){
            $token = get_post_meta($wpaicg_embedding->ID,'wpaicg_embedding_token',true);
            ?>
            <tr id="wpaicg-builder-<?php echo esc_html($wpaicg_embedding->ID)?>">
                <td><a data-content="<?php echo esc_html($wpaicg_embedding->post_content)?>" href="javascript:void()" class="wpaicg-embedding-content"><?php echo esc_html($wpaicg_embedding->post_title)?>..</a></td>
                <td><?php echo esc_html($token)?></td>
                <td><?php echo !empty($token) ? ((int)esc_html($token)*0.0004).'$': '--'?></td>
                <td><?php echo esc_html($wpaicg_embedding->post_date)?></td>
                <td><button data-id="<?php echo esc_html($wpaicg_embedding->ID)?>" class="button button-link-delete wpaicg_delete button-small">Delete</button></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>
<div class="wpaicg-paginate">
    <?php
    echo paginate_links( array(
        'base'         => admin_url('admin.php?page=wpaicg_embeddings&action=logs&wpage=%#%'),
        'total'        => $wpaicg_embeddings->max_num_pages,
        'current'      => $wpaicg_embedding_page,
        'format'       => '?wpage=%#%',
        'show_all'     => false,
        'prev_next'    => false,
        'add_args'     => false,
    ));
    ?>
</div>
<script>
    jQuery(document).ready(function ($){
        function wpaicgLoading(btn){
            btn.attr('disabled','disabled');
            if(!btn.find('spinner').length){
                btn.append('<span class="spinner"></span>');
            }
            btn.find('.spinner').css('visibility','unset');
        }
        function wpaicgRmLoading(btn){
            btn.removeAttr('disabled');
            btn.find('.spinner').remove();
        }
        $('.wpaicg_modal_close').click(function (){
            $('.wpaicg_modal_close').closest('.wpaicg_modal').hide();
            $('.wpaicg-overlay').hide();
        })
        $('.wpaicg-embedding-content').click(function (){
            var content = $(this).attr('data-content');
            $('.wpaicg_modal_title').html('Embedding Content');
            $('.wpaicg_modal_content').html(content);
            $('.wpaicg-overlay').show();
            $('.wpaicg_modal').show();
        });
        $(document).on('click','.wpaicg_delete' ,function (e){
            var btn = $(e.currentTarget);
            var id = btn.attr('data-id');
            var conf = confirm('Are you sure?');
            if(conf){
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php')?>',
                    data: {action: 'wpaicg_builder_delete', id: id},
                    dataType: 'JSON',
                    type: 'POST',
                    beforeSend: function (){
                        wpaicgLoading(btn);
                    },
                    success: function (res){
                        wpaicgRmLoading(btn);
                        if(res.status === 'success'){
                            $('#wpaicg-builder-'+id).remove();
                        }
                        else{
                            alert(res.msg);
                        }
                    },
                    error: function (){
                        wpaicgRmLoading(btn);
                        alert('Something went wrong');
                    }
                })
            }
        });
    })
</script>
