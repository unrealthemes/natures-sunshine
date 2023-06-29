<?php

class UT_Sponsors {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'admin_menu', [$this, 'settings_page'] );
        $this->update_table();
    }

    public function settings_page() {
        add_menu_page( 
            'Sponsor IDs', 
            'Sponsor IDs', 
            'edit_posts', 
            'sponsors', 
            [$this, 'sponsors_display'], 
            '', 
            124
        );
    }

    public function sponsors_display() {

        global $wpdb;    

        $total = $this->get_total_sponsors();
        $items_per_page = get_option( 'posts_per_page' );
        $page = isset( $_GET['cpage'] ) ? abs( (int)$_GET['cpage'] ) : 1;
        $total_page = ceil( $total / $items_per_page );
        $pagination_html = $this->get_pagination( $page, $total_page );
        $sponsors = $this->get_sponsors( $page, $items_per_page );
        get_template_part( 
            'template-parts/admin/sponsors', 
            'table', 
            [
                'sponsors' => $sponsors,
                'pagination' => $pagination_html,
            ] 
        );
    }

    function get_sponsors( $page, $items_per_page ) {

        global $wpdb;   

        $offset = ( $page * $items_per_page ) - $items_per_page;
        $table = $wpdb->prefix . 'sponsor_ids';

        if ( isset($_GET['search']) && !empty($_GET['search']) ) {
            $search_txt = $_GET['search'];
            $data = $wpdb->get_results("
                    SELECT *                                           
                    FROM $table
                    WHERE reg_no LIKE '%$search_txt%'
                    OR sponsor_regno LIKE '%$search_txt%'
                    OR email LIKE '%$search_txt%'
                    LIMIT $offset, $items_per_page

                ", 
                'ARRAY_A'
            );
        } else {
            $data = $wpdb->get_results("
                SELECT *                                           
                FROM $table
                LIMIT $offset, $items_per_page
            ", 
            'ARRAY_A'
        );
        }

        return $data;
    }

    function get_total_sponsors() {

        global $wpdb;    

        $table = $wpdb->prefix . 'sponsor_ids';

        if ( isset($_GET['search']) && !empty($_GET['search']) ) {
            $search_txt = $_GET['search'];
            $total_query = "SELECT COUNT(1) FROM $table WHERE reg_no LIKE '%$search_txt%' OR sponsor_regno LIKE '%$search_txt%' OR email LIKE '%$search_txt%'";
        } else {
            $total_query = "SELECT COUNT(1) FROM $table";
        }

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

    public function update_table() {

        if ( isset($_POST["submit_csv_file"]) ) { 

            global $wpdb;
            $redirect_url = $_POST['redirect_url'];

            if ( empty($_FILES["sponsors_file"]["name"]) ) {
                $status_msg = __("Select your CSV file.", "natures-sunshine");
                wp_redirect( $redirect_url . '&msg=' . $status_msg );
                exit;
            }
            $sponsors_table = $wpdb->prefix . 'sponsor_ids';
            $file_name = basename($_FILES["sponsors_file"]["name"]);
            $target_file_path = /*$targetDir .*/$file_name;
            $file_type = pathinfo($target_file_path, PATHINFO_EXTENSION);
            // Allow certain file formats
            $allow_yypes = ['csv'];

            if (!in_array($file_type, $allow_yypes)) {
                $status_msg = __("Sorry, only CSV files are allowed to upload.", "natures-sunshine");
                wp_redirect( $redirect_url . '&msg=' . $status_msg );
                exit;
            }
            // Check the resource is valid
            if (($handle = fopen($_FILES["sponsors_file"]['tmp_name'], 'r')) === FALSE) { 
                $status_msg = __("File upload failed, please try again.", "natures-sunshine");
                wp_redirect( $redirect_url . '&msg=' . $status_msg );
                exit;
            }

            $delete = $wpdb->query("TRUNCATE TABLE $sponsors_table");
            // Check opening the file is OK!
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { 
                // Loop over the data using $i as index pointer
                for ($i = 0; $i < count($data); $i++) { 
                    $row = $data[$i];
                    $row_arr = explode(";", $row); 
                    $data= [
                        'reg_no' => $row_arr[0], 
                        'sponsor_regno' => $row_arr[1],
                        'fio_1c' => $row_arr[2], 
                        'fio_original' => $row_arr[3],
                        'fio' => $row_arr[4], 
                        'sponsor_checked_ok' => $row_arr[5], 
                        'email' => $row_arr[6],
                        'sponsor_error' => $row_arr[7], 
                        'main_phone' => $row_arr[8],
                    ];
                    $wpdb->insert( $sponsors_table, $data );
                }
            }
            fclose($handle);
            $status_msg = sprintf( __("The file %s has been uploaded successfully.", "natures-sunshine"), $file_name );
            wp_redirect( $redirect_url . '&msg=' . $status_msg );
            exit;
        } 
    }

} 