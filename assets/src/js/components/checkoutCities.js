export default function () {
    // const city = document.querySelector('.form-checkout__city');
    // const cities = ['Киев', 'Харьков', 'Одесса', 'Днепр', 'Запорожье', 'Львов', 'Кривой Рог', 'Николаев', 'Мариуполь', 'Винница'];
    // const cityField = document.getElementById('city');
    // const citiesList = document.querySelector('.location-cities');
    // const cityChoose = document.querySelector('.js-choose-city');
    // const cityLocation = document.querySelectorAll('.location-list__item-link');

    // cityLocation && cityLocation.forEach(city => {
    //     city.addEventListener('click', e => {
    //         e.preventDefault();
    //         for (let i = 0; i < cityLocation.length; i++) {
    //             cityLocation[i].classList.remove('active');
    //         }
    //         e.currentTarget.classList.add('active');
    //         if (e.currentTarget.textContent.indexOf(cities) === -1) {
    //             cityField.value = e.currentTarget.textContent;
    //         }
    //     });
    // });

    // const addItem = value => {
    //     citiesList.innerHTML += `<li class="text">${value}</li>`
    // };

    // const autoComplete = val => cities.sort().filter(value => value.toLowerCase().includes(val.toLowerCase()));

    // const selectItem = ({target}) => {
    //     if (target.tagName === 'LI') {
    //         cityField.value = target.textContent;
    //         cityLocation && cityLocation.forEach(city => {
    //             if (target.textContent !== city.textContent) {
    //                 city.classList.remove('active');
    //             } else {
    //                 city.classList.add('active');
    //             }
    //         });
    //         citiesList.innerHTML = ``;
    //         cityField.parentElement.classList.remove('match');
    //     }
    // }

    // const changeAutoComplete = ({target}) => {
    //     let data = target.value;
    //     citiesList.innerHTML = ``;
    //     let autoCompleteValues = autoComplete(data);
    //     if (data) {
    //         autoCompleteValues.forEach(value => addItem(value));
    //         if (autoCompleteValues.length !== 0 && autoCompleteValues.length !== cities.length) {
    //             cityField.parentElement.classList.add('match');
    //         } else {
    //             cityField.parentElement.classList.remove('match');
    //         }
    //     } else {
    //         cityField.parentElement.classList.remove('match');
    //     }
    // }

    // cityField && cityField.addEventListener('focus', e => e.target.select());
    // cityField && cityField.addEventListener('input', changeAutoComplete);
    // citiesList && citiesList.addEventListener('click', e => {
    //     e.preventDefault();
    //     selectItem(e);
    // });

    // cityChoose && cityChoose.addEventListener('click', e => {
    //     e.preventDefault();
    //     console.log(cities, cityField)
    //     const closeBtn = e.target.closest('.popup-checkout').querySelector('.js-close-popup');

    //     if (cities.indexOf(cityField.value) !== -1) {
    //         city.textContent = city && cityField.value;
    //         city && city.setAttribute('data-city', cityField.value);
    //         closeBtn.dispatchEvent(new Event('click'));
    //     }

    //     return false;
    // });
}