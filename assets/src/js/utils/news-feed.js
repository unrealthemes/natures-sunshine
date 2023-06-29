import inlineScroll from "./inlineScroll";

export default function () {
    const bp992 = window.matchMedia('(min-width: 992px)');
    const expression = /(Mac|iPhone|iPod|iPad)/i;
    const newsFeed = document.querySelector('.news-feed');
    const newsFeedItems = document.querySelectorAll('.news-feed__item:not(.news-feed__main)');

    const newsFeedScroll = document.createElement('div');
    const newsFeedScrollWrapper = document.createElement('div');
    newsFeedScroll.className = 'inline-scroll';
    newsFeedScrollWrapper.className = 'inline-scroll__content';

    function homeNewsFeedScroll(bp) {
        if (bp.matches) {
            newsFeedScroll && newsFeedScroll.remove();
            newsFeedItems.forEach(item => {
                newsFeed.append(item);
            });
        } else {
            newsFeedItems.forEach(item => {
                newsFeedScrollWrapper.append(item);
            });

            newsFeedScroll.append(newsFeedScrollWrapper);
            newsFeed.append(newsFeedScroll);

            inlineScroll();
        }
    }

    if (expression.test(window.navigator)) {
        bp992.addEventListener('change', homeNewsFeedScroll);
    } else {
        bp992.onchange = homeNewsFeedScroll;
    }
    homeNewsFeedScroll(bp992);
}