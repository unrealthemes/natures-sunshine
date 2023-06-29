export default function () {
    const bp1200 = window.matchMedia('(min-width: 1200px)');
    const expression = /(Mac|iPhone|iPod|iPad)/i;
    const ordersFilterTop = document.querySelector('.orders-filter__top');
    const ordersFilterTopSelects = document.querySelectorAll('.orders-filter__select');
    const ordersFilterLine = document.querySelector('.orders-filter__line');

    const transferFilterTopSelects = bp => {
        ordersFilterTopSelects && ordersFilterTopSelects.forEach(select => {
            if (bp.matches) {
                ordersFilterLine.contains(select) && ordersFilterTop.append(select);
            } else {
                ordersFilterTop.contains(select) && ordersFilterLine.prepend(select);
            }
        });
    }

    if (expression.test(window.navigator)) {
        bp1200.addEventListener('change', transferFilterTopSelects);
    } else {
        bp1200.onchange = transferFilterTopSelects;
    }
    transferFilterTopSelects(bp1200);
}