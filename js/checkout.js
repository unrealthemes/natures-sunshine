'use strict';

let CHECKOUT = {

  init: function init() {

    CHECKOUT.validate_field();
    CHECKOUT.select();
    CHECKOUT.phone_mask();
    CHECKOUT.add_coupon();
    CHECKOUT.remove_coupon();
    CHECKOUT.checkout_auth();
    CHECKOUT.checkout_id_auth();
    CHECKOUT.edit_id();
    CHECKOUT.time_delivery();
  },

  validate_field: function validate_field() {

    var checkout_form = $('form.woocommerce-checkout');

    checkout_form.on('checkout_place_order', function () {
      var validated = CHECKOUT.validate_handler();

      if ( ! validated ) {  
        
        if ( $(window).innerWidth() < 992 ) {
          var offset = 140;
        } else {
          var offset = 100;
        }

        $('html, body').animate({
          scrollTop: $(".error-field:first").offset().top - offset
        }, 1000);
      }

      return validated;
    });

    $(document).on( 'focusout', '.form-checkout input', function (e) {
      e.preventDefault();
      CHECKOUT.validate_handler();
    });
    
    // $(document).on( 'change', '.form-checkout select', function (e) {
    //   e.preventDefault();
    //   CHECKOUT.validate_handler();
    // });
    
    $(document).on( 'change', '#delivery_date', function (e) {
      e.preventDefault();
      CHECKOUT.validate_handler();
    });
    
    $(document).on( 'change', '#delivery_time', function (e) {
      e.preventDefault();
      CHECKOUT.validate_handler();
    });

    $('#promo').change(function() { 

      if( ! this.checked ) { 
        $('#code').removeClass('invalid');
        $('#code').parents('.form-checkout__row').find('label').removeClass('invalid');
        $('.coupon-wrapper .error-field').remove();
      }
    });


    $('.shipping_method').prop('disabled', true);

  },

  validate_handler: function validate_handler() {

    var validated = true;
    var shipping_method = $('input[name="shipping_method[0]"]:checked', '.form-checkout').val();
    $('.error-field').remove();

    if ( $('#billing_city').val() == 0 ) {  
      $('.form-checkout__city').addClass('invalid');
      $('.form-checkout__city').parent().find('label').addClass('invalid');
      $('.form-checkout__city').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('.form-checkout__city').removeClass('invalid');
      $('.form-checkout__city').parent().find('label').removeClass('invalid');
    }

    // if selected dilivery
    if ( shipping_method == 'ut_free_shipping:17' ) {

      if ( ( $('#billing_city').val() == 'Киев' || $('#billing_city').val() == 'Київ' ) && ! $('#delivery_date').val() ) {
        $('#delivery_date').parent().parent().find('label').addClass('invalid');
        $('#delivery_date').parent().find('.select-selected').css('box-shadow', '0 0 0 1px #eb5454');
        $('#delivery_date').parent().after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
        validated = false;
      } else {
        $('#delivery_date').parent().parent().find('label').removeClass('invalid');
        $('#delivery_date').parent().find('.select-selected').css('box-shadow', '0 0 0 1px #e5e5e5');
      }
      
      if ( ( $('#billing_city').val() == 'Киев' || $('#billing_city').val() == 'Київ' ) && ! $('#delivery_time').val() ) {
        $('#delivery_time').parent().parent().find('label').addClass('invalid');
        $('#delivery_time').parent().find('.select-selected').css('box-shadow', '0 0 0 1px #eb5454');
        $('#delivery_time').parent().after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
        validated = false;
      } else {
        $('#delivery_time').parent().parent().find('label').removeClass('invalid');
        $('#delivery_time').parent().find('.select-selected').css('box-shadow', '0 0 0 1px #e5e5e5');
      }

      if ( $('#billing_address_1').val() == 0 ) {  
        $('#billing_address_1').addClass('invalid');
        $('#billing_address_1').parent().parent().find('label').addClass('invalid');
        $('#billing_address_1').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
        validated = false;
      } else {
        $('#billing_address_1').removeClass('invalid');
        $('#billing_address_1').parent().parent().find('label').removeClass('invalid');
      }

      if ( $('#billing_address_2').val() == 0 ) {  
        $('#billing_address_2').addClass('invalid');
        $('#billing_address_2').parent().find('label').addClass('invalid');
        $('#billing_address_2').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
        validated = false;
      } else {
        $('#billing_address_2').removeClass('invalid');
        $('#billing_address_2').parent().find('label').removeClass('invalid');
      }

    } else {
      $('#billing_address_1').removeClass('invalid');
      $('#billing_address_1').parent().parent().find('label').removeClass('invalid');

      $('#billing_address_2').removeClass('invalid');
      $('#billing_address_2').parent().find('label').removeClass('invalid');
    }

    // if selected pickup
    if ( shipping_method == 'nova_poshta_shipping_method:12' && $('select[name="nova_poshta_warehouse"]').val() == 0 ) { 
      $('#nova_poshta_warehouse .select2-selection__rendered').css('box-shadow', '0 0 0 1px #eb5454');
      $('#nova_poshta_warehouse .select2-container').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#nova_poshta_warehouse .select2-selection__rendered').css('box-shadow', '0 0 0 1px #e5e5e5');
    }
    
    if ( shipping_method == 'nova_poshta_shipping_method_poshtomat:13' && $('select[name="nova_poshta_poshtomat"]').val() == 0 ) { 
      $('#nova_poshta_poshtomat .select2-selection__rendered').css('box-shadow', '0 0 0 1px #eb5454');
      $('#nova_poshta_poshtomat .select2-container').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#nova_poshta_poshtomat .select2-selection__rendered').css('box-shadow', '0 0 0 1px #e5e5e5');
    }
    
    if ( shipping_method == 'ukrposhta_shippping:15' && $('select[name="ukr_warehouses"]').val() == 0 ) { 
      $('#ukr_warehouses .select2-selection__rendered').css('box-shadow', '0 0 0 1px #eb5454');
      $('#ukr_warehouses .select2-container').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#ukr_warehouses .select2-selection__rendered').css('box-shadow', '0 0 0 1px #e5e5e5');
    }
    
    if ( $('#billing_last_name').val() == 0 ) {  
      $('#billing_last_name').addClass('invalid');
      $('#billing_last_name').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#billing_last_name').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#billing_last_name').removeClass('invalid');
      $('#billing_last_name').parents('.form-checkout__row').find('label').removeClass('invalid');
    }

    if ( $('#billing_first_name').val() == 0 ) {  
      $('#billing_first_name').addClass('invalid');
      $('#billing_first_name').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#billing_first_name').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#billing_first_name').removeClass('invalid');
      $('#billing_first_name').parents('.form-checkout__row').find('label').removeClass('invalid');
    }

    if ( $('#patronymic').val() == 0 ) {  
      $('#patronymic').addClass('invalid');
      $('#patronymic').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#patronymic').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#patronymic').removeClass('invalid');
      $('#patronymic').parents('.form-checkout__row').find('label').removeClass('invalid');
    }

    /* https://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
    var pattern = new RegExp( /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[0-9a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i ); // eslint-disable-line max-len

    if ( $('#billing_email').val() == 0 ) {  
      $('#billing_email').addClass('invalid');
      $('#billing_email').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#billing_email').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else if ( ! pattern.test( $('#billing_email').val() ) ) {
      $('#billing_email').addClass('invalid');
      $('#billing_email').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#billing_email').after('<span class="error-field">' + ut_checkout.email_txt + '</span>');
      validated = false;
    } else {
      $('#billing_email').removeClass('invalid');
      $('#billing_email').parents('.form-checkout__row').find('label').removeClass('invalid');
    }

    if ( $('#billing_phone').val() == 0 ) {  
      $('#billing_phone').addClass('invalid');
      $('#billing_phone').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#billing_phone').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else if ( $('#billing_phone').val().match(/\d/g)?.length != 12 ) {
      $('#billing_phone').addClass('invalid');
      $('#billing_phone').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#billing_phone').after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#billing_phone').removeClass('invalid');
      $('#billing_phone').parents('.form-checkout__row').find('label').removeClass('invalid');
    }

    if ( $('#promo').is(":checked") && $('#code').val() == 0 ) {
      $('#code').addClass('invalid');
      $('#code').parents('.form-checkout__row').find('label').addClass('invalid');
      $('#code').parent().parent().after('<span class="error-field">' + ut_checkout.required_txt + '</span>');
      validated = false;
    } else {
      $('#code').removeClass('invalid');
      $('#code').parents('.form-checkout__row').find('label').removeClass('invalid');
    }

    return validated;
  },

  select: function select() {
    $('.js-basic-single').select2({
      width: '100%'
    })
  },

  phone_mask: function phone_mask() {
    $('.mask-js').inputmask({"mask": "+38(999) 999-9999"});
  },

  add_coupon: function add_coupon() {

    $('body').on('click', '.coupon-js', function() {
        
    $('.coupon-wrapper .ut-loader').addClass('loading');
    var data = {
      action : 'add_coupon',
      ajax_nonce : ut_checkout.ajax_nonce,
      coupon : $('#code').val(),
    };

    $.ajax({
      type : 'POST',
      url  : ut_checkout.ajax_url,
      data : data,
      success: function(response) {

        $('.coupon-wrapper .error-field').remove();

        if ( response.success ) {
          // $('#code').css({'border-color': '#e5e5e5'});
          // $('body').trigger('update_checkout');
          $('#code').removeClass('invalid');
          $('#code').parents('.form-checkout__row').find('label').removeClass('invalid');
        } else {
          $('#code').addClass('invalid');
          $('#code').parents('.form-checkout__row').find('label').addClass('invalid');
          $('#code').parent().parent().after('<span class="error-field">' + response.data.message + '</span>');
          // $('#code').css({'border-color': '#ee6a5e'});
          // $('.woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout').remove();
          // $('.woocommerce-notices-wrapper').remove();
          // $('.form-checkout').prepend(response.data.message);
          // CHECKOUT.scroll_to_notices();
        }
        $('.coupon-wrapper .ut-loader').removeClass('loading');
      }
    });
      });
  },
  	
  remove_coupon: function remove_coupon() {

    $('body').on('click', '.remove-coupon-js', function() {
        
    $('.form-checkout__total .ut-loader').addClass('loading');
    var data = {
      action : 'remove_coupon',
      ajax_nonce : ut_checkout.ajax_nonce,
      coupon : $(this).data('coupon'),
    };

    $.ajax({
      type : 'POST',
      url  : ut_checkout.ajax_url,
      data : data,
      success: function(response) {

        if ( response.success ) {
          $('body').trigger('update_checkout');
        }
        // $('.form-checkout__total .ut-loader').removeClass('loading');
      }
    });
      });
  },

  checkout_auth: function checkout_auth() {

    $('#checkout_auth_form button').on('click', function (e) {
      e.preventDefault();
      $('#checkout_auth_form .ut-loader').addClass('loading');
      let data = {
        action: 'user_auth',
        ajax_nonce: ut_user.ajax_nonce,
        page: 'checkout',
        login_name: $('#login_name').val(),
        login_password: $('#login_password').val(),
      };

      $.ajax({
        url: ut_user.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {
          $('#auth_error').hide();

          if (response.success == false) {

            if (response.data.message) {
              // without tag <a href=""></a>
              let message = response.data.message.replace(/<a\b[^>]*>(.*?)<\/a>/i, "");
              $('#auth_error span span').html(message);
              $('#auth_error').show();
            }
            $('#checkout_auth_form .ut-loader').removeClass('loading');
          } else {
            location.reload();
          }
        }
      });
    });
  },

  checkout_id_auth: function checkout_id_auth() {

    $('#checkout_auth_id_form button').on('click', function (e) {
      e.preventDefault();
      $('#checkout_auth_id_form .ut-loader').addClass('loading');
      let data = {
        action: 'register_id_auth',
        ajax_nonce: ut_checkout.ajax_nonce,
        register_id: $('#register_id').val(),
      };

      $.ajax({
        url: ut_checkout.ajax_url,
        data: data,
        type: 'POST',
        success: function (response) {
          $('#auth_id_error').hide();

          if (response.success == false) {

            if (response.data.message) {
              $('#auth_id_error span span').html(response.data.message);
              $('#auth_id_error').show();
            }
            $('#checkout_auth_id_form .ut-loader').removeClass('loading');
          } else {
            location.reload();
          }
        }
      });
    });
  },

  scroll_to_notices: function scroll_to_notices() {

    var scrollElement = $( '.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout' );

    if ( ! scrollElement.length ) {
      scrollElement = $( 'form.checkout' );
    }
    $.scroll_to_notices( scrollElement );
  },

  edit_id: function edit_id() {

    $('#form_edit_id').submit( function( e ) {
      e.preventDefault();
      $('#edit_id .ut-loader').addClass('loading');
      var data = {
        action : 'edit_id',
        ajax_nonce : ut_checkout.ajax_nonce,
        form : $('#form_edit_id').serialize(),
      };

      $.ajax({
        type : 'POST',
        url  : ut_checkout.ajax_url,
        data : data,
        success: function( response ) {

          if ( response.success == false ) { 
            $('#register_id').addClass('invalid');
          } else {
            $('.your-id b').text($('#register_id').val());
            $('#register_id').removeClass('invalid');
            Fancybox.getInstance().close();
          }
          $('#edit_id .ut-loader').removeClass('loading');
        }
      });
    });
  },

  time_delivery: function time_delivery() {

    $('#delivery_date').change(function() {
      var date_val = $(this).val();
      // reset time select
      $("#delivery_time").val($("#delivery_time option:first").val());
      // 
      CHECKOUT.time_delivery_disabled( date_val );
    });

  },

  time_delivery_disabled: function time_delivery_disabled( current_date ) {

    if ( current_date == "" ) {
      $("#delivery_time option").prop('disabled', true);
    } else {
      $("#delivery_time > option").each(function() {
        var dates = $(this).data('dates');

        if ( dates.indexOf(current_date) >= 0 ) {
          $(this).prop('disabled', false);
        } else {
          $(this).prop('disabled', true);
        }
      });
    }
    CHECKOUT.rebuild_select();
  },

  rebuild_select: function rebuild_select() {
		$('.select-selected').remove();
		$('.select .select-hide').remove();
		customSelect(); // init custom select
	}

};

$(document).ready(CHECKOUT.init());