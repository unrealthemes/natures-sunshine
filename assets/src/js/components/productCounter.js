export default function () {
    /* Counter */
    /*
    const counterMinus = document.querySelectorAll('.counter-minus');
    const qty = document.querySelectorAll('.qty');
    const counterPlus = document.querySelectorAll('.counter-plus');

    const decreaseCount = (e) => {
        const input = e.currentTarget.nextElementSibling;
        let value = parseInt(input.value, 10);
        if (value > 1) {
            value = isNaN(value) ? 0 : value;
            value--;
            input.value = value;
        }
    }

    const increaseCount = (e) => {
        const input = e.currentTarget.previousElementSibling;
        let value = parseInt(input.value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        input.value = value;
    }

    function isNumber() { */
        // this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
    /*}

    counterMinus && counterMinus.forEach(minus => {
        minus.addEventListener('click', decreaseCount);
    });

    counterPlus && counterPlus.forEach(plus => {
        plus.addEventListener('click', increaseCount);
    });

    qty && qty.forEach(function(input) {
        input.addEventListener('input', isNumber);
        input.addEventListener('change', function() {
            this.value = !this.value ? 1 : this.value;
        });
    });
    */
}