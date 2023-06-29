'use strict';

let FRONT_PAGE = {

  	init: function init() {

		FRONT_PAGE.categories_tabs_with_slider();

        if ( $(".form__row.verrify_user_email").length ) {
            $(".form__row.verrify_user_email").fadeOut(5000);
        }
  	},

  	categories_tabs_with_slider: function categories_tabs_with_slider() { 

        $(document).on('click', '.products-cats__item', function (e) {
            e.preventDefault();
            $('.js-products-tabs .ut-loader').addClass('loading');
            var $this = $(this);
            var taxonomy = $this.data('taxonomy');
            var term_id = $this.data('id');
            // var count = $this.data('count');

            // if ( ! $this.hasClass('loaded') ) {
            //     var data = {
            //         action: 'categories_tabs_with_slider',
            //         ajax_nonce: ut_front_page.ajax_nonce,
            //         taxonomy: taxonomy,
            //         term_id: term_id,
            //         count: count,
            //     };

            //     $.ajax({
            //         type : 'POST',
            //         url: ut_front_page.ajax_url,
            //         data: data,
            //         success: function (response) {
        
            //             if (response.success) {
            //                 $('.js-products-tabs .products-slider').removeClass('active');
            //                 $this.parents('.js-products-tabs').find('.products-slider[data-taxonomy="'+ taxonomy +'"][data-id="'+ term_id +'"]').addClass('active');
            //                 $('.products-cats__item').removeClass('active');
            //                 $this.addClass('active');
            //                 $this.addClass('loaded');
            //                 $this.parents('.js-products-tabs')
            //                      .find('.products-slider[data-id="'+ term_id +'"] .swiper-wrapper')
            //                      .html( response.data.slider_html );
            //             }
            //             $('.js-products-tabs .ut-loader').removeClass('loading');
            //         },
            //     });
            // } else {
                $('.js-products-tabs .products-slider').removeClass('active');
                $this.parents('.js-products-tabs').find('.products-slider[data-taxonomy="'+ taxonomy +'"][data-id="'+ term_id +'"]').addClass('active');
                $('.products-cats__item').removeClass('active');
                $this.addClass('active');
                $('.js-products-tabs .ut-loader').removeClass('loading');
            // }
            cardHeightResize();
        });
  	},

};

$(document).ready( FRONT_PAGE.init() ); 