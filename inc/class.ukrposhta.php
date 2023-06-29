<?php
ini_set('max_execution_time', 0);

if ( ! defined('ABSPATH')) {
	exit;
}

use Google\Cloud\Translate\V2\TranslateClient;

class UT_Ukr_Poshta_Api
{

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	public function __construct() {

		$this->api_key = get_option('ut_google_translate_api_key');
		$this->token = get_option('ukr_poshta_token'); // 'f9027fbb-cf33-3e11-84bb-5484491e2c94';
		add_action( 'admin_menu', [$this, 'settings_page'] );
        $this->translate_cities();
        $this->update_cities();
        $this->update_warehouses();
        $this->save_google_api_key();
        $this->save_ukrposhta_token();
        
	}

	public function settings_page() {
        add_menu_page( 
            __('UkrPoshta перевод', 'natures-sunshine'), 
            __('UkrPoshta перевод', 'natures-sunshine'), 
            'edit_posts', 
            'ukr_city_translations', 
            [$this, 'ukr_city_translations_display'], 
            '', 
            125
        );
    }

	public function ukr_city_translations_display() { 

        $total = $this->get_total_cities();
        $total_warehouses = $this->get_total_warehouses();
        $items_per_page = get_option( 'posts_per_page' );
        $page = isset( $_GET['cpage'] ) ? abs( (int)$_GET['cpage'] ) : 1;
        $total_page = ceil( $total / $items_per_page );
        $cities_data = $this->get_cities( $page, $items_per_page );
        $pagination_html = $this->get_pagination( $page, $total_page );
        get_template_part( 
            'template-parts/admin/ukrposhta-cities', 
            'table', 
            [
                'cities_data' => $cities_data,
                'pagination' => $pagination_html,
                'total_warehouses' => $total_warehouses,
                'total' => $total,
            ] 
        );
    }

    function save_google_api_key() {

        if ( isset($_POST['ukr_cities_api_key']) && ! empty($_POST['google_translate_api_key']) ) {
            $redirect_url = sanitize_url($_POST['redirect_url']);
            $api_key = sanitize_text_field($_POST['google_translate_api_key']);
            update_option( 'ut_google_translate_api_key', $api_key );

            wp_redirect($redirect_url);
            exit;
        }
    }
    
    function save_ukrposhta_token() {

        if ( isset($_POST['ukr_poshta_token_btn']) && ! empty($_POST['ukr_poshta_token']) ) {
            $redirect_url = sanitize_url($_POST['redirect_url']);
            $api_key = sanitize_text_field($_POST['ukr_poshta_token']);
            update_option( 'ukr_poshta_token', $api_key );

            wp_redirect($redirect_url);
            exit;
        }
    }

    function translate_cities() {

        if ( isset($_POST['ukr_cities_translate_start']) ) {

            global $wpdb; 
            $redirect_url = sanitize_url($_POST['redirect_url']);

            if ( empty($this->api_key) ) {
                $redirect_url = $redirect_url . '&message=Сохрание API ключ!';
                wp_redirect($redirect_url);
                exit;
            }

            $sql = "";
            $table = $wpdb->prefix . 'ukr_poshta_cities';
            $cities = $this->get_cities_for_translate();
            $translate = new TranslateClient( [ 'key' => $this->api_key ] );

            if ( $cities ) {
                foreach ( $cities as $city ) {

                    if ( $city['city_ru'] ) {
                        continue;
                    }

                    $id = $city['id'];
                    $city_ua = $city['city_ua'];
                    $result = $translate->translate(
                        $city_ua,
                        [
                            'source' => 'uk',
                            'target' => 'ru',
                        ]
                    );
                    $city_ru = $result['text'];
                    $wpdb->update( 
                        $table, 
                        [ 'city_ru' => $city_ru ], 
                        [ 'id' => $id ] 
                    );
                }
            }

            wp_redirect($redirect_url);
            exit;
        }
    }

    function update_cities() {

        if ( isset($_POST['ukr_cities_update_start']) ) {

            global $wpdb; 
            $city_ids_arr = [];
            $table = $wpdb->prefix . 'ukr_poshta_cities';
            $redirect_url = sanitize_url($_POST['redirect_url']);

            if ( empty($this->token) ) {
                $redirect_url = $redirect_url . '&message=Сохрание UkrPoshta Token!';
                wp_redirect($redirect_url);
                exit;
            }

            $city_ids_local = $this->get_city_ids_local();
            foreach ( $city_ids_local as $city_id_local ) {
                $city_ids_arr[] = $city_id_local['city_id'];
            }

            $regions = $this->getData($this->token, 'https://ukrposhta.ua/address-classifier-ws/get_regions_by_region_ua');
            foreach ($regions['Entries']['Entry'] ?? [] as $region) {
                $districts = $this->getData($this->token, 'https://ukrposhta.ua/address-classifier-ws/get_districts_by_region_id_and_district_ua?region_id=' . $region['REGION_ID']);
                foreach ($districts['Entries']['Entry'] ?? [] as $district) {
                    $cities = $this->getData($this->token, 'https://ukrposhta.ua/address-classifier-ws/get_city_by_region_id_and_district_id_and_city_ua?district_id=' .$district['DISTRICT_ID']);
                    foreach ($cities['Entries']['Entry'] ?? [] as $city) {
                        $city_arr = array_change_key_case($city, CASE_LOWER);
                        
                        if ( ! in_array($city_arr['city_id'], $city_ids_arr) ) {
                            $wpdb->insert( $table, $city_arr );
                        }
                    }
                }
            }

            wp_redirect($redirect_url);
            exit;
        }
    }

    function update_warehouses() {

        if ( isset($_POST['ukr_warehouses_update_start']) ) {

            global $wpdb; 
            $warehouse_ids_arr = [];
            $table = $wpdb->prefix . 'ukr_poshta_warehouses';
            $redirect_url = sanitize_url($_POST['redirect_url']);

            if ( empty($this->token) ) {
                $redirect_url = $redirect_url . '&message=Сохрание UkrPoshta Token!';
                wp_redirect($redirect_url);
                exit;
            }

            $warehouse_ids_local = $this->get_warehouse_ids_local();
            foreach ( $warehouse_ids_local as $warehouse_id_local ) {
                $warehouse_ids_arr[] = $warehouse_id_local['postcode'];
            }

            $regions = $this->getData($this->token, 'https://ukrposhta.ua/address-classifier-ws/get_regions_by_region_ua');
            foreach ($regions['Entries']['Entry'] ?? [] as $region) {
                $districts = $this->getData($this->token, 'https://ukrposhta.ua/address-classifier-ws/get_districts_by_region_id_and_district_ua?region_id=' . $region['REGION_ID']);
                foreach ($districts['Entries']['Entry'] ?? [] as $district) {
                    $cities = $this->getData($this->token, 'https://ukrposhta.ua/address-classifier-ws/get_city_by_region_id_and_district_id_and_city_ua?district_id=' .$district['DISTRICT_ID']);
                    foreach ($cities['Entries']['Entry'] ?? [] as $city) {
                        $branches = $this->getData($this->token, 'https://ukrposhta.ua/address-classifier-ws/get_postoffices_by_postcode_cityid_cityvpzid?city_id=' . $city['CITY_ID']);
                        foreach ($branches['Entries']['Entry'] ?? [] as $branch) {
                            $branch_arr = array_change_key_case($branch, CASE_LOWER);
        
                            if ( ! in_array($branch_arr['postcode'], $warehouse_ids_arr) ) {
                                $wpdb->insert( $table, $branch_arr );
                            }
                        }
                    }
                }
            }

            wp_redirect($redirect_url);
            exit;
        }
    }

    function get_city_ids_local() {

        global $wpdb;    

        $table = $wpdb->prefix . 'ukr_poshta_cities';
        $data = $wpdb->get_results("
                                    SELECT 
                                        city_id                                           
                                    FROM $table
                                ", 
                                'ARRAY_A'
                                );

        return $data;
    }
    
    function get_warehouse_ids_local() {

        global $wpdb;    

        $table = $wpdb->prefix . 'ukr_poshta_warehouses';
        $data = $wpdb->get_results("
                                    SELECT 
                                        postcode                                           
                                    FROM $table
                                ", 
                                'ARRAY_A'
                                );

        return $data;
    }
    
    function get_cities( $page, $items_per_page ) {

        global $wpdb;    

        $offset = ( $page * $items_per_page ) - $items_per_page;
        $table = $wpdb->prefix . 'ukr_poshta_cities';
        $data = $wpdb->get_results("
                                    SELECT 
                                        id,
                                        city_ua,
                                        city_ru                                            
                                    FROM $table
                                    LIMIT $offset, $items_per_page
                                ", 
                                'ARRAY_A'
                                );

        return $data;
    }
    
    function get_cities_for_translate() {

        global $wpdb;    

        $table = $wpdb->prefix . 'ukr_poshta_cities';
        $data = $wpdb->get_results("
                                    SELECT 
                                        id,
                                        city_ua,
                                        city_ru                                            
                                    FROM $table
                                    WHERE city_ru = '0' 
                                ", 
                                'ARRAY_A'
                                );

        return $data;
    }

    function get_total_cities() {

        global $wpdb;    

        $table = $wpdb->prefix . 'ukr_poshta_cities';
        $total_query = "SELECT COUNT(1) FROM $table";
        $result = $wpdb->get_var( $total_query );

        return $result;
    }
    
    function get_total_warehouses() {

        global $wpdb;    

        $table = $wpdb->prefix . 'ukr_poshta_warehouses';
        $total_query = "SELECT COUNT(1) FROM $table";
        $result = $wpdb->get_var( $total_query );

        return $result;
    }

    function get_pagination( $page, $total_page ) {

        $html = '';

        if ( $total_page > 1 ) :
            $html = '<div class="tablenav ">
                        <div class="tablenav-pages">
                            <!--<span class="displaying-num">' . __('Page', 'acf') . ' ' . $page . ' ' . __('of', 'sitepress') . ' ' . $total_page . '</span>-->' .
                            paginate_links( [
                                'base' => add_query_arg( 'cpage', '%#%' ),
                                'format' => '',
                                'prev_text' => __('&laquo;'),
                                'next_text' => __('&raquo;'),
                                'total' => $total_page,
                                'current' => $page
                            ] )
                    . '</div>
                    </div>';
        endif;

        return $html;
    }

	function getData($token, $url) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($result, true);
    }
}