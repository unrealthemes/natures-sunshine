export default function () {
    const checkoutTabs = document.querySelectorAll('.form-checkout__tabs-item');
    const checkoutPanels = document.querySelectorAll('.form-checkout__tabs-panel');

    checkoutTabs && checkoutTabs.forEach(tab => {
        tab.addEventListener('click', e => {
            const id = e.currentTarget.hash.replace('#', '');
            const target = document.getElementById(id);

            if (target) {
                e.preventDefault();

                for (let i = 0; i < checkoutPanels.length; i++) {
                    checkoutPanels[i].classList.remove('active');
                }

                for (let i = 0; i < checkoutTabs.length; i++) {
                    checkoutTabs[i].classList.remove('active');
                }

                target.classList.add('active');
                e.currentTarget.classList.add('active');
            }
        });
    });
}