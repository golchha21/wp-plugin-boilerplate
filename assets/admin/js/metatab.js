(function () {

  function initMetaTabs(context = document) {

    const wrappers = context.querySelectorAll('.wppb-meta-tabs');

    wrappers.forEach(wrapper => {

      const tabs = wrapper.querySelectorAll('.wppb-meta-tab');

      tabs.forEach(tab => {

        tab.addEventListener('click', function () {

          const target = this.dataset.target;
          if (!target) return;

          const panels = wrapper.querySelectorAll('.wppb-meta-tab-panel');

          tabs.forEach(t => t.classList.remove('active'));
          panels.forEach(p => p.classList.remove('active'));

          this.classList.add('active');

          const panel = wrapper.querySelector('#' + target);
          if (panel) {
            panel.classList.add('active');
          }

        });

      });

    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initMetaTabs();
  });

})();
