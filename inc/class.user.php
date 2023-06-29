<?php

class UT_User {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        add_action( 'admin_init', [ $this, 'new_role' ] );
        add_action( 'delete_pending_user_event', [ $this, 'delete_pending_user' ], 10, 3 );
        add_action( 'pre_user_query', [ $this, 'pre_user_search']  );

        if ( wp_doing_ajax() ) { 
            add_action( 'wp_ajax_user_registration_step_2', [ $this, 'user_registration_step_2' ] );
            add_action( 'wp_ajax_nopriv_user_registration_step_2', [ $this, 'user_registration_step_2' ] );
            
            add_action( 'wp_ajax_user_registration_step_3', [ $this, 'user_registration_step_3' ] );
            add_action( 'wp_ajax_nopriv_user_registration_step_3', [ $this, 'user_registration_step_3' ] );

            add_action( 'wp_ajax_user_auth', [ $this, 'user_auth' ] );
            add_action( 'wp_ajax_nopriv_user_auth', [ $this, 'user_auth' ] );

            add_action('wp_ajax_reset_password', [ $this, 'reset_password' ] );
            add_action('wp_ajax_nopriv_reset_password', [ $this, 'reset_password' ] );

            add_action('wp_ajax_new_password', [ $this, 'new_password' ] );
            add_action('wp_ajax_nopriv_new_password', [ $this, 'new_password' ] );
        }
    } 

    public function pre_user_search( $user_search ) {

        global $wpdb;

        if ( ! isset($_GET['s']) ) {
            return;
        }

        //Enter Your Meta Fields To Query
        $search_array = [
            'register_id', 
            'customer_id', 
            'postal_code', 
            'first_name', 
            'last_name'
        ];
        $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID={$wpdb->usermeta}.user_id AND (";
        for ( $i=0; $i < count($search_array); $i++ ) {
            if ( $i > 0 ) {
                $user_search->query_from .= " OR ";
            }
            $user_search->query_from .= "{$wpdb->usermeta}.meta_key='" . $search_array[$i] . "'";
        }
        $user_search->query_from .= ")";        
        $custom_where = $wpdb->prepare("{$wpdb->usermeta}.meta_value LIKE '%s'", "%" . $_GET['s'] . "%");
        $user_search->query_where = str_replace('WHERE 1=1 AND (', "WHERE 1=1 AND ({$custom_where} OR ",$user_search->query_where);  
    }

    public function new_role() {  
 
        //add the new user role
        add_role(
            'pending',
            'Pending',
            [
                'read' => true,
                'edit_posts' => false,  
		        'upload_files' => false,
                'delete_posts' => false
            ]
        );
    }

    public function delete_pending_user( $user_id ){

        if ( $this->user_has_role( $user_id, 'pending' ) ) {
            require_once(ABSPATH.'wp-admin/includes/user.php');
            wp_delete_user( $user_id );
        }
    }

    public function verrify_user_email() {

        if ( isset($_GET['ut_verify_email']) /*&& wp_verify_nonce( $_GET['_wpnonce'], 'natures_key')*/ ) {

            $verify_email_val = str_replace(' ', '+', $_GET['ut_verify_email']);
            $verify_email = ut_decrypt($verify_email_val);
            $user_pending = get_user_by( 'email', $verify_email );

            if ( $this->user_has_role( $user_pending->ID, 'pending' ) ) {
                $user_id = wp_update_user( [
                    'ID' => $user_pending->ID,
                    'role' => 'subscriber',
                ] );

                if ( ! is_wp_error( $user_id ) ) {
                    get_template_part('template-parts/verify-email-alert');
                    wp_clear_scheduled_hook( 'delete_pending_user_event', [ $user_id ] );
                }
            }
        }
    }

    public function user_has_role( $user_id, $role_name ) {

        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;

        return in_array($role_name, $user_roles);
    }

    public function user_registration_step_2OLD() {  

        check_ajax_referer( 'user_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        $this->check_register_user_fields( $form );

        $first_name = sanitize_text_field( $form['register_name'] );
        $last_name = sanitize_text_field( $form['register_surname'] );
        $phone = sanitize_text_field( $form['register_phone'] );
        $register_id = sanitize_text_field( $form['register_id'] );
        $sponsor_id = sanitize_text_field( $form['register_sponsor_id'] );
        // get user email of api
        $user_email = $this->check_sponsor_id( $register_id, $sponsor_id );
        // $user_email = sanitize_email( $form['register_email'] );
        $user_pending = get_user_by( 'email', $user_email );

        $parts = explode( "@", $user_email );
        $user_login = $parts[0];

        if ( $user_pending ) {
            $user_id = wp_update_user( [
                 'ID' 		 => $user_pending->ID,
                'user_login' => $user_login,
                'user_pass'  => wp_generate_password(),
                'user_email' => $user_email,
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'role'       => 'pending',
            ] );
        } else {
            $user_id = wp_insert_user( [
                'user_login' => $user_login,
                'user_pass'  => wp_generate_password(),
                'user_email' => $user_email,
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'role'       => 'pending',
            ] );
        }

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array(
                'message' => $user_id->get_error_message(),
            ) );
        } 
        // save user data
        update_user_meta( $user_id, 'billing_first_name', $first_name );
        update_user_meta( $user_id, 'billing_last_name', $last_name );
        update_user_meta( $user_id, 'shipping_first_name', $first_name );
        update_user_meta( $user_id, 'shipping_last_name', $last_name );
        update_user_meta( $user_id, 'billing_phone', $phone );
        update_user_meta( $user_id, 'register_id', $register_id );
        update_user_meta( $user_id, 'sponsor_id', $sponsor_id );

        wp_send_json_success( [
            'user_email' => $user_email,
        ] );
    }
    
    public function user_registration_step_2() {  

        check_ajax_referer( 'user_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        $this->check_register_user_fields( $form );

        $register_id = sanitize_text_field( $form['register_id'] );
        $sponsor_id = sanitize_text_field( $form['register_sponsor_id'] );
        // get user email of api
        $user_email = $this->check_sponsor_id( $register_id, $sponsor_id );

        wp_send_json_success( [
            'user_email' => $user_email,
        ] );
    }

    public function user_registration_step_3() {  

        check_ajax_referer( 'user_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( empty( $form['register_email'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_email',
                'message' => __('Field Email is required', 'natures-sunshine'),
            ) );
        }  
        
        if ( empty( $form['register_password'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_password',
                'message' => __('Field Password is required', 'natures-sunshine'),
            ) );
        }  

        $first_name = sanitize_text_field( $form['register_name'] );
        $last_name = sanitize_text_field( $form['register_surname'] );
        $phone = sanitize_text_field( $form['register_phone'] );
        $register_id = sanitize_text_field( $form['register_id'] );
        $sponsor_id = sanitize_text_field( $form['register_sponsor_id'] );
        $user_email = sanitize_email( $form['register_email'] );
        $turn_on = get_field('turn_on_allowed_domains', 'option');
        $parts = explode( "@", $user_email );
        $user_login = $parts[0];
        $user_password = $form['register_password'];

        if ( $turn_on ) {
            $allowed_domains_str = get_field('list_allowed_domains', 'option');
            $allowed_domains_str = preg_replace('/\s+/', '', $allowed_domains_str);
            $allowed_domains_arr = explode (",", $allowed_domains_str); 
            // Split out the local and domain parts.
	        list( $local, $domain ) = explode( '@', $user_email, 2 );

            if ( in_array($domain, $allowed_domains_arr) ) {
                $message = __( 'Mail with this domain is prohibited!', 'natures-sunshine' );
                wp_send_json_error( array(
                    'message' => $message,
                ) );
            }
        }

        if ( $this->password_strength( $user_password, $user_login ) !== 4 ) {
            wp_send_json_error( array(
                'name_field' => 'register_password',
                'message' => __('Weak password', 'natures-sunshine'),
            ) );
        }

        $user_id = wp_insert_user( [
            'user_login' => $user_login,
            'user_pass'  => $user_password,
            'user_email' => $user_email,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'role'       => 'pending',
        ] );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array(
                'message' => $user_id->get_error_message(),
            ) );
        } 
        // save user data
        update_user_meta( $user_id, 'billing_first_name', $first_name );
        update_user_meta( $user_id, 'billing_last_name', $last_name );
        update_user_meta( $user_id, 'shipping_first_name', $first_name );
        update_user_meta( $user_id, 'shipping_last_name', $last_name );
        update_user_meta( $user_id, 'billing_phone', $phone );
        update_user_meta( $user_id, 'register_id', $register_id );
        update_user_meta( $user_id, 'sponsor_id', $sponsor_id );

        // Start cron job for delete user after 3 days
        wp_schedule_single_event( time() + 259200, 'delete_pending_user_event', [ $user_id ] ); // 3 days in seconds
        // Authorization new user
        $credentials = [
            'user_login' => $user_email,
            'user_password' => $user_password,
        ];
        $user = wp_signon( $credentials, false );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( array(
                'message' => $user->get_error_message(),
            ) );
        }

        $full_name = $user->last_name . ' ' . $user->first_name;
        $verify_url = home_url() . '?ut_verify_email=' . ut_encrypt($user_email);
        $nonce = wp_create_nonce('natures_key');
        $nonce_url = add_query_arg( ['_wpnonce' => $nonce], $verify_url );
        $data = [
            'full_name' => $full_name, 
            'user_email' => $user_email, 
            'user_password' => $user_password,
            'nonce_url' => $nonce_url,
        ];
        // $this->send_email_to_new_user( $first_name, $user_email, $user_password );
        ut_help()->esputnik->send_new_user_message( $data );
        
        if ( ! empty( $form['redirect_url'] ) ) {
            $redirect = sanitize_text_field( $form['redirect_url'] );
        } else {
            $redirect = ut_get_permalik_by_template('template-user.php');
        }

        wp_send_json_success( [
            'redirect_url' => $redirect,
        ] );
    }
    
    public function user_registration_step_3OLD() {  

        check_ajax_referer( 'user_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( empty( $form['register_email'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_email',
                'message' => __('Field Email is required', 'natures-sunshine'),
            ) );
        }  
        
        if ( empty( $form['register_password'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_password',
                'message' => __('Field Password is required', 'natures-sunshine'),
            ) );
        }  

        $user_email = sanitize_email( $form['register_email'] );
        $turn_on = get_field('turn_on_allowed_domains', 'option');

        if ( $turn_on ) {
            $allowed_domains_str = get_field('list_allowed_domains', 'option');
            $allowed_domains_str = preg_replace('/\s+/', '', $allowed_domains_str);
            $allowed_domains_arr = explode (",", $allowed_domains_str); 
            // Split out the local and domain parts.
	        list( $local, $domain ) = explode( '@', $user_email, 2 );

            if ( in_array($domain, $allowed_domains_arr) ) {
                $message = __( 'Mail with this domain is prohibited!', 'natures-sunshine' );
                wp_send_json_error( array(
                    'message' => $message,
                ) );
            }
        }

        $user_pending = get_user_by( 'email', $user_email );

        if ( ! $user_pending ) {
            wp_send_json_error( array(
                'message' => __( 'You have not completed the first 2 steps', 'natures-sunshine' ),
            ) );
        }

        $parts = explode( "@", $user_email );
        $user_login = $parts[0];
        $user_password = $form['register_password'];

        if ( $this->password_strength( $user_password, $user_login ) !== 2 ) {
            wp_send_json_error( array(
                'name_field' => 'register_password',
                'message' => __('Weak password', 'natures-sunshine'),
            ) );
        }

        $user_id = wp_update_user( [
            'ID' 		 => $user_pending->ID,
            'user_login' => $user_login,
            'user_pass'  => $user_password,
            'user_email' => $user_email,
            // 'role'       => 'subscriber',
        ] );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array(
                'message' => $user_id->get_error_message(),
            ) );
        } 
        // Start cron job for delete user after 3 days
        wp_schedule_single_event( time() + 259200, 'delete_pending_user_event', [ $user_id ] ); // 3 days in seconds
        // Authorization new user
        $credentials = [
            'user_login' => $user_email,
            'user_password' => $user_password,
        ];
        $user = wp_signon( $credentials, false );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( array(
                'message' => $user->get_error_message(),
            ) );
        }

        $middle_name = get_user_meta( $user_id, 'patronymic', true );
        $full_name = $user->last_name . ' ' . $user->first_name . ' ' . $middle_name;
        $verify_url = home_url() . '?ut_verify_email=' . ut_encrypt($user_email);
        $nonce = wp_create_nonce('natures_key');
        $nonce_url = add_query_arg( ['_wpnonce' => $nonce], $verify_url );

        $data = [
            'full_name' => $full_name, 
            'user_email' => $user_email, 
            'user_password' => $user_password,
            'nonce_url' => $nonce_url,
        ];
        // $this->send_email_to_new_user( $first_name, $user_email, $user_password );
        ut_help()->esputnik->send_new_user_message( $data );
        
        if ( ! empty( $form['redirect_url'] ) ) {
            $redirect = sanitize_text_field( $form['redirect_url'] );
        } else {
            $redirect = ut_get_permalik_by_template('template-user.php');
        }

        wp_send_json_success( [
            'redirect_url' => $redirect,
        ] );
    }

    public function check_register_user_fields( $form ) {

        if ( empty( $form['register_name'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_name',
                'message' => __('Field First name is required', 'natures-sunshine'),
            ) );
        }   
               
        if ( empty( $form['register_surname'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_surname',
                'message' => __('Field Last name is required', 'natures-sunshine'),
            ) );
        } 
        
        if ( empty( $form['register_phone'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_phone',
                'message' => __('Field Phone is required', 'natures-sunshine'),
            ) );
        }  

        if ( empty( $form['register_id'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_id',
                'message' => __('Field ID is required', 'natures-sunshine'),
            ) );
        }     
               
        if ( empty( $form['register_sponsor_id'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_sponsor_id',
                'message' => __('Field Sponsor ID is required', 'natures-sunshine'),
            ) );
        }   
    }
    
    public function check_sponsor_id( $register_id, $sponsor_id ) {

        global $wpdb;    

        $result_api = false;
        $sponsors_table = $wpdb->prefix . 'sponsor_ids';
        $query = "SELECT * 
                  FROM $sponsors_table
                  WHERE reg_no = $register_id AND sponsor_regno = $sponsor_id
                 ";
        $result = $wpdb->get_results(
            $query, 
            'ARRAY_A'
        );
        $domain = get_option('enable_user_reg_api', 'option');
        
        if ( ! $domain ) {
            return true;
        }
        
        if ( empty($result) ) {
            // request to API
            $login = get_option('login_user_reg_api', 'option');
            $pass = get_option('pass_user_reg_api', 'option');
            $url = "https://nsp25.ru/_scripts/inthenetname.php?id=". $sponsor_id ."&idmy=". $register_id ."&whoask=". $login ."&f=1&pass=". $pass;
            $result_api = $this->get_request_curl( $url );
            $result_api = json_decode( $result_api, true );

            if ( isset($result_api['exists']) && $result_api['exists'] == 1 ) {
                $email = $result_api['m'];
            } else {
                wp_send_json_error( array(
                    'message' => __('Check your ID and Sponsor ID', 'natures-sunshine'),
                ) );
            }

        } else {
            $email = $result[0]['email'];
        }

        return $email;
    }

    public function user_auth() {  
        
        check_ajax_referer( 'user_nonce', 'ajax_nonce' );

        if ( isset($_POST['page']) && $_POST['page'] == 'checkout' ) {
            $form = [
                'redirect_url' => null,
                'login_name' => $_POST['login_name'],
                'login_password' => $_POST['login_password'],
            ];
        } else {
            parse_str( $_POST['form'], $form );
        }

        if ( empty( $form['login_name'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'login_name',
                'message' => __('Field Email or ID is required', 'natures-sunshine'),
            ) );
        }   
               
        if ( empty( $form['login_password'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'login_password',
                'message' => __('Field Password is required', 'natures-sunshine'),
            ) );
        }   
           
        $email_or_id = sanitize_text_field( $form['login_name'] );
        $password = $form['login_password'];

        if ( is_email($email_or_id) ) {
            $user = get_user_by( 'email', $email_or_id );
        } else {
            $users = get_users( [
                'meta_key' => 'register_id',
                'meta_value' => $email_or_id
            ] );

            if ( ! isset($users[0]) ) {
                wp_send_json_error( [
                    'message' => __('User with this ID does not exist', 'natures-sunshine'),
                ] );
            }
            $user = $users[0];
        }

        if ( ! $user ) {
            wp_send_json_error( [
                'message' => __('User with this Email does not exist', 'natures-sunshine'),
            ] );
        }

        $credentials = [
            'user_login' => $user->user_login,
            'user_password' => $password,
        ];  
        $user = wp_signon( $credentials, false ); 

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( [
                'message' => $user->get_error_message(),
            ] );
        }

        if ( ! empty( $form['redirect_url'] ) ) {
            $redirect = sanitize_text_field( $form['redirect_url'] );
        } else {
            $redirect = ut_get_permalik_by_template('template-user.php');
        }

        $redirect = $this->redirect_via_interface_language( $redirect, $user->ID );

        wp_send_json_success( [
            'redirect_url' => $redirect,
        ] );
    }

    public function redirect_via_interface_language( $redirect_url, $user_id ) {

        $interface_language = get_user_meta($user_id, 'interface_language', true);
        $interface_language = ( $interface_language ) ? $interface_language : 'uk';
        $current_lang = apply_filters('wpml_current_language', NULL);
        $default_lang = apply_filters('wpml_default_language', NULL);
        $remove_domain = home_url();
        $slug = str_replace($remove_domain, "", $redirect_url);

        if ( preg_match("/^\//", $slug) == 1 ) {
            $slug = $interface_language . $slug;
        } else {
            $slug = $interface_language . '/' . $slug;
        }
        $result = site_url('/') .  $slug;

        return $result;
    }

    public function reset_password() {

        check_ajax_referer( 'user_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( empty( $form['lostpassword_name'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'lostpassword_name',
                'message' => __('Field Email or ID is required', 'natures-sunshine'),
            ) );
        }   

        $email_or_id = sanitize_text_field( $form['login_name'] );

        if ( is_email($email_or_id) ) {
            $user = get_user_by( 'email', $email_or_id );
        } else {
            $users = get_users( [
                'meta_key' => 'register_id',
                'meta_value' => $email_or_id
            ] );

            if ( ! isset($users[0]) ) {
                wp_send_json_error( [
                    'message' => __('User with this ID does not exist', 'natures-sunshine'),
                ] );
            }
            $user = $users[0];
        }

        if ( ! $user ) {
            wp_send_json_error( [
                'message' => __('User with this Email does not exist', 'natures-sunshine'),
            ] );
        } 

        $middle_name = get_user_meta( $user->ID, 'patronymic', true );
        $full_name = $user->last_name . ' ' . $user->first_name . ' ' . $middle_name;
        $generate_url = wp_nonce_url( ut_get_permalik_by_template('template-restorepassword.php') . '?login=' . $user->user_login, 'ut_reset_pass' );
        $data = [
            'full_name' => $full_name,
            'user_login' => $user->user_login,
            'generate_url' => $generate_url,
            'user_email' => $user->user_email,
        ];
        // $this->send_email_reset_password( $user );
        ut_help()->esputnik->send_reset_password_message( $data );
        wp_send_json_success();
    }

    public function new_password() {

        check_ajax_referer( 'user_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( empty( $form['user_login'] ) ) {
            wp_send_json_error( [
                'name_field' => 'user_login',
                'message' => __('Error, try later', 'natures-sunshine'),
            ] );
        } 

        if ( empty( $form['new_password'] ) ) {
            wp_send_json_error( [
                'name_field' => 'new_password',
                'message' => __('Field New password is required', 'natures-sunshine'),
            ] );
        } 

        if ( empty( $form['repeat_password'] ) ) {
            wp_send_json_error( [
                'name_field' => 'repeat_password',
                'message' => __('Field Repeat password is required', 'natures-sunshine'),
            ] );
        } 
        
        if ( $form['new_password'] != $form['repeat_password'] ) {
            wp_send_json_error( [
                'name_field' => 'repeat_password',
                'message' => __('Passwords do not match', 'natures-sunshine'),
            ] );
        } 

        $user_login = sanitize_text_field( $form['user_login'] );
        $user = get_user_by( 'login', $user_login );
     
        if ( ! $user ) {
            wp_send_json_error( [
                'message' => __( 'This user does not exist!', 'natures-sunshine' ),
            ] );
        }   

        reset_password( $user, $form['new_password'] );

        wp_send_json_success( [
            'message' => __( 'Your password has been successfully updated!', 'natures-sunshine' ),
        ] );
    }

    public function send_email_to_new_user( $first_name, $user_email, $user_password ) {

        $message  = file_get_contents( dirname(__FILE__) . '/email templates/header.php' );
        $message .= '<p style="margin-bottom:10px;">' . __('Hi' , 'natures-sunshine') . ' ' . $first_name . ',</p>';
        $message .= '<p style="margin-bottom:10px;">' . __('Thank you for registering.' , 'natures-sunshine') . '</p>';
        $message .= '<p>' . __('From now on, you can log in to your account using your email' , 'natures-sunshine') . ': <span style="color: #1F8599;">' . $user_email . '</span>' . __(' and password' , 'natures-sunshine') . ': <span style="color: #1F8599;">' . $user_password . '</span>'.'</p>';
        $message .= file_get_contents( dirname(__FILE__) . '/email templates/footer.php' );

        add_filter( 'wp_mail_content_type', [ $this, 'set_html_content_type' ] );

        wp_mail( $user_email, sprintf( __('[%s] Your username and password', 'natures-sunshine'), get_option('blogname') ), $message );

        remove_filter( 'wp_mail_content_type', [ $this, 'set_html_content_type' ] );
    }

    public function send_email_reset_password( $user ) {

        $blogname = wp_specialchars_decode( get_option('blogname'), ENT_QUOTES );
        $title = sprintf( __('[%s] Password recovery', 'natures-sunshine'), $blogname );
        $generate_url = wp_nonce_url( ut_get_permalik_by_template('template-restorepassword.php') . '?login=' . $user->user_login, 'ut_reset_pass' );

        $message  = file_get_contents( dirname(__FILE__).'/email templates/header.php' );
        $message .= '<p style="margin-bottom:10px;">'.__('Hi', 'natures-sunshine') . ' ' . $user->first_name . ',</p>';
        $message .= '<p style="margin-bottom:10px;">'.__('Someone requested a password reset for the following account', 'natures-sunshine').'</p>';
        $message .= '<p style="margin-bottom:10px;">'.__('Username', 'natures-sunshine').': <span style="color: #1F8599;">' . $user->user_login . '</span></p>';
        $message .= '<p style="margin-bottom:10px;">'.__('To reset your password, click on the following link:', 'natures-sunshine').'</p>';
        $message .= '<p style="margin-bottom:10px;"><a href="' . $generate_url . '">' . $generate_url . '</a></p>';
        $message .= '<p>'.__('If it was not you, just ignore this email and nothing will happen.', 'natures-sunshine').'</p>';
        $message .= file_get_contents( dirname(__FILE__).'/email templates/footer.php' );
     
        add_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );

        if ( $message && ! wp_mail( $user->user_email, $title, $message ) ) {
            wp_send_json_error( [
                'message' => __('Failed to send email. Possible reason: your host may turn off the feature mail()...', 'natures-sunshine'),
            ] );
        }

        remove_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );

        return true;
    }

    public function set_html_content_type() {

        return 'text/html';
    }

    /**
     * Check for password strength - based on JS function in pre-3.7 WP core: /wp-admin/js/password-strength-meter.js
     *
     * @since   1.0
     * @param   string $i   The password.
     * @param   string $f   The user's username.
     * @return  integer 1 = very weak; 2 = weak; 3 = medium; 4 = strong
     */
    function password_strength( $i, $f ) {

        $h = 1;
        $e = 2;
        $b = 3;
        $a = 4;
        $d = 0;
        $g = null;
        $c = null;

        if ( strlen( $i ) < 4 ) {
            return $h;
        }

        if ( strtolower( $i ) === strtolower( $f ) ) {
            return $e;
        }

        if ( preg_match( '/[0-9]/', $i ) ) {
            $d += 10;
        }

        if ( preg_match( '/[a-z]/', $i ) ) {
            $d += 26;
        }

        if ( preg_match( '/[A-Z]/', $i ) ) {
            $d += 26;
        }

        if ( preg_match( '/[^a-zA-Z0-9]/', $i ) ) {
            $d += 31;
        }
        $g = log( pow( $d, strlen( $i ) ) );
        $c = $g / log( 2 );

        if ( $c < 40 ) {
            return $e;
        }

        if ( $c < 56 ) {
            return $b;
        }

        return $a;
    }

    public function get_request_curl( $url ) {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        
        return $resp;
    }

} 