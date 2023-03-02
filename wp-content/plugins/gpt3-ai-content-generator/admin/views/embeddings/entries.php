<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<form action="" method="post" id="wpaicg_embeddings_form">
    <input type="hidden" name="action" value="wpaicg_embeddings">
    <div class="wpaicg-embeddings-success" style="padding: 10px;background: #fff;border-left: 2px solid #11ad6b;display: none">Record saved successfully</div>
    <div class="wpaicg-alert">
        <h3>Steps</h3>
        <p>1. First watch this video tutorial <a href="https://www.youtube.com/watch?v=NPMLGwFQYrY" target="_blank">here</a>.</p>
        <p>2. Get your API key from <a href="https://www.pinecone.io/" target="_blank">Pinecone</a>.</p>
        <p>3. Create an Index on Pinecone.</p>
        <p>4. Make sure to set your dimension to <b>1536</b>.</p>
        <p>5. Make sure to set your metric to <b>cosine</b>.</p>
        <p>6. Enter your data manually or use Index Builder to convert all your content automatically.</p>
        <p>7. Go to Settings - ChatGPT tab and select Embeddings method.</p>
    </div>
    <div class="wpaicg-mb-10">
        <p><strong>Content</strong></p>
        <textarea name="content" class="wpaicg-embeddings-content" rows="15"></textarea>
    </div>
    <button class="button button-primary">Save</button>
</form>
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
        $('#wpaicg_embeddings_form').on('submit', function (e){
            var form = $(e.currentTarget);
            var btn = form.find('button');
            var content = $('.wpaicg-embeddings-content').val();
            if(content === ''){
                alert('Please insert content')
            }
            else{
                var data = form.serialize();
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php')?>',
                    data: data,
                    dataType: 'JSON',
                    type: 'POST',
                    beforeSend: function (){
                        wpaicgLoading(btn)
                    },
                    success: function (res){
                        wpaicgRmLoading(btn);
                        if(res.status === 'success'){
                            $('.wpaicg-embeddings-success').show();
                            $('.wpaicg-embeddings-content').val('');
                            setTimeout(function (){
                                $('.wpaicg-embeddings-success').hide();
                            },2000)
                        }
                        else{
                            alert(res.msg)
                        }
                    },
                    error: function (){
                        wpaicgRmLoading(btn);
                        alert('Something went wrong');
                    }
                })
            }
            return false;
        })
    })
</script>
