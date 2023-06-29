'use strict';

let USER = {

  	init: function init() {
	    
		USER.authentication();
		USER.registration();
		USER.registration_step_2();
		USER.registration_step_3();
		USER.password_recovery();
		USER.new_password();
		USER.change_esputnik_category_switch_lang();
		USER.phone_mask();
		USER.promo_banner();
  	},

	promo_banner: function promo_banner() {

		if ( $.cookie('promo') ) {
			$('.promo-wrapper').hide();
		}

		$('.promo-close').on('click', function() {
			$.cookie('promo', true, {
				expires: 365,
				path: '/'
			});
			$('.promo-wrapper').hide();
		});
	},

	phone_mask: function phone_mask() {
		$('.mask-js').inputmask({"mask": "+38(999)999-9999"});
	},

	registration_step_2: function registration_step_2() {

		$(document).on( 'click', '.js-step-2', function() {
			$('#step').val( 2 );
			$('#user_register_form').submit();
		});
    },
	 
	registration_step_3: function registration_step_3() {

		$(document).on( 'click', '.js-step-3', function() {
			$('#step').val( 3 );
			$('#user_register_form').submit();
		});
    },

	registration: function registration() {

		$('#user_register_form').submit( function( e ) {
	    	e.preventDefault();
			var step = $('#step').val();
			 
			$('.login-block .ut-loader').addClass('loading');
			let data = {
				action : 'user_registration_step_' + step,
				ajax_nonce : ut_user.ajax_nonce,
				form : $(this).serialize(),
			};

			$.ajax({
				data : data,
				url  : ut_user.ajax_url,
				type : 'POST',
				success: function( response ) {
					$('.step_'+ step +'.response span span').html('');
					$('.step_'+ step +'.response .form__alert').removeClass('error');
					$('.step_'+ step +'.response').hide();

					if ( response.success == false ) {

						if ( response.data.message ) {
							$('.step_'+ step +'.response span span').html( response.data.message );
							$('.step_'+ step +'.response .form__alert').addClass('error');
							$('.step_'+ step +'.response').show();
						}
					} else {

						if ( step == 2 ) {
							// next step 3
							$('.form__step').removeClass('active');
							$('.form__step.step-3').addClass('active');
							$('#register_email').val( response.data.user_email );
						} else if ( step == 3 ) {
							document.location.href = response.data.redirect_url;
						}
					}
					$('.login-block .ut-loader').removeClass('loading');
				}
			});

	    });

	},

	authentication: function authentication() {

    	$('#auth_form').submit( function( e ) {
	    	e.preventDefault();
            $('.login-block .ut-loader').addClass('loading');
		    let data = {
		        action : 'user_auth',
		        ajax_nonce : ut_user.ajax_nonce,
		        form : $(this).serialize(),
		    };

		    $.ajax({
		        url  : ut_user.ajax_url,
		        data : data,
		        type : 'POST',
		        success: function( response ) {
                    $('#auth_error').hide();

		        	if ( response.success == false ) {

		                if ( response.data.message ) {
                            // without tag <a href=""></a>
		                	let message = response.data.message.replace(/<a\b[^>]*>(.*?)<\/a>/i,""); 
		                	$('#auth_error span span').html( message );
                            $('#auth_error').show();
		                }
                        $('.login-block .ut-loader').removeClass('loading');
		            } else {
		                document.location.href = response.data.redirect_url;
		            }
		        }
		    });
	    });
	},

	password_recovery: function password_recovery() {

    	$('#forgot_pass_form').submit( function( e ) {
	    	e.preventDefault();
            $('.login-block .ut-loader').addClass('loading');
		    let data = {
		        action : 'reset_password',
		        ajax_nonce : ut_user.ajax_nonce,
		        form : $(this).serialize(),
		    };

		    $.ajax({
		        url  : ut_user.ajax_url,
		        data : data,
		        type : 'POST',
		        success: function( response ) {
					$('#forgot_pass_error').hide();

		        	if ( response.success == false ) {

		                if ( response.data.message ) {
		                	$('#forgot_pass_error span span').html( response.data.message );
                            $('#forgot_pass_error').show();
		                }
		            } else {
                        $('.reset-password').hide();
                        $('.success-reset-password').show();
		            }
                    $('.login-block .ut-loader').removeClass('loading');
		        }
		    });
	    });
	},

	new_password: function new_password() {

    	$('#new_password_form').submit( function( e ) {
	    	e.preventDefault();
            $('.login-block .ut-loader').addClass('loading');
		    let data = {
		        action     : 'new_password',
		        ajax_nonce : ut_user.ajax_nonce,
		        form       : $(this).serialize(),
		    };

		    $.ajax({
		        url  : ut_user.ajax_url,
		        data : data,
		        type : 'POST',
		        success: function( response ) {
					$('#new_pass_error').hide();
					$('#new_pass_success').hide();

		        	if ( response.success == false ) {

		                if ( response.data.message ) {
		                	$('#new_pass_error span span').html( response.data.message );
                            $('#new_pass_error').show();
		                }
		            } else {
                        $('#new_password').val('');
                        $('#repeat_password').val('');
                        $('#new_pass_success span span').html( response.data.message );
                        $('#new_pass_success').show();
		            }
                    $('.login-block .ut-loader').removeClass('loading');
		        }
		    });
	    });
	},

	change_esputnik_category_switch_lang: function change_esputnik_category_switch_lang() {

        $(document).on('click', '.langs__item', function(e) {	

			if ( $('body').hasClass('logged-in') ) {
				e.preventDefault();
				var data = {
					action : 'change_esputnik_category_switch_lang',
					ajax_nonce : ut_user.ajax_nonce,
					url : $(this).attr('href'),
					current_lang : $(this).data('code'),
				};
				$.ajax({
					url  : ut_user.ajax_url,
					data : data,
					type : 'POST',
					success: function( response ) {
						if ( response.success ) {
							document.location.href = response.data.redirect_url;
						}
					}
				});
			}
        });
    },

};

$(document).ready( USER.init() ); 