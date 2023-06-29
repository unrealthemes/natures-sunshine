export default {
  init() {
    document.addEventListener('change', e => {
      const panels = document.querySelectorAll('.favorites-panel');
      panels && panels.forEach(panel => {
        if (panel.contains(e.target)) {
          const panelCheckboxes = panel.querySelectorAll('[type="checkbox"]');
          const btnMove = panel.querySelector('.js-move');
          const btnRemove = panel.querySelector('.js-remove');

          const allUnchecked = () => [...panelCheckboxes].every(checkbox => !checkbox.checked);

          if (allUnchecked()) {
            btnMove.disabled = true;
            btnRemove.disabled = true;
          } else {
            btnMove.disabled = false;
            btnRemove.disabled = false;
          }
        }
      });
    });
  }
}