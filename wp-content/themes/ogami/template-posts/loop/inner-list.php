<?php 
global $post;
$thumbsize = !isset($thumbsize) ? ogami_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
$thumb = ogami_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-list-item'); ?>>
    <div class="list-inner ">
        <div class="row <?php echo (!empty($thumb))?'flex-middle':''; ?>">
            <?php
                if ( !empty($thumb) ) {
                    ?>
                    <div class="image col-xs-5">
                        <?php echo trim($thumb); ?>
                    </div>
                    <?php
                }
            ?>
            <div class="<?php echo (!empty($thumb))?'col-xs-7':'col-xs-12'; ?>">
                <?php ogami_post_categories($post); ?>
                <?php if (get_the_title()) { ?>
                    <h4 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                <?php } ?>
                <div class="top-info">
                    <a href="<?php the_permalink(); ?>"><?php the_time( get_option('date_format', 'd M, Y') ); ?></a>
                    <span class="comments"><?php comments_number( esc_html__('0 Comments', 'ogami'), esc_html__('1 Comment', 'ogami'), esc_html__('% Comments', 'ogami') ); ?></span>
                </div>
                <?php if(has_excerpt()){?>
                    <div class="description"><?php echo ogami_substring( get_the_excerpt(), 15, '...' ); ?></div>
                <?php } ?>
                <a class="btn-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'ogami'); ?><i class="text-theme fa fa-angle-double-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</article>