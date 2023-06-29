<?php

class UT_Account {

    private static $_instance = null;

    private $esputnik_category_keys;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        $this->esputnik_category_keys = [
            'news_ru' => get_field('key_news_ru_esputnik', 'options'),
            'news_uk' => get_field('key_news_uk_esputnik', 'options'),
            'stock_ru' => get_field('key_stock_ru_esputnik', 'options'),
            'stock_uk' => get_field('key_stock_uk_esputnik', 'options'),
        ];
        
        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_update_avatar', [$this, 'update_avatar'] );
            add_action( 'wp_ajax_nopriv_update_avatar', [$this, 'update_avatar'] );
            
            add_action( 'wp_ajax_remove_avatar', [$this, 'remove_avatar'] );
            add_action( 'wp_ajax_nopriv_remove_avatar', [$this, 'remove_avatar'] );
            
            add_action( 'wp_ajax_save_account', [$this, 'save_account'] );
            add_action( 'wp_ajax_nopriv_save_account', [$this, 'save_account'] );
            
            add_action( 'wp_ajax_change_password', [$this, 'change_password'] );
            add_action( 'wp_ajax_nopriv_change_password', [$this, 'change_password'] );
            
            add_action( 'wp_ajax_change_main_phone_email', [$this, 'change_main_phone_email'] );
            add_action( 'wp_ajax_nopriv_change_main_phone_email', [$this, 'change_main_phone_email'] );
            
            add_action( 'wp_ajax_delete_account', [$this, 'delete_account'] );
            add_action( 'wp_ajax_nopriv_delete_account', [$this, 'delete_account'] );
            
            add_action( 'wp_ajax_save_public_info', [$this, 'save_public_info'] );
            add_action( 'wp_ajax_nopriv_save_public_info', [$this, 'save_public_info'] );
            
            add_action( 'wp_ajax_save_newsletter_settings', [$this, 'save_newsletter_settings'] );
            add_action( 'wp_ajax_nopriv_save_newsletter_settings', [$this, 'save_newsletter_settings'] );
            
            add_action( 'wp_ajax_confirm_user_data', [$this, 'confirm_user_data'] );
            add_action( 'wp_ajax_nopriv_confirm_user_data', [$this, 'confirm_user_data'] );

            add_action( 'wp_ajax_admin_update_avatar', [$this, 'admin_update_avatar'] );
            add_action( 'wp_ajax_admin_remove_avatar', [$this, 'admin_remove_avatar'] );
        }

        add_action( 'template_redirect', [$this, 'redirect_non_logged_users'] );
        add_action( 'init', [$this, 'user_rewrite'] );
        add_action( 'admin_menu', [$this, 'settings_page'] );

        add_filter( 'manage_users_columns', [$this, 'modify_user_table'] );
        add_filter( 'manage_users_custom_column', [$this, 'modify_user_table_row'], 10, 3 );

        add_action( 'show_user_profile', [$this, 'add_meta_fields_to_admin_profile'] );
        add_action( 'edit_user_profile', [$this, 'add_meta_fields_to_admin_profile'] );

        add_action( 'personal_options_update', [$this, 'save_meta_fields_to_admin_profile'] );
        add_action( 'edit_user_profile_update', [$this, 'save_meta_fields_to_admin_profile'] );
    }

    public function redirect_non_logged_users() {

        if ( is_page_template('template-account.php') && ! is_user_logged_in() ) {
            wp_redirect( home_url() ); 
            exit; 
        }

        if ( is_page_template('template-account-public.php') && ! is_user_logged_in() ) {
            wp_redirect( home_url() ); 
            exit; 
        }

        if ( is_page_template('template-account-orders.php') && ! is_user_logged_in() ) {
            wp_redirect( home_url() ); 
            exit; 
        }

        if ( is_page_template('template-account-addresses.php') && ! is_user_logged_in() ) {
            wp_redirect( home_url() ); 
            exit; 
        
        }
        if ( is_page_template('template-account-newsletters.php') && ! is_user_logged_in() ) {
            wp_redirect( home_url() ); 
            exit; 
        }
    }

    public function update_avatar() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        $avatar_html = '';
        $user_id = get_current_user_id();
        $prev_avatar_id = get_user_meta( $user_id, '_avatar', true );
        
        if ( 	
            isset( $_FILES['file_avatar'] ) &&
            'image/png'  != $_FILES['file_avatar']['type'] && 
            'image/jpeg' != $_FILES['file_avatar']['type'] && 
            'image/gif'  != $_FILES['file_avatar']['type'] 
        ) {
            wp_send_json_error( array(
                'name_field' => 'avatar',
                'message' => __( 'Invalid file type! Use format file: jpg, png, gif', 'natures-sunshine' ),
            ) );
        } elseif ( ! isset( $_FILES['file_avatar'] ) && empty( $prev_avatar_id ) ) {
            wp_send_json_error( array(
                'name_field' => 'avatar',
            ) );
        }
        
        if ( $prev_avatar_id && isset( $_FILES['file_avatar'] ) ) {
            wp_delete_attachment( $prev_avatar_id, true );
        }
        
        // save company logo
        if ( isset( $_FILES['file_avatar'] ) ) {
            $attach_id = $this->process_upload( $_FILES['file_avatar'] );
            update_user_meta( $user_id, '_avatar', $attach_id );
        }

        ob_start();
        get_template_part(
            'template-parts/profile/account/avatar',
            null,
            [
                'user' => get_userdata( $user_id ),
                'avatar_id' => $attach_id,
            ]
        ); 
        $avatar_html = ob_get_clean();
        
        wp_send_json_success([
            'message' => __('Saved', 'natures-sunshine'),
            'avatar_html' => $avatar_html,
        ]);
    }

    public function save_account() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );
        $user_id = get_current_user_id();
        $success = [
            'message' => __('Saved', 'natures-sunshine'),
        ];
        $error = [
            'message' => __('Field is required', 'natures-sunshine'),
        ];

        if ( $_POST['field_name'] == 'description' ) {
            update_user_meta( $user_id, 'description', $form['description'] );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'surname' ) {

            if ( empty($form['surname']) ) {
                wp_send_json_error($error);
            }

            $user_id = wp_update_user( [
                'ID' => $user_id,
                'last_name' => $form['surname'],
            ] );

            if ( is_wp_error( $user_id ) ) {
                wp_send_json_error( array(
                    'message' => $user_id->get_error_message(),
                ) );
            } 
            update_user_meta( $user_id, 'billing_last_name', $form['surname'] );
            update_user_meta( $user_id, 'shipping_last_name', $form['surname'] );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'name' ) {

            if ( empty($form['name']) ) {
                wp_send_json_error($error);
            }

            $user_id = wp_update_user( [
                'ID' => $user_id,
                'first_name' => $form['name'],
            ] );

            if ( is_wp_error( $user_id ) ) {
                wp_send_json_error( array(
                    'message' => $user_id->get_error_message(),
                ) );
            } 
            update_user_meta( $user_id, 'billing_first_name', $form['name'] );
            update_user_meta( $user_id, 'shipping_first_name', $form['name'] );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'patronymic' ) {
            update_user_meta( $user_id, 'patronymic', $form['patronymic'] );
            wp_send_json_success($success);
        }

        // Main Email

        // Main Phone

        if ( $_POST['field_name'] == 'additional_phones[]' ) {
            $phones = [];
            $additional_phones = array_filter($form['additional_phones']);
            foreach ( $additional_phones as $key => $additional_phone ) {
                $phones[$key] = [
                    'number' => $additional_phone,
                    'public' => false,
                ];
            }
            update_user_meta( $user_id, 'additional_phones', $phones );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'additional_emails[]' ) {
            $phones = [];
            $additional_emails = array_filter($form['additional_emails']);
            foreach ( $additional_emails as $key => $additional_email ) {
                if ( is_email($additional_email) ) {
                    $phones[$key] = [
                        'email' => $additional_email,
                        'public' => false,
                    ];
                }
            }
            update_user_meta( $user_id, 'additional_emails', $phones );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'interface_language' ) {
            update_user_meta( $user_id, 'interface_language', $form['interface_language'] );
            wp_send_json_success($success);
        }
        
        if ( $_POST['field_name'] == 'timezone_string' ) {
            update_user_meta( $user_id, 'timezone_string', $form['timezone_string'] );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'telegram' ) {
            update_user_meta( $user_id, 'telegram', $form['telegram'] );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'whatsapp' ) {
            update_user_meta( $user_id, 'whatsapp', $form['whatsapp'] );
            wp_send_json_success($success);
        }

        if ( $_POST['field_name'] == 'skype' ) {
            update_user_meta( $user_id, 'skype', $form['skype'] );
            wp_send_json_success($success);
        }
        
    }
    
    public function remove_avatar() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        $avatar_html = '';
        $user_id = get_current_user_id();
        $prev_avatar_id = get_user_meta( $user_id, '_avatar', true );
        $success = [
            'message' => __('Saved', 'natures-sunshine'),
        ];
        
        wp_delete_attachment( $prev_avatar_id, true );
        update_user_meta( $user_id, '_avatar', null );
        
        ob_start();
        get_template_part(
            'template-parts/profile/account/avatar',
            null,
            [
                'user' => get_userdata( $user_id ),
                'avatar_id' => null,
            ]
        ); 
        $avatar_html = ob_get_clean();
        
        wp_send_json_success([
            'message' => __('Saved', 'natures-sunshine'),
            'avatar_html' => $avatar_html,
        ]);
    }
    
    public function change_password() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        $user_id = get_current_user_id();
        $user = get_user_by( 'id', $user_id );
     
        if ( ! $user ) {
            wp_send_json_error( [
                'message' => __( 'This user does not exist!', 'natures-sunshine' ),
            ] );
        } 
        
        if ( ! $form['old_password'] ) {
            wp_send_json_error( [
                'name_field' => 'old_password',
                'message' => __( 'Field Old password is required', 'natures-sunshine' ),
            ] );
        }

        $hash = $user->data->user_pass;

        if ( ! wp_check_password( $form['old_password'], $hash ) ) {
            wp_send_json_error( [
                'message' => __( 'Old password is incorrect', 'natures-sunshine' ),
            ] );
        }

        if ( ! $form['password'] ) {
            wp_send_json_error( [
                'name_field' => 'password',
                'message' => __( 'Field Password is required', 'natures-sunshine' ),
            ] );
        }

        if ( empty( $form['repeat_password'] ) ) {
            wp_send_json_error( [
                'name_field' => 'repeat_password',
                'message' => __('Field Repeat password is required', 'natures-sunshine'),
            ] );
        } 
        
        if ( $form['password'] != $form['repeat_password'] ) {
            wp_send_json_error( [
                'name_field' => 'repeat_password',
                'message' => __('Passwords do not match', 'natures-sunshine'),
            ] );
        } 

        if ( ut_help()->user->password_strength( $form['password'], $user->user_login ) !== 4 ) {
            wp_send_json_error( array(
                'name_field' => 'password',
                'message' => __('Weak password', 'natures-sunshine'),
            ) );
        }

        reset_password( $user, $form['password'] );

        wp_send_json_success( [
            'message' => __( 'Your password has been successfully updated!', 'natures-sunshine' ),
        ] );
    }
    
    public function change_main_phone_email() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        $user_id = get_current_user_id();
        $user = get_user_by( 'id', $user_id );
     
        if ( ! $user ) {
            wp_send_json_error( [
                'message' => __( 'This user does not exist!', 'natures-sunshine' ),
            ] ); 
        } 
        
        if ( ! $form['phone'] ) {
            wp_send_json_error( [
                'name_field' => 'phone',
                'message' => __( 'Field Main number is required', 'natures-sunshine' ),
            ] );
        }
        
        if ( ! $form['email'] ) {
            wp_send_json_error( [
                'name_field' => 'email',
                'message' => __( 'Field Email is required', 'natures-sunshine' ),
            ] );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'users_data_confirmation';
        // delete user data if they exist
        $wpdb->delete( $table, [ 'user_id' => $user_id ] );
        // insert data
        $data= [
            'user_id' => $user_id, 
            'user_email' => $form['email'],
            'user_phone' => $form['phone'], 
        ];
        $wpdb->insert( $table, $data );
        // send data
        $middle_name = get_user_meta( $user->ID, 'patronymic', true );
        $full_name = $user->last_name . ' ' . $user->first_name . ' ' . $middle_name;
        $data = [
            'full_name' => $full_name,
            'user_email' => get_option('admin_email'), // send to moderator
            'phone' => $form['phone'],
            'email' => $form['email'],
        ];
        ut_help()->esputnik->send_users_data_confirmation( $data );

        wp_send_json_success( [
            'message' => __( 'Data sent for verification', 'natures-sunshine' ),
        ] );
    }
    
    public function delete_account() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        require_once ABSPATH . 'wp-admin/includes/user.php';

        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        $del = wp_delete_user( $user_id );

        if ( ! $del ) {
            wp_send_json_error( [
                'message' => __( 'Error', 'natures-sunshine' ),
            ] );
        }  

        $middle_name = get_user_meta( $user_id, 'patronymic', true );
        $full_name = $user->last_name . ' ' . $user->first_name . ' ' . $middle_name;
        $data = [
            'full_name' => $full_name, 
            'user_email' => $user->user_email, 
        ];
        ut_help()->esputnik->send_delete_account_message( $data );

        wp_send_json_success( [
            'redirect_url' => home_url() . '?delete-user=success',
        ] );
    }

    /**
     * Add Images to Media Library after Uploading
     */
    public function process_upload( $image ) {
       
        // WordPress environment
        // require( dirname(__FILE__) . '/../../../wp-load.php' );
        $wp_upload_dir = wp_upload_dir();
        $i = 1; // number of tries when the file with the same name is already exists
        $new_file_path = $wp_upload_dir['path'] . '/' . $image['name'];
        $new_file_mime = mime_content_type( $image['tmp_name'] );
         
        while ( file_exists( $new_file_path ) ) {
            $i++;
            $new_file_path = $wp_upload_dir['path'] . '/' . $i . '_' . $image['name'];
        }
         
        // looks like everything is OK
        if ( move_uploaded_file( $image['tmp_name'], $new_file_path ) ) {
         
         
            $upload_id = wp_insert_attachment( 
                [
                    'guid' => $new_file_path, 
                    'post_mime_type' => $new_file_mime,
                    'post_title' => preg_replace( '/\.[^.]+$/', '', $image['name'] ),
                    'post_content' => '',
                    'post_status' => 'inherit'
                ], 
                $new_file_path 
            );
            // wp_generate_attachment_metadata() won't work if you do not include this file
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            // Generate and save the attachment metas into the database
            wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
    
            return $upload_id;
        }
    }

    public function get_account_sidebar_pages() {

        $account_pages = [];
        $account = ut_get_page_data_by_template('template-account.php');
        $public = ut_get_page_data_by_template('template-account-public.php');
        $orders = ut_get_page_data_by_template('template-account-orders.php');
        $addresses = ut_get_page_data_by_template('template-account-addresses.php');
        $newsletters = ut_get_page_data_by_template('template-account-newsletters.php');

        if ( $account ) {
            $account_pages[] = [
                'active' => ( is_page_template('template-account.php') ) ? 'is-active' : '',
                'url' => get_permalink( $account->ID ),
                'title' => $account->post_title,
                'short_title' => get_field('title_mobile_account', $account->ID),
                'icon' => '<svg width="24" height="24"><use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#profile-account"></use></svg>',
            ];
        }

        if ( $public ) {
            $account_pages[] = [
                'active' => ( is_page_template('template-account-public.php') ) ? 'is-active' : '',
                'url' => get_permalink( $public->ID ),
                'title' => $public->post_title,
                'short_title' => get_field('title_mobile_public', $public->ID),
                'icon' => '<svg width="24" height="24"><use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#profile-public"></use></svg>',
            ];
        }

        if ( $orders ) {
            $account_pages[] = [
                'active' => ( is_page_template('template-account-orders.php') ) ? 'is-active' : '',
                'url' => get_permalink( $orders->ID ),
                'title' => $orders->post_title,
                'short_title' => get_field('title_mobile_orders', $orders->ID),
                'icon' => '<svg width="24" height="24"><use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#profile-orders"></use></svg>',
            ];
        }

        if ( $addresses ) {
            $account_pages[] = [
                'active' => ( is_page_template('template-account-addresses.php') ) ? 'is-active' : '',
                'url' => get_permalink( $addresses->ID ),
                'title' => $addresses->post_title,
                'short_title' => get_field('title_mobile_addresses', $addresses->ID),
                'icon' => '<svg width="24" height="24"><use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#profile-addresses"></use></svg>',
            ];
        }

        if ( $newsletters ) {
            $account_pages[] = [
                'active' => ( is_page_template('template-account-newsletters.php') ) ? 'is-active' : '',
                'url' => get_permalink( $newsletters->ID ),
                'title' => $newsletters->post_title,
                'short_title' => $newsletters->post_title,
                'icon' => '<svg width="24" height="24"><use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#profile-mailing"></use></svg>',
            ];
        }


        return $account_pages;
    }

    // public function send_email_to_moderator( $user, $new_number, $new_email ) {

    //     $admin_email = get_option('admin_email');
    //     $user_full_name = sprintf( 
    //         '%1s %2s %3s',
    //         $user->last_name,
    //         $user->first_name,
    //         get_user_meta( $user->ID, 'patronymic', true )
    //     ); 
    //     $blogname = wp_specialchars_decode( get_option('blogname'), ENT_QUOTES );
    //     $title = sprintf( __('[%s] Change email and phone', 'natures-sunshine'), $blogname );

    //     $message  = file_get_contents( dirname(__FILE__).'/email templates/header.php' );
    //     $message .= '<p style="margin-bottom:10px;">'.__('User ', 'natures-sunshine') . ' ' . $user_full_name . ',</p>';
    //     $message .= '<p style="margin-bottom:10px;">'.__('Requested confirmation to update data', 'natures-sunshine').'</p>';
    //     $message .= '<p style="margin-bottom:10px;">'.__('Main number', 'natures-sunshine').': <span style="color: #1F8599;">' . $new_number . '</span></p>';
    //     $message .= '<p style="margin-bottom:10px;">'.__('Email', 'natures-sunshine').': <span style="color: #1F8599;">' . $new_email . '</span></p>';
    //     $message .= file_get_contents( dirname(__FILE__).'/email templates/footer.php' );
     
    //     add_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );

    //     if ( $message && ! wp_mail( $admin_email, $title, $message ) ) {
    //         wp_send_json_error( [
    //             'message' => __('Failed to send email. Possible reason: your host may turn off the feature mail()...', 'natures-sunshine'),
    //         ] );
    //     }

    //     remove_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );

    //     return true;
    // }

    public function set_html_content_type() {

        return 'text/html';
    }

    public function save_public_info() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );
        $name = $_POST['field_name'];
        $user_id = get_current_user_id();

        if ( $name == 'public_profile' && isset($form['public_profile']) ) {
            update_user_meta( $user_id, 'public_profile', true );
        } else if ( $name == 'public_profile' && !isset($form['public_profile']) ) {
            update_user_meta( $user_id, 'public_profile', false );
        }
       
        if ( $name == 'profile_photo' && isset($form['profile_photo']) ) {
            update_user_meta( $user_id, 'profile_photo', true );
        } else if ( $name == 'profile_photo' && !isset($form['profile_photo']) ) {
            update_user_meta( $user_id, 'profile_photo', false );
        }

        if ( $name == 'profile_name' && isset($form['profile_name']) ) {
            update_user_meta( $user_id, 'profile_name', true );
        } else if ( $name == 'profile_name' && !isset($form['profile_name']) ) {
            update_user_meta( $user_id, 'profile_name', false );
        }

        if ( $name == 'profile_info' && isset($form['profile_info']) ) {
            update_user_meta( $user_id, 'profile_info', true );
        } else if ( $name == 'profile_info' && !isset($form['profile_info']) ) {
            update_user_meta( $user_id, 'profile_info', false );
        }
        
        if ( $name == 'profile_phone' && isset($form['profile_phone']) ) {
            update_user_meta( $user_id, 'profile_phone', true );
        } else if ( $name == 'profile_phone' && !isset($form['profile_phone']) ) {
            update_user_meta( $user_id, 'profile_phone', false );
        }

        if ( $name == 'profile_email' && isset($form['profile_email']) ) {
            update_user_meta( $user_id, 'profile_email', true );
        } else if ( $name == 'profile_email' && !isset($form['profile_email']) ) {
            update_user_meta( $user_id, 'profile_email', false );
        }
       
        if ( $name == 'profile_messengers' && isset($form['profile_messengers']) ) {
            update_user_meta( $user_id, 'profile_messengers', true );
        } else if ( $name == 'profile_messengers' && !isset($form['profile_messengers']) ) {
            update_user_meta( $user_id, 'profile_messengers', false );
        }

        if ( $name == 'additional_phones[]' && isset($form['additional_phones']) ) {
            $save_phones = get_user_meta( $user_id, 'additional_phones', true );
            $additional_phones = array_filter($form['additional_phones']);
            foreach ( $save_phones as $key => $save_phone ) {
                foreach ( $additional_phones as $additional_phone ) {
                    if ( $additional_phone == $save_phone['number'] ) {
                        $save_phones[ $key ]['public'] = true;
                    }
                }
            }
            update_user_meta( $user_id, 'additional_phones', $save_phones );
        } else if ( $name == 'additional_phones[]' && !isset($form['additional_phones']) ) {
            $save_phones = get_user_meta( $user_id, 'additional_phones', true );
            foreach ( $save_phones as $key => $save_phone ) {
                $save_phones[ $key ]['public'] = false;
            }
            update_user_meta( $user_id, 'additional_phones', $save_phones );
        }

        if ( $name == 'additional_emails[]' && isset($form['additional_emails']) ) {
            $save_emails = get_user_meta( $user_id, 'additional_emails', true );
            $additional_emails = array_filter($form['additional_emails']);
            foreach ( $save_emails as $key => $save_email ) {
                foreach ( $additional_emails as $additional_email ) {
                    if ( $additional_email == $save_email['email'] ) {
                        $save_emails[ $key ]['public'] = true;
                    }
                }
            }
            update_user_meta( $user_id, 'additional_emails', $save_emails );
        } else if ( $name == 'additional_emails[]' && !isset($form['additional_emails']) ) {
            $save_emails = get_user_meta( $user_id, 'additional_emails', true );
            foreach ( $save_emails as $key => $save_email ) {
                $save_emails[ $key ]['public'] = false;
            }
            update_user_meta( $user_id, 'additional_emails', $save_emails );
        }

        if ( $name == 'profile_msg_telegram' && isset($form['profile_msg_telegram']) ) {
            update_user_meta( $user_id, 'public_telegram', true );
        } else if ( $name == 'profile_msg_telegram' && !isset($form['profile_msg_telegram']) ) {
            update_user_meta( $user_id, 'public_telegram', false );
        }

        if ( $name == 'profile_msg_whatsapp' && isset($form['profile_msg_whatsapp']) ) {
            update_user_meta( $user_id, 'public_whatsapp', true );
        } else if ( $name == 'profile_msg_whatsapp' && !isset($form['profile_msg_whatsapp']) ) {
            update_user_meta( $user_id, 'public_whatsapp', false );
        }
        
        if ( $name == 'profile_msg_skype' && isset($form['profile_msg_skype']) ) {
            update_user_meta( $user_id, 'public_skype', true );
        } else if ( $name == 'profile_msg_skype' && !isset($form['profile_msg_skype']) ) {
            update_user_meta( $user_id, 'public_skype', false );
        }

        wp_send_json_success([
            'message' => __('Saved', 'natures-sunshine'),
        ]);
    }

    public function user_rewrite() {

        add_rewrite_rule( '^(partner)/(\d+)[/]?$', 'index.php?pagename=$matches[1]&partner_id=$matches[2]', 'top' );

        add_filter( 'query_vars', function( $vars ){

            $vars[] = 'partner_id';
            
            return $vars;
        } );
    }

    public function save_newsletter_settings() {

        check_ajax_referer( 'account_nonce', 'ajax_nonce' );
        $current_lang = apply_filters( 'wpml_current_language', NULL );
        $field_name = $_POST['field_name'];
        $subscriber_id = $_POST['subscriber_id'];
        $checked = ( $_POST['checked'] == 'true' ) ? true : false;
        $user_id = get_current_user_id();

        if ( $field_name == 'profile_news' && $current_lang == 'uk' ) {
            $group_data = ['subscriptions' => [$this->esputnik_category_keys[ 'news_' . $current_lang ]]];
        } else if ( $field_name == 'profile_promo' && $current_lang == 'uk' ) {
            $group_data = ['subscriptions' => [$this->esputnik_category_keys[ 'stock_' . $current_lang ]]];
        }

        update_user_meta( $user_id, $field_name, $checked );

        if ( $checked ) {

            $response = ut_help()->esputnik->subscribe( $group_data );
            update_user_meta( $user_id, $field_name . '_id', $response['id'] );
            wp_send_json_success($response);

        } else {

            if ( ! $subscriber_id ) {
                wp_send_json_error();
            }

            $response = ut_help()->esputnik->unsubscribe( $subscriber_id, $group_data );
            update_user_meta( $user_id, $field_name . '_id', null );
            wp_send_json_success($response);
        }

        wp_send_json_error();
    }

    public function settings_page() {
        add_menu_page( 
            __('Users conformation', 'natures-sunshine'), 
            __('Users conformation', 'natures-sunshine'), 
            'edit_posts', 
            'users_conformation', 
            [$this, 'users_conformation_display'], 
            '', 
            124
        );
    }

    public function users_conformation_display() {

        global $wpdb;    

        $table = $wpdb->prefix . 'users_data_confirmation';
        $users_data = $wpdb->get_results("
                                            SELECT 
                                                user_id,
                                                user_email,
                                                user_phone,
                                                confirmed,
                                                date                                             
                                            FROM $table
                                            ORDER BY date DESC
                                         ", 
                                         'ARRAY_A'
                                        );

        get_template_part( 
            'template-parts/admin/users-conformation', 
            'table', 
            [
                'users_data' => $users_data,
            ] 
        );
    }

    public function confirm_user_data() {

        check_ajax_referer( 'admin_nonce', 'ajax_nonce' );
        global $wpdb;    
        // update table
        $table = $wpdb->prefix . 'users_data_confirmation';
        $wpdb->update( 
            $table,
            [ 'confirmed' => true ],
            [ 'user_id' => $_POST['id'] ]
        );
        // update user data
        $user_id = wp_update_user( [
            'ID' => $_POST['id'],
            'user_email' => $_POST['email'],
        ] );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array(
                'message' => $user_id->get_error_message(),
            ) );
        } 
        update_user_meta( $user_id, 'billing_email', $_POST['email'] );
        update_user_meta( $user_id, 'billing_phone', $_POST['phone'] );
        update_user_meta( $user_id, 'shipping_phone', $_POST['phone'] );
        // send notification
        $user = get_user_by( 'email', $_POST['email'] );
        $middle_name = get_user_meta( $user_id, 'patronymic', true );
        $full_name = $user->last_name . ' ' . $user->first_name . ' ' . $middle_name;
        $data = [
            'full_name' => $full_name, 
            'user_email' => $_POST['email'],
            'user_phone' => $_POST['phone'],
        ];
        ut_help()->esputnik->send_user_data_is_confirmed( $data );

        wp_send_json_success();
    }

    public function modify_user_table( $column ) {

        $column['register_id'] = 'ID';

        return $column;
    }
    
    public function modify_user_table_row( $val, $column_name, $user_id ) {

        switch ($column_name) {
            case 'register_id' :
                return get_the_author_meta( 'register_id', $user_id );
            default:
        }

        return $val;
    }

    public function admin_update_avatar() {

        check_ajax_referer( 'admin_nonce', 'ajax_nonce' );
        $avatar_html = '';
        $user_id = $_POST['user_id'];
        $prev_avatar_id = get_user_meta( $user_id, '_avatar', true );
        
        if ( 	
            isset( $_FILES['file_avatar'] ) &&
            'image/png'  != $_FILES['file_avatar']['type'] && 
            'image/jpeg' != $_FILES['file_avatar']['type'] && 
            'image/gif'  != $_FILES['file_avatar']['type'] 
        ) {
            wp_send_json_error( array(
                'name_field' => 'avatar',
                'message' => __( 'Invalid file type! Use format file: jpg, png, gif', 'natures-sunshine' ),
            ) );
        } elseif ( ! isset( $_FILES['file_avatar'] ) && empty( $prev_avatar_id ) ) {
            wp_send_json_error( array(
                'name_field' => 'avatar',
            ) );
        }
        
        if ( $prev_avatar_id && isset( $_FILES['file_avatar'] ) ) {
            wp_delete_attachment( $prev_avatar_id, true );
        }
        
        // save company logo
        if ( isset( $_FILES['file_avatar'] ) ) {
            $attach_id = $this->process_upload( $_FILES['file_avatar'] );
            update_user_meta( $user_id, '_avatar', $attach_id );
        }

        ob_start();
        get_template_part(
            'template-parts/profile/account/avatar',
            null,
            [
                'user' => get_userdata( $user_id ),
                'avatar_id' => $attach_id,
            ]
        ); 
        $avatar_html = ob_get_clean();
        
        wp_send_json_success([
            'avatar_html' => $avatar_html,
        ]);
    }
    
    public function admin_remove_avatar() {

        check_ajax_referer( 'admin_nonce', 'ajax_nonce' );
        $avatar_html = '';
        $user_id = $_POST['user_id'];
        $prev_avatar_id = get_user_meta( $user_id, '_avatar', true );
        
        wp_delete_attachment( $prev_avatar_id, true );
        update_user_meta( $user_id, '_avatar', null );
        
        ob_start();
        get_template_part(
            'template-parts/profile/account/avatar',
            null,
            [
                'user' => get_userdata( $user_id ),
                'avatar_id' => null,
            ]
        ); 
        $avatar_html = ob_get_clean();
        
        wp_send_json_success([
            'avatar_html' => $avatar_html,
        ]);
    }

    public function add_meta_fields_to_admin_profile( $user ) {

        // $register_phone = get_user_meta( $user->ID, 'billing_phone', true );
        $middle_name = get_user_meta( $user->ID, 'patronymic', true );
        $tzstring = get_user_meta( $user->ID, 'timezone_string', true );
        $avatar_id = get_user_meta( $user->ID, '_avatar', true );
        $register_id = get_user_meta( $user->ID, 'register_id', true );
        $description = get_user_meta( $user->ID, 'description', true );
        $additional_phones = get_user_meta( $user->ID, 'additional_phones', true );
        $additional_emails = get_user_meta( $user->ID, 'additional_emails', true );
        $telegram = get_user_meta( $user->ID, 'telegram', true );
        $whatsapp = get_user_meta( $user->ID, 'whatsapp', true );
        $skype = get_user_meta( $user->ID, 'skype', true );
        ?>
            <div class="user-admin-account-wrapper">

                <div class="profile-content__account-photo">
                    <div class="ut-loader"></div>
                    <div class="profile-content__account-photo-inner">

                        <?php 
                            get_template_part(
                                'template-parts/profile/account/avatar',
                                null,
                                [
                                    'user' => $user,
                                    'avatar_id' => $avatar_id,
                                ]
                            ); 
                        ?>

                    </div>
                    <div class="error-avatar"></div>
                    <a href="#" class="profile-content__account-photo-delete"><?php _e('Delete', 'natures-sunshine'); ?></a>
                </div>

                <table class="form-table">
                    <tr>
                        <th><label for="register_id"><?php _e('ID', 'natures-sunshine'); ?></label></th>
                        <td>
                            <input name="register_id" id="register_id" type="text" required value="<?php echo esc_attr( $register_id ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <!-- <tr>
                        <th><label for="register_phone"><?php //_e('Main number', 'natures-sunshine'); ?></label></th>
                        <td>
                            <input name="register_phone" id="register_phone" type="text" required value="<?php //echo esc_attr( $register_phone ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="register_email"><?php //_e('Email', 'natures-sunshine'); ?></label></th>
                        <td>
                            <input name="register_email" id="register_email" type="text" required value="<?php //echo esc_attr( $user->user_email ); ?>" class="regular-text">
                        </td>
                    </tr> -->
                    <tr>
                        <th><label for="description"><?php _e('A little bit about yourself', 'natures-sunshine'); ?></label></th>
                        <td>
                            <textarea value="<?php echo esc_attr( $description ); ?>" name="description" id="description" cols="30" rows="3"><?php echo nl2br( $description ); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="patronymic"><?php _e('Middle name', 'natures-sunshine'); ?></label></th>
                        <td>
                            <input name="patronymic" id="patronymic" type="text" required value="<?php echo esc_attr( $middle_name ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="timezone_string"><?php _e('Time zone and time', 'natures-sunshine'); ?></label></th>
                        <td>
                            <select id="timezone_string" name="timezone_string" aria-describedby="timezone-description">
                                <?php echo wp_timezone_choice( $tzstring, get_user_locale() ); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="messengers__item-label color" for="telegram">
                                <svg class="messengers__item-label__icon messengers__item-label__icon--tg" width="20" height="20">
                                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#telegram'; ?>"></use>
                                </svg>
                                Telegram
                            </label>
                        </th>
                        <td>
                            <input class="messengers__item-input regular-text" type="text" name="telegram" id="telegram" placeholder="@username" value="<?php echo esc_attr( $telegram ); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="messengers__item-label color" for="whatsapp">
                                <svg class="messengers__item-label__icon messengers__item-label__icon--wa" width="20" height="20">
                                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#whatsapp'; ?>"></use>
                                </svg>
                                Whatsapp
                            </label>
                        </th>
                        <td>
                            <input class="messengers__item-input regular-text" type="text" name="whatsapp" id="whatsapp" placeholder="+7 000 000-00-00" value="<?php echo esc_attr( $whatsapp ); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="messengers__item-label color" for="skype">
                                <svg class="messengers__item-label__icon messengers__item-label__icon--sk" width="20" height="20">
                                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#skype'; ?>"></use>
                                </svg>
                                Skype
                            </label>
                        </th>
                        <td>
                            <input class="messengers__item-input regular-text" type="text" name="skype" id="skype" value="<?php echo esc_attr( $skype ); ?>" placeholder="<?php _e('Account login', 'natures-sunshine'); ?>">
                        </td>
                    </tr>
                </table>

                <div class="profile-content__row profile-content__extrafields">
                    <div class="profile-content__extrafields-title profile-title"><?php _e('Additional Phone', 'natures-sunshine'); ?></div>
                    <div class="profile-content__extrafields-list additional-phones-js">

                        <?php foreach ( (array)$additional_phones as $key => $additional_phone ) : ?>

                            <div class="profile-content__extrafields-list__item">
                                <div class="profile-content__extrafields-list__item-input">
                                    <input type="text" 
                                        class="mask-js"
                                        name="additional_phones[]" 
                                        value="<?php echo esc_attr( $additional_phone['number'] ); ?>" 
                                        placeholder="+38(___) ___-____">
                                </div>
                                <div class="profile-content__extrafields-list__item-delete remove-additional-phone-js">
                                    <svg width="24" height="24"><use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-input-delete'; ?>"></use></svg>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>
                    <button type="button" class="profile-content__extrafields-add btn btn-secondary add-additional-phone-js"><?php _e('Add', 'natures-sunshine'); ?></button>
                </div>

                <div class="profile-content__row profile-content__extrafields">
                    <div class="profile-content__extrafields-title profile-title"><?php _e('Additional email', 'natures-sunshine'); ?></div>
                    <div class="profile-content__extrafields-list additional-emails-js">

                        <?php foreach ( (array)$additional_emails as $key => $additional_email ) : ?>

                            <div class="profile-content__extrafields-list__item">
                                <div class="profile-content__extrafields-list__item-input">
                                    <input type="text" 
                                        class="emask-js"
                                        name="additional_emails[]" 
                                        value="<?php echo esc_attr( $additional_email['email'] ); ?>">
                                </div>
                                <div class="profile-content__extrafields-list__item-delete remove-additional-email-js">
                                    <svg width="24" height="24"><use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-input-delete'; ?>"></use></svg>
                                </div>
                            </div>
                        
                        <?php endforeach; ?>

                    </div>
                    <button type="button" class="profile-content__extrafields-add btn btn-secondary add-additional-email-js"><?php _e('Add', 'natures-sunshine'); ?></button>
                </div>

            </div>

        <?php
    }

    function save_meta_fields_to_admin_profile( $user_id ) {

        update_user_meta( $user_id, 'register_id', sanitize_text_field( $_POST['register_id'] ) );
        // update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['register_phone'] ) );
        // update_user_meta( $user_id, 'shipping_phone', sanitize_text_field( $_POST['register_phone'] ) );
        // update_user_meta( $user_id, 'billing_email', sanitize_text_field( $_POST['register_email'] ) );
        update_user_meta( $user_id, 'patronymic', sanitize_text_field( $_POST['patronymic'] ) );
        update_user_meta( $user_id, 'description', sanitize_text_field( $_POST['description'] ) );
        update_user_meta( $user_id, 'timezone_string', sanitize_text_field( $_POST['timezone_string'] ) );
        update_user_meta( $user_id, 'telegram', sanitize_text_field( $_POST['telegram'] ) );
        update_user_meta( $user_id, 'whatsapp', sanitize_text_field( $_POST['whatsapp'] ) );
        update_user_meta( $user_id, 'skype', sanitize_text_field( $_POST['skype'] ) );

        if ( ! empty($_POST['additional_phones']) ) {
            $phones = [];
            $additional_phones = array_filter($_POST['additional_phones']);
            foreach ( $additional_phones as $key => $additional_phone ) {
                $phones[$key] = [
                    'number' => $additional_phone,
                    'public' => false,
                ];
            }
            update_user_meta( $user_id, 'additional_phones', $phones );

        } else {
            update_user_meta( $user_id, 'additional_phones', [] );
        }

        if ( ! empty($_POST['additional_emails']) ) {
            $emails = [];
            $additional_emails = array_filter($_POST['additional_emails']);
            foreach ( $additional_emails as $key => $additional_email ) {
                if ( is_email($additional_email) ) {
                    $emails[$key] = [
                        'email' => $additional_email,
                        'public' => false,
                    ];
                }
            }
            update_user_meta( $user_id, 'additional_emails', $emails );

        } else {
            update_user_meta( $user_id, 'additional_emails', [] );
        }
    }
    
}  