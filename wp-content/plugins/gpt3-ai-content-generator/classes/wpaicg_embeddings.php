<?php
namespace WPAICG;
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('\\WPAICG\\WPAICG_Embeddings')) {
    class WPAICG_Embeddings
    {
        private static  $instance = null ;
        public $wpaicg_max_file_size = 10485760;

        public static function get_instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct()
        {
            add_action('wp_ajax_wpaicg_embeddings',[$this,'wpaicg_embeddings']);
            add_action( 'admin_menu', array( $this, 'wpaicg_menu' ) );
            add_action('init',[$this,'wpaicg_cron_job'],1);
            add_action('wp_ajax_wpaicg_builder_reindex',[$this,'wpaicg_builder_reindex']);
            add_action('wp_ajax_wpaicg_builder_delete',[$this,'wpaicg_builder_delete']);
            add_action('wp_ajax_wpaicg_builder_list',[$this,'wpaicg_builder_list']);
        }

        public function wpaicg_builder_list()
        {
            global $wpdb;
            $wpaicg_result = array('status' => 'success', 'msg' => 'Something went wrong');
            $wpaicg_embedding_page = isset($_REQUEST['wpage']) && !empty($_REQUEST['wpage']) ? sanitize_text_field($_REQUEST['wpage']) : 1;
            $wpaicg_embeddings = new \WP_Query(array(
                'post_type' => 'wpaicg_builder',
                'posts_per_page' => 40,
                'paged' => $wpaicg_embedding_page,
                'order' => 'DESC',
                'orderby' => 'date'
            ));
            ob_start();
            if($wpaicg_embeddings->have_posts()){
                foreach ($wpaicg_embeddings->posts as $wpaicg_embedding){
                    include WPAICG_PLUGIN_DIR.'admin/views/embeddings/builder_item.php';
                }
            }
            $wpaicg_result['html'] = ob_get_clean();
            ob_start();
            echo paginate_links( array(
                'base'         => admin_url('admin.php?page=wpaicg_embeddings&action=builder&wpage=%#%'),
                'total'        => $wpaicg_embeddings->max_num_pages,
                'current'      => $wpaicg_embedding_page,
                'format'       => '?wpage=%#%',
                'show_all'     => false,
                'prev_next'    => false,
                'add_args'     => false,
            ));
            $wpaicg_result['paginate'] = ob_get_clean();
            $wpaicg_builder_types = get_option('wpaicg_builder_types',[]);
            $wpaicg_result['types'] = array();
            if($wpaicg_builder_types && is_array($wpaicg_builder_types) && count($wpaicg_builder_types)){
                foreach($wpaicg_builder_types as $wpaicg_builder_type){
                    $sql_count_data = "SELECT COUNT(p.ID) FROM ".$wpdb->posts." p WHERE p.post_type='".$wpaicg_builder_type."' AND p.post_status = 'publish'";
                    $total_data = $wpdb->get_var($sql_count_data);
                    $sql_done_data = "SELECT COUNT(p.ID) FROM ".$wpdb->postmeta." m LEFT JOIN ".$wpdb->posts." p ON p.ID=m.post_id WHERE p.post_type='".$wpaicg_builder_type."' AND p.post_status = 'publish' AND m.meta_key='wpaicg_indexed' AND m.meta_value IN ('error','skip','yes')";
                    $total_converted = $wpdb->get_var($sql_done_data);
                    if($total_data > 0) {
                        $percent_process = ceil($total_converted * 100 / $total_data);
                        $wpaicg_result['types'][] = array(
                            'type' => $wpaicg_builder_type,
                            'text' => $total_converted.'/'.$total_data,
                            'percent' => $percent_process
                        );
                    }
                }
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_builder_delete()
        {
            $wpaicg_result = array('status' => 'error','msg' => 'Something went wrong');
            if(isset($_POST['id']) && !empty($_POST['id'])) {
                $id = sanitize_text_field($_POST['id']);
                $wpaicg_pinecone_api = get_option('wpaicg_pinecone_api','');
                $wpaicg_pinecone_environment = get_option('wpaicg_pinecone_environment','');
                if(empty($wpaicg_pinecone_api) || empty($wpaicg_pinecone_environment)){
                    $wpaicg_result['msg'] = 'Missing Pinecone API Settings';
                }
                else {
                    $headers = array(
                        'Content-Type' => 'application/json',
                        'Api-Key' => $wpaicg_pinecone_api
                    );
                    $response = wp_remote_get('https://'.$wpaicg_pinecone_environment.'/databases',array(
                        'headers' => $headers
                    ));
                    if(is_wp_error($response)){
                        $wpaicg_result['msg'] = $response->get_error_message();
                        return $wpaicg_result;
                    }
                    $response_code = $response['response']['code'];
                    if($response_code !== 200){
                        $wpaicg_result['msg'] = $response['body'];
                        return $wpaicg_result;
                    }
                    $response = wp_remote_request('https://' . $wpaicg_pinecone_environment . '/vectors/delete?ids='.$id, array(
                        'method' => 'DELETE',
                        'headers' => $headers
                    ));
                    if(is_wp_error($response)){
                        $wpaicg_result['msg'] = $response->get_error_message();
                    }
                    else{
                        wp_delete_post($id);
                        $wpaicg_result['status'] = 'success';
                    }
                }

            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_builder_reindex()
        {
            $wpaicg_result = array('status' => 'error','msg' => 'Something went wrong');
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $id = sanitize_text_field($_POST['id']);
                $parent_id = get_post_meta($id,'wpaicg_parent',true);
                if($parent_id && get_post($parent_id)){
                    update_post_meta($id,'wpaicg_indexed','reindex');
                    update_post_meta($parent_id,'wpaicg_indexed','reindex');
                    $wpaicg_result['status'] = 'success';
                }
                else{
                    $wpaicg_result['msg'] = 'Data need convert has been deleted';
                }
            }
            wp_send_json($wpaicg_result);
        }

        public function wpaicg_cron_job()
        {
            if(isset($_SERVER['argv']) && is_array($_SERVER['argv']) && count($_SERVER['argv'])){
                foreach( $_SERVER['argv'] as $arg ) {
                    $e = explode( '=', $arg );
                    if($e[0] == 'wpaicg_builder') {
                        if (count($e) == 2)
                            $_GET[$e[0]] = sanitize_text_field($e[1]);
                        else
                            $_GET[$e[0]] = 0;
                    }
                }
            }
            if(isset($_GET['wpaicg_builder']) && sanitize_text_field($_GET['wpaicg_builder']) == 'yes'){
                //$wpaicg_running = WPAICG_PLUGIN_DIR.'/wpaicg_builder.txt';
                //if(!file_exists($wpaicg_running)) {
//                    $wpaicg_file = fopen($wpaicg_running, "a") or die("Unable to open file!");
//                    $txt = 'running';
//                    fwrite($wpaicg_file, $txt);
//                    fclose($wpaicg_file);
                    try {
                        $_SERVER["REQUEST_METHOD"] = 'GET';
//                        chmod($wpaicg_running,0777);
                        $this->wpaicg_builer();
                    }
                    catch (\Exception $exception){
                        $wpaicg_error = WPAICG_PLUGIN_DIR.'wpaicg_error.txt';
                        $wpaicg_file = fopen($wpaicg_error, "a") or die("Unable to open file!");
                        $txt = $exception->getMessage();
                        fwrite($wpaicg_file, $txt);
                        fclose($wpaicg_file);

                    }
//                    @unlink($wpaicg_running);
//                }
                exit;
            }
        }

        public function wpaicg_builer()
        {
            global $wpdb;
            $wpaicg_cron_added = get_option( 'wpaicg_cron_builder_added', '' );
            if(empty($wpaicg_cron_added)){
                update_option( 'wpaicg_cron_builder_added', time() );
            }
            else {
                update_option( 'wpaicg_crojob_builder_last_time', time() );
                $wpaicg_builder_types = get_option('wpaicg_builder_types', []);
                $wpaicg_builder_enable = get_option('wpaicg_builder_enable', '');
                if ($wpaicg_builder_enable == 'yes' && is_array($wpaicg_builder_types) && count($wpaicg_builder_types)) {
                    $wpaicg_sql = "SELECT p.ID,p.post_title, p.post_content,p.post_type FROM " . $wpdb->posts . " p LEFT JOIN " . $wpdb->postmeta . " m ON m.post_id=p.ID AND m.meta_key='wpaicg_indexed' WHERE (m.meta_value IS NULL OR m.meta_value='' OR m.meta_value='reindex') AND p.post_content!='' AND p.post_type IN ('" . implode("','", $wpaicg_builder_types) . "') AND p.post_status = 'publish' ORDER BY p.ID ASC LIMIT 1";
                    $wpaicg_data = $wpdb->get_row($wpaicg_sql);
                    if($wpaicg_data) {
                        $wpaicg_content = $wpaicg_data->post_content;
                        preg_match_all('@\[([^<>&/\[\]\x00-\x20=]++)@', $wpaicg_content, $matches);
                        if ($matches && is_array($matches) && count($matches)) {
                            $pattern = get_shortcode_regex($matches[1]);
                            $wpaicg_content = preg_replace_callback("/$pattern/", 'strip_shortcode_tag', $wpaicg_content);
                        }
                        $wpaicg_content = trim($wpaicg_content);
                        $wpaicg_content = preg_replace('/<a(.*)href="([^"]*)"(.*)>(.*?)<\/a>/i', '$2', $wpaicg_content);
                        $wpaicg_content = strip_tags($wpaicg_content);
                        $wpaicg_content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $wpaicg_content);
                        $wpaicg_content = trim($wpaicg_content);
                        if (empty($wpaicg_content)) {
                            update_post_meta($wpaicg_data->ID, 'wpaicg_indexed', 'skip');
                        } else {
                            /*Check If is Re-Index*/
                            $check = $wpdb->get_row("SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key='wpaicg_parent' AND meta_value=" . $wpaicg_data->ID);
                            $wpaicg_old_builder = false;
                            if ($check) {
                                $wpaicg_old_builder = $check->post_id;
                            }
                            $wpaicg_result = $this->wpaicg_save_embedding($wpaicg_content, 'wpaicg_builder', $wpaicg_data->post_title, $wpaicg_old_builder);
                            if ($wpaicg_result && is_array($wpaicg_result) && isset($wpaicg_result['status'])) {
                                if ($wpaicg_result['status'] == 'error') {
                                    if ($wpaicg_old_builder) {
                                        $embedding_id = $wpaicg_old_builder;
                                    } else {
                                        $embedding_data = array(
                                            'post_type' => 'wpaicg_builder',
                                            'post_title' => $wpaicg_data->post_title,
                                            'post_content' => $wpaicg_content,
                                            'post_status' => 'publish'
                                        );
                                        $embedding_id = wp_insert_post($embedding_data);
                                    }
                                    update_post_meta($wpaicg_data->ID, 'wpaicg_indexed', 'error');
                                    update_post_meta($embedding_id, 'wpaicg_indexed', 'error');
                                    update_post_meta($embedding_id, 'wpaicg_source', $wpaicg_data->post_type);
                                    update_post_meta($embedding_id, 'wpaicg_parent', $wpaicg_data->ID);
                                    update_post_meta($embedding_id, 'wpaicg_error_msg', $wpaicg_result['msg']);
                                } else {
                                    update_post_meta($wpaicg_data->ID, 'wpaicg_indexed', 'yes');
                                    update_post_meta($wpaicg_result['id'], 'wpaicg_indexed', 'yes');
                                    update_post_meta($wpaicg_result['id'], 'wpaicg_source', $wpaicg_data->post_type);
                                    update_post_meta($wpaicg_result['id'], 'wpaicg_parent', $wpaicg_data->ID);
                                }
                            } else {
                                if ($wpaicg_old_builder) {
                                    $embedding_id = $wpaicg_old_builder;
                                } else {
                                    $embedding_data = array(
                                        'post_type' => 'wpaicg_builder',
                                        'post_title' => $wpaicg_data->post_title,
                                        'post_content' => $wpaicg_content,
                                        'post_status' => 'publish'
                                    );
                                    $embedding_id = wp_insert_post($embedding_data);
                                }
                                update_post_meta($embedding_id, 'wpaicg_source', $wpaicg_data->post_type);
                                update_post_meta($embedding_id, 'wpaicg_parent', $wpaicg_data->ID);
                                update_post_meta($wpaicg_data->ID, 'wpaicg_indexed', 'error');
                                update_post_meta($embedding_id, 'wpaicg_indexed', 'error');
                                update_post_meta($embedding_id, 'wpaicg_error_msg', 'Something went wrong');
                            }
                        }
                    }
                }
            }
        }

        public function wpaicg_menu()
        {
            add_submenu_page(
                'wpaicg',
                'Content Builder',
                'Content Builder',
                'manage_options',
                'wpaicg_embeddings',
                array( $this, 'wpaicg_main' )
            );
        }

        public function wpaicg_main()
        {
            include WPAICG_PLUGIN_DIR.'admin/views/embeddings/index.php';
        }

        public function wpaicg_save_embedding($content, $post_type = '', $title = '', $embaddings_id = false)
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            $openai = WPAICG_OpenAI::get_instance()->openai();
            $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
            if($openai){
                $wpaicg_pinecone_api = get_option('wpaicg_pinecone_api','');
                $wpaicg_pinecone_environment = get_option('wpaicg_pinecone_environment','');
                if(empty($wpaicg_pinecone_api) || empty($wpaicg_pinecone_environment)){
                    $wpaicg_result['msg'] = 'Missing Pinecone API Settings';
                }
                else{
                    $headers = array(
                        'Content-Type' => 'application/json',
                        'Api-Key' => $wpaicg_pinecone_api
                    );
                    /*Check Pinecone API*/
                    $response = wp_remote_get('https://'.$wpaicg_pinecone_environment.'/databases',array(
                        'headers' => $headers
                    ));
                    if(is_wp_error($response)){
                        $wpaicg_result['msg'] = $response->get_error_message();
                        return $wpaicg_result;
                    }

                    $response_code = $response['response']['code'];
                    if($response_code !== 200){
                        $wpaicg_result['msg'] = $response['body'];
                        return $wpaicg_result;
                    }
                    $response = $openai->embeddings(array(
                        'input' => $content,
                        'model' => 'text-embedding-ada-002'
                    ));
                    $response = json_decode($response,true);
                    if(isset($response['error']) && !empty($response['error'])) {
                        $wpaicg_result['msg'] = $response['error']['message'];
                    }
                    else{
                        $embedding = $response['data'][0]['embedding'];
                        if(empty($embedding)){
                            $wpaicg_result['msg'] = 'No data returned';
                        }
                        else{
                            $pinecone_url = 'https://' . $wpaicg_pinecone_environment . '/vectors/upsert';
                            if(!$embaddings_id) {
                                $embedding_title = empty($title) ? substr($content, 0, 50) : $title;
                                $embedding_data = array(
                                    'post_type' => 'wpaicg_embeddings',
                                    'post_title' => $embedding_title,
                                    'post_content' => $content,
                                    'post_status' => 'publish'
                                );
                                if (!empty($post_type)) {
                                    $embedding_data['post_type'] = $post_type;
                                }
                                $embaddings_id = wp_insert_post($embedding_data);
                            }
                            if(is_wp_error($embaddings_id)){
                                $wpaicg_result['msg'] = $embaddings_id->get_error_message();
                            }
                            else {
                                update_post_meta($embaddings_id,'wpaicg_start',time());
                                $usage_tokens = $response['usage']['total_tokens'];
                                add_post_meta($embaddings_id, 'wpaicg_embedding_token', $usage_tokens);
                                $vectors = array(
                                    array(
                                        'id' => (string)$embaddings_id,
                                        'values' => $embedding
                                    )
                                );
                                $response = wp_remote_post($pinecone_url, array(
                                    'headers' => $headers,
                                    'body' => json_encode(array('vectors' => $vectors))
                                ));
                                if(is_wp_error($response)){
                                    $wpaicg_result['msg'] = $response->get_error_message();
                                    wp_delete_post($embaddings_id);
                                }
                                else{
                                    $body = json_decode($response['body'],true);
                                    if($body){
                                        if(isset($body['code']) && isset($body['message'])){
                                            $wpaicg_result['msg'] = strip_tags($body['message']);
                                            wp_delete_post($embaddings_id);
                                        }
                                        else{
                                            $wpaicg_result['status'] = 'success';
                                            $wpaicg_result['id'] = $embaddings_id;
                                            update_post_meta($embaddings_id,'wpaicg_completed',time());
                                        }
                                    }
                                    else{
                                        $wpaicg_result['msg'] = 'No data returned';
                                        wp_delete_post($embaddings_id);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else{
                $wpaicg_result['msg'] = 'Missing OpenAI API Settings';
            }
            return $wpaicg_result;
        }

        public function wpaicg_embeddings()
        {
            $wpaicg_result = array('status' => 'error', 'msg' => 'Something went wrong');
            if(isset($_POST['content']) && !empty($_POST['content'])){
                $content = wp_kses_post(strip_tags($_POST['content']));
                if(!empty($content)){
                    $wpaicg_result = $this->wpaicg_save_embedding($content);
                }
                else $wpaicg_result['msg'] = 'Please insert content';
            }
            wp_send_json($wpaicg_result);
        }
    }
    WPAICG_Embeddings::get_instance();
}
