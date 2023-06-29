'use strict';

let CART = {

  init: function init() {

    CART.quantity_update_cart();
    CART.remove_product_cart();
    CART.remove_product_mini_cart();
    CART.quantity_update_mini_cart();
    CART.clear_cart_search();
    CART.add_to_simple_cart_search();
    CART.add_to_join_cart_search();
    CART.add_to_join_cart_partner_search();
    CART.clear_join_cart_search();
    CART.joint_order_mode();
    CART.exit_join_cart();
    CART.add_partner_id();
    CART.prepare_to_remove_partner_cart();
    CART.remove_partner_cart();
    // autocomplete
    CART.autocomplete_cart_search();
    CART.autocomplete_join_cart_search();
    CART.autocomplete_join_cart_partner_search();

  },

  quantity_update_cart: function quantity_update_cart() {

    $('body').on('click', '.woocommerce-cart-form .counter__btn_mini', function () {

      var $this = $(this);
      var val = $(this).parent().find('input[name="qty"]').val();
      $this.parents('.cart-products__list-item').find('.ut-loader').addClass('loading');

      if ($(this).hasClass('counter-plus')) {
        var result_val = Math.max(parseInt(val) + 1, 0);
      } else if ($(this).hasClass('counter-minus')) {
        var result_val = Math.max(parseInt(val) - 1, 0);
      }

      if (!result_val) {
        result_val = 1;
      }

      $(this).parent().find('input[name="qty"]').val(result_val);

      var data = {
        action: 'quantity_update_cart',
        ajax_nonce: ut_cart.ajax_nonce,
        cart_item_key: $(this).parents('.cart-products__list-item').data('item-key'),
        qty: $(this).parent().find('input[name="qty"]').val(),
      };

      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $(document.body).trigger('wc_fragment_refresh');
          }
          $this.parents('.cart-products__list-item').find('.ut-loader').removeClass('loading');
        }
      });
    });
  },

  quantity_update_mini_cart: function quantity_update_mini_cart() {

    $('body').on('click', '.mini-cart-wrapper .counter__btn_mini', function () {

      $('.mini-cart-wrapper .ut-loader').addClass('loading');
      var val = $(this).parent().find('input[name="qty"]').val();

      if ($(this).hasClass('counter-plus')) {
        var result_val = Math.max(parseInt(val) + 1, 0);
      } else if ($(this).hasClass('counter-minus')) {
        var result_val = Math.max(parseInt(val) - 1, 0);
      }

      if (!result_val) {
        result_val = 1;
      }

      $(this).parent().find('input[name="qty"]').val(result_val);

      var data = {
        action: 'quantity_update_mini_cart',
        ajax_nonce: ut_cart.ajax_nonce,
        cart_item_key: $(this).parents('.cart-products__list-item').data('item-key'),
        qty: $(this).parent().find('input[name="qty"]').val(),
      };

      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $(document.body).trigger('wc_fragment_refresh');
          }
          $('.mini-cart-wrapper .ut-loader').removeClass('loading');
        }
      });
    });
  },

  remove_product_cart: function remove_product_cart() {

    $('body').on('click', '.woocommerce-cart-form .remove_from_cart_button', function (event) {

      // $(this).parents('.cart-products__list-item').find('.ut-loader').addClass('loading');
      $('.cart-products__list-item .ut-loader').addClass('loading');
    });
  },

  remove_product_mini_cart: function remove_product_mini_cart() {

    $('body').on('click', '.mini-cart-wrapper .remove_from_cart_button', function (event) {

      $('.mini-cart-wrapper .ut-loader').addClass('loading');
    });

  },

  clear_cart_search: function clear_cart_search() {

    $('body').on('click', '#clear_cart_search', function (event) {

      $('.woocommerce-cart-form .cart-products__search .ut-loader').addClass('loading');
      $('.cart-products__search-clean').hide();
      $('.woocommerce-cart-form .cart-products__search-list').html('');
      $('#cart_search').val('');
      $('.woocommerce-cart-form .cart-products__search .ut-loader').removeClass('loading');
    });

  },

  add_to_simple_cart_search: function add_to_simple_cart_search() {

    $('body').on('click', '.simple-cart .add_to_cart_search', function (event) {

      $('.woocommerce-cart-form .cart-products__search .ut-loader').addClass('loading');
      var data = {
        action: 'add_to_cart',
        ajax_nonce: ut_cart.ajax_nonce,
        product_id: $(this).data('id'),
      };

      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $(document.body).trigger('wc_fragment_refresh');
            cartCollapse();
          }
          $('.mini-cart-wrapper .ut-loader').removeClass('loading');
          $('.woocommerce-cart-form .cart-products__search .ut-loader').removeClass('loading');
        }
      });

    });

  },

  /********* Join cart *********/

  add_to_join_cart_search: function add_to_join_cart_search() {

    $('body').on('click', '.join-cart .my-cart .add_to_cart_search', function (event) {

      var $this = $(this);
      $this.parents('.cart-products__search').find('.ut-loader').addClass('loading');
      var data = {
        action: 'add_to_cart',
        ajax_nonce: ut_cart.ajax_nonce,
        product_id: $(this).data('id'),
      };

      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $(document.body).trigger('wc_fragment_refresh');
            document.querySelectorAll('.cart-collapse').forEach(collapse => {
              if (collapse.classList.contains('active')) {
                collapse.style.maxHeight = collapse.scrollHeight + "px";
              }
            });
          }
          $('.mini-cart-wrapper .ut-loader').removeClass('loading');
          $this.parents('.cart-products__search').find('.ut-loader').removeClass('loading');
        }
      });

    });

  },

  add_to_join_cart_partner_search: function add_to_join_cart_partner_search() {

    $('body').on('click', '.join-cart .partner-cart .add_to_cart_search', function (event) {

      var $this = $(this);
      var partner_id = $this.parents('.cart-products__block').data('partner-id');
      $this.parents('.cart-products__search').find('.ut-loader').addClass('loading');
      var data = {
        action: 'add_to_cart_partner',
        ajax_nonce: ut_cart.ajax_nonce,
        product_id: $this.data('id'),
        partner_id: partner_id,
      };

      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $(document.body).trigger('wc_fragment_refresh');
            setTimeout(function () {
              document.querySelectorAll('.cart-collapse').forEach(collapse => {
                if (collapse.classList.contains('active')) {
                  collapse.style.maxHeight = collapse.scrollHeight + "px";
                }
              });
            }, 2000);
          }
          $this.parents('.cart-products__search').find('.ut-loader').removeClass('loading');
        }
      });

    });

  },

  clear_join_cart_search: function clear_join_cart_search() {

    $('body').on('click', '.clear-join-cart-search', function (event) {
      $(this).parents('.cart-products__search').find('.ut-loader').addClass('loading');
      $(this).parents('.cart-products__search').find('.cart-products__search-clean').hide();
      $(this).parents('.cart-products__search').find('.cart-products__search-list').html('');
      $(this).parents('.cart-products__search').find('.join_cart_partner_search').val('');
      $(this).parents('.cart-products__search').find('#join_cart_search').val('');
      $(this).parents('.cart-products__search').find('.ut-loader').removeClass('loading');
    });

  },

  joint_order_mode: function joint_order_mode() {

    $('#joint_order_mode').change(function () {

      var checked = $(this).prop('checked');
      var redirect_url = $('#redirect_url').val();
      $(this).prop('disabled', true);

      if (checked) {
        $('.switcher__label span').css('background-color', '#00a88f');
        $('.switcher__label span').addClass('on');
      } else {
        $('.switcher__label span').css('background-color', '#e5e5e5');
        $('.switcher__label span').removeClass('on');
      }

      var data = {
        action: 'joint_order_mode',
        ajax_nonce: ut_cart.ajax_nonce,
        checked: checked,
        redirect_url: redirect_url,
      };
      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            document.location.href = redirect_url;
          }
        }
      });
    });

  },

  exit_join_cart: function exit_join_cart() {

    $('body').on('click', '.exit-join-cart-js', function (event) {

      var redirect_url = $('#redirect_url').val();
      var data = {
        action: 'exit_join_cart',
        ajax_nonce: ut_cart.ajax_nonce,
        redirect_url: redirect_url,
      };
      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            document.location.href = redirect_url;
          }
        }
      });
    });
  },

  add_partner_id: function add_partner_id() {

    $(document).on('click', '.js-joint-id', function (event) {

      event.preventDefault();
      $('#partner_id').removeClass('invalid');
      $('.cart-products__partner .ut-loader').addClass('loading');
      var partner_id = $('#partner_id').val();
      var send_ajax = true;

      if (partner_id == '') {
        $('#partner_id').addClass('invalid');
        $('.cart-products__partner .ut-loader').removeClass('loading');
        send_ajax = false;
      }

      $('.cart-products__block').each((index, element) => {

        if ($(element).data('partner-id') == partner_id) {
          $([document.documentElement, document.body]).animate({
            scrollTop: $('#' + partner_id).offset().top
          }, 2000);

          send_ajax = false;
        }
      });

      if (send_ajax) {
        var data = {
          action: 'check_reg_id',
          ajax_nonce: ut_cart.ajax_nonce,
          partner_id: partner_id,
        };

        $.ajax({
          type: 'POST',
          url: ut_cart.ajax_url,
          data: data,
          success: function (response) {

            if (response.success == false) { // Show modal

              if (response.data.show_modal) {
                $('#cart_id .partner-id').html(partner_id);
                $('#add_new_partner_id').val(partner_id);
                Fancybox.show([{
                  src: "#cart_id",
                  type: "inline",
                  dragToClose: false
                }]);
              }
            } else { // Send ajax for create block partner id cart
              CART.add_parent_id_ajax(partner_id, false);
            }
            $('.cart-products__partner .ut-loader').removeClass('loading');
          }
        });
      }
    });

    $(document).on('click', '.add-new-partner-id-js', function (event) {

      var partner_id = $('#add_new_partner_id').val();
      $('#cart_id .ut-loader').addClass('loading');

      CART.add_parent_id_ajax(partner_id, true);
    });
  },

  add_parent_id_ajax: function add_parent_id_ajax(partner_id, modal) {

    if (modal) {
      $('#cart_id .ut-loader').addClass('loading');
    } else {
      $('.cart-products__partner .ut-loader').addClass('loading');
    }
    var data = {
      action: 'add_reg_id',
      ajax_nonce: ut_cart.ajax_nonce,
      partner_id: partner_id,
    };

    $.ajax({
      type: 'POST',
      url: ut_cart.ajax_url,
      data: data,
      success: function (response) {

        if (response.success) {
          $('#partner_id').val('');
          $('.cart-products').append(response.data.join_cart_partner_item_html);
          CART.autocomplete_join_cart_partner_search();
          // cartCollapse();

          if (modal) {
            $('#add_new_partner_id').val('');
            Fancybox.getInstance().close();
          }
          $([document.documentElement, document.body]).animate({
            scrollTop: $('#' + response.data.partner_id).offset().top
          }, 2000);
        }

        $(document.body).trigger('wc_fragment_refresh');

        if (modal) {
          $('#cart_id .ut-loader').removeClass('loading');
        } else {
          $('.cart-products__partner .ut-loader').removeClass('loading');
        }
      }
    });
  },

  prepare_to_remove_partner_cart: function prepare_to_remove_partner_cart() {

    $(document).on('click', '.cart-products__block-remove', function (event) {
      var $this = $(this);
      var partner_id = $this.parents('.cart-products__block').data('partner-id');
      $('#delete_partner_id').val(partner_id);
    });
  },

  remove_partner_cart: function remove_partner_cart() {

    $(document).on('click', '.delete-partner-js', function (event) {
      $('#remove_cart .ut-loader').addClass('loading');
      var partner_id = $('#delete_partner_id').val();
      var data = {
        action: 'remove_partner_cart',
        ajax_nonce: ut_cart.ajax_nonce,
        partner_id: partner_id,
      };

      $.ajax({
        type: 'POST',
        url: ut_cart.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $('#' + partner_id).remove();
            $(document.body).trigger('wc_fragment_refresh');
            Fancybox.getInstance().close();
          }
          $('#remove_cart .ut-loader').removeClass('loading');
        }
      });
    });
  },

  autocomplete_cart_search: function autocomplete_cart_search() {

    $('#cart_search').autocomplete({

      source: function (request, response) {

        $('.woocommerce-cart-form .cart-products__search .ut-loader').addClass('loading');
        $.ajax({
          dataType: 'json',
          url: ut_cart.ajax_url,
          data: {
            ajax_nonce: ut_cart.ajax_nonce,
            action: 'autocomplete_cart',
            search_txt: request.term,
          },
          success: function (response) {

            if (response.data.products != '') {
              $('.cart-products__search-clean').show();
              $('.cart-search-list').html(response.data.products);
            } else {
              $('.cart-products__search-clean').hide();
              $('.cart-search-list').html(
                '<h2 class="text_not_found">' + response.data.text_not_found + '</h2>'
              );
            }
            $('.woocommerce-cart-form .cart-products__search .ut-loader').removeClass('loading');
          }
        });
      },
      minLength: 3,
    });
  },

  autocomplete_join_cart_search: function autocomplete_join_cart_search() {

    $('#join_cart_search').autocomplete({

      source: function (request, response) {

        var $this = $(this.element);
        $this.parents('.cart-products__block').find('.cart-products__search .ut-loader').addClass('loading');
        $.ajax({
          dataType: 'json',
          url: ut_cart.ajax_url,
          data: {
            ajax_nonce: ut_cart.ajax_nonce,
            action: 'autocomplete_cart',
            search_txt: request.term,
          },
          success: function (response) {

            if (response.data.products != '') {
              $this.parents('.cart-products__block').find('.cart-products__search-clean').show();
              $this.parents('.cart-products__block').find('.join-cart-search-list').html(response.data.products);
            } else {
              $this.parents('.cart-products__block').find('.cart-products__search-clean').hide();
              $this.parents('.cart-products__block').find('.join-cart-search-list').html(
                '<h2 class="text_not_found">' + response.data.text_not_found + '</h2>'
              );
            }
            document.querySelectorAll('.cart-collapse').forEach(collapse => {
              if (collapse.classList.contains('active')) {
                collapse.style.maxHeight = collapse.scrollHeight + "px";
              }
            });
            $this.parents('.cart-products__block').find('.cart-products__search .ut-loader').removeClass('loading');
          }
        });
      },
      minLength: 3,
    });
  },

  autocomplete_join_cart_partner_search: function autocomplete_join_cart_partner_search() {

    $('.join_cart_partner_search').autocomplete({

      source: function (request, response) {

        var $this = $(this.element);
        $this.parents('.cart-products__block').find('.cart-products__search .ut-loader').addClass('loading');
        $.ajax({
          dataType: 'json',
          url: ut_cart.ajax_url,
          data: {
            ajax_nonce: ut_cart.ajax_nonce,
            action: 'autocomplete_cart',
            search_txt: request.term,
          },
          success: function (response) {

            if (response.data.products != '') {
              $this.parents('.cart-products__block').find('.cart-products__search-clean').show();
              $this.parents('.cart-products__block').find('.join-cart-search-list').html(response.data.products);
            } else {
              $this.parents('.cart-products__block').find('.cart-products__search-clean').hide();
              $this.parents('.cart-products__block').find('.join-cart-search-list').html(
                '<h2 class="text_not_found">' + response.data.text_not_found + '</h2>'
              );
            }
            // cartCollapse();
            document.querySelectorAll('.cart-collapse').forEach(collapse => {
              if (collapse.classList.contains('active')) {
                collapse.style.maxHeight = collapse.scrollHeight + "px";
              }
            });
            $this.parents('.cart-products__block').find('.cart-products__search .ut-loader').removeClass('loading');
          }
        });
      },
      minLength: 3,
    });
  },

};

$(document).ready(CART.init);