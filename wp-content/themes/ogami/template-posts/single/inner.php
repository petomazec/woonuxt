<?php
$post_format = get_post_format();
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (get_the_title()) { ?>
        <h1 class="entry-title-detail">
            <?php the_title(); ?>
        </h1>
    <?php } ?>
    <div class="top-info-detail post-layout">
        <?php if( $post_format == 'link' ) {
            $format = ogami_post_format_link_helper( get_the_content(), get_the_title() );
            $title = $format['title'];
            $link = ogami_get_link_attributes( $title );
            $thumb = ogami_post_thumbnail('', $link);
            echo trim($thumb);
        } else { ?>
            <div class="entry-thumb <?php echo  (!has_post_thumbnail() ? 'no-thumb' : ''); ?>">
                <?php
                    $thumb = ogami_post_thumbnail();
                    echo trim($thumb);
                ?>
            </div>
        <?php } ?>

        <?php if ( has_post_thumbnail() ) { ?>
            <?php ogami_post_categories($post); ?>
        <?php } ?>
        
    </div>
	<div class="entry-content-detail">
        <div class="post-layout">
            <div class="top-info">
                <?php if ( !has_post_thumbnail() ) { ?>
                    <?php ogami_post_categories($post); ?>
                <?php } ?>
                <a href="<?php the_permalink(); ?>"><?php the_time( get_option('date_format', 'd M, Y') ); ?></a>
                <span class="comments"><?php comments_number( esc_html__('0 Comments', 'ogami'), esc_html__('1 Comment', 'ogami'), esc_html__('% Comments', 'ogami') ); ?></span>
            </div>
        </div>
    	<div class="single-info info-bottom">
            <div class="entry-description">
                <?php
                    
                        the_content();
                ?>
            </div><!-- /entry-content -->
    		<?php
    		wp_link_pages( array(
    			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'ogami' ) . '</span>',
    			'after'       => '</div>',
    			'link_before' => '<span>',
    			'link_after'  => '</span>',
    			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'ogami' ) . ' </span>%',
    			'separator'   => '',
    		) );
    		?>
            <?php  
                $posttags = get_the_tags();
            ?>
            <?php if( !empty($posttags) || ogami_get_config('show_blog_social_share', false) ){ ?>
        		<div class="tag-social clearfix">
                    <?php ogami_post_tags(); ?>
        			<?php if( ogami_get_config('show_blog_social_share', false) ) {
        				get_template_part( 'template-parts/sharebox' );
        			} ?>
        		</div>
            <?php } ?>
    	</div>
    </div>
</article>