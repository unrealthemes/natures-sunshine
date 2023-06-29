// import {Fancybox} from "@fancyapps/ui";

export default function () {
    /* All */
    Fancybox.bind("[data-fancybox]", {
        Hash: false,
        dragToClose: false,
        Thumbs: {
            autoStart: false,
        },
        Toolbar: {
            display: [
                "close",
            ]
        },
        on: {
            done: (fancybox, slide) => {
                if (slide.$content.classList.contains('popup-address')) {
                    slide.$content.style.display = 'block';
                }
            },
        }
    });

    /* Close button */
    const closePopup = document.querySelectorAll('.js-close-popup');

    closePopup && closePopup.forEach(close => {
        close.addEventListener('click', e => {
            e.preventDefault();
            Fancybox.getInstance().close();
        });
    });

    /* Cities popup */
    const checkoutCity = document.querySelector('.form-checkout__city');

    checkoutCity && checkoutCity.addEventListener('click', e => {
        e.preventDefault();
        Fancybox.show([{
            src: "#cities-popup",
            type: "inline",
            dragToClose: false,
            click: 'close'
        }]);
    });
}