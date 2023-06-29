'use strict';

let ADDRESS = {

  	init: function init() {
	    
	    ADDRESS.remove_address();
	    ADDRESS.add_address();
	    ADDRESS.prepare_edit_address();
	    ADDRESS.set_default_address();
	    ADDRESS.filter_address();
	    ADDRESS.select_city();
	    ADDRESS.select_main_city();
	    ADDRESS.close_city_modal();
	    ADDRESS.get_cities_by_shipping_method();
        ADDRESS.change_shipping_method();
        ADDRESS.set_fields_default_address();
        
        $(document).on( 'click', '.form-checkout__tabs-item--icon', function( e ) {

            $('body').trigger('update_checkout');
            // if shipping type courier then select free shipping
            if ( $(this).data('value') == 'delivery' ) { 
                $('#delivery').show();
                
                if ( $('body').hasClass('checkout') ) {
                    $("#shipping_method_0_ut_free_shipping17").prop('checked', true);
                } else {
                    $("#17").prop('checked', true);
                }
            // if shipping type pickup then select nova poshta shipping
            } else {
                $('.form-checkout__row .ut-loader').addClass('loading');

                if ( $('body').hasClass('checkout') ) {
                    $("#shipping_method_0_nova_poshta_shipping_method12").prop('checked', true);
                } else {
                    $("#12").prop('checked', true);
                }

                if ( $('body').hasClass('woocommerce-checkout') ) {
                    var current_shipping_method = $("#shipping_method_0_nova_poshta_shipping_method12").val();
                } else {
                    var current_shipping_method = $("#12").val();
                }
    
                var shipping_method = ADDRESS.prepare_value_shipping_method( current_shipping_method );
                ADDRESS.show_select_warehouse( shipping_method );
                ADDRESS.set_shipping_method( shipping_method );
                $('.form-checkout__row .ut-loader').removeClass('loading');
            }
        });

        setTimeout(function() { 
            ADDRESS.edit_address();
			$('#billing_nova_poshta_city').prop('disabled', false);
			$('#billing_nova_poshta_warehouse').prop('disabled', false);
			$('#ukrposhta_shippping_city').prop('disabled', false);
			$('#ukrposhta_shippping_warehouse').prop('disabled', false);
			$('#billing_mrkvnp_patronymics').prop('disabled', false);
			$('#billing_mrkvnp_street').prop('disabled', false);
			$('#billing_mrkvnp_house').prop('disabled', false);
			$('#billing_mrkvnp_flat').prop('disabled', false);
		}, 1000);

        if ( $('#saved_addresses').length == 0 && $('body').hasClass('checkout') ) {  
            $("#shipping_method_0_nova_poshta_shipping_method12").prop('checked', true);
            $('#city_shipping_method').val('nova_poshta_warehouses'); 
        }
  	}, 

    phone_mask: function phone_mask() { 
        $('.mask-js').inputmask({"mask": "+38(999) 999-9999"});
    },

    prepare_edit_address: function prepare_edit_address() {

        $(document).on( 'click', '.js-edit-address', function( e ) {
            e.preventDefault();

            $('#nova_poshta_warehouse').hide();
            $('#nova_poshta_poshtomat').hide();
            $('#ukr_warehouses').hide();

            var element = $(this).parents('.profile-places__item.address');
            var id = element.data('id');
            var type = element.data('type');
            var city = element.data('city');
            var code_city = element.data('code-city');
            var street = element.data('street');
            var house = element.data('house');
            var flat = element.data('flat');
            var method = element.data('method');
            var first_name = element.data('first_name');
            var last_name = element.data('last_name');
            var middle_name = element.data('middle_name');
            var email = element.data('email');
            var phone = element.data('phone');
            var np_city_code = element.data('np-city-code');
            // var justin_city_code = element.data('justin-city-code');
            var ukr_city_code = element.data('ukr-city-code');
            var warehouse_code = element.data('warehouse-code');
            
            $('#add_address_form #main_warehouse_code').val(warehouse_code);
            $('#add_address_form #nova_poshta_city_code').val(np_city_code);
            // $('#add_address_form #justin_city_code').val(justin_city_code);
            $('#add_address_form #ukr_city_code').val(ukr_city_code);
            $('#add_address_form #address_id').val( id );
            $('#add_address_form #type').val( type );
            $('#type').val( type );
            $('#add_address_form #billing_city').val( city );
            $('.shipping_method').prop('disabled', false);
            $('button.form-checkout__city.btn.w-100').data('city', city).text(city);
            $('#city').val(city);
            $('#add_address_form #address_city_code').val( code_city );
            $('.popup .location-list__item-link').removeClass('active');
            $('.popup .location-list__item').each( function( index, el ) {
                var elem = $(el);
                var city_item = elem.find('span').text();
    
                if ( city_item == city ) {
                    elem.find('span').addClass('active');
                }
            });
            $('#add_address_form #billing_address_1').val( street );
            $('#add_address_form #billing_address_2').val( house );
            $('#add_address_form #billing_address_3').val( flat );
            $('#add_address_form #billing_first_name').val( first_name );
            $('#add_address_form #billing_last_name').val( last_name );
            $('#add_address_form #patronymic').val( middle_name );
            $('#add_address_form #billing_email').val( email );
            $('#add_address_form #billing_phone').val( phone );
            $('#add_address_form input[name=shipping_method][value="' + method + '"]').prop('checked', true);

            var shipping_method = $('#add_address_form input[name=shipping_method][value="' + method + '"]').data('value');
                shipping_method = ADDRESS.prepare_value_shipping_method( shipping_method );
            $('#city_shipping_method').val(shipping_method);

            $('.form-checkout__tabs-item').removeClass('active');
            $('.form-checkout__tabs-panel').removeClass('active');
            $('.form-checkout__tabs-item[data-value="'+ type +'"]').addClass('active');
            $('#'+ type +'.form-checkout__tabs-panel').addClass('active');

			ADDRESS.addresses_fields_mapping( element, 'change', shipping_method, warehouse_code );
            
            $('.upd-title').show();
            $('.upd-btn').show();
            $('.add-title').hide();
            $('.add-btn').hide();
        });
  	},
    
    remove_address: function remove_address() {

        $(document).on( 'click', '.js-delete-address', function( e ) {
            e.preventDefault();
            var address_id = $(this).data('id');
            var type = $(this).data('type');

            var data = {
				action : 'remove_address',
				ajax_nonce : ut_address.ajax_nonce,
				id : address_id,
				type : type,
			};

			$.ajax({
				type : 'POST',
				url  : ut_address.ajax_url,
				data : data,
				success: function( response ) {
                    location.reload();
                    $('.profile-places__item.address[data-id="'+ address_id +'"]').remove();
				}
			});
        });
  	},

  	add_address: function add_address() { 

        $(document).on( 'click', '.js-add-address', function( e ) {

            ADDRESS.phone_mask();
            $('#nova_poshta_warehouse').hide();
            $('#nova_poshta_poshtomat').hide();
            $('#ukr_warehouses').hide();

            $('#add_address_form #address_id').val('');
            $('.upd-title').hide();
            $('.upd-btn').hide();
            $('.add-title').show();
            $('.add-btn').show();
            //
            $('#add_address_form #main_warehouse_code').val('');
            $('#add_address_form #nova_poshta_city_code').val('');
            // $('#add_address_form #justin_city_code').val('');
            $('#add_address_form #ukr_city_code').val('');
            $('#billing_address_1').val('');
            $('#billing_address_2').val('');
            $('#billing_address_3').val('');
            $('#billing_first_name').val('');
            $('#billing_last_name').val('');
            $('#patronymic').val('');
            $('#billing_email').val('');
            $('#billing_phone').val('');
            $('#billing_city').val('');
            $('#address_city_code').val('');
            $('.popup .location-list__item-link').removeClass('active');
            $('button.form-checkout__city.btn.w-100').data('city', ut_address.choose_txt).text(ut_address.choose_txt);
            $('.form-checkout__tabs-item').removeClass('active');
            $('.form-checkout__tabs-panel').removeClass('active');
            $('.form-checkout__tabs-item[data-value="pickup"]').addClass('active');
            $('#pickup.form-checkout__tabs-panel').addClass('active');
            // set shipping method
            var curr_shipping_method_el = $('.shipping_method:checked');
            var current_shipping_method = curr_shipping_method_el.data('value');    
            var shipping_method = ADDRESS.prepare_value_shipping_method( current_shipping_method );
            ADDRESS.set_shipping_method( shipping_method );
        });

        $(document).on( 'click', '#add_address_form .form-checkout__tabs-item', function( e ) {
            $('#type').val( $(this).data('value') );
        });
        
        $(document).on( 'click', 'form[name="checkout"] .form-checkout__tabs-item', function( e ) {
            $('#type').val( $(this).data('value') );
        });

        $(document).on( 'submit', '#add_address_form', function( e ) { 
            e.preventDefault();
			$('#add-address .ut-loader').addClass('loading');
			var data = {
				action : 'add_address',
				ajax_nonce : ut_address.ajax_nonce,
				form : $('#add_address_form').serialize(),
			};

			$.ajax({
				type : 'POST',
				url  : ut_address.ajax_url,
				data : data,
				success: function( response ) {
					$('#add-address .form__row.response span span').html('');
                    $('#add-address .form__row.response .form__alert').removeClass('error');
                    $('#add-address .form__row.response').hide();

					if ( response.success == false ) {

						if ( response.data.message ) {
							$('#add-address .form__row.response span span').html( response.data.message );
		                	$('#add-address .form__row.response .form__alert').addClass('error');
		                	$('#add-address .form__row.response').show();
						}
					} else {
                        Fancybox.getInstance().close();
                        location.reload();
						// $('#billing_address_1').val('');
                        // $('#billing_address_2').val('');
                        // $('#billing_address_3').val('');
                        // $('#billing_first_name').val('');
                        // $('#billing_last_name').val('');
                        // $('#patronymic').val('');
                        // $('#billing_email').val('');
                        // $('#billing_phone').val('');

                        // $('.profile-row.' + response.data.type + ' .profile-places').html( response.data.addresses_html );

                        // setTimeout(function() { 
                        //     $('#add-address .form__row.response span span').html('');
		                // 	$('#add-address .form__row.response .form__alert').removeClass('error');
		                // 	$('#add-address .form__row.response').hide();
                        //     Fancybox.getInstance().close();
                        // }, 1000);
					}
					$('#add-address .ut-loader').removeClass('loading');
				}
			});
        });
  	},

    set_default_address: function set_default_address() {

        $(document).on( 'click', '.default', function( e ) {
            var name = $(this).attr('name');
            var type = $(this).parents('.profile-places__item.address').data('type');
            var address_id = $(this).parents('.profile-places__item.address').data('id');
            
            var data = {
				action : 'set_default_address',
				ajax_nonce : ut_address.ajax_nonce,
				id : address_id,
				type : type,
				name : name,
			};

			$.ajax({
				type : 'POST',
				url  : ut_address.ajax_url,
				data : data,
				success: function( response ) {
                    
				}
			});
        });
    },

    filter_address: function filter_address() {

        $('#filter_city').change( function ( e ) {
            var current_city_name = $(this).val();
            var current_type = $('#filter_type').val();
            $('.profile-places__item.address').each( function( index, el ) {
                var city_name = $(el).data('city');
                var type = $(el).data('type');

                if ( current_type !== '' ) {   
                    if ( current_city_name == city_name && current_type == type ) {
                        $(el).show();
                    } else {
                        $(el).hide();
                    }
                } else {    
                    if ( current_city_name == city_name ) {
                        $(el).show();
                    } else {
                        $(el).hide();
                    }
                }
            });

        });

        $('#filter_type').change( function ( e ) { 
            var current_type = $(this).val();
            var current_city_name = $('#filter_city').val();
            $('.profile-places__item.address').each( function( index, el ) {
                var type = $(el).data('type');
                var city_name = $(el).data('city');

                if ( current_city_name !== '' ) {
                    if ( current_type == type && current_city_name == city_name ) {
                        $(el).show();
                    } else {
                        $(el).hide();
                    }
                } else {
                    if ( current_type == type ) {
                        $(el).show();
                    } else {
                        $(el).hide();
                    }
                }

                if ( current_type == 'all' ) {
                    $(el).show();
                }
            });

        });
    },

    select_city: function select_city() {
        // select city of search autocomplete result
        $(document).on( 'click', '#cities-popup .location-cities li', function( e ) {

            var city_code = $(this).data('city-code');
            var shipping_method = $('#city_shipping_method').val();

            if ( shipping_method == 'ukr_warehouses' ) {
                var city = $(this).data('city');
            } else {
                var city = $(this).text(); 
            }

            $('.popup .location-list__item-link.active').removeClass('active');
            $('#cities-popup .location-cities').empty();
            // set city for fields
            $('button.form-checkout__city.btn.w-100').data('city', city).text(city);
            $('#billing_nova_poshta_city').val(city);
            $('#ukrposhta_shippping_city').val(city);
            $('#billing_city').val(city);
            $('.shipping_method').prop('disabled', false);
            ADDRESS.validate_time_delivery();
            $('#city').val(city);
            $('#city_code').val(city_code);
            $('#address_city_code').val(city_code);
            $('.popup .location-list__item-link').removeClass('active');
            $('.popup .location-list__item').each( function( index, el ) {
                var elem = $(el);
                var city_item = elem.find('span').text();
    
                if ( city_item == city ) {
                    elem.find('span').addClass('active');
                }
            });
            // filter select saved addresses
            var city = $('#city').val();
            $('#saved_addresses option').prop('disabled', false);
            $('#saved_addresses option').each( function( index, el ) {
                if ( $(el).data('city') != city ) {
                    $(el).prop('disabled', true);
                }
            });
            ADDRESS.rebuild_select();
            ADDRESS.update_city_codes(city, city_code, shipping_method);
            ADDRESS.remove_validate_city();
            // setTimeout(function() { 
            //     ADDRESS.get_warehouses_for_all_shipping_methods();
            // }, 2000);
            // $('body').trigger('update_checkout');
        });
    },

    select_main_city: function select_main_city() {
        // select main city 
        $(document).on( 'click', '.popup .location-list__item', function( e ) {
            var city = $(this).find('span').text();
            var nova_poshta_city_code = $(this).data('np-code');
            var shipping_method = $('#city_shipping_method').val(); 
            // var justin_city_code = $(this).data('justin');
            var ukr_city_code = $(this).data('ukr');
            $('button.form-checkout__city.btn.w-100').data('city', city).text(city);
            $('#city').val($.trim(city));
            $('#billing_nova_poshta_city').val($.trim(city));
            $('#ukrposhta_shippping_city').val($.trim(city));
            $('#billing_city').val($.trim(city));
            $('.shipping_method').prop('disabled', false);
            ADDRESS.validate_time_delivery();
            // $('#address_city_code').val(city_code);
            $('.popup .location-list__item-link').removeClass('active');
            $(this).find('span').addClass('active');
            // set city code for all shipping methods
            $('#nova_poshta_city_code').val( nova_poshta_city_code );
            // $('#justin_city_code').val( justin_city_code );
            $('#ukr_city_code').val( ukr_city_code );
            // get warehouses by city
            $('#cities-popup .ut-loader').addClass('loading');  
            ADDRESS.get_warehouses_for_all_shipping_methods(shipping_method);
            // $('body').trigger('update_checkout');
            Fancybox.getInstance().close();
            ADDRESS.remove_validate_city();
        });
    },
    
    close_city_modal: function close_city_modal() {
        // applay selected city
        $(document).on( 'click', '.js-choose-city', function( e ) {
            // $('body').trigger('update_checkout');
            Fancybox.getInstance().close();
        });

    },

    // get seleted city with code for another shipping methods
    update_city_codes: function update_city_codes(city, city_code, shipping_method) {
        
        if ( shipping_method == 'nova_poshta_warehouses' || shipping_method == 'nova_poshta_poshtomats' ) {
            $('#nova_poshta_city_code').val(city_code);
            // ADDRESS.get_city_code_other_shipping_methods(city, 'justin_warehouses');
            ADDRESS.get_city_code_other_shipping_methods(city, 'ukr_warehouses');
        } /*else if ( shipping_method == 'justin_warehouses' ) {
            $('#justin_city_code').val(city_code);
            ADDRESS.get_city_code_other_shipping_methods(city, 'nova_poshta_warehouses');
            ADDRESS.get_city_code_other_shipping_methods(city, 'ukr_warehouses');
        }*/ else if ( shipping_method == 'ukr_warehouses' ) {
            $('#ukr_city_code').val(city_code);
            ADDRESS.get_city_code_other_shipping_methods(city, 'nova_poshta_warehouses');
            // ADDRESS.get_city_code_other_shipping_methods(city, 'justin_warehouses');
        }
    },

    get_city_code_other_shipping_methods: function get_city_code_other_shipping_methods(city, shipping_method) {

        $.ajax({
            dataType: 'json',
            url: autocomplete_search.ajax_url,
            data: {
                ajax_nonce: autocomplete_search.ajax_nonce,
                action: 'get_another_cities_code',
                city: city,
                shipping_method: shipping_method,
            },
            success: function( response ) {

                if ( response.success ) {
                    if (shipping_method == 'nova_poshta_warehouses' ) {
                        $('#nova_poshta_city_code').val(response.data.city_code);
                    } /*else if (shipping_method == 'justin_warehouses' ) {
                        $('#justin_city_code').val(response.data.city_code);
                    }*/ else if (shipping_method == 'ukr_warehouses' ) {
                        $('#ukr_city_code').val(response.data.city_code);
                    }
                    ADDRESS.get_warehouses_for_all_shipping_methods();
                }
            }
        });
    },

    // old method  use for single shipping method
    get_warehouse_shipping: function get_warehouse_shipping() {

        var city_code = $('#city_code').val();
        var shipping_method = $('#city_shipping_method').val();
        var data = {
            action : 'get_warehouse_shipping',
            ajax_nonce : ut_address.ajax_nonce,
            city_code : city_code,
            shipping_method : shipping_method,
        };

        $.ajax({
            type : 'POST',
            url  : ut_address.ajax_url,
            data : data,
            success: function( response ) {
                
                if ( response.success ) {

                    if ( response.data.options_html ) {

                        if ( shipping_method == 'nova_poshta_warehouses' ) {

                            $('#nova_poshta_warehouse').show();
                            $('#nova_poshta_warehouse select').html( response.data.options_html );
                            ADDRESS.rebuild_select();
                        
                        } else if ( shipping_method == 'nova_poshta_poshtomats' ) {

                            $('#nova_poshta_poshtomat').show();
                            $('#nova_poshta_poshtomat select').html( response.data.options_html );
                            ADDRESS.rebuild_select();
                        
                        } /*else if ( shipping_method == 'justin_warehouses' ) {

                            $('#justin_warehouse').show();
                            $('#justin_warehouse select').html( response.data.options_html );
                            ADDRESS.rebuild_select();
                        
                        }*/ else if ( shipping_method == 'ukr_warehouses' ) {

                            $('#ukr_warehouses').show();
                            $('#ukr_warehouses select').html( response.data.options_html );
                            ADDRESS.rebuild_select();
                        }
                    }
                }
            }
        });

    },

    get_warehouses_for_all_shipping_methods: function get_warehouses_for_all_shipping_methods(shipping_method = false, is_saved_addrr = false, edit_address_shipping_method = false, warehouse_code = false ) {

        $('.form-checkout__row .ut-loader').addClass('loading');
        var nova_poshta_city_code = $('#nova_poshta_city_code').val();
        // var justin_city_code = $('#justin_city_code').val();
        var ukr_city_code = $('#ukr_city_code').val();

        if ( ! shipping_method ) {
            var shipping_method = $('#city_shipping_method').val(); 
        }

        var data = {
            action : 'get_warehouses_for_all_shipping_methods',
            ajax_nonce : ut_address.ajax_nonce,
            nova_poshta_city_code : nova_poshta_city_code,
            // justin_city_code : justin_city_code,
            ukr_city_code : ukr_city_code,
        };

        $.ajax({
            type : 'POST',
            url  : ut_address.ajax_url,
            data : data,
            success: function( response ) {
                
                if ( ! response.success ) {
                    return;
                }

                if ( response.data.nova_poshta_warehouses_html ) {
                    $('#nova_poshta_warehouse').show();
                    $('#nova_poshta_warehouse select').html( response.data.nova_poshta_warehouses_html );
                } 
                
                if ( response.data.nova_poshta_poshtomats_html ) {
                    $('#nova_poshta_poshtomat').show();
                    $('#nova_poshta_poshtomat select').html( response.data.nova_poshta_poshtomats_html );
                }
                
                // if ( response.data.justin_warehouses_html ) {
                //     $('#justin_warehouse').show();
                //     $('#justin_warehouse select').html( response.data.justin_warehouses_html );
                // }
                
                if ( response.data.ukr_warehouses_html ) {
                    $('#ukr_warehouses').show();
                    $('#ukr_warehouses select').html( response.data.ukr_warehouses_html );
                }

                // set saved warehouse
                if ( is_saved_addrr ) {
                    var curr_address_el = $('#saved_addresses').find(":selected");
                
                    if ( edit_address_shipping_method ) {
                        var saved_shipping_method = edit_address_shipping_method;
                    } else {
                        var saved_shipping_method = curr_address_el.data('method');
                            saved_shipping_method = ADDRESS.prepare_value_shipping_method(saved_shipping_method);
                    }
               
                    if ( ! warehouse_code ) {
                        warehouse_code = curr_address_el.data('warehouse-code');
                    }
                    
                    if ( saved_shipping_method == 'nova_poshta_warehouses' ) {
                        $('select[name="nova_poshta_warehouse"] option[value="' + warehouse_code + '"]').prop('selected', true);
                        var selected_txt = $('select[name="nova_poshta_warehouse"] option:selected').text();
                        $('#billing_nova_poshta_warehouse').val(selected_txt);

                    } else if ( saved_shipping_method == 'nova_poshta_poshtomats' ) {
                        $('select[name="nova_poshta_poshtomat"] option[value="' + warehouse_code + '"]').prop('selected', true);
                        var selected_txt = $('select[name="nova_poshta_poshtomat"] option:selected').text();
                        $('#billing_nova_poshta_warehouse').val(selected_txt);

                    } else if ( saved_shipping_method == 'ukr_warehouses' ) {
                        $('select[name="ukr_warehouses"] option[value="' + warehouse_code + '"]').prop('selected', true);
                        var selected_txt = $('select[name="ukr_warehouses"] option:selected').text();
                        $('#ukrposhta_shippping_warehouse').val(selected_txt);
                    }

                    $('#main_warehouse_code').val(warehouse_code);
                    $('#warehouse').val(selected_txt);
                }

                ADDRESS.show_select_warehouse( shipping_method );
                ADDRESS.rebuild_select();
                $('#cities-popup .ut-loader').removeClass('loading');
                $('.form-checkout__row .ut-loader').removeClass('loading');
            }
        });
    },

    show_select_warehouse: function show_select_warehouse( shipping_method ) { 

        if ( shipping_method == '' ) {
            return false;
        }

        $('#nova_poshta_warehouse').hide();
        $('#nova_poshta_poshtomat').hide();
        $('#ukr_warehouses').hide();

        if ( shipping_method == 'nova_poshta_warehouses' ) {
            $('#nova_poshta_warehouse').show();
        } else if ( shipping_method == 'nova_poshta_poshtomats' ) {
            $('#nova_poshta_poshtomat').show();
        } /*else if ( shipping_method == 'justin_warehouses' ) {
            $('#justin_warehouse').show();
        }*/ else if ( shipping_method == 'ukr_warehouses' ) {   
            $('#ukr_warehouses').show();
        }
    },

    change_shipping_method: function change_shipping_method() {

		$(document).on('change', 'input.shipping_method', function( e ) {   
            $('.form-checkout__row .ut-loader').addClass('loading');
            $('#delivery').hide();

            // if ( $('body').hasClass('woocommerce-checkout') ) {
			    var current_shipping_method = $(this).val();
            // } else {
            //     var current_shipping_method = $(this).data('value');
            // }
            
			var shipping_method = ADDRESS.prepare_value_shipping_method( current_shipping_method );

            ADDRESS.show_select_warehouse( shipping_method );
			ADDRESS.set_shipping_method( shipping_method );
            // show address fields if nova poshta courier
            /*if ( 
                // $('body').hasClass('woocommerce-checkout') && 
                // (
                    current_shipping_method == 'npttn_address_shipping_method' || 
                    current_shipping_method == 'shipping_method_0_ukrposhta_shippping15' ||
                    current_shipping_method == 'ukrposhta_shippping:15' ||
                    current_shipping_method == 'ukrposhta_shippping'
                // ) 
            ) {
                $('#delivery').show();
            } */
            $('.form-checkout__row .ut-loader').removeClass('loading');
		});
        
	},

    prepare_value_shipping_method: function prepare_value_shipping_method( current_shipping_method ) { 

        var shipping_method = '';

        if ( current_shipping_method == 'nova_poshta_shipping_method' || current_shipping_method == 'nova_poshta_shipping_method:12' ) {
            shipping_method = 'nova_poshta_warehouses';
        } else if ( current_shipping_method == 'nova_poshta_shipping_method_poshtomat' || current_shipping_method == 'nova_poshta_shipping_method_poshtomat:13' ) {
            shipping_method = 'nova_poshta_poshtomats';
        } else if ( current_shipping_method == 'ukrposhta_shippping' || current_shipping_method == 'ukrposhta_shippping:15' ) {
            shipping_method = 'ukr_warehouses';
        }

        return shipping_method;
    },

    set_shipping_method: function set_shipping_method( shipping_method ) {

        $('#city_shipping_method').val( shipping_method );
        // clear selected city
        // $('.popup .location-list__item-link').removeClass('active');
        // $('button.form-checkout__city.btn.w-100').data('city', ut_address.choose_txt).text(ut_address.choose_txt);
        // $('#city').val('');
    },

    set_fields_default_address: function set_fields_default_address() {

		var curr_address_el = $('#saved_addresses').find(":selected");

		if ( curr_address_el.length ) {
			ADDRESS.addresses_fields_mapping( curr_address_el, 'load' );
		} else if ( ! curr_address_el.length && $('body').hasClass('woocommerce-checkout') ) {
            var curr_shipping_method_el = $('.shipping_method:checked');
            var current_shipping_method = curr_shipping_method_el.val();    
            var shipping_method = ADDRESS.prepare_value_shipping_method( current_shipping_method );
            ADDRESS.set_shipping_method( shipping_method );
        }

		$('#saved_addresses').change( function ( e ) {
			var curr_address_el = $('#saved_addresses option[value="'+ $(this).val() +'"]');
			ADDRESS.addresses_fields_mapping( curr_address_el, 'change' );
		});
		
	},

    addresses_fields_mapping: function addresses_fields_mapping( $option, type, edit_address_shipping_method = false, warehouse_code = false ) { 

		var city = $option.data('city');
		var city_code = $option.data('code-city');
		var type = $option.data('type');
		var street = $option.data('street');
		var house = $option.data('house');
		var flat = $option.data('flat');
		var method = $option.data('method');
		var first_name = $option.data('first_name');
		var last_name = $option.data('last_name');
		var middle_name = $option.data('middle_name');
		var email = $option.data('email');
		var phone = $option.data('phone');
        var np_city_code = $option.data('np-city-code');
        // var justin_city_code = $option.data('justin-city-code');
        var ukr_city_code = $option.data('ukr-city-code');
        // set inputs value
        $('#nova_poshta_city_code').val(np_city_code);
        // $('#justin_city_code').val(justin_city_code);
        $('#ukr_city_code').val(ukr_city_code);
		$('#billing_address_1').val(street);
		$('#billing_address_2').val(house);
		$('#billing_address_3').val(flat);
		$('#billing_first_name').val(first_name);
		$('#billing_last_name').val(last_name);
		$('#patronymic').val(middle_name);
		$('#billing_email').val(email);
		$('#billing_phone').val(phone);
		$('input[value="' + method + '"].form-checkout__radio-input.shipping_method').prop('checked', true);
		// set city
		$('button.form-checkout__city.btn.w-100').data('city', city).text(city);
		$('#billing_nova_poshta_city').val(city);
		$('#ukrposhta_shippping_city').val(city);
		$('#billing_city').val(city);
        $('.shipping_method').prop('disabled', false);
        ADDRESS.validate_time_delivery();
		$('#city').val(city);
		$('#city_code').val( city_code );
		$('.popup .location-list__item-link').removeClass('active');
		$('.popup .location-list__item').each( function( index, el ) {
			var elem = $(el);
			var city_item = elem.find('span').text();

			if ( city_item == city ) {
				elem.find('span').addClass('active');
			}
		});
		// set active tab
		$('.form-checkout__tabs-item').removeClass('active');
		$('.form-checkout__tabs-panel').removeClass('active');
		$('.form-checkout__tabs-item[data-value="'+ type +'"]').addClass('active');
		$('#'+ type +'.form-checkout__tabs-panel').addClass('active');
		// shipping Nova Poshta
		$('#billing_mrkvnp_street').val(street);
		$('#billing_mrkvnp_house').val(house);
		$('#billing_mrkvnp_flat').val(flat);
		$('#billing_mrkvnp_patronymics').val(middle_name); 
        var shipping_method = $('input[value="' + method + '"].form-checkout__radio-input.shipping_method').val();
            shipping_method = ADDRESS.prepare_value_shipping_method( shipping_method );
        $('#city_shipping_method').val(shipping_method); 
		// get shipping warehouses
        if ( type == 'change' ) {
            ADDRESS.update_city_codes(city, city_code, shipping_method);
        }
        setTimeout(function() { 
            ADDRESS.get_warehouses_for_all_shipping_methods(shipping_method, true, edit_address_shipping_method, warehouse_code);
            // ADDRESS.show_select_warehouse( shipping_method );
        }, 2000);
        // $('body').trigger('update_checkout');
	},

    edit_address: function edit_address() {

		$(document).on('keyup', '#billing_address_1', function() {
			$('#billing_mrkvnp_street').val( $(this).val() );
		});

		$(document).on('keyup', '#billing_address_2', function() {
			$('#billing_mrkvnp_house').val( $(this).val() );
		});
		
		$(document).on('keyup', '#billing_address_3', function() {
			$('#billing_mrkvnp_flat').val( $(this).val() );
		});
		
		$(document).on('keyup', '#patronymic', function() {
			$('#billing_mrkvnp_patronymics').val( $(this).val() );
		});

		$('select[name="nova_poshta_warehouse"]').change( function ( e ) {
			var val = $(this).val();
            var option = $('select[name="nova_poshta_warehouse"] option[value="' + val + '"]');
			var warehouse_code = option.val();
			var warehouse = option.text();
			$('#main_warehouse_code').val( warehouse_code );
			$('#billing_nova_poshta_warehouse').val( warehouse );
			$('#warehouse').val( warehouse );
		});
		
		$('select[name="nova_poshta_poshtomat"]').change( function ( e ) {
			var val = $(this).val();
            var option = $('select[name="nova_poshta_poshtomat"] option[value="' + val + '"]');
			var warehouse_code = option.val();
			var warehouse = option.text();
			$('#main_warehouse_code').val( warehouse_code );
			$('#billing_nova_poshta_warehouse').val( warehouse );
			$('#warehouse').val( warehouse );
		});
		
        $('select[name="ukr_warehouses"]').change( function ( e ) {
			var val = $(this).val();
            var option = $('select[name="ukr_warehouses"] option[value="' + val + '"]');
			var warehouse_code = option.val();
			var warehouse = option.text();
            $('#main_warehouse_code').val( warehouse_code );
			$('#ukrposhta_shippping_warehouse').val( warehouse );
			$('#warehouse').val( warehouse );
		});

	},

    get_cities_by_shipping_method: function get_cities_by_shipping_method() { 

        setTimeout( function() {

            $('#city').autocomplete({

                source: function( request, response ) {

                    // var $this = $(this.element);
                    $('#cities-popup .ut-loader').addClass('loading');
                    $.ajax({
                        dataType: 'json',
                        url: autocomplete_search.ajax_url,
                        data: {
                            ajax_nonce: autocomplete_search.ajax_nonce,
                            action: 'autocomplete_cities',
                            search_txt: request.term,
                            shipping_method: $('#city_shipping_method').val(),
                        },
                        success: function( response ) {

                            if ( response.data.products != '' ) {
                                $('#cities-popup .location-cities').html( response.data.cities_html );
                            } 
                            $('#cities-popup .ut-loader').removeClass('loading');
                        }
                    });
                },
                minLength: 0,
            });

        },2000);
        
    },

    rebuild_select: function rebuild_select() {
		$('.select-selected').remove();
		$('.select .select-hide').remove();
		customSelect(); // init custom select
	},

    remove_validate_city: function remove_validate_city() {
        $('.form-checkout__city').removeClass('invalid');
        $('.form-checkout__city').parent().find('label').removeClass('invalid');
        $('.form-checkout__city').parent().find('.error-field').remove();
    },

    validate_time_delivery: function validate_time_delivery() {

        if ( $('#billing_city').val() == 'Киев' || $('#billing_city').val() == 'Київ' ) {
          $('.valid-td-section').show();
        } else {
          $('.valid-td-section').hide();
        }
    
    }

};

$(document).ready( ADDRESS.init() ); 