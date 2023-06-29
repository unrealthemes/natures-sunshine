'use strict';

let WISHLIST = {

  init: function init() {

    WISHLIST.buy_all_list();
    WISHLIST.remove_from_wishlist();
    WISHLIST.remove_from_wishlist_checkbox();
    WISHLIST.remove_wishlist();
    WISHLIST.prepare_to_move();
    WISHLIST.products_move_to_list();
    WISHLIST.choose_all_wishlist();
    WISHLIST.prepare_to_edit_name();
    WISHLIST.edit_name_wishlist();
    WISHLIST.create_wishlist();
    WISHLIST.make_primary_wishlist();
    WISHLIST.add_product_to_wishlist();
    WISHLIST.add_product_to_wishlist_postpone();
    WISHLIST.copy_wishlist_link();

  },

  buy_all_list: function buy_all_list() {

    $('body').on('click', '.buy_all', function (event) {

      event.preventDefault();
      var $this = $(this);
      var loader_el = $(this).parents('.favorites-block').find('.ut-loader');
      loader_el.addClass('loading');

      var product_ids = [];
      var product_list = $(this).parents('.favorites-block').find('.favorites-slider .cards__item');

      product_list.each(function (index, value) {
        var product_id = $(this).data('id');
        product_ids.push(product_id);
      });

      var data = {
        action: 'buy_all_list',
        ajax_nonce: ut_wishlist.ajax_nonce,
        product_ids: product_ids,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $(document.body).trigger('wc_fragment_refresh');
            // $this.html(response.data.txt_btn).attr('href', response.data.cart_url);
            var link_html = '<a href="' + wc_add_to_cart_params.cart_url + '" class="btn btn-transparent card__controls-buy">' +
                                '<svg class="btn__icon" width="24" height="24">' +
                                '<use xlink:href="' + ut_product.dist_uri + '/images/sprite/svg-sprite.svg#check"></use>' +
                                '</svg>' +
                                '<span class="btn__text">' + ut_product.cart_txt + '</span>' +
                            '</a>';
            $this.after(link_html);
            $this.hide();
          }
          loader_el.removeClass('loading');
        }
      });
    });
  },

  remove_from_wishlist: function remove_from_wishlist() {

    $('body').on('click', '.remove_from_wishlist', function (event) {

      event.preventDefault();
      // var loader_el = $(this).parents('.favorites-block').find('.ut-loader');
      // loader_el.addClass('loading');
      var wishlist_id = $(this).parents('.favorites-block').data('wishlist-id');
      var product_id = $(this).parents('.cards__item').data('id');
      var data = {
        action: 'remove_product_from_wishlist',
        ajax_nonce: ut_wishlist.ajax_nonce,
        wishlist_id: wishlist_id,
        product_id: product_id,
      };
      $(this).parents('.cards__item.swiper-slide').remove();
      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $('.favorites-block[data-wishlist-id="' + wishlist_id + '"]').replaceWith(response.data.wishlist_html);
            $('.cards__item.swiper-slide.post-' + response.data.product_id + ' .add_product_to_wishlist').replaceWith(response.data.icon_html);
            collapseResize();
            cardsSliderInit();
          }
          // loader_el.removeClass('loading');
        }
      });
    });
  },

  remove_from_wishlist_checkbox: function remove_from_wishlist_checkbox() {

    $('body').on('click', '.remove_from_wishlist_checkbox', function (event) {

      event.preventDefault();
      var loader_el = $(this).parents('.favorites-block').find('.ut-loader');
      loader_el.addClass('loading');
      var wishlist_id = $(this).parents('.favorites-block').data('wishlist-id');
      var product_ids = [];
      var product_list = $(this).parents('.favorites-block').find('.card__checkbox input');

      product_list.each(function (index, value) {

        if (this.checked) {
          var product_id = $(this).attr('id');
          product_ids.push(product_id);
        }
      });

      var data = {
        action: 'remove_from_wishlist_checkbox',
        ajax_nonce: ut_wishlist.ajax_nonce,
        wishlist_id: wishlist_id,
        product_ids: product_ids,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $('.favorites-block[data-wishlist-id="' + wishlist_id + '"]').replaceWith(response.data.wishlist_html);
            $.each(response.data.product_ids, function (index, value) {
              $('.cards__item.swiper-slide.post-' + value + ' .add_product_to_wishlist')
                .prop('disabled', false)
                .removeClass('header__controls-filled')
                .find('svg use').attr('xlink:href', ut_wishlist.theme_uri + '/assets/dist/images/sprite/svg-sprite.svg#heart');
            });
            collapseResize();
            cardsSliderInit();
          }
          loader_el.removeClass('loading');
        }
      });
    });
  },

  remove_wishlist: function remove_wishlist() {

    $('body').on('click', '.remove_wishlist', function (event) {

      event.preventDefault();
      var loader_el = $(this).parents('.favorites-block').find('.ut-loader');
      loader_el.addClass('loading');
      var wishlist_id = $(this).parents('.favorites-block').data('wishlist-id');

      var data = {
        action: 'remove_wishlist',
        ajax_nonce: ut_wishlist.ajax_nonce,
        wishlist_id: wishlist_id,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            //
            $('#replace_list select option[value="'+wishlist_id+'"]').remove();
            WISHLIST.rebuild_select();
            //
            $('.favorites-block[data-wishlist-id="' + wishlist_id + '"]').remove();
          }
          loader_el.removeClass('loading');
        }
      });
    });
  },

  prepare_to_move: function prepare_to_move() {

    $('body').on('click', '.js-move', function (event) {

      var product_ids = [];
      var wishlist_id_from = $(this).parents('.favorites-block').data('wishlist-id');
      var product_list = $(this).parents('.favorites-block').find('.card__checkbox input');

      product_list.each(function (index, value) {

        if (this.checked) {
          var product_id = $(this).attr('id');
          product_ids.push(product_id);
        }
      });

      $('#replace_list input[name="product_ids"]').val(product_ids);
      $('#replace_list input[name="wishlist_id_from"]').val(wishlist_id_from);
      $('#replace_list select option').prop("disabled", "");
      $('#replace_list option[value="' + wishlist_id_from + '"]').attr("selected", "selected").prop("disabled", "disabled");
      WISHLIST.rebuild_select();

    });
  },

  products_move_to_list: function products_move_to_list() {

    $('#lists').change(function() { 

      $('.favorites-block .ut-loader').addClass('loading');
      $('#replace_list .ut-loader').addClass('loading');
      var wishlist_id_from = $('#replace_list input[name="wishlist_id_from"]').val();
      var wishlist_id_to = this.value;
      var product_ids = $('#replace_list input[name="product_ids"]').val();

      var data = {
        action: 'products_move_to_wishlist',
        ajax_nonce: ut_wishlist.ajax_nonce,
        wishlist_id_from: wishlist_id_from,
        wishlist_id_to: wishlist_id_to,
        product_ids: product_ids,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.data.wishlist_from_html && response.data.wishlist_from_to) {
            $('.favorites-block[data-wishlist-id="' + wishlist_id_from + '"]').replaceWith(response.data.wishlist_from_html);
            $('.favorites-block[data-wishlist-id="' + wishlist_id_to + '"]').replaceWith(response.data.wishlist_from_to);
            collapseResize();
            cardsSliderInit();
            Fancybox.getInstance().close();
          }
          $('.favorites-block .ut-loader').removeClass('loading');
          $('#replace_list .ut-loader').removeClass('loading');
        }
      });

    });
  },

  choose_all_wishlist: function choose_all_wishlist() {

    $('body').on('click', '.choose_all_wishlist', function (event) {

      var checked = $(this).find('input[type="checkbox"]').prop('checked');
      var product_list = $(this).parents('.favorites-block').find('.card__checkbox input[type="checkbox"]');

      if (checked) {
        $(this).find('input[type="checkbox"]').prop('checked', false);
        product_list.prop('checked', false);
        $(this).parent().find('.js-move').prop('disabled', true);
        $(this).parent().find('.js-remove').prop('disabled', true);
      } else {
        $(this).find('input[type="checkbox"]').prop('checked', true);
        product_list.prop('checked', true);
        $(this).parent().find('.js-move').prop('disabled', false);
        $(this).parent().find('.js-remove').prop('disabled', false);
      }

    });
  },

  prepare_to_edit_name: function prepare_to_edit_name() {

    $('body').on('click', '.prepare_edit_name_wishlist', function (event) {

      var wishlist_id = $(this).parents('.favorites-block').data('wishlist-id');
      var old_name = $(this).parents('.favorites-block').find('.favorites-title').text();

      $('#edit_title input[name="wishlist_id"]').val(wishlist_id);
      $('#edit_title input[name="edit_name"]').val($.trim(old_name));
    });
  },

  edit_name_wishlist: function edit_name_wishlist() {

    $('body').on('click', '.edit_name_wishlist', function (event) {

      $('#edit_title .ut-loader').addClass('loading');
      var wishlist_id = $('#edit_title input[name="wishlist_id"]').val();
      var name = $('#edit_title input[name="edit_name"]').val();

      var data = {
        action: 'edit_name_wishlist',
        ajax_nonce: ut_wishlist.ajax_nonce,
        wishlist_id: wishlist_id,
        name: name,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $('.favorites-block[data-wishlist-id="' + wishlist_id + '"] .favorites-title').html(name);
            Fancybox.getInstance().close();
          }
          $('#edit_title .ut-loader').removeClass('loading');
        }
      });
    });
  },

  create_wishlist: function create_wishlist() {

    $('body').on('click', '.create_wishlist', function (event) {

      $('#add_list .ut-loader').addClass('loading');
      var name = $('#add_list input[name="list_name"]').val();
      var primary = $('#add_list input[name="list_main"]').prop('checked');

      if ( ! name ) {
        $('#add_list .ut-loader').removeClass('loading');
        return false;
      }

      var data = {
        action: 'create_wishlist',
        ajax_nonce: ut_wishlist.ajax_nonce,
        name: name,
        primary: primary,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            //
            $('#replace_list select').append('<option value="'+response.data.wishlist_id+'">'+response.data.wishlist_name+'</option>');
            WISHLIST.rebuild_select();
            //
            $('.form.form-favorites').append(response.data.wishlist_html); 
            Fancybox.getInstance().close();
            collapseResize();
          }
          $('#add_list .ut-loader').removeClass('loading');
        }
      });
    });
  },

  make_primary_wishlist: function make_primary_wishlist() {

    $('body').on('click', '.make_primary_wishlist', function (event) {

      $('.favorites-block .ut-loader').addClass('loading');
      var new_wishlist_id = $(this).parents('.favorites-block').data('wishlist-id');

      var data = {
        action: 'make_primary_wishlist',
        ajax_nonce: ut_wishlist.ajax_nonce,
        new_wishlist_id: new_wishlist_id,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.data.wishlist_old_primary_html && response.data.wishlist_new_primary_html) {
            $('.favorites-block[data-wishlist-id="' + response.data.wishlist_id_old_primary + '"]').replaceWith(response.data.wishlist_old_primary_html);
            $('.favorites-block[data-wishlist-id="' + response.data.wishlist_id_new_primary + '"]').replaceWith(response.data.wishlist_new_primary_html);
            collapseResize();
            cardsSliderInit();
          }
          $('.favorites-block .ut-loader').removeClass('loading');
        }
      });
    });
  },

  add_product_to_wishlist: function add_product_to_wishlist() {

    $('body').on('click', '.add_product_to_wishlist', function (event) {

      var $this = $(this);
      $this.prop('disabled', true);
      // $this.find('.ut-loader').addClass('loading');
      var product_id = $this.data('product-id');
      var wishlist_id = $this.data('wishlist-id');
      var wishlist_page = $('body').hasClass('page-template-template-favorites');

      var slider = $this.parents('.cards');
      // var slideID = slider[0].swiper.activeIndex;

      if ( wishlist_id ) {
        var action = 'remove_product_from_wishlist';
        WISHLIST.replace_icon($this, 'remove');
      } else {
        var action = 'add_product_to_wishlist';
        WISHLIST.replace_icon($this, 'add');
      }

      var data = {
        action: action,
        ajax_nonce: ut_wishlist.ajax_nonce,
        product_id: product_id,
        wishlist_id: wishlist_id,
        wishlist_page: wishlist_page,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $this.replaceWith(response.data.icon_html);

            if (wishlist_page) {

              if ( $('.favorites-block[data-wishlist-id="' + response.data.wishlist_id + '"]').length ) {
                $('.favorites-block[data-wishlist-id="' + response.data.wishlist_id + '"]').replaceWith(response.data.wishlist_html);
              } else {
                $('.form.form-favorites').append(response.data.wishlist_html);
              }
              cardsSliderInit();
              collapseResize();
            }
          }
          $this.prop('disabled', false);

          // setTimeout(function() {
          //   slider[0].swiper.slideTo(slideID);
          // }, 300);
          // $this.find('.ut-loader').removeClass('loading');
        }
      });
    });
  },
  
  add_product_to_wishlist_postpone: function add_product_to_wishlist_postpone() {

    $('body').on('click', '.add_product_to_wishlist_postpone', function (event) {

      var $this = $(this);
      $this.prop('disabled', true);
      // $this.find('.ut-loader').addClass('loading');
      var product_id = $this.data('product-id');
      var wishlist_id = $this.data('wishlist-id');
      var wishlist_page = $('body').hasClass('page-template-template-favorites');

      if ( wishlist_id ) {
        var action = 'remove_product_from_wishlist_postpone';
      } else {
        var action = 'add_product_to_wishlist_postpone';
      }

      var data = {
        action: action,
        ajax_nonce: ut_wishlist.ajax_nonce,
        product_id: product_id,
        wishlist_id: wishlist_id,
        wishlist_page: wishlist_page,
      };

      $.ajax({
        type: 'POST',
        url: ut_wishlist.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $this.replaceWith(response.data.icon_html);

            if (wishlist_page) {

              if ( $('.favorites-block[data-wishlist-id="' + response.data.wishlist_id + '"]').length ) {
                $('.favorites-block[data-wishlist-id="' + response.data.wishlist_id + '"]').replaceWith(response.data.wishlist_html);
              } else {
                $('.form.form-favorites').append(response.data.wishlist_html);
              }
              cardsSliderInit();
              collapseResize();
            }
          }
          $this.prop('disabled', false);
          // $this.find('.ut-loader').removeClass('loading');

          $(document.body).trigger('wc_fragment_refresh');
          cartCollapse();
        }
      });
    });
  },

  copy_wishlist_link: function copy_wishlist_link() {

    $('body').on('click', '.copy_wishlist_link', function (event) {
      var $this = $(this);
      $this.prop('disabled', true);
      WISHLIST.copy_to_clipboard($this.data('url'));
      WISHLIST.copy_text_message($this);
    });
  }, 

  copy_to_clipboard: function copy_to_clipboard(url) {

    var sampleTextarea = document.createElement("textarea");
    document.body.appendChild(sampleTextarea);
    sampleTextarea.value = url; //save main text in it
    sampleTextarea.select(); //select textarea contenrs
    document.execCommand("copy");
    document.body.removeChild(sampleTextarea);
  },

  copy_text_message: function copy_text_message(elemnt) {

    var copy_txt = $(elemnt).data('copy-text');
    var desctop_txt = $(elemnt).find('.hidden-mobile').text();
    var mobile_txt = $(elemnt).find('.hidden-desktop').text();

    $(elemnt).find('.hidden-mobile').text(copy_txt);
    $(elemnt).find('.hidden-desktop').text(copy_txt);

    // const message = document.createElement('span');
    // message.className = 'user-info__id-copy-message';
    // message.textContent = text;
    // $('.copy_wishlist_link').append(message);

    setTimeout(() => {
      // $('.user-info__id-copy-message').remove();
      $(elemnt).find('.hidden-mobile').text(desctop_txt);
      $(elemnt).find('.hidden-desktop').text(mobile_txt);
      $(elemnt).prop('disabled', false);
    }, 2500);
  },

  rebuild_select: function rebuild_select() {
    $('.select-selected').remove();
    $('.select .select-hide').remove();
    customSelect(); // init custom select
  },

  replace_icon: function replace_icon(element, type) {

    if ( type == 'add' ) {
      $(element)
        .addClass('header__controls-filled')
        .find('svg').addClass('icon-filled')
        .find('use').attr('xlink:href', ut_wishlist.icon_heart_filled_url);
    } else {
      $(element)
        .removeClass('header__controls-filled added')
        .find('svg').removeClass('icon-filled')
        .find('use').attr('xlink:href', ut_wishlist.icon_heart_url);
    }
  }

};

$(document).ready(WISHLIST.init());