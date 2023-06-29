'use strict';

let BLOG = {

  init: function init() {

        BLOG.ajax_blog_form();
        BLOG.ajax_pagination();

        $(document).on( 'click', '.js-load-more', function() {
            BLOG.set_filter_type('loadmore');
            BLOG.submit_form();
        });

  	}, 

    ajax_blog_form: function ajax_blog_form() {

        $(document).on( 'submit', '#blog_form', function( event ) {
            event.preventDefault();
            $('#blog_form > .ut-loader').addClass('loading');

            // update type filter (filter, loadmore, pagination)
            if ( $('#filter_type').val() == 'loadmore' ) {
                let paged = $('#paged').val();
                $('#paged').val( Math.max( parseInt(paged) + 1, 0 ) );
            } 

			var data = {
				action : 'load_more',
				ajax_nonce : ut_blog.ajax_nonce,
				form : $(this).serialize(),
			};

			$.ajax({
				type : 'POST',
				url  : ut_blog.ajax_url,
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
                            $('.blog-section__feed.news').html( response.data.products_html );
                        } else if ( filter_type == 'loadmore' ) {
                            $('.blog-section__feed.news').append( response.data.products_html );
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

					$('#blog_form .ut-loader').removeClass('loading');
				}
			});
        });
  	},

    submit_form: function submit_form() {
        $('#blog_form').submit();
    },

    set_filter_type: function set_filter_type( type ) {
        $('#filter_type').val( type );
    },

    ajax_pagination: function ajax_pagination() {

        $(document).on( 'click', '.blog-pagination a.page-numbers', function(event) {
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
            BLOG.set_filter_type('pagination');
            BLOG.submit_form();
        });
    },

};

$(document).ready( BLOG.init() );
