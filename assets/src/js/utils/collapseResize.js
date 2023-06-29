export default function collapseResize() {
  document.querySelectorAll('.collapse-panel') && document.querySelectorAll('.collapse-panel').forEach(panel => {
    const parent = panel.closest('.collapse-panel');

    if (panel.classList.contains('active')) {
      panel.style.maxHeight = panel.scrollHeight + "px";
      setTimeout(() => {
        panel.style.overflow = 'visible';
      }, 500);
    }

    if (parent && parent.classList.contains('active')) {
      parent.style.maxHeight = parent.scrollHeight * 2 + "px";
    }
  });
}