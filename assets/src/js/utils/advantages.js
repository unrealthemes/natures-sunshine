export default function () {
    const bp768 = window.matchMedia('(min-width: 768px)');
    const expression = /(Mac|iPhone|iPod|iPad)/i;
    const adv = document.querySelector('.advantages');
    const content = adv.querySelector('.advantages-list');
    const pos = { left: 0, x: 0 };

    content && content.addEventListener('wheel', (e) => {
        if (content.scrollWidth > content.offsetWidth) {
            e.preventDefault();
            content.scrollLeft += e.deltaX + e.deltaY;
        }
    });

    const mouseDownHandler = function (e) {
        pos.left = content.scrollLeft;
        pos.x = e.clientX;

        document.addEventListener('mousemove', mouseMoveHandler);
        document.addEventListener('mouseup', mouseUpHandler);

        content.style.cursor = 'grabbing';
        content.style.userSelect = 'none';
    };

    const mouseMoveHandler = function (e) {
        const dx = e.clientX - pos.x;
        content.scrollLeft = pos.left - dx;
    };

    const mouseUpHandler = function () {
        document.removeEventListener('mousemove', mouseMoveHandler);
        document.removeEventListener('mouseup', mouseUpHandler);

        content.style.cursor = 'grab';
        content.style.removeProperty('user-select');
    };

    function advMobileScrollChecker(bp) {
        if (bp.matches) {
            content.removeEventListener('mousedown', mouseDownHandler);
        } else {
            content.addEventListener('mousedown', mouseDownHandler);
        }
    }

    if (expression.test(window.navigator)) {
        bp768.addEventListener('change', advMobileScrollChecker);
    } else {
        bp768.onchange = advMobileScrollChecker;
    }
    advMobileScrollChecker(bp768);
}