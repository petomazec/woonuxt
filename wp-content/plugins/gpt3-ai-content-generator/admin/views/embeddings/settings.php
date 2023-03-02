<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wpaicg_embeddings_settings_updated = false;
if(isset($_POST['wpaicg_pinecone_update'])){
    if(isset($_POST['wpaicg_pinecone_api']) && !empty($_POST['wpaicg_pinecone_api'])) {
        update_option('wpaicg_pinecone_api', sanitize_text_field($_POST['wpaicg_pinecone_api']));
    }
    else{
        delete_option('wpaicg_pinecone_api');
    }
    if(isset($_POST['wpaicg_pinecone_environment']) && !empty($_POST['wpaicg_pinecone_environment'])) {
        update_option('wpaicg_pinecone_environment', sanitize_text_field($_POST['wpaicg_pinecone_environment']));
    }
    else{
        delete_option('wpaicg_pinecone_environment');
    }
    $wpaicg_embeddings_settings_updated = true;
}
$wpaicg_pinecone_api = get_option('wpaicg_pinecone_api','');
$wpaicg_pinecone_environment = get_option('wpaicg_pinecone_environment','');
if($wpaicg_embeddings_settings_updated){
    ?>
    <div class="notice notice-success">
        <p>Records updated successfully</p>
    </div>
    <?php
}
?>
<form action="" method="post">
    <div class="wpaicg-alert">
        <h3>Steps</h3>
        <p>1. First watch this video tutorial <a href="https://www.youtube.com/watch?v=NPMLGwFQYrY" target="_blank">here</a>.</p>
        <p>2. Get your API key from <a href="https://www.pinecone.io/" target="_blank">Pinecone</a>.</p>
        <p>3. Create an Index on Pinecone.</p>
        <p>4. Make sure to set your dimension to <b>1536</b>.</p>
        <p>5. Make sure to set your metric to <b>cosine</b>.</p>
        <p>6. Enter your data.</p>
        <p>7. Go to Settings - ChatGPT tab and select Embeddings method.</p>
    </div>
    <table class="form-table">
        <tr>
            <th scope="row">Pinecone API</th>
            <td>
                <input type="text" class="regular-text" name="wpaicg_pinecone_api" value="<?php echo esc_attr($wpaicg_pinecone_api)?>">
            </td>
        </tr>
        <tr>
            <th scope="row">Pinecone Index</th>
            <td>
                <input type="text" class="regular-text" name="wpaicg_pinecone_environment" value="<?php echo esc_attr($wpaicg_pinecone_environment)?>">
                <p style="font-style: italic">Example: gptpowerai-de3f510.svc.us-east1-gcp.pinecone.io</p>
            </td>
        </tr>
        <tr>
            <th scope="row"></th>
            <td><button name="wpaicg_pinecone_update" class="button button-primary">Save</button></td>
        </tr>
    </table>
</form>
