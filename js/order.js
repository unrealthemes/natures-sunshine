'use strict';

let ORDER = {

  	init: function init() {
	    
		ORDER.filter();
		ORDER.repeat_order();

  	},

	filter: function filter() { 

        $('input[name="date"]').change( function() { 
            ORDER.submit_form();
		});
        
        $('#filter-type').change( function() { 
            ORDER.submit_form();
		});
        
        $('#filter-status').change( function() { 
            ORDER.submit_form();
		});
    },

    submit_form: function submit_form() {
        $('#order_form').submit();
    },

    repeat_order: function repeat_order() {

        $(document).on('click', '.repeat-order-js', function (event) {
            var $this = $(this);
            $this.parents('.orders-result').find('.ut-loader').addClass('loading');
            var order_id = $(this).data('id');
            var data = {
                action: 'repeat_order',
                ajax_nonce: ut_order.ajax_nonce,
                order_id: order_id,
            };  
            $.ajax({
                type: 'POST',
                url: ut_order.ajax_url,
                data: data,
                success: function (response) {
                    $this.parents('.orders-result').find('.ut-loader').removeClass('loading');
                    window.location.href = response.data.redirect_url; 
                }
            });
        });
    },

};

$(document).ready( ORDER.init() ); 