'use strict';

let COMMENT = {

  	init: function init() {
	    
	    COMMENT.add_data_to_form();
	    COMMENT.insert_comment();
	    COMMENT.ajax_pagination();
  	},

  	add_data_to_form: function add_data_to_form() {

        $('body').on('click', '.comment-reply a, .open-comment-form-js', function( event ) {
            let comment_id = $(this).data('commentid');
            $('#comment-form form input[name="comment_id"]').val( comment_id );
        });
  	},

    insert_comment: function insert_comment() {

        $('#comment-form form').submit( function( e ) {
            e.preventDefault();
            var response_el = $('.form__notice.response');
            $('#comment-form .ut-loader').addClass('loading');
			var data = {
				action : 'insert_comment',
				ajax_nonce : ut_comment.ajax_nonce,
				form : $('#comment-form form').serialize(),
			};

			$.ajax({
				type : 'POST',
				url  : ut_comment.ajax_url,
				data : data,
				success: function( response ) {

                    response_el.hide();

					if ( response.success == false && response.data.message ) { 
		                response_el.html( response.data.message ).show();
		            } else {
						$('#comment-form form textarea').val('');
                        $('#comment-form form input[name="comment_id"]').val('');
						COMMENT.update_comments();
						Fancybox.getInstance().close();
					}
                    $('#comment-form .ut-loader').removeClass('loading');
				}
			});
        });
    },
    
	ajax_pagination: function ajax_pagination() {

        $('body').on('click', '.commentPagination a.page-numbers', function( event ) {
            event.preventDefault();
			$('.description .tabs-panel__content .ut-loader').addClass('loading');
            let url = $(this).attr('href');
			let page = COMMENT.get_url_parameter( url, 'cpage' );
			let product_id = $('button[name="add-to-cart"]').val();
			var data = {
				action : 'ajax_pagination',
				ajax_nonce : ut_comment.ajax_nonce,
				page : page,
				product_id : product_id,
			}; 

			$.ajax({
				type : 'POST',
				url  : ut_comment.ajax_url,
				data : data,
				success: function( response ) {

					if ( response.success ) { 
		                $('.comments__body').html( response.data.comments_html );
    					$('.description .testimonials__comments .catalog-footer').html( response.data.pagination_html );
		            }
                    $('.description .tabs-panel__content .ut-loader').removeClass('loading');
					COMMENT.scroll_to_element('#reviews', 1000);
				}
			});
        });
    },

	update_comments: function update_comments() {

		$('.description .tabs-panel__content .ut-loader').addClass('loading');
		let page = ( $('span.page-numbers.current').text() ) ?? 1;
		let product_id = $('button[name="add-to-cart"]').val();
		var data = {
			action : 'ajax_pagination',
			ajax_nonce : ut_comment.ajax_nonce,
			page : page,
			product_id : product_id,
		}; 

		$.ajax({
			type : 'POST',
			url  : ut_comment.ajax_url,
			data : data,
			success: function( response ) {

				if ( response.success ) { 
					$('.comments__body').html( response.data.comments_html );
					$('.description .testimonials__comments .catalog-footer').html( response.data.pagination_html );
				}
				$('.description .tabs-panel__content .ut-loader').removeClass('loading');
				COMMENT.scroll_to_element('#reviews', 1000);
			}
		});
    },

	get_url_parameter: function get_url_parameter(sPageURL, sParam) {

		var sURLVariables = sPageURL.split(/\?|&/),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
	
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}

		return false;
	},

	scroll_to_element: function scroll_to_element(selector, time) {
		$([document.documentElement, document.body]).animate({
			scrollTop: $(selector).offset().top
		}, time);
	},

};

$(document).ready( COMMENT.init() ); 