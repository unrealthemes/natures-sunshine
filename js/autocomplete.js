jQuery(function ($) {

  $('#s').autocomplete({

    source: function (request, response) {

      $('.header__search .ut-loader').addClass('loading');
      $.ajax({
        dataType: 'json',
        url: autocomplete_search.ajax_url,
        data: {
          ajax_nonce: autocomplete_search.ajax_nonce,
          action: 'autocomplete_search',
          search_txt: request.term,
        },
        success: function (response) {

          if (response.data.suggestions != '') {

            var items = '';
            $.each(response.data.suggestions, function (key, product) {
              items +=
                '<li class="cart-products__search-item">'
                + '<a href="' + product.link + '" class="product-preview__image" title="' + product.name + '">'
                  + '<img src="' + product.image + '" loading="lazy" decoding="async" alt="">'
                + '</a>'
                + '<div class="product-preview__info">'
                  + '<span class="product-preview__available">' + product.eng_name + '</span>'
                  + '<a href="' + product.link + '" class="product-preview__title text" title="' + product.name + '">' + product.name + '</a>'
                + '</div>'
                + '<div class="product-preview__button">'
                  + '<!--<button class="product-preview__action btn btn-secondary">Добавить</button>-->'
                  + '<span class="product-preview__price card__price-current">' + product.price + '</span>'
                + '</div>'
              + '</li>';
            });

            $('.header__search .result_wrapper').show();
            $('.header__search .result_wrapper').html(items);
          } else {
            $('.header__search .result_wrapper').html(
              '<h2 class="text_not_found">' + response.data.text_not_found + '</h2>'
            );
          }

          $('.header__search .ut-loader').removeClass('loading');
        }
      });
    },
    minLength: 0,
  });

  $('.search__reset').on('click', function () {
    $('.header__search .result_wrapper').hide();
  });

  $('#s').keyup(function () {
    if ($(this).val() == '') {
      $('.header__search .result_wrapper').hide();
    }
  });

  $(document).on('scroll', function () {
    if ($('.header').hasClass('header--unsticky')) {
      $('.header__search .result_wrapper').hide();
      $('.header__search-overlay').removeClass('header__search-overlay--visible');
      $('.header__search .search__input').removeClass('filled').val('').blur();
      $('.header__search .search__submit').removeClass('hidden');
      $('.header__search .search__reset').addClass('hidden');
    }
  });

});