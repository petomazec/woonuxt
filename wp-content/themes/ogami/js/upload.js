jQuery(document).ready(function($){
	"use strict";
	var ogami_upload;
	var ogami_selector;

	function ogami_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		ogami_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( ogami_upload ) {
			ogami_upload.open();
			return;
		} else {
			// Create the media frame.
			ogami_upload = wp.media.frames.ogami_upload =  wp.media({
				// Set the title of the modal.
				title: "Select Image",

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: "Selected",
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			ogami_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = ogami_upload.state().get('selection').first();

				ogami_upload.close();
				ogami_selector.find('.upload_image').val(attachment.attributes.url).change();
				if ( attachment.attributes.type == 'image' ) {
					ogami_selector.find('.ogami_screenshot').empty().hide().prepend('<img src="' + attachment.attributes.url + '">').slideDown('fast');
				}
			});

		}
		// Finally, open the modal.
		ogami_upload.open();
	}

	function ogami_remove_file(selector) {
		selector.find('.ogami_screenshot').slideUp('fast').next().val('').trigger('change');
	}
	
	$('body').on('click', '.ogami_upload_image_action .remove-image', function(event) {
		ogami_remove_file( $(this).parent().parent() );
	});

	$('body').on('click', '.ogami_upload_image_action .add-image', function(event) {
		ogami_add_file(event, $(this).parent().parent());
	});

});