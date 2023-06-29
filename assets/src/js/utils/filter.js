export default function () {
    /* Collapse filters */
    const filterCollapseShow = document.querySelectorAll('.js-filter-show');
    const expression = /(Mac|iPhone|iPod|iPad)/i;

    // filterCollapseShow && filterCollapseShow.forEach(show => {
    //     show.addEventListener('click', e => {
    //         e.preventDefault();
    //         const filterCollapse = e.target.previousElementSibling;
    //         const text = e.target.dataset.text;
    //
    //         if (filterCollapse && filterCollapse.style.maxHeight) {
    //             filterCollapse.style.maxHeight = null;
    //             e.target.textContent = text;
    //         } else {
    //             filterCollapse.style.maxHeight = filterCollapse.scrollHeight + "px";
    //             e.target.textContent = 'Свернуть';
    //         }
    //     });
    // });

    /* Filter mobile */
    const bp1200 = window.matchMedia('(min-width: 1200px)');
    const openFilter = document.querySelector('.js-open-filter');
    const closeFilter = document.querySelector('.js-filter-close');
    const filter = document.querySelector('.js-filter');

    if (filter) {
        openFilter.addEventListener('click', e => {
            e.preventDefault();
            !document.body.classList.contains('no-scroll') && document.body.classList.add('no-scroll');
            !filter.classList.contains('shown') && filter.classList.add('shown');
        });

        closeFilter.addEventListener('click', e => {
            e.preventDefault();
            document.body.classList.contains('no-scroll') && document.body.classList.remove('no-scroll');
            filter.classList.contains('shown') && filter.classList.remove('shown');
        });
    }

    function filterRemoveClass(bp) {
        if (bp.matches) {
            document.body.classList.contains('no-scroll') && document.body.classList.remove('no-scroll');
            filter.classList.contains('shown') && filter.classList.remove('shown');
            filter.classList.contains('mobile') && filter.classList.remove('mobile');
        } else {
            !filter.classList.contains('mobile') && filter.classList.add('mobile');
        }
    }

    if (expression.test(window.navigator)) {
        bp1200.addEventListener('change', filterRemoveClass);
    } else {
        bp1200.onchange = filterRemoveClass;
    }
    filterRemoveClass(bp1200);
}