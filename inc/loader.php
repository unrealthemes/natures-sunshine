<?php

/**
 * Get instance of helper
 */
function ut_help() {
    return UT_Theme_Helper::getInstance();
}

/**
 * Main class of all tehe,e settings
 */
class UT_Theme_Helper {

    private static $_instance = null;

    public $blog;
    public $breadcrumbs;
    public $home;
    public $autocomplete;
    public $product;
    public $cart;
    public $wishlist;
    public $compare;
    public $contacts;
    public $product_filter;
    public $user;
    public $sponsors;
    public $account;
    public $checkout;
    public $address;
    public $order;
    public $ukr_poshta_api;
    public $esputnik;
    public $time_delivery;
    public $diapasons_pagination;

    private function __construct() {
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
    }

    protected function __clone() {

    }

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Load files, plugins, add hooks and filters and do all magic
     */
    function init() {

        // load needed files
        $this->import();
        $this->load_classes();
        $this->register_hooks();

  	    return $this;
    }

    function load_classes() {

        $this->blog = UT_Blog::getInstance();
        $this->breadcrumbs = UT_Breadcrumbs::getInstance();
        $this->home = UT_Home::getInstance();
        $this->autocomplete = UT_Autocomplete::getInstance();
        $this->product = UT_Product::getInstance();
        $this->cart = UT_Cart::getInstance();
        $this->wishlist = UT_Wishlist::getInstance();
        $this->compare = UT_Compare::getInstance();
        $this->contacts = UT_Contacts::getInstance();
        $this->product_filter = UT_Product_Filter::getInstance();
        $this->user = UT_User::getInstance();
        $this->sponsors = UT_Sponsors::getInstance();
        $this->account = UT_Account::getInstance();
        $this->checkout = UT_Checkout::getInstance();
        $this->address = UT_Address::getInstance();
        $this->order = UT_Order::getInstance();
        $this->ukr_poshta_api = UT_Ukr_Poshta_Api::getInstance();
        $this->esputnik = UT_Esputnik::getInstance();
        $this->time_delivery = UT_Time_Delivery::getInstance();
        $this->diapasons_pagination = UT_Diapasons_Pagination::getInstance();
    }

    /**
     * Register all needed hooks
     */
    public function register_hooks() {

        add_action( 'wp_enqueue_scripts', [ $this, 'load_scripts_n_styles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_scripts_n_styles' ] );
        add_action( 'after_setup_theme',  [ $this, 'register_menus' ] );
        add_action( 'after_setup_theme',  [ $this, 'add_theme_support' ] );
        //register new shipping method
        add_action('woocommerce_shipping_init', [$this, 'init_shipping_methods']);
        add_filter('woocommerce_shipping_methods', [$this, 'add_shipping_methods']);
    }

    function register_menus() {

        register_nav_menus(
			[
				'header-menu-top' => esc_html__('Header (сверху)', 'natures-sunshine'),
				'header-menu-main' => esc_html__('Header (основное)', 'natures-sunshine'),
				'footer-menu-left' => esc_html__('Footer (слева)', 'natures-sunshine'),
				'footer-menu-center' => esc_html__('Footer (по центру)', 'natures-sunshine'),
				'footer-menu-right' => esc_html__('Footer (справа)', 'natures-sunshine'),
            ]
		);
    }

    public function add_theme_support() {

        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]
        );
        add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
        add_theme_support( 'woocommerce' );
    }

    function load_scripts_n_styles() {

        $wp_scripts = wp_scripts();

        // ========================================= CSS ========================================= //

        wp_dequeue_style('global-styles');
	    wp_enqueue_style('styles', DIST_URI . '/css/style.min.css', array(), '1.0.1');
	    wp_enqueue_style('fontawesome-v6', 'https://use.fontawesome.com/releases/v6.1.1/css/all.css?ver=6.1.1', array(), time());
        wp_enqueue_style( 'ut-style', get_stylesheet_uri() );

        // ========================================= JS ========================================= //

        ////////////////////////////////////// Bundle
        wp_deregister_script('jquery-core');
        wp_register_script('jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', false, null, true);
        wp_deregister_script('jquery');
        wp_register_script('jquery', false, array('jquery-core'), null, true);

        wp_enqueue_script('jquery-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', [], null, true);
        wp_enqueue_script('jquery-mask', THEME_URI . '/js/inputmask.js', [], null, true);

        wp_enqueue_script('scripts', DIST_URI . '/js/main.bundle.js', [], null, true);
        wp_localize_script('scripts', 'theme', [
            'ajax_url' => admin_url('admin-ajax.php')
        ]);
        ////////////////////////////////////// Autocomplete
        wp_enqueue_script('ut-autocomplete-search', THEME_URI . '/js/autocomplete.js', [ 'jquery', 'jquery-ui-autocomplete' ], null, true );
        wp_localize_script('ut-autocomplete-search', 'autocomplete_search', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('autocomplete_search_nonce'),
            'theme_uri' => THEME_URI,
        ]);
		wp_enqueue_style(
			'jquery-ui-css',
			'//ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-autocomplete']->ver . '/themes/smoothness/jquery-ui.css',
			false,
			null,
			false
		);
        ////////////////////////////////////// Cart
        wp_enqueue_script('ut-cart', THEME_URI . '/js/cart.js', ['jquery'], null, true );
        wp_localize_script('ut-cart', 'ut_cart', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('cart_nonce'),
            'theme_uri' => THEME_URI,
        ]);
        ////////////////////////////////////// Comment
        if ( is_product() ) {
            wp_enqueue_script('ut-comment', THEME_URI . '/js/comment.js', ['jquery'], null, true );
            wp_localize_script('ut-comment', 'ut_comment', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('comment_nonce'),
                'theme_uri' => THEME_URI,
            ]);
        }
        ////////////////////////////////////// Product
        wp_enqueue_script('ut-product', THEME_URI . '/js/product.js', ['jquery'], null, true );
        wp_localize_script('ut-product', 'ut_product', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('product_nonce'),
            'theme_uri' => THEME_URI,
            'dist_uri' => DIST_URI,
            'cart_txt' => __('Go to cart short', 'natures-sunshine'),
        ]);
        ////////////////////////////////////// Wishlist
        wp_enqueue_script('ut-wishlist', THEME_URI . '/js/wishlist.js', ['jquery'], null, true );
        wp_localize_script('ut-wishlist', 'ut_wishlist', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('wishlist_nonce'),
            'theme_uri' => THEME_URI,
            'icon_heart_filled_url' => DIST_URI . '/images/sprite/svg-sprite.svg#heart-filled',
            'icon_heart_url' => DIST_URI . '/images/sprite/svg-sprite.svg#heart',
        ]);
        ////////////////////////////////////// Compare
        wp_enqueue_script('ut-compare', THEME_URI . '/js/compare.js', ['jquery'], null, true );
        wp_localize_script('ut-compare', 'ut_compare', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('compare_nonce'),
            'theme_uri' => THEME_URI,
            'compare_url' => ut_get_permalik_by_template('template-compare.php'),
        ]);
        ////////////////////////////////////// Contacts
        if ( is_page_template('template-contacts.php') ) {
            wp_enqueue_script('ut-contacts', THEME_URI . '/js/contacts.js', ['jquery'], null, true );
            wp_localize_script('ut-contacts', 'ut_contacts', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('contacts_nonce'),
                'theme_uri' => THEME_URI,
            ]);
        }
        ////////////////////////////////////// Filter
        if ( is_page_template('template-catalog.php') || is_shop() || is_product_category()) {
            wp_enqueue_script('ut-filter', THEME_URI . '/js/product-filter.js', ['jquery'], null, true );
            wp_localize_script('ut-filter', 'ut_filter', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('filter_nonce'),
                'theme_uri' => THEME_URI,
            ]);
        }
        ////////////////////////////////////// Front-page
        if ( is_front_page() ) {
            wp_enqueue_script('ut-front-page', THEME_URI . '/js/front-page.js', ['jquery'], null, true );
            wp_localize_script('ut-front-page', 'ut_front_page', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('front_page_nonce'),
                'theme_uri' => THEME_URI,
            ]);
        }
        ////////////////////////////////////// User
        wp_enqueue_script('ut-user', THEME_URI . '/js/user.js', ['jquery'], null, true );
        wp_localize_script('ut-user', 'ut_user', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('user_nonce'),
            'theme_uri' => THEME_URI,
        ]);
        ////////////////////////////////////// Account
        if (
            is_page_template('template-account.php') ||
            is_page_template('template-account-public.php') ||
            is_page_template('template-account-addresses.php') ||
            is_page_template('template-account-orders.php') ||
            is_page_template('template-account-newsletters.php')
        ) {
            wp_enqueue_script('ut-account', THEME_URI . '/js/account.js', ['jquery'], null, true );
            wp_localize_script('ut-account', 'ut_account', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('account_nonce'),
                'theme_uri' => THEME_URI,
                'dist_uri' => DIST_URI,
                'error_type' => __('File must be JPG, GIF or PNG', 'natures-sunshine'),
                'error_size' => __('File must be less than 2MB', 'natures-sunshine'),
            ]);
        }
        ////////////////////////////////////// Blog
        if ( is_category() ) {
            wp_enqueue_script('ut-blog', THEME_URI . '/js/blog.js', ['jquery'], null, true );
            wp_localize_script('ut-blog', 'ut_blog', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('blog_nonce'),
                'theme_uri' => THEME_URI,
            ]);
        }
        ////////////////////////////////////// Checkout
        if ( is_page_template('template-account-addresses.php') || is_checkout() ) {
            wp_enqueue_style('ut-select2', THEME_URI . '/js/select2/select2.min.css', ['styles-css'], null );
            wp_enqueue_script('ut-select2', THEME_URI . '/js/select2/select2.min.js', ['jquery'], null, true );
            wp_enqueue_script('ut-checkout', THEME_URI . '/js/checkout.js', ['jquery'], null, true );
            wp_localize_script('ut-checkout', 'ut_checkout', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('checkout_nonce'),
                'theme_uri' => THEME_URI,
                'required_txt' => __('Field is required', 'natures-sunshine'),
                'email_txt' => __('Email address is not correct', 'natures-sunshine'),
            ]);
        }
        ////////////////////////////////////// Address
        if ( is_page_template('template-account-addresses.php') || is_checkout() ) {
            wp_enqueue_script('ut-address', THEME_URI . '/js/address.js', ['jquery'], null, true );
            wp_localize_script('ut-address', 'ut_address', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('address_nonce'),
                'theme_uri' => THEME_URI,
                'choose_txt' => __('Choose', 'natures-sunshine'),
            ]);
        }
        ////////////////////////////////////// Order
        if ( is_page_template('template-account-orders.php') ) {
            wp_enqueue_script('ut-order', THEME_URI . '/js/order.js', ['jquery'], null, true );
            wp_localize_script('ut-order', 'ut_order', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('order_nonce'),
                'theme_uri' => THEME_URI,
            ]);
        }
        //////////////////////////////////////
    }

    public function load_admin_scripts_n_styles() {

        wp_enqueue_script('jquery-mask', THEME_URI . '/js/inputmask.js', [], null, true);
        wp_enqueue_style( 'admin', THEME_URI . '/admin.css' );
        wp_enqueue_script( 'ut-admin', THEME_URI . '/js/admin.js' );
        wp_localize_script('ut-admin', 'ut_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('admin_nonce'),
            'theme_uri' => THEME_URI,
            'dist_uri' => DIST_URI,
            'error_type' => __('File must be JPG, GIF or PNG', 'natures-sunshine'),
            'error_size' => __('File must be less than 2MB', 'natures-sunshine'),
        ]);
    }

    public function import() {

        require get_template_directory() . '/app/vendor/autoload.php';
        require get_template_directory() . '/app/init.php';

        include_once 'helpers.php';
        include_once 'class.blog.php';
        include_once 'class.breadcrumbs.php';
        include_once 'class.home.php';
        include_once 'class.autocomplete.php';
        include_once 'class.product.php';
        include_once 'class.cart.php';
        include_once 'class.mega-menu.php';
        include_once 'class.comment.php';
        include_once 'class.wishlist.php';
        include_once 'class.compare.php';
        include_once 'class.contacts.php';
        include_once 'class.product-filter.php';
        include_once 'class.user.php';
        include_once 'class.sponsors.php';
        include_once 'class.account.php';
        include_once 'class.checkout.php';
        include_once 'class.address.php';
        include_once 'class.order.php';
        include_once 'class.ukrposhta.php';
        include_once 'class.esputnik.php';
        include_once 'class.time-delivery.php';
        include_once 'class.diapasons-pagination.php';
    }

    public function add_shipping_methods($methods) {

        $methods['ukrposhta_shippping'] = 'WC_UkrPoshta_Shipping_Method';
        $methods['nova_poshta_shipping_method'] = 'WC_NovaPoshta_Shipping_Method';
        $methods['nova_poshta_shipping_method_poshtomat'] = 'WC_NovaPoshta_Shipping_Method_Poshtomat';
        $methods['ut_free_shipping'] = 'WC_UT_Free_Shipping_Method';

        return $methods;
    }

    public function init_shipping_methods() {

        if ( ! class_exists('WC_UkrPoshta_Shipping_Method') ) {
            include_once 'WC_UkrPoshta_Shipping_Method.php';
        }

        if ( ! class_exists('WC_NovaPoshta_Shipping_Method') ) {
            include_once 'WC_NovaPoshta_Shipping_Method.php';
        }

        if ( ! class_exists('WC_NovaPoshta_Shipping_Method_Poshtomat') ) {
            include_once 'WC_NovaPoshta_Shipping_Method_Poshtomat.php';
        }

        if ( ! class_exists('WC_UT_Free_Shipping_Method') ) {
            include_once 'WC_UT_Free_Shipping_Method.php';
        }
    }

}
