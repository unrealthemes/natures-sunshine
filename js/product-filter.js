'use strict';

let PRODUCT_FILTER = {

  init: function init() {

    PRODUCT_FILTER.ajax_filter_form();
    PRODUCT_FILTER.ajax_pagination();

    $(document).on('click', '#filter_form input[type=checkbox]', function () {
      PRODUCT_FILTER.set_filter_type('filter');
      PRODUCT_FILTER.submit_form();
    });

    $('#sort').change(function () {
      PRODUCT_FILTER.set_filter_type('filter');
      PRODUCT_FILTER.submit_form();
    });

    $(document).on('click', '.js-load-more', function () {
      PRODUCT_FILTER.set_filter_type('loadmore');
      PRODUCT_FILTER.submit_form();
    });

    $(document).on('click', '.js-clear-filter', function () {
      PRODUCT_FILTER.set_filter_type('filter');
      PRODUCT_FILTER.reset_form();
      PRODUCT_FILTER.submit_form();
    });

    $(document).on('click', '.js-change-filter svg', function () {
      let slug = $(this).parent().data('slug');
      $(this).parent().remove();
      $('#filter_form input[type=checkbox]#' + slug).prop('checked', false);
      PRODUCT_FILTER.set_filter_type('filter');
      PRODUCT_FILTER.submit_form();
    });

    $(document).on('click', '.js-filter-show', function (event) {
      event.preventDefault();
      $(this).addClass('hide-show-more');
      $(this).parent().find('.filter__control .filter__input').removeClass('hide-option-show-more');
    });

    PRODUCT_FILTER.hide_pagination_section();

    if ( $('.catalog-filter.js-filter').css('display') != 'none' ) {
      $('#filter_form').submit();
    }
  },

  ajax_filter_form: function ajax_filter_form() {

    $(document).on('submit', '#filter_form', function (event) {
      event.preventDefault();
      $('.catalog-cards .ut-loader').addClass('loading');
      $('.product-pagination .ut-loader').addClass('loading');
      // update type filter (filter, loadmore, pagination)
      if ($('#filter_type').val() === 'filter') {
        $('#paged').val(1);
      } else if ($('#filter_type').val() == 'loadmore') {
        let paged = $('#paged').val();
        $('#paged').val(Math.max(parseInt(paged) + 1, 0));
      }

      var data = {
        action: 'filter',
        ajax_nonce: ut_filter.ajax_nonce,
        form: $(this).serialize(),
      };

      $.ajax({
        type: 'POST',
        url: ut_filter.ajax_url,
        data: data,
        success: function (response) {
          // update filter options & update product list html
          if (response.success) {
            let filter_type = $('#filter_type').val();
            let count_post = parseInt(response.data.count_posts); // parseInt($('.cards__item').length);
            let found_posts = parseInt(response.data.found_posts);
            // update info section
            if (filter_type == 'filter') {
              $('.catalog-info').replaceWith(response.data.info_html);
            }
            // update products for filter and load more button
            if (filter_type == 'filter' || filter_type == 'pagination') {
              $('.catalog-cards').html(response.data.products_html);
              cardHeightResize();
            } else if (filter_type == 'loadmore') {
              $('.catalog-cards').append(response.data.products_html);
              PRODUCT_FILTER.sort_product_cards();
              cardHeightResize();
            }
            $('.catalog-cards').append('<div class="ut-loader"></div>');
            // update pagination
            $('.js-pagination').html(response.data.pagination_html);
            // show/hide load more button
            if (count_post == found_posts) {
              $('.js-load-more').hide();
            } else {
              $('.js-load-more').show();
            }

            if (response.data.url) {
              history.pushState(null, null, response.data.url);
            }
            PRODUCT_FILTER.update_filter_options(response);
          }

          PRODUCT_FILTER.hide_pagination_section();

          $('.catalog-cards .ut-loader').removeClass('loading');
          $('.product-pagination .ut-loader').removeClass('loading');
        }
      });
    });
  },

  sort_product_cards: function sort_product_cards() {

      if ( $('.cards__item--complex').length ) {
        var cards_arr = [];
        $('.cards__item--complex').each(function (index, el) {
          cards_arr.push( $(el) );
          $(this).remove();
        });
        $.each( cards_arr, function(index, el) {
          $('.catalog-cards').append( el );
        });
      }
  },

  update_filter_options: function update_filter_options(response, reset = false) {

    // product type
    if ( ! response.data.product_types.pack /*&& $('.filter__input[data-id="pack"] input').is(':checked') == false*/) { 
      $('.filter__input[data-id="pack"]').find('input').prop('disabled', true);
      $('.filter__input[data-id="pack"]').addClass('disabled');
    } else {
      $('.filter__input[data-id="pack"]').find('input').prop('disabled', false);
      $('.filter__input[data-id="pack"]').removeClass('disabled');
    }

    if ( ! response.data.product_types.single /*&& $('.filter__input[data-id="single"] input').is(':checked') == false*/) {
      $('.filter__input[data-id="single"]').find('input').prop('disabled', true);
      $('.filter__input[data-id="single"]').addClass('disabled');
    } else {
      $('.filter__input[data-id="single"]').find('input').prop('disabled', false);
      $('.filter__input[data-id="single"]').removeClass('disabled');
    }

    // health topics
    if (response.data.health_topics_ids) {
      let health_topics_ids = Array.of(...(Object.entries(response.data.health_topics_ids)).map(item => item[1])); // object to array
      $('.filter__input[data-tax="health-topics"]').each(function (index, el) {

        if ($.inArray($(el).data('id'), health_topics_ids) == -1 && $(el).find('input').is(':checked') == false) {
          $(el).find('input').prop('disabled', true);
          $(el).addClass('disabled');
        } else {
          $(el).find('input').prop('disabled', false);
          $(el).removeClass('disabled');
        }

      });
    } else {
      $('.filter__input[data-tax="health-topics"]').find('input').prop('disabled', true);
      $('.filter__input[data-tax="health-topics"]').addClass('disabled');
    }

    // main components
    if (response.data.main_components_ids) {
      let main_components_ids = Array.of(...(Object.entries(response.data.main_components_ids)).map(item => item[1])); // object to array
      $('.filter__input[data-tax="main-components"]').each(function (index, el) {

        if ($.inArray($(el).data('id'), main_components_ids) == -1 && $(el).find('input').is(':checked') == false) {
          $(el).find('input').prop('disabled', true);
          $(el).addClass('disabled');
        } else {
          $(el).find('input').prop('disabled', false);
          $(el).removeClass('disabled');
        }

      });
    } else {
      $('.filter__input[data-tax="main-components"]').find('input').prop('disabled', true);
      $('.filter__input[data-tax="main-components"]').addClass('disabled');
    }

    // categories
    if (response.data.category_ids) {
      let category_ids = Array.of(...(Object.entries(response.data.category_ids)).map(item => item[1])); // object to array
      $('.filter__input[data-tax="product_cat"]').each(function (index, el) {

        if ($.inArray($(el).data('id'), category_ids) == -1 && $(el).find('input').is(':checked') == false) {
          $(el).find('input').prop('disabled', true);
          $(el).addClass('disabled');
        } else {
          $(el).find('input').prop('disabled', false);
          $(el).removeClass('disabled');
        }

      });
    } else {
      $('.filter__input[data-tax="product_cat"]').find('input').prop('disabled', true);
      $('.filter__input[data-tax="product_cat"]').addClass('disabled');
    }

    // cat_product
    if (response.data.cat_product_ids) {
      let cat_product_ids = Array.of(...(Object.entries(response.data.cat_product_ids)).map(item => item[1])); // object to array
      $('.filter__input[data-tax="cat_product"]').each(function (index, el) {

        if ($.inArray($(el).data('id'), cat_product_ids) == -1 && $(el).find('input').is(':checked') == false) {
          $(el).find('input').prop('disabled', true);
          $(el).addClass('disabled');
        } else {
          $(el).find('input').prop('disabled', false);
          $(el).removeClass('disabled');
        }

      });
    } else {
      $('.filter__input[data-tax="cat_product"]').find('input').prop('disabled', true);
      $('.filter__input[data-tax="cat_product"]').addClass('disabled');
    }

    // body systems
    if (response.data.body_systems_ids) {
      let body_systems_ids = Array.of(...(Object.entries(response.data.body_systems_ids)).map(item => item[1])); // object to array
      $('.filter__input[data-tax="body-systems"]').each(function (index, el) {

        if ($.inArray($(el).data('id'), body_systems_ids) == -1 && $(el).find('input').is(':checked') == false) {
          $(el).find('input').prop('disabled', true);
          $(el).addClass('disabled');
        } else {
          $(el).find('input').prop('disabled', false);
          $(el).removeClass('disabled');
        }

      });
    } else {
      $('.filter__input[data-tax="body-systems"]').find('input').prop('disabled', true);
      $('.filter__input[data-tax="body-systems"]').addClass('disabled');
    }

    $('.js-filter-show').addClass('hide-show-more');
    PRODUCT_FILTER.update_filter_options_show_more('health-topics');
    PRODUCT_FILTER.update_filter_options_show_more('main-components');
    PRODUCT_FILTER.update_filter_options_show_more('product_cat');
    PRODUCT_FILTER.update_filter_options_show_more('cat_product');
    PRODUCT_FILTER.update_filter_options_show_more('body-systems');
  },

  update_filter_options_show_more: function update_filter_options_show_more(tax_name) {
    var count_show = 5;
    var show_btn = false;
    $('.filter__input[data-tax="' + tax_name + '"]').not(".hide-option").each(function (index, el) {

      if ((index > count_show - 1)) {
        $(el).addClass('hide-option-show-more');
        show_btn = true;
      } else {
        $(el).removeClass('hide-option-show-more');
      }

      if (show_btn) {
        let text_btn_with_num = $('.' + tax_name + ' .js-filter-show').text();
        let text_btn_without_numbers = text_btn_with_num.replace(/\d+/g, '');
        let text_buton = text_btn_without_numbers + ' ' + $('.filter__input.hide-option-show-more[data-tax="' + tax_name + '"]').not(".hide-option").length;
        $('.' + tax_name + ' .js-filter-show')
          .removeClass('hide-show-more')
          .text(text_buton);
      }
    });

  },

  hide_pagination_section: function hide_pagination_section() {
    if ( $('.js-load-more').css('display') == 'none' && $('.js-pagination').is(':empty') ) {
      $('.product-pagination').hide();
    } else {
      $('.product-pagination').show();
    }
  },

  reset_form: function reset_form() {
    // $('#filter_form')[0].reset();
    $('#filter_form input[type=checkbox]').prop('checked', false);
    $('#sort').prop('selectedIndex', 0);
    PRODUCT_FILTER.rebuild_select();
  },

  submit_form: function submit_form() {
    $('#filter_form').submit();
  },

  set_filter_type: function set_filter_type(type) {
    $('#filter_type').val(type);
  },

  ajax_pagination: function ajax_pagination() {

    $(document).on('click', '.catalog-content a.page-numbers', function () {
      event.preventDefault();
      let url = $(this).attr('href');
      // get current page of url
      let pathname = url.split("/").filter(entry => entry !== "");
      let lastPath = pathname[pathname.length - 1];
      let lastPathOfStrings = lastPath.split('-');
      let page_num = (lastPathOfStrings[0] == 'p') ? lastPathOfStrings[1] : 1;

      $('#paged').val(page_num);
      PRODUCT_FILTER.set_filter_type('pagination');
      PRODUCT_FILTER.submit_form();
    });
  },

  rebuild_select: function rebuild_select() {
    $('.select-selected').remove();
    $('.select .select-hide').remove();
    customSelect(); // init custom select
  },

};

$(document).ready(PRODUCT_FILTER.init());
