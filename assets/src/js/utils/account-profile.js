export default function () {
  //Personal information form
  // const inputs = document.querySelectorAll('.profile-content__person-fields_input input');
  // const updateBtn = document.querySelector('.profile-content__person-update');
  // const defaultValues = [];
  //
  // function checkInputs(index) {
  //   return function () {
  //     if (inputs[index].value !== defaultValues[index] && inputs[index].validity.valid) {
  //       updateBtn.removeAttribute('disabled')
  //     } else {
  //       updateBtn.setAttribute('disabled', '')
  //     }
  //   }
  // }
  //
  // if (inputs && updateBtn) {
  //
  //   for (let i = 0; i < inputs.length; i++) {
  //     defaultValues[i] = inputs[i].value;
  //     inputs[i].addEventListener('input', checkInputs(i))
  //   }
  //
  // }

  //Phones and Mails edit
  // const contactInputs = document.querySelectorAll('.profile-content__mails-grid__item-input input');
  // const changeBtn = document.querySelector('.profile-content__mails-update');
  //
  // if (contactInputs && changeBtn) {
  //   changeBtn.addEventListener('click', function (e) {
  //     e.preventDefault();
  //     if (changeBtn.classList.contains('save')) {
  //       let validInputs = true;
  //       contactInputs.forEach(input => {
  //         if (input.validity.valid) {
  //           input.setAttribute('disabled', '')
  //         } else {
  //           validInputs = false;
  //         }
  //       })
  //
  //       if (validInputs) {
  //         changeBtn.innerHTML = 'Изменить'
  //       }
  //
  //     } else {
  //       changeBtn.classList.add('save');
  //       changeBtn.innerHTML = 'Сохранить'
  //       contactInputs.forEach(input => {
  //         input.removeAttribute('disabled')
  //       })
  //     }
  //   })
  // }

  // Messengers
  const msgrs = document.querySelectorAll('.messengers__item-input');
  msgrs && msgrs.forEach(msgr => {
    function fieldsActions(condition) {
      if (condition) {
        msgr.parentElement.children[0].classList.add('color');
      } else {
        msgr.parentElement.children[0].classList.remove('color');
      }
    }

    if (msgr.type === 'tel') {
      fieldsActions(msgr.inputmask.isComplete());
      msgr.addEventListener('change', () => fieldsActions(msgr.inputmask.isComplete()));
    } else {
      fieldsActions(msgr.value);
      msgr.addEventListener('change', () => fieldsActions(msgr.value));
    }
  });

  // Delete account
  // const formsDelete = document.querySelectorAll('.form-delete:not(#change_main_phone_email_form)');
  //
  // console.log(formsDelete)
  //
  // formsDelete && formsDelete.forEach(form => {
  //   const formDeleteLabel = form.querySelector('label');
  //   const formDeleteInput = form.querySelector('input');
  //   const formDeleteSubmit = form.querySelector('button');
  //
  //   formDeleteInput && formDeleteInput.addEventListener('input', function () {
  //     if (this.value === 'Удалить') {
  //       this.classList.remove('invalid', 'warning');
  //       formDeleteLabel.classList.remove('invalid', 'warning');
  //       formDeleteSubmit.disabled = false;
  //     } else {
  //       this.classList.add('warning');
  //       formDeleteLabel.classList.add('warning');
  //       formDeleteSubmit.disabled = true;
  //     }
  //   });
  // });

  // Public phones
  const profilePhonesSwitcher = document.querySelector('.js-profile-phones');
  const profilePhones = document.querySelectorAll('.profile-info__checkbox-input');

  if (profilePhones) {
    profilePhonesSwitcher && profilePhonesSwitcher.addEventListener('change', (e) => {
      profilePhones.forEach(phone => {
        e.target.checked ? phone.removeAttribute('disabled') : phone.setAttribute('disabled', '');
      });
    });
  }

  // Addresses checked
  const places = document.getElementsByClassName('profile-places');
  places && [...places].forEach(place => {
    const addresses = place.querySelectorAll('.address');

    for (let i = 0; i < addresses.length; i++) {
      const useAsCurrent = addresses[i].querySelector('[type="radio"]');

      useAsCurrent.addEventListener('change', e => {
        for (let address of addresses) {
          address.classList.remove('active');
        }
        e.target.checked && addresses[i].classList.add('active');
      });
    }
  });
}