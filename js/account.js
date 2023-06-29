'use strict';

let ACCOUNT = {

  init: function init() {

    ACCOUNT.phone_mask();
    ACCOUNT.email_mask();
    ACCOUNT.add_additional_phone();
    ACCOUNT.remove_additional_phone();
    ACCOUNT.add_additional_email();
    ACCOUNT.remove_additional_email();
    ACCOUNT.update_avatar();
    ACCOUNT.remove_avatar();
    ACCOUNT.change_password();
    ACCOUNT.delete_account();
    ACCOUNT.change_main_phone_email();
    ACCOUNT.public_information();
    ACCOUNT.newsletter_settings();

    $(document).on(
      'focusout',
      '#accaunt_form input[type="text"], #accaunt_form input[type="tel"], #accaunt_form input[type="email"], #accaunt_form textarea',
      function (e) {
        e.preventDefault();
        ACCOUNT.save_account($(this), $(this).attr('name'));
      });

    $('select[name="interface_language"]').on(
      'change',
      function (e) {
        e.preventDefault();
        ACCOUNT.save_account($(this), $(this).attr('name'));
      });

    $('#timezone_string').on(
      'change',
      function (e) {
        e.preventDefault();
        ACCOUNT.save_account($(this), $(this).attr('name'));
      });
  },

  phone_mask: function phone_mask() {
    $('.mask-js').inputmask({"mask": "+38(999)999-9999"});
  },

  email_mask: function email_mask() {
    $('.emask-js').inputmask("email");
  },

  add_additional_phone: function add_additional_phone() {

    $(document).on('click', '.add-additional-phone-js', function () {
      var html = '<div class="profile-content__extrafields-list__item">' +
        '<div class="profile-content__extrafields-list__item-input">' +
        '<input type="text" class="mask-js" name="additional_phones[]" value="">' +
        '</div>' +
        '<div class="profile-content__extrafields-list__item-delete remove-additional-phone-js">' +
        '<svg width="24" height="24"><use xlink:href="' + ut_account.dist_uri + '/images/sprite/svg-sprite.svg#account-input-delete"></use></svg>' +
        '</div>' +
        '</div>';
      $('.additional-phones-js').append(html);
      ACCOUNT.phone_mask();
    });
  },

  remove_additional_phone: function remove_additional_phone() {

    $(document).on('click', '.remove-additional-phone-js', function () {
      var $this = $(this);
      $this.parents('.profile-content__extrafields-list__item').remove();
      var data = {
        action: 'save_account',
        ajax_nonce: ut_account.ajax_nonce,
        field_name: 'additional_phones[]',
        form: $('#accaunt_form').serialize(),
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {

          if (response.success) {
          }
        }
      });
    });
  },

  add_additional_email: function add_additional_email() {

    $(document).on('click', '.add-additional-email-js', function () {
      var html = '<div class="profile-content__extrafields-list__item">' +
        '<div class="profile-content__extrafields-list__item-input">' +
        '<input type="text" class="emask-js" name="additional_emails[]" value="">' +
        '</div>' +
        '<div class="profile-content__extrafields-list__item-delete remove-additional-email-js">' +
        '<svg width="24" height="24"><use xlink:href="' + ut_account.dist_uri + '/images/sprite/svg-sprite.svg#account-input-delete"></use></svg>' +
        '</div>' +
        '</div>';
      $('.additional-emails-js').append(html);
      ACCOUNT.email_mask();
    });
  },

  remove_additional_email: function remove_additional_email() {

    $(document).on('click', '.remove-additional-email-js', function () {
      var $this = $(this);
      $this.parents('.profile-content__extrafields-list__item').remove();
      var data = {
        action: 'save_account',
        ajax_nonce: ut_account.ajax_nonce,
        field_name: 'additional_emails[]',
        form: $('#accaunt_form').serialize(),
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {

          if (response.success) {
          }
        }
      });
    });
  },

  update_avatar: function update_avatar() {

    $(document).on('change', '#file_avatar', function (e) {
      e.stopPropagation();
      e.preventDefault();

      var input = this;
      var data = new FormData;

      if (input.files && input.files[0]) {

        $('.profile-content__account-photo .ut-loader').addClass('loading');

        if (input.files[0].type != 'image/jpeg' && input.files[0].type != 'image/png' && input.files[0].type != 'image/gif') {
          $('.error-avatar').text(ut_account.error_type).show();
          $('.profile-content__account-photo .ut-loader').removeClass('loading');
          return false;
        }

        if (input.files[0].size > 2097152) {
          $('.error-avatar').text(ut_account.error_size).show();
          $('.profile-content__account-photo .ut-loader').removeClass('loading');
          return false;
        }

        data.append('file_avatar', input.files[0]);
        data.append('action', 'update_avatar');
        data.append('ajax_nonce', ut_account.ajax_nonce,);

        $.ajax({
          url: ut_account.ajax_url,
          data: data,
          type: 'POST',
          processData: false,
          contentType: false,
          success: function (response) {
            $('.error-avatar').hide();

            if (response.success) {
              // var reader = new FileReader();
              // reader.onload = function (e) {
              //     $('.image-absolute').attr('src', e.target.result);
              // }
              // reader.readAsDataURL( input.files[0] );
              $('.profile-content__account-photo-inner').html(response.data.avatar_html);
            }
            $('.profile-content__account-photo .ut-loader').removeClass('loading');

            if (response.success) {
              $('.form__row.response span span').html(response.data.message);
              $('.form__row.response .form__alert').addClass('success');
              $('.form__row.response').show();
            } else {
              $('.form__row.response span span').html(response.data.message);
              $('.form__row.response .form__alert').addClass('error');
              $('.form__row.response').show();
            }
    
            setTimeout(function () {
              $('.form__row.response span span').html('');
              $('.form__row.response .form__alert').removeClass('success');
              $('.form__row.response').hide();
            }, 2000);

          }
        });
      }
    });

  },

  remove_avatar: function remove_avatar() {

    $(document).on('click', '.profile-content__account-photo-delete', function (e) {

      e.preventDefault();
      $('.profile-content__account-photo .ut-loader').addClass('loading');

      var data = {
        action: 'remove_avatar',
        ajax_nonce: ut_account.ajax_nonce,
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {
          $('.error-avatar').hide();

          if (response.success) {
            // $('.image-absolute').attr('src', ut_account.theme_uri + '/img/user-avatar.png');
            $('.profile-content__account-photo-inner').html(response.data.avatar_html);
          }
          $('.profile-content__account-photo .ut-loader').removeClass('loading');

          if (response.success) {
            $('.form__row.response span span').html(response.data.message);
            $('.form__row.response .form__alert').addClass('success');
            $('.form__row.response').show();
          } else {
            $('.form__row.response span span').html(response.data.message);
            $('.form__row.response .form__alert').addClass('error');
            $('.form__row.response').show();
          }
  
          setTimeout(function () {
            $('.form__row.response span span').html('');
            $('.form__row.response .form__alert').removeClass('success');
            $('.form__row.response').hide();
          }, 2000);
          
        }
      });
    });

  },

  change_password: function change_password() {

    $(document).on('submit', '#change_password_form', function (e) {
      e.preventDefault();
      $('#change_password .ut-loader').addClass('loading');
      let data = {
        action: 'change_password',
        ajax_nonce: ut_account.ajax_nonce,
        form: $(this).serialize(),
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {
          $('#change_password_form .form__row.response span span').html('');
          $('#change_password_form .form__row.response .form__alert').removeClass('error');
          $('#change_password_form .form__row.response').hide();

          if (response.success === false) {

            if (response.data.message) {
              $('#change_password_form .form__row.response span span').html(response.data.message);
              $('#change_password_form .form__row.response .form__alert').addClass('error');
              $('#change_password_form .form__row.response').show();
            }
          } else {
            $('#old_password').val('');
            $('#password').val('');
            $('#repeat_password').val('');

            $('#change_password_form .form__row.response span span').html(response.data.message);
            $('#change_password_form .form__row.response .form__alert').addClass('success');
            $('#change_password_form .form__row.response').show();

            setTimeout(function () {
              $('#change_password_form .form__row.response span span').html('');
              $('#change_password_form .form__row.response .form__alert').removeClass('success');
              $('#change_password_form .form__row.response').hide();
              Fancybox.getInstance().close();
            }, 2000);
          }
          $('#change_password .ut-loader').removeClass('loading');
        }
      });
    });

  },

  change_main_phone_email: function change_main_phone_email() {

    $(document).on('click', '.change-main-phone-email-js', function (e) {
      ACCOUNT.phone_mask();
      let phone = $(this).data('phone');
      let email = $(this).data('email');
      $('#change_main_phone_email_form input[name="phone"]').val(phone);
      $('#change_main_phone_email_form input[name="email"]').val(email);
    });

    $(document).on('submit', '#change_main_phone_email_form', function (e) {
      e.preventDefault();
      $('#change_main_phone_email .ut-loader').addClass('loading');
      let data = {
        action: 'change_main_phone_email',
        ajax_nonce: ut_account.ajax_nonce,
        form: $(this).serialize(),
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {
          $('#change_main_phone_email_form .form__row.response span span').html('');
          $('#change_main_phone_email_form .form__row.response .form__alert').removeClass('error');
          $('#change_main_phone_email_form .form__row.response').hide();

          if (response.success === false) {

            if (response.data.message) {
              $('#change_main_phone_email_form .form__row.response span span').html(response.data.message);
              $('#change_main_phone_email_form .form__row.response .form__alert').addClass('error');
              $('#change_main_phone_email_form .form__row.response').show();
            }
          } else {
            $('#phone').val('');
            $('#email').val('');

            $('#change_main_phone_email_form .form__row.response span span').html(response.data.message);
            $('#change_main_phone_email_form .form__row.response .form__alert').addClass('success');
            $('#change_main_phone_email_form .form__row.response').show();

            setTimeout(function () {
              $('#change_main_phone_email_form .form__row.response span span').html('');
              $('#change_main_phone_email_form .form__row.response .form__alert').removeClass('success');
              $('#change_main_phone_email_form .form__row.response').hide();
              Fancybox.getInstance().close();
            }, 2000);
          }
          $('#change_main_phone_email .ut-loader').removeClass('loading');
        }
      });
    });

  },

  delete_account: function delete_account() {

    $(document).on('input', '.form-delete input', function () {

      if ($(this).val() === 'Удалить' || $(this).val() === 'Видалити') {
        $(this).removeClass('invalid', 'warning');
        $('.form-delete label').removeClass('invalid', 'warning');
        $('.form-delete button').prop('disabled', false);
      } else {
        $(this).addClass('warning');
        $('.form-delete label').addClass('warning');
        $('.form-delete button').prop('disabled', true);
      }
    });

    $(document).on('submit', '#delete_account_form', function (e) {
      e.preventDefault();
      $('#change_password .ut-loader').addClass('loading');
      let data = {
        action: 'delete_account',
        ajax_nonce: ut_account.ajax_nonce,
        form: $(this).serialize(),
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {

          if (response.success == false) {
            $('#repeat_password').val('');
            Fancybox.getInstance().close();
            $('#change_password .ut-loader').removeClass('loading');
          } else {
            window.location.href = response.data.redirect_url;
          }
        }
      });
    });

  },

  save_account: function save_account(element, field_name) {

    if (!field_name) {
      return false;
    }

    var data = {
      action: 'save_account',
      ajax_nonce: ut_account.ajax_nonce,
      field_name: field_name,
      form: $('#accaunt_form').serialize(),
    };

    $.ajax({
      url: ut_account.ajax_url,
      data: data,
      type: 'POST',
      success: function (response) {

        if (response.success) {
          $(element).removeClass('invalid').addClass('valid');
          $(element).parent().prev().removeClass('invalid').addClass('valid');

          if ($(element).hasClass('mask-js') || $(element).hasClass('emask-js')) {
            if (!$(element).inputmask("isComplete")) {
              $(element).removeClass('valid').addClass('invalid');
              $(element).parent().prev().removeClass('valid').addClass('invalid');
            }
          }

          setTimeout(function () {
            $(element).removeClass('valid');
            $(element).parent().prev().removeClass('valid');
          }, 2000);
        } else {
          $(element).addClass('invalid');
          $(element).parent().prev().addClass('invalid');
        }

        if (response.success) {
          $('.form__row.response span span').html(response.data.message);
          $('.form__row.response .form__alert').addClass('success');
          $('.form__row.response').show();
        } else {
          $('.form__row.response span span').html(response.data.message);
          $('.form__row.response .form__alert').addClass('error');
          $('.form__row.response').show();
        }

        setTimeout(function () {
          $('.form__row.response span span').html('');
          $('.form__row.response .form__alert').removeClass('success');
          $('.form__row.response').hide();
        }, 2000);

      }
    });
  },

  public_information: function public_information() {

    $('.page-template-template-account-public input[type="checkbox"]').change(function () {
      var id = $(this).attr('id');
      var name = $(this).attr('name');
      var checked = $(this).prop('checked');

      if (name == 'public_profile' && checked) {
        $('.profile-content__row.profile-content__public').removeClass('public-hide');
        $('.profile-content__info.profile-info').removeClass('public-hide');
      } else if (name == 'public_profile' && !checked) {
        $('.profile-content__row.profile-content__public').addClass('public-hide');
        $('.profile-content__info.profile-info').addClass('public-hide');
      }

      if (name == 'profile_photo' && checked) {
        $('.user-content__image').removeClass('public-hide');
      } else if (name == 'profile_photo' && !checked) {
        $('.user-content__image').addClass('public-hide');
      }

      if (name == 'profile_name' && checked) {
        $('.user-info__name').removeClass('public-hide');
      } else if (name == 'profile_name' && !checked) {
        $('.user-info__name').addClass('public-hide');
      }

      if (name == 'profile_info' && checked) {
        $('.profile_info').removeClass('public-hide');
      } else if (name == 'profile_info' && !checked) {
        $('.profile_info').addClass('public-hide');
      }

      if (name == 'profile_phone' && checked) {
        $('.phones').removeClass('public-hide');
      } else if (name == 'profile_phone' && !checked) {
        $('.phones').addClass('public-hide');
      }

      if (name == 'profile_email' && checked) {
        $('.emails').removeClass('public-hide');
      } else if (name == 'profile_email' && !checked) {
        $('.emails').addClass('public-hide');
      }

      if (name == 'profile_messengers' && checked) {
        $('.messengers').removeClass('public-hide');
      } else if (name == 'profile_messengers' && !checked) {
        $('.messengers').addClass('public-hide');
      }

      if (name == 'additional_phones[]' && checked) {
        $('.user-info__row.phones a.' + id).removeClass('public-hide');
      } else if (name == 'additional_phones[]' && !checked) {
        $('.user-info__row.phones a.' + id).addClass('public-hide');
      }

      if (name == 'additional_emails[]' && checked) {
        $('.user-info__row.emails a.' + id).removeClass('public-hide');
      } else if (name == 'additional_emails[]' && !checked) {
        $('.user-info__row.emails a.' + id).addClass('public-hide');
      }

      if (name == 'profile_msg_telegram' && checked) {
        $('.user-info__link.' + id).removeClass('public-hide');
      } else if (name == 'profile_msg_telegram' && !checked) {
        $('.user-info__link.' + id).addClass('public-hide');
      }

      if (name == 'profile_msg_whatsapp' && checked) {
        $('.user-info__link.' + id).removeClass('public-hide');
      } else if (name == 'profile_msg_whatsapp' && !checked) {
        $('.user-info__link.' + id).addClass('public-hide');
      }

      if (name == 'profile_msg_skype' && checked) {
        $('.user-info__link.' + id).removeClass('public-hide');
      } else if (name == 'profile_msg_skype' && !checked) {
        $('.user-info__link.' + id).addClass('public-hide');
      }

      var data = {
        action: 'save_public_info',
        ajax_nonce: ut_account.ajax_nonce,
        field_name: name,
        form: $('#public_info_form').serialize(),
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {

          if (response.success) {
            $('.form__row.response span span').html(response.data.message);
            $('.form__row.response .form__alert').addClass('success');
            $('.form__row.response').show();
          } else {
            $('.form__row.response span span').html(response.data.message);
            $('.form__row.response .form__alert').addClass('error');
            $('.form__row.response').show();
          }
  
          setTimeout(function () {
            $('.form__row.response span span').html('');
            $('.form__row.response .form__alert').removeClass('success');
            $('.form__row.response').hide();
          }, 2000);
          
        }
      });

    });
  },

  newsletter_settings: function newsletter_settings() {

    $('.page-template-template-account-newsletters input[type="checkbox"].switcher__input').change(function () {

      $('.profile-content__letters .ut-loader').addClass('loading');
      var name = $(this).attr('name');
      var checked = $(this).prop('checked');
      var subscriber_id = $(this).attr('data-esputnik-id');
      var data = {
        action: 'save_newsletter_settings',
        ajax_nonce: ut_account.ajax_nonce,
        field_name: name,
        checked: checked,
        subscriber_id: subscriber_id,
      };

      $.ajax({
        url: ut_account.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {

          if (response.success && response?.data?.id) {
            $('#' + name).attr('data-esputnik-id', response.data.id);
          }
          $('.profile-content__letters .ut-loader').removeClass('loading');
        }
      });

    });
  },

};

$(document).ready(ACCOUNT.init());