<?php 
    $thumbsize = !isset($thumbsize) ? ogami_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
    $thumb = ogami_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-grid-v1'); ?>>
    <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
        <span class="post-sticky"><?php echo esc_html__('Featured','ogami'); ?></span>
    <?php endif; ?>
    <?php if (get_the_title()) { ?>
        <h4 class="entry-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
    <?php } ?>
    <?php if($thumb) {?>
        <div class="top-image">
            <?php
                echo trim($thumb);
            ?>
            <?php ogami_post_categories($post); ?>
         </div>
    <?php }else{ ?>
        <div class="no-image">
            <?php ogami_post_categories($post); ?>
        </div>
    <?php } ?>
    <div class="top-info">
        <a href="<?php the_permalink(); ?>"><?php the_time( get_option('date_format', 'd M, Y') ); ?></a>
        <span class="comments"><?php comments_number( esc_html__('0 Comments', 'ogami'), esc_html__('1 Comment', 'ogami'), esc_html__('% Comments', 'ogami') ); ?></span>
    </div>
    <?php if(has_excerpt()){?>
        <div class="description"><?php echo ogami_substring( get_the_excerpt(),45, '...' ); ?></div>
    <?php } ?>
    <a class="btn-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'ogami'); ?><i class="text-theme fa fa-angle-double-right" aria-hidden="true"></i></a>
</article>