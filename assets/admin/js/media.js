(function ($) {
  'use strict';

  /* =========================================
     SELECT MEDIA
  ========================================= */

  $(document).on('click', '.wppb-media-select', function (e) {
    e.preventDefault();

    const btn = $(this);
    const wrap = btn.closest('.wppb-media-field');
    const preview = wrap.find('.wppb-media-preview');

    const fieldType = btn.data('field');
    const mimes = btn.data('mimes');
    const multiple = btn.data('multiple') === true || btn.data('multiple') === 'true';
    const baseName = wrap.data('name');

    const frame = wp.media({
      title: 'Select file',
      button: {text: 'Use this file'},
      multiple: multiple,
      library: (() => {
        if (fieldType === 'image') return {type: 'image'};
        if (fieldType === 'audio') return {type: 'audio'};
        if (fieldType === 'video') return {type: 'video'};
        return {};
      })()
    });

    frame.on('select', function () {

      const selection = frame.state().get('selection');
      if (!selection || !selection.length) return;

      if (!multiple) {

        // SINGLE MODE
        const attachment = selection.first().toJSON();

        if (Array.isArray(mimes) && !mimes.includes(attachment.mime)) {
          showNotice(
            wrap,
            `File type not allowed: ${attachment.filename}`,
            'error'
          );
          return;
        }

        preview.empty();
        wrap.find('input[type="hidden"]').remove();

        preview.append(renderItem(attachment, fieldType, false, baseName));

      } else {

        // MULTIPLE MODE
        const existingIds = getExistingIds(wrap);
        const duplicates = [];
        const invalidTypes = [];

        selection.each(function (model) {

          const attachment = model.toJSON();

          if (Array.isArray(mimes) && !mimes.includes(attachment.mime)) {
            invalidTypes.push(attachment.filename);
            return;
          }

          if (existingIds.includes(attachment.id)) {
            duplicates.push(attachment.filename);
            return;
          }

          preview.append(renderItem(attachment, fieldType, true, baseName));
        });

        if (duplicates.length) {
          showNotice(
            wrap,
            duplicates.length === 1
              ? `Already selected: ${duplicates[0]}`
              : `Already selected: ${duplicates.join(', ')}`,
            'warning'
          );
        }

        if (invalidTypes.length) {
          showNotice(
            wrap,
            invalidTypes.length === 1
              ? `File type not allowed: ${invalidTypes[0]}`
              : `File types not allowed: ${invalidTypes.join(', ')}`,
            'error'
          );
        }
      }

      getExistingIds(wrap);
      updateRemoveState(wrap);
    });

    frame.open();
  });


  /* =========================================
     REMOVE SINGLE ITEM (Multiple Mode)
  ========================================= */

  $(document).on('click', '.wppb-media-item-remove', function (e) {

    e.preventDefault();
    e.stopPropagation(); // 🔥 important

    const item = $(this).closest('.wppb-media-item');
    const wrap = item.closest('.wppb-media-field');

    item.remove();

    updateRemoveState(wrap);
  });


  /* =========================================
     REMOVE ALL
  ========================================= */

  $(document).on('click', '.wppb-media-field > .wppb-media-actions .wppb-media-remove', function (e) {
    e.preventDefault();

    const wrap = $(this).closest('.wppb-media-field');

    wrap.find('.wppb-media-preview').empty();
    wrap.find('input[type="hidden"]').remove();

    updateRemoveState(wrap);
  });


  /* =========================================
     RENDER ITEM
  ========================================= */

  function renderItem(attachment, fieldType, multiple, baseName) {

    let previewHtml = '';

    if (
      fieldType === 'image' ||
      (fieldType === 'media' && attachment.type === 'image')
    ) {
      if (attachment.sizes && attachment.sizes.thumbnail) {
        previewHtml = `<img src="${attachment.sizes.thumbnail.url}" />`;
      }
    } else {
      previewHtml = `<code>${attachment.filename}</code>`;
    }

    const inputName = multiple ? baseName + '[]' : baseName;

    // SINGLE MODE
    if (!multiple) {
      return `
        <div class="wppb-media-single">
          ${previewHtml}
          <input type="hidden" name="${inputName}" value="${attachment.id}">
        </div>
      `;
    }

    // MULTIPLE MODE
    return `
      <div class="wppb-media-item" draggable="true">

        <div class="wppb-media-item-header">
          <span class="wppb-media-drag dashicons dashicons-move"></span>
          <button type="button"
            class="wppb-media-item-remove dashicons dashicons-no-alt"
            aria-label="Remove"></button>
        </div>

        <div class="wppb-media-item-body">
          ${previewHtml}
          <input type="hidden" name="${inputName}" value="${attachment.id}">
        </div>

      </div>
    `;
  }


  /* =========================================
     SORTABLE (Multiple Only)
  ========================================= */

  $(document).on('dragstart', '.wppb-media-item', function (e) {

    // Only allow sorting if parent is multiple
    const wrap = $(this).closest('.wppb-media-field');
    if (wrap.data('multiple') !== true && wrap.data('multiple') !== 'true') {
      e.preventDefault();
      return;
    }

    this.classList.add('is-dragging');
    e.originalEvent.dataTransfer.effectAllowed = 'move';
  });

  $(document).on('dragover', '.wppb-media-item', function (e) {

    const dragging = document.querySelector('.wppb-media-item.is-dragging');
    if (!dragging || dragging === this) return;

    e.preventDefault();

    const rect = this.getBoundingClientRect();
    const midpoint = rect.top + rect.height / 2;

    if (e.originalEvent.clientY < midpoint) {
      this.parentNode.insertBefore(dragging, this);
    } else {
      this.parentNode.insertBefore(dragging, this.nextSibling);
    }
  });

  $(document).on('dragend', '.wppb-media-item', function () {
    this.classList.remove('is-dragging');
  });


  /* =========================================
     HELPERS
  ========================================= */

  function updateRemoveState(wrap) {

    const hasItems =
      wrap.find('.wppb-media-item').length > 0 ||
      wrap.find('.wppb-media-single').length > 0;

    wrap.find('.wppb-media-remove').prop('disabled', !hasItems);

    wrap.toggleClass('has-media', hasItems);
  }

  function getExistingIds(wrap) {
    return wrap
      .find('input[type="hidden"]')
      .map(function () {
        return parseInt($(this).val(), 10);
      })
      .get();
  }

  function showNotice(wrap, message, type = 'warning') {

    const typeClass =
      type === 'error'
        ? 'notice-error'
        : type === 'success'
          ? 'notice-success'
          : 'notice-warning';

    const notice = $(`
    <div class="wppb-media-notice notice ${typeClass} inline is-dismissible">
      <p>${message}</p>
    </div>
  `);

    wrap.prepend(notice);

    notice.on('click', '.notice-dismiss', function () {
      notice.remove();
    });

    setTimeout(function () {
      notice.fadeOut(150, function () {
        $(this).remove();
      });
    }, 3000);
  }

  $(document).ready(function () {

    $('.wppb-media-field').each(function () {
      updateRemoveState($(this));
    });

  });

})(jQuery);
