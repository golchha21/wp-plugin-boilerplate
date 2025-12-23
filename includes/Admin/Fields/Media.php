<?php

namespace PluginBoilerplate\Admin\Fields;

class Media extends Field
{
    public function render(): void
    {
        $value        = $this->get_value();
        $button_label = $this->args['button'] ?? 'Select Media';

        wp_enqueue_media();

        $has_image = is_string($value) && $value !== '';
        ?>
        <div class="PluginBoilerplate-media-wrapper">

            <div class="PluginBoilerplate-media-preview" style="margin-bottom:10px;">
                <?php if ($has_image): ?>
                    <img src="<?php echo esc_url($value); ?>"
                         style="max-width:150px;height:auto;display:block;">
                <?php endif; ?>
            </div>

            <input
                    type="text"
                    name="<?php echo esc_attr($this->get_option_name()); ?>"
                    value="<?php echo esc_attr((string) $value); ?>"
                    class="regular-text PluginBoilerplate-media-url"
            >

            <div style="margin-top:6px;">
                <button type="button" class="button PluginBoilerplate-media-upload">
                    <?php echo esc_html($button_label); ?>
                </button>

                <button type="button" class="button PluginBoilerplate-media-clear">
                    Clear
                </button>
            </div>
        </div>

        <script>
            jQuery(function($){

                $(document).on('click', '.PluginBoilerplate-media-upload', function(e){
                    e.preventDefault();

                    const wrapper  = $(this).closest('.PluginBoilerplate-media-wrapper');
                    const urlField = wrapper.find('.PluginBoilerplate-media-url');
                    const preview  = wrapper.find('.PluginBoilerplate-media-preview');

                    const frame = wp.media({
                        title: 'Select Media',
                        button: { text: 'Use this file' },
                        multiple: false
                    });

                    frame.on('select', function(){
                        const attachment = frame.state().get('selection').first().toJSON();
                        urlField.val(attachment.url);

                        // Render thumbnail
                        if (attachment.sizes && attachment.sizes.thumbnail) {
                            preview.html(
                                '<img src="' + attachment.sizes.thumbnail.url +
                                '" style="max-width:150px;height:auto;display:block;">'
                            );
                        } else {
                            preview.html(
                                '<img src="' + attachment.url +
                                '" style="max-width:150px;height:auto;display:block;">'
                            );
                        }
                    });

                    frame.open();
                });

                $(document).on('click', '.PluginBoilerplate-media-clear', function(){
                    const wrapper = $(this).closest('.PluginBoilerplate-media-wrapper');
                    wrapper.find('.PluginBoilerplate-media-url').val('');
                    wrapper.find('.PluginBoilerplate-media-preview').empty();
                });

            });
        </script>
        <?php
    }

    public function sanitize($value): ?string
    {
        if ($value === null) {
            return null;
        }
        return esc_url_raw((string) $value);
    }

}
