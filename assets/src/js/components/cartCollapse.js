export default function cartCollapse () {

    /* Cart total collapse */
    const cartTotalTitle = document.querySelectorAll('.cart-total__title');
    const cartTotalCollapse = document.querySelectorAll('.cart-collapse');

    function cartCollapseResize() {
        cartTotalCollapse && cartTotalCollapse.forEach(collapse => {
            if (collapse.classList.contains('active')) {
                collapse.style.maxHeight = collapse.scrollHeight + "px";
            }
        });
    }

    cartCollapseResize();
    window.addEventListener('resize', cartCollapseResize);

    // cartTotalTitle && cartTotalTitle.forEach(title => {
    //     title.addEventListener('click', e => {
    //         const panel = e.currentTarget.nextElementSibling;
    //
    //         e.currentTarget.classList.toggle('active');
    //         panel.classList.toggle('active');
    //
    //         if (panel.style.maxHeight) {
    //             panel.style.maxHeight = null;
    //         } else {
    //             panel.style.maxHeight = panel.scrollHeight + "px";
    //         }
    //     });
    // });

    document.addEventListener('click', e => {
        const target = e.target.closest('.cart-total__title');

        if (target) {
            const panel = target.nextElementSibling;

            target.classList.toggle('active');
            panel.classList.toggle('active');

            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        }
    });

    // $('body').on('updated_cart_totals', function() {
    //     console.log('Cart ajax update');
    // });
}