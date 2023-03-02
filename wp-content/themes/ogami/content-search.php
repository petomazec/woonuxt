<?php
/**
 * The template part for displaying results in search pages
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Ogami
 * @since Ogami 1.0
 */
?>
<article <?php post_class('post post-layout post-grid-v1'); ?>>
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
        <div class="description"><?php echo ogami_substring( get_the_excerpt(),45, '...' ); ?></div>
    <?php } ?>
    <a class="btn-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'ogami'); ?><i class="text-theme fa fa-angle-double-right" aria-hidden="true"></i></a>
</article>