<?php

if ( !function_exists( 'ogami_autocomplete_search' ) ) {
    function ogami_autocomplete_search() {
        add_action( 'wp_ajax_ogami_autocomplete_search', 'ogami_autocomplete_suggestions' );
        add_action( 'wp_ajax_nopriv_ogami_autocomplete_search', 'ogami_autocomplete_suggestions' );
    }
}
add_action( 'init', 'ogami_autocomplete_search' );

if ( !function_exists( 'ogami_autocomplete_suggestions' ) ) {
    function ogami_autocomplete_suggestions() {
        // Query for suggestions
        $args = array(
            'posts_per_page' => -1,
            'fields' => array('ID', 'post_title')
        );
        if ( isset($_REQUEST['post_type']) ) {
            $args['post_type'] = $_REQUEST['post_type'];
        }
        if ( !isset($args['post_type']) ) {
            $args['post_type'] = array( 'product' );
        }
        $posts = get_posts( $args );
        $suggestions = array();

        foreach ($posts as $post) {
            
            $suggestion = array();
            $suggestion['title'] = esc_html($post->post_title);
            $suggestion['url'] = get_permalink($post);
            if ( has_post_thumbnail( $post->ID ) ) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
                $suggestion['image'] = $image[0];
            } else {
                $suggestion['image'] = '';
            }

            $product = wc_get_product( $post->ID );
            $suggestion['price'] = $product->get_price_html();
            $suggestion['id'] = $post->ID;

            $terms = get_the_terms( $post->ID, 'product_cat' );
            $termids = array();

            if ($terms) {
                foreach($terms as $term) {
                    $termids[] = $term->slug;
                }
            }

            $suggestion['product_cat'] = $termids;

            $suggestions[] = $suggestion;
        }
        
        echo json_encode( $suggestions );
     
        exit;
    }
}