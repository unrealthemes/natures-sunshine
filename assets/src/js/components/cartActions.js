import {Fancybox} from "@fancyapps/ui";

export default function () {
    /* Cart partner ID */
    const addID = document.querySelector('.js-joint-id');
    const idField = document.getElementById('partner_id');

    // if (idField) {
    //     addID.addEventListener('click', e => { 
    //         e.preventDefault();

    //         if (!idField.value) {
    //             !idField.classList.contains('invalid') && idField.classList.add('invalid');
    //         } else {
    //             idField.classList.contains('invalid') && idField.classList.remove('invalid');

    //             /* TODO: Здесь нужно условие, если нет ID, то выводить попап, либо создавать корзину партнера сразу */
    //             Fancybox.show([{
    //                 src: "#cart_id",
    //                 type: "inline",
    //                 dragToClose: false
    //             }]);
    //         }
    //     });
    // }

    /* Cart mode notice */
    const cartModeSwitcher = document.querySelector('.js-cart-mode');

    if (cartModeSwitcher) {
        const cartModeNotice = cartModeSwitcher.parentElement.nextElementSibling;

        if (cartModeNotice) {
            cartModeNotice.style.maxHeight = cartModeSwitcher.checked && cartModeNotice.scrollHeight + "px";

            cartModeSwitcher && cartModeSwitcher.addEventListener('change', e => {
                if (!e.target.checked && cartModeNotice.style.maxHeight) {
                    // cartModeNotice.style.maxHeight = null;

                    /* TODO: Функционал переключения режима корзины */
                    e.target.checked = !e.target.checked;
                    Fancybox.show([{
                        src: "#joint_exit",
                        type: "inline",
                        dragToClose: false
                    }]);
                } else {
                    cartModeNotice.style.maxHeight = cartModeNotice.scrollHeight + "px";
                }
            });
        }
    }
}