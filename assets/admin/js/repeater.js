document.addEventListener('click', function (e) {

  const wrapper = e.target.closest('.wppb-repeater-wrapper');
  if (!wrapper) return;

  const repeater = wrapper.querySelector('.wppb-repeater');
  const template = repeater.querySelector('[data-index="__index__"]');

  /* ---------------------------------
     ADD ROW
  --------------------------------- */

  if (e.target.closest('.wppb-repeater-add')) {

    if (isMaxReached(wrapper)) return;

    const template = wrapper.querySelector('.wppb-repeater-template');
    const repeater = wrapper.querySelector('.wppb-repeater');

    const index = getItems(repeater).length;

    const clone = template.content.cloneNode(true);

    const item = clone.querySelector('.wppb-repeater-item');

    item.dataset.index = index;

    // Replace __index__ in id attributes
    clone.querySelectorAll('[id]').forEach(el => {
      el.id = el.id.replace(/__index___/g, index);
    });

    // Replace __index__ in name attributes
    clone.querySelectorAll('[name]').forEach(el => {
      el.name = el.name.replace(/__index__/g, index);
    });

    // 🔥 Replace __index__ in data-name attributes
    clone.querySelectorAll('[data-name]').forEach(el => {
      el.dataset.name = el.dataset.name.replace(/__index__/g, index);
    });

    repeater.appendChild(clone);

    reindexRows(repeater);
    updateAddState(wrapper);

    return;
  }

  /* ---------------------------------
     REMOVE ROW
  --------------------------------- */

  if (e.target.closest('.wppb-repeater-remove')) {

    if (isMinReached(wrapper)) return;

    e.target.closest('.wppb-repeater-item').remove();

    reindexRows(repeater);
    updateAddState(wrapper);
    return;
  }

  /* ---------------------------------
     DUPLICATE ROW
  --------------------------------- */

  if (e.target.closest('.wppb-repeater-duplicate')) {

    if (isMaxReached(wrapper)) return;

    const item = e.target.closest('.wppb-repeater-item');
    const clone = item.cloneNode(true);

    repeater.appendChild(clone);

    reindexRows(repeater);
    updateAddState(wrapper);
    return;
  }

  /* ---------------------------------
     TOGGLE COLLAPSE
  --------------------------------- */

  if (e.target.closest('.wppb-repeater-toggle')) {

    const item = e.target.closest('.wppb-repeater-item');
    const toggle = item.querySelector('.wppb-repeater-toggle');
    const icon = toggle.querySelector('.dashicons');

    item.classList.toggle('is-collapsed');

    const collapsed = item.classList.contains('is-collapsed');

    toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');

    icon.classList.remove('dashicons-plus', 'dashicons-minus');
    icon.classList.add(collapsed ? 'dashicons-plus' : 'dashicons-minus');

    return;
  }

});


/* =====================================
   DRAG & DROP
===================================== */

let draggedItem = null;

document.addEventListener('dragstart', function (e) {

  const handle = e.target.closest('.wppb-repeater-drag');
  if (!handle) return;

  draggedItem = handle.closest('.wppb-repeater-item');
  draggedItem.classList.add('is-dragging');

  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/plain', '');
});

document.addEventListener('dragover', function (e) {

  if (!draggedItem) return;

  const targetItem = e.target.closest('.wppb-repeater-item');
  if (!targetItem || targetItem === draggedItem) return;

  e.preventDefault();

  const repeater = targetItem.closest('.wppb-repeater');
  const rect = targetItem.getBoundingClientRect();
  const midpoint = rect.top + rect.height / 2;

  if (e.clientY < midpoint) {
    repeater.insertBefore(draggedItem, targetItem);
  } else {
    repeater.insertBefore(draggedItem, targetItem.nextSibling);
  }
});

document.addEventListener('dragend', function () {

  if (!draggedItem) return;

  const repeater = draggedItem.closest('.wppb-repeater');

  draggedItem.classList.remove('is-dragging');
  reindexRows(repeater);

  draggedItem = null;
});


/* =====================================
   HELPERS
===================================== */

function getItems(repeater) {
  return repeater.querySelectorAll('.wppb-repeater-item:not([data-index="__index__"])');
}

function isMaxReached(wrapper) {
  const max = wrapper.dataset.max ? parseInt(wrapper.dataset.max, 10) : null;
  if (max === null) return false;
  return getItems(wrapper.querySelector('.wppb-repeater')).length >= max;
}

function isMinReached(wrapper) {
  const min = parseInt(wrapper.dataset.min || 0, 10);
  return getItems(wrapper.querySelector('.wppb-repeater')).length <= min;
}

function updateAddState(wrapper) {

  const addBtn = wrapper.querySelector('.wppb-repeater-add');
  if (!addBtn) return;

  addBtn.disabled = isMaxReached(wrapper);
}

function reindexRows(repeater) {

  const items = getItems(repeater);

  items.forEach((item, index) => {

    item.dataset.index = index;

    // Update name attributes safely
    item.querySelectorAll('[name]').forEach(input => {
      input.name = input.name.replace(
        /(\[[^\]]+\])\[\d+\](\[[^\]]+\])/,
        `$1[${index}]$2`
      );
    });

    // 🔥 Keep media data-name in sync
    item.querySelectorAll('.wppb-media-field').forEach(field => {
      if (field.dataset.name) {
        field.dataset.name = field.dataset.name.replace(
          /(\[[^\]]+\])\[\d+\](\[[^\]]+\])/,
          `$1[${index}]$2`
        );
      }
    });

    updateRowTitle(item);
  });
}

function updateRowTitle(item) {

  const wrapper = item.closest('.wppb-repeater-wrapper');
  const titleFieldKey = wrapper.dataset.titleField;

  const index = parseInt(item.dataset.index, 10) + 1;
  const titleElement = item.querySelector('.wppb-repeater-title');

  let value = '';

  if (titleFieldKey) {
    const input = item.querySelector(`[name*="[${titleFieldKey}]"]`);
    if (input) value = input.value.trim();
  }

  if (!value) {
    // fallback to first text input
    const firstText = item.querySelector('input[type="text"], textarea');
    if (firstText) value = firstText.value.trim();
  }

  titleElement.textContent = value || `Item ${index}`;
}


/* =====================================
   INITIALIZE STATE ON LOAD
===================================== */

document.addEventListener('DOMContentLoaded', function () {

  document.querySelectorAll('.wppb-repeater-wrapper').forEach(wrapper => {

    updateAddState(wrapper);

    const repeater = wrapper.querySelector('.wppb-repeater');
    if (!repeater) return;

    const items = getItems(repeater);

    items.forEach(item => {
      updateRowTitle(item);
    });

  });
});

document.addEventListener('input', function (e) {

  const item = e.target.closest('.wppb-repeater-item');
  if (!item) return;

  updateRowTitle(item);
});
