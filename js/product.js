'use strict';

let PRODUCT = {

  	init: function init() {
	    
		PRODUCT.quantity_update();
		PRODUCT.add_to_cart();
		PRODUCT.change_btn_after_add_to_cart();
		PRODUCT.back_in_stock_notifier();
        PRODUCT.ajax_product_form();
        PRODUCT.ajax_pagination();

        $(document).on( 'click', '.js-load-more', function() {
            PRODUCT.set_filter_type('loadmore');
            PRODUCT.submit_form();
        });
        
        $(document).on( 'click', '.ajax_add_to_cart', function() {
            $(this).removeAttr('href');
        });
  	},

    set_filter_type: function set_filter_type( type ) {
        $('#filter_type').val( type );
    },

    submit_form: function submit_form() {
        $('#product_form').submit();
    },

    ajax_product_form: function ajax_product_form() {

        $(document).on( 'submit', '#product_form', function( event ) {
            event.preventDefault();
            $('#product_form > .ut-loader').addClass('loading');

            // update type filter (filter, loadmore, pagination)
            if ( $('#filter_type').val() == 'loadmore' ) {
                let paged = $('#paged').val();
                $('#paged').val( Math.max( parseInt(paged) + 1, 0 ) );
            } 

			var data = {
				action : 'product_load_more',
				ajax_nonce : ut_product.ajax_nonce,
				form : $(this).serialize(),
			};

			$.ajax({
				type : 'POST',
				url  : ut_product.ajax_url,
				data : data,
				success: function(response) {
                    // console.log( response );
                    // update load_more options & update product list html
                    if ( response.success ) {
                        let filter_type = $('#filter_type').val(); 
                        let count_post = parseInt(response.data.count_posts); // parseInt($('.cards__item').length);
                        let found_posts = parseInt(response.data.found_posts);
                        // update products for filter and load more button
                        if ( filter_type == 'pagination' ) {
                            $('.catalog-cards').html( response.data.products_html );
                        } else if ( filter_type == 'loadmore' ) {   
                            $('.catalog-cards').append( response.data.products_html );
                        }
                        // update pagination 
                        $('.js-pagination').html( response.data.pagination_html );
                        // show/hide load more button
                        if ( count_post == found_posts ) {
                            $('.js-load-more').hide();
                        } else {
                            $('.js-load-more').show();
                        }

						if ( response.data.url ) {
							history.pushState(null, null, response.data.url);
						}
                    }

					$('#product_form .ut-loader').removeClass('loading');
				}
			});
        });
  	},

    ajax_pagination: function ajax_pagination() {

        $(document).on( 'click', '.product-pagination a.page-numbers', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            // get current page of url
            var pathname = url.split("/").filter(entry => entry !== "");
            var lastPath = pathname[pathname.length - 1];

            if ( isNaN(lastPath) ) {
                var page_num = 1;
            }  else {
                var page_num = lastPath;
            }

            $('#paged').val( page_num );	
            PRODUCT.set_filter_type('pagination');
            PRODUCT.submit_form();
        });
    },

    quantity_update: function quantity_update() {

        $(document).on('click', '.product-info .counter__btn', function() {
            
			var val = $(this).parent().find('input[name="qty"]').val();
			
			if ( $(this).hasClass('counter-plus') ) {
				var result_val = Math.max(parseInt(val) + 1, 0);
			} else if ( $(this).hasClass('counter-minus') ) {
				var result_val = Math.max(parseInt(val) - 1, 0);
			}

			if ( !result_val ) {
				result_val = 1;
			}
			
			$(this).parent().find('input[name="qty"]').val( result_val );
        });
    },

    change_btn_after_add_to_cart: function change_btn_after_add_to_cart() {

        $(document.body).on('added_to_cart', function( e, fragments, cart_hash, $button ) {

            var link_html = '<a href="' + wc_add_to_cart_params.cart_url + '" class="btn btn-transparent card__controls-buy">' +
                                '<svg class="btn__icon" width="24" height="24">' +
                                '<use xlink:href="' + ut_product.dist_uri + '/images/sprite/svg-sprite.svg#check"></use>' +
                                '</svg>' +
                                '<span class="btn__text">' + ut_product.cart_txt + '</span>' +
                            '</a>';

            // console.log('add btn 1');
            
            if ( $button.parent().find('.btn.btn-transparent.card__controls-buy').length ) {
                return false;
            }

            if ( $button.hasClass('loop-btn') ) {   // console.log('loop');
                $button.parent().prepend(link_html);
            } else {    // console.log('single');
                $('form .card__controls-counter').after(link_html);
            }
        });
    },

  	add_to_cart: function add_to_cart() { 

        $('#carts').on('change', function(e) { 
            // e.preventDefault();
            var $thisbutton = $('button[name="add-to-cart"]'),
                id = $thisbutton.val(),
                product_qty = $('#select_cart input[name=quantity]').val() || 1,
                product_id = $('#select_cart input[name=product_id]').val() || id,
                variation_id = $('#select_cart input[name=variation_id]').val() || 0;

            var data = {
                action: 'ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
                cart_partner_id: $(this).val(),
            };
    
            $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
    
            $.ajax({
                type: 'post',
                url: wc_add_to_cart_params.ajax_url,
                data: data,
                beforeSend: function (response) {
                    $thisbutton.removeClass('added').addClass('loading').prop( "disabled", true );
                },
                complete: function (response) {
                    $thisbutton.addClass('added').removeClass('loading').prop( "disabled", false );
                },
                success: function (response) {
    
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {

                        if ( 'loop' == $('#select_cart input[name=type]').val() ) {
                            var $button = $('button[value="' + product_id + '"]');
                            var link_html = '<a href="' + wc_add_to_cart_params.cart_url + '" class="btn btn-transparent card__controls-buy">' +
                                                '<svg class="btn__icon" width="24" height="24">' +
                                                '<use xlink:href="' + ut_product.dist_uri + '/images/sprite/svg-sprite.svg#check"></use>' +
                                                '</svg>' +
                                                '<span class="btn__text">' + ut_product.cart_txt + '</span>' +
                                            '</a>';
                            // console.log('add btn 2');

                            if ( ! $button.parent().find('.btn.btn-transparent.card__controls-buy').length ) {
                                $button.parent().prepend(link_html);
                                $button.hide();
                            }
                            
                        } else {
                            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                        }
                    }
                },
            });

            Fancybox.getInstance().close();
    
            // return false;
        });

        $(document).on('click', 'button[data-src="#select_cart"]', function (e) {
            $('#select_cart input[name="product_id"]').val( $(this).val() );
            $('#select_cart input[name="quantity"]').val( $(this).data('quantity') );
            $('#select_cart input[name="variation_id"]').val( $(this).data('variation_id') );

            if ( $(this).hasClass('loop-btn') ) {
                $('#select_cart input[name="type"]').val('loop');
            } else {
                $('#select_cart input[name="quantity"]').val( $('input[name="qty"]').val() );
            }
        });

        $(document).on('click', '.single_add_to_cart_button.not-modal', function (e) {
            e.preventDefault();
    
            var $thisbutton = $(this),
                $form = $thisbutton.closest('form.cart'),
                id = $thisbutton.val(),
                product_qty = $form.find('input[name=qty]').val() || 1,
                product_id = $form.find('input[name=product_id]').val() || id,
                variation_id = $form.find('input[name=variation_id]').val() || 0;
    
            var data = {
                action: 'ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
            };
    
            $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
    
            $.ajax({
                type: 'post',
                url: wc_add_to_cart_params.ajax_url,
                data: data,
                beforeSend: function (response) {
                    $thisbutton.removeClass('added').addClass('loading').prop( "disabled", true );
                },
                complete: function (response) {
                    $thisbutton.addClass('added').removeClass('loading').prop( "disabled", false );
                },
                success: function (response) {
    
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    }
                },
            });
    
            return false;
        });
  	},

    back_in_stock_notifier: function back_in_stock_notifier() { 

        $('#back_in_stock_notifier').submit( function( e ) { 
            e.preventDefault();
            $('#back_in_stock_notifier .ut-loader').addClass('loading');
			var data = {
				action : 'back_in_stock_notifier',
				ajax_nonce : ut_product.ajax_nonce,
				form : $('#back_in_stock_notifier').serialize(),
			};

			$.ajax({
				type : 'POST',
				url  : ut_product.ajax_url,
				data : data,
				success: function( response ) {

					if ( response.success ) { 
                        $('#note_email').removeClass('invalid');
                        $('#note_email').val('');
                        alert("Your email was success saved");
					} else {
                        $('#note_email').addClass('invalid');
                    }
                    $('#back_in_stock_notifier .ut-loader').removeClass('loading');
				}
			});
        });
    }

};

$(document).ready( PRODUCT.init() ); 