'use strict';

let CONTACTS = {

  init: function init() {

    CONTACTS.phone_mask();
    CONTACTS.send_form();

  },

  phone_mask: function phone_mask() {
    $('.mask-js').inputmask({"mask": "+38(999) 999-9999"});
  },

  send_form: function send_form() {

    $('.send_form').submit(function (event) {

      event.preventDefault();
      $('.form-contacts .ut-loader').addClass('loading');


      var data = {
        action: 'send_form',
        ajax_nonce: ut_contacts.ajax_nonce,
        form: $(this).serialize(),
      };

      $.ajax({
        type: 'POST',
        url: ut_contacts.ajax_url,
        data: data,
        success: function (response) {

          $('#success_msg').hide();
          $('#error_msg').hide();

          if (response.success) {
            CONTACTS.reset_form();
            $('#success_msg').html(response.data.message).show();
          } else {
            $('#error_msg').html(response.data.message).show();
          }
          $('.form-contacts .ut-loader').removeClass('loading');
        }
      });
    });
  },

  reset_form: function reset_form() {
    $('.send_form')[0].reset();
    $('.send_form select').prop('selectedIndex', 0);
    CONTACTS.rebuild_select();
  },

  rebuild_select: function rebuild_select() {
    $('.select-selected').remove();
    $('.select .select-hide').remove();
    customSelect(); // init custom select
  },

};

$(document).ready(CONTACTS.init());