import isMobile from "./isMobile";

export default function () {
    /* Mega menu */
    const mmLinks = document.getElementsByClassName('mega-menu__link');
    const mmPanels = document.getElementsByClassName('mega-menu__panel');
    const expression = /(Mac|iPhone|iPod|iPad)/i;

    mmLinks && [...mmLinks].forEach(link => {
        link.addEventListener('click', e => e.preventDefault());

        link.addEventListener('mouseenter', e => {
            const targetID = e.target.hash.replace('#', '');
            const target = document.getElementById(targetID);

            for (let mmLink of mmLinks) {
                !mmLink.contains(e.target) && mmLink.classList.remove('active');
            }

            for (let mmPanel of mmPanels) {
                !mmPanel.contains(target) && mmPanel.classList.remove('active');
            }

            !link.classList.contains('active') && link.classList.add('active');
            !target.classList.contains('active') && target.classList.add('active');
        });
    });

    /* Menu hover */
    const headerNav = document.querySelector('.header__nav');
    const hasMMenus = document.getElementsByClassName('has-mega-menu');

    const isScrollable = () => {
        headerNav.style.overflowX = isMobile() ? 'auto' : 'unset';
    }

    headerNav && window.addEventListener('load', isScrollable);
    headerNav && window.addEventListener('resize', isScrollable);

    hasMMenus && [...hasMMenus].forEach(item => {
        const megaMenu = item.querySelector('.mega-menu');

        if (!isMobile()) {
            item.addEventListener('mouseenter', () => {
                megaMenu.classList.add('open');
            });
    
            item.addEventListener('mouseleave', () => {
                megaMenu.classList.remove('open');
            });
        }
    });

    /* Mobile menu scroll */
    const inlineScroll = document.querySelectorAll(".header__nav");

    inlineScroll && inlineScroll.forEach(scrollEl => {
        const content = scrollEl.querySelector('.menu');
        const pos = { left: 0, x: 0 };

        const mouseDownHandler = function (e) {
            pos.left = content.scrollLeft;
            pos.x = e.clientX;

            isMobile() && document.addEventListener('mousemove', mouseMoveHandler);
            isMobile() && document.addEventListener('mouseup', mouseUpHandler);

            content.style.cursor = isMobile() && 'grabbing';
            content.style.userSelect = isMobile() && 'none';
        };

        const mouseMoveHandler = function (e) {
            const dx = e.clientX - pos.x;
            content.scrollLeft = pos.left - dx;

            if (isMobile()) {
                content.style.overflowX = 'auto';
            } else {
                content.style.overflowX = 'unset';
            }
        };

        const mouseUpHandler = function () {
            document.removeEventListener('mousemove', mouseMoveHandler);
            document.removeEventListener('mouseup', mouseUpHandler);

            content.style.cursor = 'grab';
            content.style.removeProperty('user-select');
        };

        isMobile() && content.addEventListener('mousemove', mouseDownHandler);
    });

    /* Header scroll */
    const header = document.querySelector('header');
    const offset = window.innerHeight / 3;
    let lastScrollTop = 0;

    window.addEventListener('scroll', () => {
        let st = window.scrollY || document.documentElement.scrollTop;

        window.scrollY >= offset && !document.body.classList.contains('compare')
            ? header.classList.add('header--unsticky')
            : header.classList.remove('header--unsticky');

        st < lastScrollTop && header.classList.remove('header--unsticky');

        lastScrollTop = st <= 0 ? 0 : st;
    }, false);

    /* Header mobile bar */
    const bp768 = window.matchMedia('(min-width: 768px)');
    const headerInner = document.querySelector('.header__main-inner');
    const headerBar = document.querySelector('.header__bar');

    const headerBarReplacement = bp => {
        if (bp.matches) {
            !headerInner.contains(headerBar) && headerInner.append(headerBar);
        } else {
            headerInner.contains(headerBar) && header.insertAdjacentElement('afterend', headerBar);
        }
    }

    if (expression.test(navigator)) {
        headerBar && bp768.addEventListener('change', headerBarReplacement);
    } else {
        bp768.onchange = headerBar && headerBarReplacement;
    }
    headerBar && headerBarReplacement(bp768);
}