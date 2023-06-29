export default function () {
    const bp768 = window.matchMedia('(min-width: 768px)');
    const fTitles = document.getElementsByClassName('footer__block-title');
    const fPanels = document.getElementsByClassName('footer__block-panel');

    fTitles && [...fTitles].forEach(title => {
        title.addEventListener('click', function () {
            const panel = this.nextElementSibling;

            for (let t of fTitles) {
                !t.contains(this) && t.classList.remove('active');
            }

            for (let p of fPanels) {
                !p.contains(panel) && p.removeAttribute('style');
            }

            this.classList.toggle("active");

            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    });
}