export default function(){

    const copyBtns = document.querySelectorAll('.js-copy-btn')

    function copyToClipboard(button){
        var input = document.createElement('input');
        document.body.appendChild(input);  
        input.value = button.dataset.value;
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
    }

    function copyBtnTooltip(button){
        const tooltip = button.querySelector('.tooltip__content');
        tooltip.innerHTML = 'Скопировано!';
        button.classList.add('green');

        setTimeout(e=> {
            button.classList.remove('green');
            tooltip.innerHTML = 'Скопировать ID';
        }, 5000);

    }

    copyBtns && copyBtns.forEach(button => {

        button.addEventListener('click', e=> {
            copyToClipboard(button);

            if(button.classList.contains('tooltip')){
                copyBtnTooltip(button);
            }
        })

    })

}