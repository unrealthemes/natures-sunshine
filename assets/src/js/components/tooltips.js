import tippy from "tippy.js";

export default function () {
  /* Tooltips */
  document.onmouseover = function (event) {
    let target = event.target;
    let tooltipHtml = target.dataset.tooltip;
    if (!tooltipHtml) return;

    let pos = target.classList.contains('card__icon') ? 'right' : 'bottom';

    tippy(target, {
      content: target.dataset.tooltip,
      appendTo: document.body,
      placement: pos,
      animation: 'fade',
    });
  }
}