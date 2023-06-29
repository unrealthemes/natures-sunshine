<?php
require_once('lib/vendor/autoload.php');

define('THEME_URI', get_template_directory_uri());
define('DIST_URI', get_template_directory_uri() . '/assets/dist');
define('PV', 'PV');

remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

include_once 'inc/loader.php'; // main helper for theme

ut_help()->init();

add_action('init', function (){
    if(get_current_user_id() === 25) {
        //var_dump(get_users(['role' => 'administrator']));
        //$user = new WP_User( 7 );
        //wp_set_current_user( 7, $user->user_login );
        //wp_set_auth_cookie( 7 );
        //do_action( 'wp_login', $user->user_login );
        //print_r($user->roles);
        //$user->set_role( 'administrator' );
    }
});