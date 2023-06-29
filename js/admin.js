jQuery(function($) {

    // let certificates = $("#acf_certificates").parent().parent();
    // $("#acf_certificates").parent().appendTo("#ut_certificates .options_group");
    // certificates.remove();
    
    // let composition = $("#acf_composition").parent().parent();
    // $("#acf_composition").parent().appendTo("#ut_composition .options_group");
    // composition.remove();

    $('#linked_product_data .options_group .form-field.hide_if_grouped.hide_if_external').remove();
    $('.linked_product_options.linked_product_tab a span').text('Состав комплекса');
    $('#linked_product_data .options_group label[for="upsell_ids"]').text('Выбор комплекса товаров');

    if ( $('.confirm-user-data-js').length ) {
        $(document).on('click', '.confirm-user-data-js', function(e){

            var $this = $(this);
            var loader = $this.parent().find('.ut-loader');
            loader.addClass('loading');
            let data = {
				action : 'confirm_user_data',
				ajax_nonce : ut_admin.ajax_nonce,
				id : $this.data('user-id'),
                email : $this.data('user-email'),
                phone : $this.data('user-phone'),
			};
			$.ajax({
				data : data,
				url  : ut_admin.ajax_url,
				type : 'POST',
				success: function( response ) {

                    if ( response.success ) {
                        $this.parents('tr').find('td:eq( 4 )').text('1');
                        $this.remove();
                    }
                    loader.removeClass('loading');
                }
			});

        });
    }

    $('body').on('click', '#sf_all', function (event) {
        var checked = $(this).prop('checked');

        if (checked) {
            $('input[name="email[]"]').prop('checked', true);
            $('.delete-emails-js').prop('disabled', false);
        } else {
            $('input[name="email[]"]').prop('checked', false);
            $('.delete-emails-js').prop('disabled', true);
        }
    });

    $('body').on('click', '.email-items', function (event) {
        var result = true;
        $('.email-items').each( function() {
            var checked = $(this).prop('checked');  
            if (checked) {
                result = false;
                return false;
            } 
        });
        $('.delete-emails-js').prop('disabled', result);
    });

    $('#submitted_form').submit( function (event) {
        event.preventDefault();
        $('.delete-emails-js').prop('disabled', true);
        var data = {
            action : 'delete_submitted_emails',
            ajax_nonce : ut_admin.ajax_nonce,
            form : $(this).serialize(),
        };
        $.ajax({
            type : 'POST',
            url  : ut_admin.ajax_url,
            data : data,
            success: function(response) {
                if ( response.success ) {
                    location.reload();
                }
            }
        });
    });

    /* Submitted form data */
    const wpContent = document.getElementById('wpcontent');
    const showContent = document.querySelectorAll('#submitted_form_data .table-item');

    showContent && showContent.forEach(function(show) {
        let cH = show.clientHeight;
        let sH = show.scrollHeight;
        if (sH > cH) show.classList.add('modal');
    });

    document.addEventListener('click', function(e) {
       if (e.target.className === 'table-item modal') {
           const modal = document.createElement('div');
           const modalClose = document.createElement('span');
           const modalOverlay = document.createElement('div');

           modal.className = 'sfd-popup';
           modalClose.className = 'sfd-close';
           modalOverlay.className = 'sfd-overlay';
           modal.append(modalClose);
           modal.append(e.target.textContent);

           wpContent.append(modalOverlay);
           modalOverlay.append(modal);
       }

       if (e.target.className === 'sfd-overlay') {
           e.target.remove();
       }

       if (e.target.className === 'sfd-close') {
           e.target.parentElement.parentElement.remove();
       }
    });

    $(document).on('change', '#file_avatar', function (e) {
        e.stopPropagation();
        e.preventDefault();
  
        var input = this;
        var data = new FormData;
  
        if (input.files && input.files[0]) {
  
            $('.profile-content__account-photo .ut-loader').addClass('loading');
  
            if (input.files[0].type != 'image/jpeg' && input.files[0].type != 'image/png' && input.files[0].type != 'image/gif') {
                $('.error-avatar').text(ut_admin.error_type).show();
                $('.profile-content__account-photo .ut-loader').removeClass('loading');
                return false;
            }
  
            if (input.files[0].size > 2097152) {
                    $('.error-avatar').text(ut_admin.error_size).show();
                    $('.profile-content__account-photo .ut-loader').removeClass('loading');
                    return false;
            }
  
            data.append('file_avatar', input.files[0]);
            data.append('action', 'admin_update_avatar');
            data.append('ajax_nonce', ut_admin.ajax_nonce);
            data.append('user_id', $('#user_id').val());
  
            $.ajax({
                url: ut_admin.ajax_url,
                data: data,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (response) {
                    $('.error-avatar').hide();
    
                    if (response.success) {
                        // var reader = new FileReader();
                        // reader.onload = function (e) {
                        //     $('.image-absolute').attr('src', e.target.result);
                        // }
                        // reader.readAsDataURL( input.files[0] );
                        $('.profile-content__account-photo-inner').html(response.data.avatar_html);
                    }
                    $('.profile-content__account-photo .ut-loader').removeClass('loading');
                }
            });
        }
    });

    $(document).on('click', '.profile-content__account-photo-delete', function (e) {

        e.preventDefault();
        $('.profile-content__account-photo .ut-loader').addClass('loading');
  
        var data = {
            action: 'admin_remove_avatar',
            ajax_nonce: ut_admin.ajax_nonce,
            user_id: $('#user_id').val(),
        };
  
        $.ajax({
            url: ut_admin.ajax_url,
            data: data,
            type: 'POST',
            success: function (response) {
                $('.error-avatar').hide();
  
                if (response.success) {
                    // $('.image-absolute').attr('src', ut_admin.theme_uri + '/img/user-avatar.png');
                    $('.profile-content__account-photo-inner').html(response.data.avatar_html);
                }
                $('.profile-content__account-photo .ut-loader').removeClass('loading');
            }
        });
    });

    $('.mask-js').inputmask({"mask": "+38(999)999-9999"});
    $('.emask-js').inputmask("email");

    $(document).on('click', '.add-additional-phone-js', function () {
        var html = '<div class="profile-content__extrafields-list__item">' +
                        '<div class="profile-content__extrafields-list__item-input">' +
                            '<input type="text" class="mask-js" name="additional_phones[]" value="">' +
                        '</div>' +
                        '<div class="profile-content__extrafields-list__item-delete remove-additional-phone-js">' +
                            '<svg width="24" height="24"><use xlink:href="' + ut_admin.dist_uri + '/images/sprite/svg-sprite.svg#account-input-delete"></use></svg>' +
                        '</div>' +
                    '</div>';
        $('.additional-phones-js').append(html);
        $('.mask-js').inputmask({"mask": "+38(999)999-9999"});
    });
    
    $(document).on('click', '.remove-additional-phone-js', function () {
        var $this = $(this);
        $this.parents('.profile-content__extrafields-list__item').remove();
    });
    
    $(document).on('click', '.add-additional-email-js', function () {
        var html = '<div class="profile-content__extrafields-list__item">' +
                        '<div class="profile-content__extrafields-list__item-input">' +
                            '<input type="text" class="emask-js" name="additional_emails[]" value="">' +
                        '</div>' +
                        '<div class="profile-content__extrafields-list__item-delete remove-additional-email-js">' +
                            '<svg width="24" height="24"><use xlink:href="' + ut_admin.dist_uri + '/images/sprite/svg-sprite.svg#account-input-delete"></use></svg>' +
                        '</div>' +
                    '</div>';
        $('.additional-emails-js').append(html);
        $('.emask-js').inputmask("email");
    });
    
    $(document).on('click', '.remove-additional-email-js', function () {
        var $this = $(this);
        $this.parents('.profile-content__extrafields-list__item').remove();
    });

});