export default function tabs(e, links, panels) {
    const id = e.currentTarget.hash.replace('#', '');

    if (id) {
        e.preventDefault();
        const target = document.getElementById(id);

        for (let i = 0; i < panels.length; i++) {
            panels[i].classList.remove('active');
        }

        for (let i = 0; i < links.length; i++) {
            links[i].classList.remove('active');
        }

        target.classList.add('active');
        e.currentTarget.classList.add('active');
    }
}