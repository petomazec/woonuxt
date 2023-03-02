<?php $thumbsize = !isset($thumbsize) ? ogami_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;?>
<article <?php post_class('post post-layout post-grid-v3'); ?>>
    <?php
        $thumb = ogami_display_post_thumb($thumbsize);
        echo trim($thumb);
    ?>
    <div class="content">
        <?php ogami_post_categories($post); ?>
        <?php if (get_the_title()) { ?>
            <h4 class="title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
        <?php } ?>
        <div class="top-info">
            <a href="<?php the_permalink(); ?>"><?php the_time( get_option('date_format', 'd M, Y') ); ?></a>
            <span class="comments"><?php comments_number( esc_html__('0 Comments', 'ogami'), esc_html__('1 Comment', 'ogami'), esc_html__('% Comments', 'ogami') ); ?></span>
        </div>
    </div>
</article>