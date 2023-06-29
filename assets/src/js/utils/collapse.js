export default function collapse() {
  document.addEventListener('click', e => {
    document.querySelectorAll('.collapse-link') && document.querySelectorAll('.collapse-link').forEach(collapse => {
      if (collapse.contains(e.target)) {
        e.preventDefault();
        const panel = collapse.nextElementSibling;
        const parent = collapse.closest('.collapse-panel');

        if (panel) {
          collapse.classList.toggle('active');
          panel.classList.toggle('active');

          if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
            panel.style.overflow = 'hidden';
          } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
            setTimeout(() => {
              panel.style.overflow = 'visible';
            }, 500);
          }
        }

        if (parent && parent.classList.contains('active')) {
          parent.style.maxHeight = parent.scrollHeight * 2 + "px";
        }
      }
    });
  }, true);
}