import tabs from "./tabs";

export default function () {
    /* Login tabs */
    const tabLinks = document.querySelectorAll('.js-tab-link');
    const tabPanels = document.querySelectorAll('.js-tab-panel');

    tabLinks && tabLinks.forEach(link => {
        link.addEventListener('click', e => tabs(e, tabLinks, tabPanels));
    });

    /* Register back to login */
    const tabBack = document.querySelector('.js-tab-back');

    tabBack && tabBack.addEventListener('click', () => {
        tabLinks[0].dispatchEvent(new Event('click'));
    });

    /* Form steps */
    const steps = document.querySelectorAll('.form__step');

    if (steps) {
        const stepNext = document.querySelectorAll('.js-step-next');
        const stepPrev = document.querySelectorAll('.js-step-prev');

        function cleanActiveClass(steps) {
            for (let i = 0; i < steps.length; i++) {
                steps[i].classList.contains('active') && steps[i].classList.remove('active');
            }
        }

        function stepForward(next, steps) {
            const nextTarget = next.closest('.form__step').nextElementSibling;
            cleanActiveClass(steps);
            nextTarget.classList.add('active');
        }

        function stepBackward(prev, steps) {
            const prevTarget = prev.closest('.form__step').previousElementSibling;
            cleanActiveClass(steps);
            prevTarget.classList.add('active');
        }

        function isAllInputsValid(inputs) {
            return [...inputs].every(input => input.validity.valid);
        }

        stepNext && stepNext.forEach(next => {
            next.addEventListener('click', e => {
                e.preventDefault();
                const stepInputs = e.target.closest('.form__step').querySelectorAll('input');

                if (isAllInputsValid(stepInputs)) {
                    for (let i = 0; i < stepInputs.length; i++) {
                        stepInputs[i].validity.valid && stepInputs[i].classList.remove('invalid');
                        stepInputs[i].validity.valid && stepInputs[i].parentElement.previousElementSibling.classList.remove('invalid');
                    }
                    stepForward(e.target, steps);
                } else {
                    for (let i = 0; i < stepInputs.length; i++) {
                        !stepInputs[i].validity.valid && stepInputs[i].classList.add('invalid');
                        !stepInputs[i].validity.valid && stepInputs[i].parentElement.previousElementSibling.classList.add('invalid');
                    }
                }
            });
        });

        stepPrev && stepPrev.forEach(prev => {
            prev.addEventListener('click', e => {
                e.preventDefault();
                stepBackward(prev, steps);
            });
        });
    }
}