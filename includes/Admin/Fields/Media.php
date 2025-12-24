<?php

namespace PluginBoilerplate\Admin\Fields;

class Media extends Field
{
    public function render(): void
    {
        $multiple = (bool) ($this->args['multiple'] ?? false);
        $type     = $this->args['type'] ?? 'image';

        $value = $this->get_value();

        $ids = $multiple
                ? (is_array($value) ? array_map('absint', $value) : [])
                : (absint($value) ? [absint($value)] : []);

        wp_enqueue_media();
        ?>

        <div class="plugin-boilerplate-media-field"
             data-field="<?php echo esc_attr($this->id); ?>"
             data-multiple="<?php echo esc_attr($multiple ? '1' : '0'); ?>"
             data-type="<?php echo esc_attr($type); ?>">

            <div class="plugin-boilerplate-media-preview"
                 style="margin-bottom:10px; display:flex; gap:10px; flex-wrap:wrap;"
                    <?php echo $multiple ? 'data-sortable="1"' : ''; ?>>

                <?php foreach ($ids as $id): ?>
                    <?php
                    $mime = get_post_mime_type($id);
                    $url  = wp_get_attachment_url($id);
                    ?>
                    <div class="plugin-boilerplate-media-item" data-id="<?php echo esc_attr($id); ?>">
                        <?php if ($multiple): ?>
                        <span class="plugin-boilerplate-media-handle" title="Drag">☰</span>
                        <?php endif; ?>
                        <?php if ($mime && strpos($mime, 'image/') === 0): ?>
                            <?php echo wp_get_attachment_image($id, 'thumbnail'); ?>
                        <?php else: ?>
                            <span class="plugin-boilerplate-media-file">
                                📄 <?php echo esc_html(basename($url)); ?>
                            </span>
                        <?php endif; ?>

                        <button type="button"
                                class="button-link plugin-boilerplate-media-remove-item"
                                title="Remove">✕</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="plugin-boilerplate-media-inputs"></div>

            <button type="button" class="button plugin-boilerplate-media-select">
                <?php echo esc_html($this->args['button'] ?? 'Select Media'); ?>
            </button>

            <?php $this->render_description(); ?>
        </div>

        <style>
            .plugin-boilerplate-media-item {
                position: relative;
            }
            .plugin-boilerplate-media-item img {
                max-width: 100px;
                height: auto;
            }
            .plugin-boilerplate-media-handle {
                position: absolute;
                top: 2px;
                left: 4px;
                cursor: grab;
                font-size: 14px;
                color: #555;
                z-index: 10;
            }
            .plugin-boilerplate-media-remove-item {
                position: absolute;
                top: 2px;
                right: 4px;
                cursor: pointer;
                color: #b32d2e !important;
                z-index: 20;
                text-decoration: none !important;
            }
            .plugin-boilerplate-media-file {
                display: inline-block;
                padding: 6px 18px;
                background: #f6f7f7;
                border: 1px solid #ccd0d4;
                border-radius: 4px;
                font-size: 13px;
            }
            .plugin-boilerplate-media-item:has(.plugin-boilerplate-media-file) {
                .plugin-boilerplate-media-handle,
                .plugin-boilerplate-media-remove-item {
                    top: 5px;
                }
            }
        </style>

        <script>
            jQuery(function($){
                const wrapper  = $('.plugin-boilerplate-media-field[data-field="<?php echo esc_js($this->id); ?>"]');
                const preview  = wrapper.find('.plugin-boilerplate-media-preview');
                const inputs   = wrapper.find('.plugin-boilerplate-media-inputs');
                const multiple = wrapper.data('multiple') === 1;

                function syncInputs() {
                    inputs.empty();

                    preview.find('.plugin-boilerplate-media-item').each(function(){
                        inputs.append(
                            '<input type="hidden" name="<?php echo esc_js($this->get_option_name()); ?>' +
                            (multiple ? '[]' : '') +
                            '" value="' + $(this).data('id') + '">'
                        );
                    });
                }

                if (multiple && preview.data('sortable') === 1) {
                    preview.sortable({
                        handle: '.plugin-boilerplate-media-handle',
                        cancel: '.plugin-boilerplate-media-remove-item',
                        update: syncInputs
                    });
                }

                preview.on('click', '.plugin-boilerplate-media-remove-item', function(e){
                    e.preventDefault();
                    e.stopPropagation();

                    $(this).closest('.plugin-boilerplate-media-item').remove();
                    syncInputs();
                });

                wrapper.find('.plugin-boilerplate-media-select').on('click', function(e){
                    e.preventDefault();

                    const frame = wp.media({
                        title: 'Select Media',
                        multiple: multiple,
                        library: { type: wrapper.data('type') },
                        button: { text: 'Use selected' }
                    });

                    frame.on('select', function(){
                        const selection = frame.state().get('selection');
                        preview.empty();

                        selection.each(function(attachment){
                            attachment = attachment.toJSON();

                            let html = '<div class="plugin-boilerplate-media-item" data-id="' + attachment.id + '">';
                            if (multiple) {
                                html += '<span class="plugin-boilerplate-media-handle">☰</span>';
                            }
                            if (attachment.type === 'image' && attachment.sizes?.thumbnail) {
                                html += '<img src="' + attachment.sizes.thumbnail.url + '" />';
                            } else {
                                html += '<span class="plugin-boilerplate-media-file">📄 ' + attachment.filename + '</span>';
                            }

                            html += '<button type="button" class="button-link plugin-boilerplate-media-remove-item">✕</button>';
                            html += '</div>';

                            preview.append(html);
                        });

                        syncInputs();
                    });

                    frame.open();
                });

                // CRITICAL: sync just before form submit
                wrapper.closest('form').on('submit', function(){
                    syncInputs();
                });

                // Initial sync for preloaded values
                syncInputs();
            });
        </script>
        <?php
    }

    public function sanitize($value)
    {
        if (is_array($value)) {
            return array_values(
                    array_filter(array_map('absint', $value))
            );
        }

        return absint($value);
    }
}
