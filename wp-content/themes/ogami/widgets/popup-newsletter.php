
<div class="popupnewsletter hidden">
  <!-- Modal -->
  <button title="<?php echo esc_html('Close (Esc)', 'ogami'); ?>" type="button" class="mfp-close apus-mfp-close"> <span class="ti-close"></span> </button>
  <div class="modal-content" <?php if ( isset($image) && $image ) : ?> style="background:url('<?php echo esc_attr( $image ); ?>') no-repeat #fff" <?php endif; ?> >
    
      <div class="popupnewsletter-widget">
        <?php if(!empty($title)){ ?>
            <h3>
                <span><?php echo esc_html( $title ); ?></span>
            </h3>
        <?php } ?>
        
        <?php if(!empty($description)){ ?>
            <p class="description">
                <?php echo trim( $description ); ?>
            </p>
        <?php } ?>      
        <?php mc4wp_show_form(''); ?>

        <a href="javascript:void(0)" class="close-dont-show"><?php esc_html_e('Don\'t show this popup again', 'ogami'); ?></a>
    </div>
  </div>
   
</div>