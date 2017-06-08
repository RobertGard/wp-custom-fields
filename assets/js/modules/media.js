/**
 * Our jquery UI slider
 */
module.exports.media = function(framework) {
    
    /**
     * Enables Uploading using the Media-Uploader
     */
    jQuery(framework).find('.divergent-upload-wrapper').each(function (index) {

        // Define the buttons for this specific group
        var add_media = jQuery(this).find('.divergent-upload-add'),
            remove_media = jQuery(this).find('.divergent-upload-remove'),
            value_input = jQuery(this).find('.divergent-upload-value'),
            title = jQuery(this).data('title'),
            type = jQuery(this).data('type'),
            button = jQuery(this).data('button'),
            multiple = jQuery(this).data('multiple'),
            add_wrap = jQuery(this).find('.divergent-single-media.empty'),
            initator = this,
            frame;

        // Click function
        add_media.live('click', function (e) {

            e.preventDefault();

            // If the media frame already has been opened before, it can just be reopened.
            if (frame) {
                frame.open();
                return;
            }

            // Create the media frame.
            frame = wp.media.frames.frame = wp.media({

                // Determine the title for the modal window
                title: title,

                // Show only the provided types
                library: {
                    type: type
                },

                // Determine the submit button text
                button: {
                    text: button
                },

                // Can we select multiple or only one?
                multiple: multiple

            });

            // If media is selected, add the input value
            frame.on('select', function () {

                // Grab the selected attachment.
                var attachments = frame.state().get('selection').toJSON(),
                    attachment_ids = value_input.val(),
                    loop_counter = 0,
                    src;

                // We store the ids for each image
                attachments.forEach(function (attachment) {
                    attachment_ids += attachment.id + ',';

                    if( attachment.type === 'image') {
                        src = attachment.url;
                    } else {
                        src = attachment.icon;
                    }

                    add_wrap.before('<div class="divergent-single-media" data-id="' + attachment.id + '"><img src="' + src + '" /><a href="#" class="divergent-upload-remove"><i class="material-icons">clear</i></a></div>');
                });

                value_input.val(attachment_ids);

            });

            // Open the media upload modal
            frame.open();

        });

        /**
         * Remove attachments
         */
        remove_media.live('click', function (e) {
            e.preventDefault();

            var target = jQuery(this).closest('.divergent-single-media'),
                target_id = target.data('id'),
                current_values = value_input.val(),
                new_values = current_values.replace(target_id + ',', '');

            target.remove();

            value_input.val(new_values);

        });

    });
    
}