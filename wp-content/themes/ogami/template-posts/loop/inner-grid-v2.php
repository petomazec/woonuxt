<?php $thumbsize = !isset($thumbsize) ? ogami_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;?>
<article <?php post_class('post post-grid-v2'); ?>>
    <?php
        $thumb = ogami_display_post_thumb($thumbsize);
        echo trim($thumb);
    ?>
    <div class="content">
        <div class="bottom-info">
            <a href="<?php the_permalink(); ?>"><i class="fa fa-calendar-o text-theme" aria-hidden="true"></i><?php the_time( get_option('date_format', 'd M, Y') ); ?></a>
            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><i class="fa fa-user text-theme" aria-hidden="true"></i><?php echo get_the_author(); ?></a>
        </div>
        <?php if (get_the_title()) { ?>
            <h4 class="title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
        <?php } ?>
    </div>
</article>