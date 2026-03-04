(function () {

  function getContainer(field) {
    if (field.closest('.wppb-repeater-field')) {
      return field.closest('.wppb-repeater-field');
    }

    if (field.closest('.wppb-meta-field')) {
      return field.closest('.wppb-meta-field');
    }

    if (field.closest('tr')) {
      return field.closest('tr');
    }

    return field;
  }

  function getFieldValue(target, localScope, name) {

    if (!target) return null;

    if (target.type === 'checkbox') {
      return target.checked ? '1' : '0';
    }

    if (target.type === 'radio') {
      const checked = localScope.querySelector(
        `[name="${CSS.escape(name)}"]:checked`
      );
      return checked ? checked.value : null;
    }

    return target.value;
  }

  function compare(value, operator, expected) {

    switch (operator) {

      case '==':
        return value == expected;

      case '!=':
        return value != expected;

      case 'empty':
        return (
          value === null ||
          value === '' ||
          (Array.isArray(value) && value.length === 0)
        );

      case 'not_empty':
        return !(
          value === null ||
          value === '' ||
          (Array.isArray(value) && value.length === 0)
        );

      case 'in':
        return Array.isArray(expected)
          ? expected.includes(value)
          : false;

      case 'not_in':
        return Array.isArray(expected)
          ? !expected.includes(value)
          : true;

      default:
        return true;
    }
  }

  function initConditionalFields(scope = document) {

    scope.querySelectorAll('.wppb-field[data-conditions]')
      .forEach(function (field) {

        if (field.dataset.conditionalInit === '1') return;
        field.dataset.conditionalInit = '1';

        let raw;

        try {
          raw = JSON.parse(field.dataset.conditions || '[]');
        } catch (e) {
          return;
        }

        if (!raw) return;

        // 🔥 Extract relation + conditions
        let relation = 'AND';
        let conditions = raw;

        if (!Array.isArray(raw)) {
          relation = (raw.relation || 'AND').toUpperCase();
          conditions = raw.conditions || [];
        }

        if (!conditions.length) return;

        const repeaterItem = field.closest('.wppb-repeater-item');
        const localScope = repeaterItem || field.closest('form') || document;

        const container = getContainer(field);

        const evaluate = function () {

          const results = conditions.map(function (condition) {

            const selector = `[name="${CSS.escape(condition.field)}"]`;
            const target = localScope.querySelector(selector);

            if (!target) return false;

            const value = getFieldValue(target, localScope, condition.field);

            return compare(value, condition.operator, condition.value);

          });

          let visible;

          if (relation === 'OR') {
            visible = results.some(Boolean);
          } else {
            visible = results.every(Boolean);
          }

          container.style.display = visible ? '' : 'none';
        };

        evaluate();

        conditions.forEach(function (condition) {

          const selector = `[name="${CSS.escape(condition.field)}"]`;
          const targets = localScope.querySelectorAll(selector);

          targets.forEach(function (target) {
            target.addEventListener('change', evaluate);
          });

        });

      });

  }

  document.addEventListener('DOMContentLoaded', function () {
    initConditionalFields(document);
  });

  document.addEventListener('click', function (e) {

    if (
      e.target.closest('.wppb-repeater-add') ||
      e.target.closest('.wppb-repeater-duplicate')
    ) {
      setTimeout(function () {
        initConditionalFields(document);
      }, 0);
    }

  });

})();
