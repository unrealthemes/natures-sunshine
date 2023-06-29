<?php

class UT_Contacts {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_send_form', [$this, 'send_form'] );
            add_action( 'wp_ajax_nopriv_send_form', [$this, 'send_form'] );
            
            add_action( 'wp_ajax_delete_submitted_emails', [$this, 'delete_submitted_emails'] );
            add_action( 'wp_ajax_nopriv_delete_submitted_emails', [$this, 'delete_submitted_emails'] );
        }

        add_action( 'admin_menu', [$this, 'settings_page'] );
    }

    public function send_form() {

        check_ajax_referer( 'contacts_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( ! $form['contacts_recipient_email'] ) {
            wp_send_json_error( [
                'message' => __( 'Field Recipient Email is required', 'natures-sunshine' ),
            ] );
        }
        
        if ( ! $form['contacts_name'] ) {
            wp_send_json_error( [
                'message' => __( 'Field Name is required', 'natures-sunshine' ),
            ] );
        }
        
        if ( ! $form['contacts_phone'] ) {
            wp_send_json_error( [
                'message' => __( 'Field Phone is required', 'natures-sunshine' ),
            ] );
        }
        
        if ( ! $form['contacts_subject'] ) {
            wp_send_json_error( [
                'message' => __( 'Field Subject is required', 'natures-sunshine' ),
            ] );
        }

        $message  = '<p style="margin-bottom:10px;">' . __('Name:', 'natures-sunshine') . ' <b>' . $form['contacts_name'] . '</b></p>';
        $message .= '<p style="margin-bottom:10px;">' . __('Phone:', 'natures-sunshine') . ' <b>' . $form['contacts_phone'] . '</b></p>';
        $message .= '<p style="margin-bottom:10px;">' . __('Topic:', 'natures-sunshine') . ' <b>' . $form['contacts_subject'] . '</b></p>';

        if ( $form['contacts_message'] ) {
            $message .= '<p style="margin-bottom:10px;">' . __('Message:', 'natures-sunshine') . ' <b>' . $form['contacts_message'] . '</b></p>';
        }

        add_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );
        $result = wp_mail( $form['contacts_recipient_email'], sprintf( __('[%s] Feedback', 'natures-sunshine'), get_option('blogname') ), $message );
        remove_filter( 'wp_mail_content_type', [$this, 'set_html_content_type'] );

        $this->save_form_data( $form, $result );

        wp_send_json_success( [
            'message' => __( 'Message sent successfully', 'natures-sunshine' ),
        ] );
    }

    public function save_form_data( $data, $sending_mail ) {

        global $wpdb;
        $table = $wpdb->prefix . 'submitted_form_data';
        $data= [
            'name' => sanitize_text_field($data['contacts_name']), 
            'phone' => sanitize_text_field($data['contacts_phone']),
            'subject' => sanitize_text_field($data['contacts_subject']), 
            'comment' => sanitize_textarea_field($data['contacts_message']), 
            'recipient_email' => sanitize_email($data['contacts_recipient_email']),  
            'status_email' => $sending_mail,  
        ];

        $wpdb->insert( $table, $data );

    }

    public function set_html_content_type() {

        return 'text/html';
    }
    
    public function settings_page() {
        add_menu_page( 
            __('Submitted form data', 'natures-sunshine'), 
            __('Submitted form data', 'natures-sunshine'), 
            'edit_posts', 
            'submitted_form_data', 
            [$this, 'submitted_form_data_display'], 
            '', 
            124
        );
    }

    public function submitted_form_data_display() {

        global $wpdb;    

        $table = $wpdb->prefix . 'submitted_form_data';
        $users_data = $wpdb->get_results("
                                            SELECT 
                                                *                                    
                                            FROM $table
                                            ORDER BY date DESC
                                         ", 
                                         'ARRAY_A'
                                        );

        get_template_part( 
            'template-parts/admin/submitted-form-data', 
            'table', 
            [
                'users_data' => $users_data,
            ] 
        );
    }

    public function delete_submitted_emails() {
        check_ajax_referer( 'admin_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        global $wpdb;    
        $table = $wpdb->prefix . 'submitted_form_data';
        foreach ( (array)$form['email'] as $email_id ) {
            $wpdb->delete( $table, [ 'id' => $email_id ] );
        }

        wp_send_json_success();
    }

} 