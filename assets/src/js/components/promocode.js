export default function () {
    /* Promo collapse */
    const promo = document.getElementById('promo');

    promo && promo.addEventListener('change', function () {
        const promoBlock = this.parentElement.nextElementSibling;

        if (!this.checked && promoBlock && promoBlock.style.maxHeight) {
            promoBlock.style.maxHeight = null;
        } else {
            promoBlock.style.maxHeight = promoBlock.scrollHeight + "px";
        }
    });
}