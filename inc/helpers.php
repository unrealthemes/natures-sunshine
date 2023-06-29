<?php 

/**
 * Get name menu by location
 */
function ut_get_title_menu_by_location( $location ) {

    if ( empty( $location ) ) {
    	return false;
	}

    $locations = get_nav_menu_locations();

    if ( ! isset( $locations[ $location ] ) ) {
    	return false;
	}
    
    $menu_obj = get_term( $locations[ $location ], 'nav_menu' );

    return $menu_obj->name;
}

function ut_get_menu_id_by_location( $location ) {

    if ( empty( $location ) ) {
    	return false;
	}

    $locations = get_nav_menu_locations();

    if ( ! isset( $locations[ $location ] ) ) {
    	return false;
	}
    
    $menu_obj = get_term( $locations[ $location ], 'nav_menu' );

    return $menu_obj->term_id;
}

/**
 * Get permalink by template name
 */
function ut_get_permalik_by_template( $template ) {

	$result = '';

	if ( ! empty( $template ) ) {
		$pages = get_pages( [
		    'meta_key'   => '_wp_page_template',
		    'meta_value' => $template
		] );
		$template_id = $pages[0]->ID;
		$page = get_post( $template_id );
		$result = get_permalink( $page );
	}
	
	return $result;
}

/**
 * Get permalink by template name
 */

function ut_get_page_id_by_template( $template ) {

	$result = '';

	if ( ! empty( $template ) ) {
		$pages = get_pages( [
		    'meta_key'   => '_wp_page_template',
		    'meta_value' => $template
		] );
		$result = $pages[0]->ID;
	}
	
	return $result;
}

function ut_get_page_data_by_template( $template ) {

	$pages = get_pages( [
		'meta_key'   => '_wp_page_template',
		'meta_value' => $template
	] );

	if ( ! $pages ) {
		return false;
	}

	$template_id = $pages[0]->ID;

	return get_post( $template_id );
}

function ut_acf_json_save_point( $path ) {
	
    $path = get_stylesheet_directory() . '/acf-json';

    return $path; 
}
add_filter('acf/settings/save_json', 'ut_acf_json_save_point');
// add_filter('acf/settings/show_admin', '__return_false');

/**
 * Declension of a word after a number
 *
 *     // Call examples:
 *     ut_num_decline( $num, 'книга,книги,книг' )
 *     ut_num_decline( $num, 'book,books' )
 *     ut_num_decline( $num, [ 'книга','книги','книг' ] )
 *     ut_num_decline( $num, [ 'book','books' ] )
 *
 * @param int|string   $number       The number followed by the word. You can specify a number in HTML tags.
 * @param string|array $titles       Declension options or first word for a multiple of 1.
 * @param bool         $show_number  Specify 00 here when you do not need to display the number itself.
 *
 * @return string For example: 1 книга, 2 книги, 10 книг.
 *
 * @version 3.1
 */
function ut_num_decline( $number, $titles, $show_number = true ) {

	if( is_string( $titles ) ){
		$titles = preg_split( '/, */', $titles );
	}

	// когда указано 2 элемента
	if( empty( $titles[2] ) ){
		$titles[2] = $titles[1];
	}

	$cases = [ 2, 0, 1, 1, 1, 2 ];

	$intnum = abs( (int) strip_tags( $number ) );

	$title_index = ( $intnum % 100 > 4 && $intnum % 100 < 20 )
		? 2
		: $cases[ min( $intnum % 10, 5 ) ];

	return ( $show_number ? "$number " : '' ) . $titles[ $title_index ];
}

function ut_branding_login() { 

    $logo_id = get_field('logo_header', 'options');
    $logo_url = wp_get_attachment_url( $logo_id, 'full' );
    ?>
        <style>
            html body {
                background-color: #f3f4f8;
            }

			.login h1 a {
                background-image: url(<?php echo $logo_url; ?>) !important;
                width: 100% !important;
                background-position: center !important;
                background-size: contain !important;
            }

			#registerform,
			#loginform {
				border-radius: 20px;
				padding: 28px 32px 32px;
				border: none;
			}

			.login .privacy-policy-page-link,
            #backtoblog {
                display: none;
            } 

			.wp-core-ui .button-primary {
				background: #00a88f !important;
				background-color: #00a88f !important;
				border: none !important;
				color: #fff !important;
			}

			.wp-core-ui .button-primary {
				border: none !important;
				border-radius: 8px !important;
				cursor: pointer !important;
				display: -webkit-inline-box !important;
				display: -ms-inline-flexbox !important;
				display: inline-flex !important;
				font-size: 15px !important;
				justify-content: center !important;
				line-height: 20px !important;
				padding: 10px 20px !important;
				text-decoration: none !important;
				-webkit-transition: all .3s !important;
				transition: all .3s !important;
				width: 100% !important;
            }

			.wp-core-ui .button-primary.focus, 
			.wp-core-ui .button-primary:focus {
				box-shadow: none !important;
			}

			.login .forgetmenot {
        	    margin-bottom: 14px !important;
            }

			.login form .input, 
            .login input[type=password], 
            .login input[type=text] {
                border: 1px solid #e5e5e5;
				border-radius: 8px;
				color: #202020;
				display: block;
				font-size: 15px!important;
				line-height: 20px!important;
				outline: none!important;
				padding: 10px 16px!important;
				-webkit-transition: border-color .3s,background-color .3s,color .3s;
				transition: border-color .3s,background-color .3s,color .3s;
				width: 100%;
				height: 40px;
            }

			.login form .input:focus, 
            .login input[type=password]:focus, 
            .login input[type=text]:focus {
				border-color: rgba(32,32,32,.8);
				box-shadow: none;
			}

			.login label {
				-webkit-box-align: center;
				-ms-flex-align: center;
				align-items: center;
				color: #000;
				cursor: pointer;
				display: -webkit-inline-box;
				display: -ms-inline-flexbox;
				display: inline-flex;
				font-size: 15px;
				line-height: 20px;
				margin-bottom: 8px;
			}

			.login .button.wp-hide-pw:focus {
				color: rgba(32,32,32,.4);
			}

			.login .button.wp-hide-pw,
			.login .button.wp-hide-pw:focus {
				background: none!important;
				border: none !important;
				border-color: none !important;
				color: rgba(32,32,32,.4)!important;
				box-shadow: none!important;
				outline: none!important;
			}
        </style>
    <?php 
}
add_action( 'login_enqueue_scripts', 'ut_branding_login' );



function ut_custom_login_url( $url ) {
    return home_url();
}
add_filter( 'login_headerurl', 'ut_custom_login_url' );



function get_excerpt_parts($text) {

	// $length = strlen($text);
	// $half = (int) ($length / 2);
	
	// $part_1 = mb_substr($text, 0, $half);
	// $part_2 = mb_substr($text, $half);
	
	// $eol_1 = strrpos( $part_1, PHP_EOL, 0); 		// last  position from string start of first part
	// $eol_2 = $half + strpos( $part_2, PHP_EOL, 0 );	// first position from string start of second part
	
	// $shift_1 = $half - $eol_1; // char shift size till middle point of first block
	// $shift_2 = $eol_2 - $half; // char shift size till middle point of last block
		
	// if ($shift_1 < $shift_2) { // PHP_EOL is closer to firts block, so larger size of block saved in second block
	// 	$part_1 = mb_substr($text, 0, $shift_1);
	// 	$part_2 = mb_substr($text, $shift_1);
	// } else {	
	// 	$part_1 = mb_substr($text, 0, $eol_1);
	// 	$part_2 = mb_substr($text, $eol_1);
	// }

	$desc = $text . " ";
	$l = intval(strlen($desc)/2 + strlen($desc) * 0.02);
	$desc = preg_replace("[\r\n]"," ",$desc);
	preg_match_all("/(.{1,$l})[ \n\r\t]+/", $desc, $desc_array);

	$part_1  = $desc_array[1][0];
	$part_2  = $desc_array[1][1];
	// $part_3  = $desc_array[1][1] ?? '';
	// $part_2 .= ' ' . $part_3;

	return [
		'first' => $part_1,
		'last' => $part_2,
	];
}



function ut_encrypt( $plaintext ) {

	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($plaintext, $cipher, ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, ENCRYPTION_KEY, $as_binary=true);
	$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

	return $ciphertext;
}


function ut_decrypt( $ciphertext ) {

	$c = base64_decode($ciphertext);
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$plaintext = openssl_decrypt($ciphertext_raw, $cipher, ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, ENCRYPTION_KEY, $as_binary=true);

	if ( hash_equals($hmac, $calcmac) ) {
		return $plaintext;
	}

	return false;
}


function ut_lang_url( $language ) {

	if ( is_archive() ) :

		if ( $language['code'] == 'uk' ) :
			$request_uri = substr( $_SERVER['REQUEST_URI'], 3 );
		else :
			$request_uri = '/ru' . $_SERVER['REQUEST_URI'];
		endif;

		return site_url() . $request_uri;

	else :
		return $language['url'];
	endif;
}


function ut_filter_lang_url($url) {

	$current_code = apply_filters( 'wpml_current_language', null );

	if ( $current_code == 'uk' ) { // remove domain and add domain/ru
		return  str_replace( home_url(), site_url() . '/ru', $url );
	} else { // remove domain/ru and add domain
		return  str_replace( home_url(), site_url() . '/', $url );
	}
}