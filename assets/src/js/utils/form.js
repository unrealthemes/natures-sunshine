// import Inputmask from "inputmask";

export default function () {
  /* All forms show password */
  const showPass = document.querySelectorAll('.js-show-pass');

  showPass && showPass.forEach(show => {
    show.addEventListener('click', e => {
      e.preventDefault();
      const passField = e.currentTarget.previousElementSibling;

      if (passField.value) {
        e.currentTarget.classList.toggle('show');
        passField.classList.toggle('shown');
        passField.type = passField.type === 'password' ? 'text' : 'password';
      }
    });
  });

  /* Forms */
  // const forms = document.querySelectorAll('form');

  // if (forms) {
    /* All forms phone mask */
    // const phones = document.querySelectorAll('[type="tel"]');
    //
    // phones && phones.forEach(function (phone) {
    //   const phoneLabel = phone.parentElement.previousElementSibling;
    //   const im = new Inputmask({
    //     mask: "+38 999 999 9999",
    //     placeholder: '',
    //     clearMaskOnLostFocus: false,
    //     onBeforeMask: function (value, opts) {
    //       phone.setCustomValidity('Неправильный номер');
    //     },
    //     onincomplete: function () {
    //       !phone.classList.contains('invalid') && phone.classList.add('invalid');
    //       phoneLabel && !phoneLabel.classList.contains('invalid') && phoneLabel.classList.add('invalid');
    //       phone.setCustomValidity('Неправильный номер');
    //       // console.log('not')
    //     },
    //     oncomplete: function () {
    //       console.log('Phone complete');
    //       phone.classList.contains('invalid') && phone.classList.remove('invalid');
    //       phoneLabel && phoneLabel.classList.contains('invalid') && phoneLabel.classList.remove('invalid');
    //       phone.setCustomValidity('');
    //       // console.log('yes')
    //     }
    //   });
    //   im.mask(phone);
    // });
    //
    // function checkFieldValidity(input) {
    //   if (input.validity.valid) {
    //     input.classList.remove('invalid');
    //     input.parentElement.previousElementSibling && input.parentElement.previousElementSibling.classList.remove('invalid');
    //   } else {
    //     input.classList.add('invalid');
    //     input.parentElement.previousElementSibling && input.parentElement.previousElementSibling.classList.add('invalid');
    //   }
    //
    //   if (input.type === 'select-one') {
    //     if (input.options[input.selectedIndex].disabled) {
    //       input.nextElementSibling.classList.add('invalid');
    //       input.parentElement.previousElementSibling.classList.add('invalid');
    //     } else {
    //       input.nextElementSibling.classList.remove('invalid');
    //       input.parentElement.previousElementSibling.classList.remove('invalid');
    //     }
    //   }
    //
    //   if (input.type === 'tel' && input.inputmask.isComplete()) {
    //     input.classList.remove('invalid');
    //     input.parentElement.previousElementSibling && input.parentElement.previousElementSibling.classList.remove('invalid');
    //   }
    // }

    // forms.forEach(form => {
    //     const fields = form.querySelectorAll('input:not([type="search"]), select[required]');
    //     const submit = form.querySelector('[type="submit"]');

    // if (fields) {
    //     for (let field of fields) {
    //         ['change', 'blur'].forEach(event => {
    //             field && field.addEventListener(event, () => checkFieldValidity(field));
    //         });
    //     }
    //
    //     submit && submit.addEventListener('click', () => {
    //         for (let field of fields) {
    //             field && checkFieldValidity(field);
    //         }
    //     });
    // }

    // if (form.classList.contains('form-lostpassword')) {
    //     form.addEventListener('submit', function() {
    //
    //     });
    // }
    // });
  // }

  /* Textareas auto height */
  document.querySelectorAll('textarea').forEach(function (el) {
    el.addEventListener('input', e => {
      el.style.height = 'auto';
      el.style.height = (el.scrollHeight) + 'px';
    });
  });

  /* Search */
  const searchInputs = document.querySelectorAll('[type="search"]');
  searchInputs && searchInputs.forEach(search => {
    ['input', 'change', 'blur'].forEach(event => {
      search.addEventListener(event, e => {
        if (e.target.value) {
          !e.target.classList.contains('filled') && e.target.classList.add('filled');
        } else {
          e.target.classList.contains('filled') && e.target.classList.remove('filled');
        }
      });
    });
  });
}