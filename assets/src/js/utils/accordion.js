export default function accordion(e, links, panels) {
    e.preventDefault();
    const panel = e.currentTarget.nextElementSibling;

    for (let i = 0; i < links.length; i++) {
        !links[i].contains(e.currentTarget) && links[i].classList.remove('active');
    }

    for (let i = 0; i < panels.length; i++) {
        !panels[i].contains(panel) && panels[i].removeAttribute('style');
    }

    e.currentTarget.classList.toggle('active');
    panel.classList.toggle('active');

    if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
    } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
    }
}