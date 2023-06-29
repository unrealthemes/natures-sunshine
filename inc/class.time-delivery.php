<?php

class UT_Time_Delivery {

    private static $_instance = null;

    public $count = 14;
    public $key_date  = 'td_date_';
    public $key_11_15 = 'td_11_15_';
    public $key_13_18 = 'td_13_18_';
    public $key_15_20 = 'td_15_20_';
    public $key_11_19 = 'td_11_19_';

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        add_action( 'admin_menu', [$this, 'settings_page'] );
        $this->update_table();

        $this->update_date_table();
        add_action( 'update_date', [$this, 'handler_update_date'] );

        add_action( 'rest_api_init', function () {

            register_rest_route( 'wc/v3', 'get_time_delivery', [
                'methods' => 'GET',
                'callback' => [$this, 'get_time_delivery'],
                // 'args' => [
                //     'key' => [
                //         'type' => 'string', // значение параметра должно быть строкой
                //         'required' => true,     // параметр обязательный
                //     ],
                // ],
                // 'permission_callback' => 'is_user_logged_in',
            ]);
            
            register_rest_route( 'wc/v3', 'set_time_delivery', [
                'methods' => 'POST',
                'callback' => [$this, 'set_time_delivery'],
                // 'args' => [
                //     'key' => [
                //         'type' => 'string', 
                //         'required' => true,     
                //     ],
                //     'val' => [
                //         'required' => true,     
                //     ],
                // ],
                // 'permission_callback' => 'is_user_logged_in',
            ]);

        });

    }

    public function get_time_delivery( $data ) {

        if ( ! $data->get_param('key') ) {
            return false;
        }

        return get_option( $data->get_param('key') );
    }
    
    public function set_time_delivery( $data ) {

        if ( ! $data->get_param('key') ) {
            return false;
        }
        
        // if ( ! $data->get_param('val') ) {
        //     return false;
        // }

        if ( $data->get_param('key') == 'woocommerce_fondy_settings' || $data->get_param('key') == 'woocommerce_bacs_accounts' ) {
            $val = json_decode( $data->get_param('val'), true );
        } else {
            $val = $data->get_param('val');
        }

        return update_option( $data->get_param('key'), $val );
    }

    public function settings_page() {
        add_menu_page( 
            'Час доставки', 
            'Час доставки', 
            'edit_posts', 
            'time_delivery', 
            [$this, 'time_delivery_display'], 
            '', 
            124
        );
    }

    public function time_delivery_display() {

        get_template_part( 
            'template-parts/admin/time-delivery', 
            'table', 
            [
                'count' => $this->count,
                'key_date' => $this->key_date,
                'key_11_15' => $this->key_11_15,
                'key_13_18' => $this->key_13_18,
                'key_15_20' => $this->key_15_20,
                'key_11_19' => $this->key_11_19,
            ] 
        );
    }

    public function update_table() {

        if ( isset($_POST["save_time_delivery"]) ) { 

            $redirect_url = $_POST['redirect_url'];

            for ($i = 1; $i <= $this->count; $i++) {

                if ( isset($_POST[ $this->key_date . $i ]) && ! empty($_POST[ $this->key_date . $i ]) ) {    
                    update_option($this->key_date . $i, $_POST[ $this->key_date . $i ]);
                } else {
                    update_option($this->key_date . $i, '0');
                }

                if ( isset($_POST[ $this->key_11_15 . $i ]) && ! empty($_POST[ $this->key_11_15 . $i ]) ) {  
                    update_option($this->key_11_15 . $i, $_POST[ $this->key_11_15 . $i ]);
                } else {
                    update_option($this->key_11_15 . $i, '0');
                }

                if ( isset($_POST[ $this->key_13_18 . $i ]) && ! empty($_POST[ $this->key_13_18 . $i ]) ) {  
                    update_option($this->key_13_18 . $i, $_POST[ $this->key_13_18 . $i ]);
                } else {
                    update_option($this->key_13_18 . $i, '0');
                }

                if ( isset($_POST[ $this->key_15_20 . $i ]) && ! empty($_POST[ $this->key_15_20 . $i ]) ) {  
                    update_option($this->key_15_20 . $i, $_POST[ $this->key_15_20 . $i ]);
                } else {
                    update_option($this->key_15_20 . $i, '0');
                }
                
                if ( isset($_POST[ $this->key_11_19 . $i ]) && ! empty($_POST[ $this->key_11_19 . $i ]) ) {  
                    update_option($this->key_11_19 . $i, $_POST[ $this->key_11_19 . $i ]);
                } else {
                    update_option($this->key_11_19 . $i, '0');
                }
            }

            $status_msg = __("Delivery date updated", "natures-sunshine");
            wp_redirect( $redirect_url . '&msg=' . $status_msg );
            exit;
        } 
    }

    public function generate_dates() {

        $date_cols = [];
        $result_dates = [];
        $dates = [];
        $show_days = (int)get_field('show_days_ch', 'options'); // max 14
        $show_days_val = $show_days - 1;

        for ($c = 0; $c <= $show_days_val; $c++) {

            if ($c == 0) {
                $dates[] = date('Y-m-d');
            } else {
                $val = ' +'. $c .' day';
                $dates[] = date('Y-m-d', strtotime($val));
            }
        }

        for ($i = 1; $i <= $this->count; $i++) {
            $date_val = get_option($this->key_date . $i);

            if ( in_array($date_val, $dates) ) {
                $date_cols[] = $i;
            }
        }
        
        foreach ( $date_cols as $date_col ) {
            $key_date_val = get_option($this->key_date . $date_col);
            $result_dates[ $key_date_val ]['11:00-15:00'] = get_option($this->key_11_15 . $date_col);
            $result_dates[ $key_date_val ]['13:00-18:00'] = get_option($this->key_13_18 . $date_col);
            $result_dates[ $key_date_val ]['15:00-20:00'] = get_option($this->key_15_20 . $date_col);
            $result_dates[ $key_date_val ]['11:00-19:00'] = get_option($this->key_11_19 . $date_col);
        }

        return $result_dates;
    }

    public function generate_time_options( $dates ) {

        $times = [];
        $options_html = '<option value="" data-dates="">' . esc_html(__('Select time', 'natures-sunshine')) . '</option>';
        // generate times array with unique dates
        foreach ( (array)$dates as $date => $times_arr ) {
            foreach ( (array)$times_arr as $time => $show ) {
                if ( $show ) {
                    $times[ $time ][] = $date;
                }
            }
        }

        // generate delivery time select options
        foreach ( $times as $time => $dates ) {
            $data_attrs = '';
            foreach ( (array)$dates as $date ) {
                $data_attrs .= $date . '|';
            }
            $selected = (WC()->session->get('delivery_time') == $time) ? 'selected' : '';
            $disabled = (WC()->session->get('delivery_time') == $time) ? '' : 'disabled';

            $options_html .= '<option value="' . esc_attr($time) . '" data-dates="' . esc_attr($data_attrs) . '" ' . $selected . ' ' . $disabled . '>';
                $options_html .= esc_html($time); 
            $options_html .= '</option>';

        }

        return $options_html;
    }

    public function remove_empty_days( $days ) {

        $result = [];
        foreach ( $days as $day => $times ) {
            foreach ( $times as $time => $count_orders ) {
                if ( $count_orders ) {
                    $result[ $day ][ $time ] = $count_orders;
                }
            }
        }

        return $result;
    }

    public function update_date_table() {

        if ( ! wp_next_scheduled( 'update_date' ) ) {
            wp_schedule_event( time(), 'daily', 'update_date' );
        }
    }

    public function handler_update_date() {
        
        for ($i = 0; $i < $this->count; $i++) {
            $k = $i + 1;
            $date = date('Y-m-d', strtotime(' +' . $i . ' day'));
            update_option($this->key_date . $k, $date);
        }
    }
} 