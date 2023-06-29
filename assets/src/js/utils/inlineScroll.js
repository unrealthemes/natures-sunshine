export default function inlineScroll() {
    const inlineScroll = document.querySelectorAll(".inline-scroll");

    inlineScroll && inlineScroll.forEach(elem => {
        const content = elem.children[0];
        const pos = { left: 0, x: 0 };

        const mouseDownHandler = function (e) {
            pos.left = content.scrollLeft;
            pos.x = e.clientX;

            document.addEventListener('mousemove', mouseMoveHandler);
            document.addEventListener('mouseup', mouseUpHandler);

            content.style.cursor = isMobile && 'grabbing';
            content.style.userSelect = isMobile && 'none';
        };

        const mouseMoveHandler = function (e) {
            const dx = e.clientX - pos.x;
            content.scrollLeft = pos.left - dx;
            content.style.overflowX = 'auto';
        };

        const mouseUpHandler = function () {
            document.removeEventListener('mousemove', mouseMoveHandler);
            document.removeEventListener('mouseup', mouseUpHandler);

            content.style.cursor = isMobile && 'grab';
            isMobile && content.style.removeProperty('user-select');
        };

        content.addEventListener('mousemove', mouseDownHandler);
    });
}