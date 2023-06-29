'use strict';

let COMPARE = {

  init: function init() {

    COMPARE.remove_all_compare();
    COMPARE.remove_from_compare();
    COMPARE.add_product_to_compare();
    COMPARE.copy_compare_link();

  },

  remove_all_compare: function remove_all_compare() {

    $('body').on('click', '.remove_all_compare', function (event) {

      event.preventDefault();
      $('.compare.page > .ut-loader').addClass('loading');

      var data = {
        action: 'remove_all_compare',
        ajax_nonce: ut_compare.ajax_nonce,
      };

      $.ajax({
        type: 'POST',
        url: ut_compare.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $('.header__controls-item.compare').replaceWith(response.data.bar_html);
            $('.compare-header').replaceWith(response.data.header_html);
            $('.compare-sticky').replaceWith(response.data.sticky_html);
            $('.compare-content').replaceWith(response.data.content_html);
            $('.cards__item.swiper-slide').each(function (index, value) {
              $(this).find('.card__controls-btn').prop('disabled', false).css('color', '#202020');
            });
            collapseResize();
            cardsSliderInit();
          }

          $('.compare.page .ut-loader').removeClass('loading');
        }
      });
    });
  },

  remove_from_compare: function remove_from_compare() {

    $('body').on('click', '.remove_from_compare', function (event) {

      event.preventDefault();
      var product_id = $(this).parents('.cards__item').data('id');
      $('.compare.page > .ut-loader').addClass('loading');
      $('.cards__item.swiper-slide.post-' + product_id + ' .add_product_to_compare .ut-loader').prop('disabled', true).addClass('loading');

      var data = {
        action: 'remove_product_from_compare',
        ajax_nonce: ut_compare.ajax_nonce,
        product_id: product_id,
      };

      $.ajax({
        type: 'POST',
        url: ut_compare.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $('.header__controls-item.compare').replaceWith(response.data.bar_html);
            $('.compare-header').replaceWith(response.data.header_html);
            $('.compare-sticky').replaceWith(response.data.sticky_html);
            $('.compare-content').replaceWith(response.data.content_html);
            $('.cards__item.swiper-slide.post-' + response.data.product_id + ' .add_product_to_compare').replaceWith(response.data.icon_html);
            collapseResize();
            cardsSliderInit();
          }

          $('.compare.page .ut-loader').removeClass('loading');
        }
      });
    });
  },

  add_product_to_compare: function add_product_to_compare() {

    $('body').on('click', '.add_product_to_compare', function (event) {

      var $this = $(this);
      $this.prop('disabled', true);
      // $this.find('.ut-loader').addClass('loading');
      COMPARE.replace_icon($this);
      $('.added_compare').show().fadeOut(5000);
      var product_id = $this.data('product-id');
      var compare_page = $('body').hasClass('page-template-template-compare');
      var data = {
        action: 'add_product_to_compare',
        ajax_nonce: ut_compare.ajax_nonce,
        product_id: product_id,
        compare_page: compare_page,
      };  

      $.ajax({
        type: 'POST',
        url: ut_compare.ajax_url,
        data: data,
        success: function (response) {

          if (response.success) {
            $('.header__controls-item.compare').replaceWith(response.data.bar_html);
            $this.replaceWith(response.data.icon_html);

            if (compare_page) {
              $('.compare-header').replaceWith(response.data.header_html);
              $('.compare-sticky').replaceWith(response.data.sticky_html);
              $('.compare-content').replaceWith(response.data.content_html);
            }
            collapseResize(); 
            cardsSliderInit();
          }
          $this.prop('disabled', false);
          // $this.find('.ut-loader').removeClass('loading');
        }
      });
    });
  },

  copy_compare_link: function copy_compare_link() {

    $('body').on('click', '.copy_compare_link', function (event) {
      var $this = $(this);
      $this.prop('disabled', true);
      COMPARE.copy_to_clipboard($this.data('url'));
      COMPARE.copy_text_message($this);
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

    var i = false;
    var copy_txt = $(elemnt).data('copy-text');
    var txt = $(elemnt).find('.btn__text').text();

    $(elemnt).find('.btn__text').text(copy_txt);

    if ( $(elemnt).find('.btn__text').css('display') == 'none' ) {
      $(elemnt).find('.btn__text').show();
      i = true;
    }

    // const message = document.createElement('span');
    // message.className = 'user-info__id-copy-message';
    // message.textContent = text;
    // $('.copy_compare_link').append(message);

    setTimeout(() => {
      // $('.user-info__id-copy-message').remove();
      $(elemnt).find('.btn__text').text(txt);
      $(elemnt).prop('disabled', false);

      if ( i ) {
        $(elemnt).find('.btn__text').hide();
      }
    }, 2500);
  },

  replace_icon: function replace_icon(element) {

    var type = ( $(element).hasClass('added') ) ? 'remove' : 'add';

    if ( type == 'add' ) {
      $(element)
        .addClass('added')
        .css({'color': '#00a88f'});

      $(element).replaceWith('<a href="' + ut_compare.compare_url + '" class="' + $(element).attr('class') + '" style="color:#00a88f;"><svg width="24" height="24">' + $(element).children().html() + '</svg></a>');

      console.log( $(element).attr('class') );
      console.log( $(element).children().html() );
      console.log( ut_compare.compare_url );

    } else {
      $(element)
        .removeClass('added')
        .css({'color': '#202020'});
    }
  }

};

$(document).ready(COMPARE.init());