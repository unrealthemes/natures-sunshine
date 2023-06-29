<?php

class UT_Esputnik {

    private static $_instance = null;

    private $api_url;

    private $user_name;

    private $password;

    private $esputnik_category_keys;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        $this->API_URL = get_field('api_url_esputnik', 'options');
        $this->user_name = get_field('user_name_esputnik', 'options');
        $this->password = get_field('password_esputnik', 'options');
        $this->esputnik_category_keys = [
            'news_ru' => get_field('key_news_ru_esputnik', 'options'),
            'news_uk' => get_field('key_news_uk_esputnik', 'options'),
            'stock_ru' => get_field('key_stock_ru_esputnik', 'options'),
            'stock_uk' => get_field('key_stock_uk_esputnik', 'options'),
        ];

        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_change_esputnik_category_switch_lang', [$this, 'change_esputnik_category_switch_lang'] );
            add_action( 'wp_ajax_nopriv_change_esputnik_category_switch_lang', [$this, 'change_esputnik_category_switch_lang'] );
        }
    }

    public function change_esputnik_category_switch_lang() {
        
        // check_ajax_referer( 'user_nonce', 'ajax_nonce' );
        $current_lang = apply_filters( 'wpml_current_language', NULL );
        $new_current_lang = $_POST['current_lang'];
        $redirect_url = $_POST['url'];
        $user_id = get_current_user_id();

        $profile_news = get_user_meta( $user_id, 'profile_news', true );
        $profile_promo = get_user_meta( $user_id, 'profile_promo', true );

        $subscriber_news_id = get_user_meta( $user_id, 'profile_news_id', true );
        $subscriber_promo_id = get_user_meta( $user_id, 'profile_promo_id', true );

        // move to another language group NEWS
        if ( $profile_news && $subscriber_news_id ) { 
            // unsubscribe
            $group_data_news = ['subscriptions' => [$this->esputnik_category_keys[ 'news_' . $current_lang ]]];
            $response = ut_help()->esputnik->unsubscribe( $subscriber_news_id, $group_data_news );
            // update_user_meta( $user_id, 'profile_news_id', null );
            // subscribe
            $group_subs_data_news = ['subscriptions' => [$this->esputnik_category_keys[ 'news_' . $new_current_lang ]]];
            $response = ut_help()->esputnik->subscribe( $group_subs_data_news );
            update_user_meta( $user_id, 'profile_news_id', $response['id'] );
        }

        // move to another language group STOCK
        if ( $profile_promo && $subscriber_promo_id ) { 
            // unsubscribe
            $group_data_stock = ['subscriptions' => [$this->esputnik_category_keys[ 'stock_' . $current_lang ]]];
            $response = ut_help()->esputnik->unsubscribe( $subscriber_promo_id, $group_data_stock );
            // update_user_meta( $user_id, 'profile_promo_id', null );
            // subscribe
            $group_subs_data_stock = [
                'subscriptions' => [
                    $this->esputnik_category_keys[ 'stock_' . $new_current_lang ]
                ]
            ];
            $response = ut_help()->esputnik->subscribe( $group_subs_data_stock );
            update_user_meta( $user_id, 'profile_promo_id', $response['id'] );
        }

        wp_send_json_success([
            'redirect_url' => $redirect_url,
        ]);
    }

    public function subscribe( $group_data ) {

        $current_lang = apply_filters( 'wpml_current_language', NULL );
        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        $tzstring = get_user_meta( $user_id, 'timezone_string', true );
        $client = new \GuzzleHttp\Client();
        $body = [
            'contact' => [
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'channels' => [ 
                    'type' => 'email',
                    'value' => $user->user_email,
                ],
                'languageCode' => $current_lang,
                'timeZone' => $tzstring,
                // 'groups' => $group_data,
            ]
        ];

        $response = $client->request(
            'POST', 
            $this->API_URL . '/contact/subscribe', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
            $categories = $this->get_categories_subscriber( $result['id'] );
            $result_categories = $this->preapre_categories( $categories, $group_data['subscriptions'][0], 'add' );
            $this->add_category_for_subscriber( $result['id'], $result_categories );
            return $result;
        }

        return false;
    }

    public function unsubscribe( $subscriber_id, $group_data ) {

        $categories = $this->get_categories_subscriber( $subscriber_id );
        $result_categories = $this->preapre_categories( $categories, $group_data['subscriptions'][0], 'remove' );
        $response = $this->add_category_for_subscriber( $subscriber_id, $result_categories );

        return $response;
    }

    public function add_category_for_subscriber( $subscriber_id, $categories ) {

        $client = new \GuzzleHttp\Client();
        $body['subscriptions'] = $categories;
        $response = $client->request(
            'PUT', 
            $this->API_URL . '/contact/' . $subscriber_id . '/subscriptions', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
            return $result;
        }

        return false;
    }

    public function get_categories_subscriber( $subscriber_id ) {

        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'GET', 
            $this->API_URL . '/contact/' . $subscriber_id . '/subscriptions', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
            return $result;
        }

        return false;
    }

    public function preapre_categories( $subscriber_categories, $select_category, $type ) {

        $categories = [];

        if ( $subscriber_categories && ! isset($subscriber_categories['key']) ) {
            foreach ( (array)$subscriber_categories as $subscriber_category ) { 
                array_push( $categories, $subscriber_category['key'] ); 
            }
        } else if ( $subscriber_categories && isset($subscriber_categories['key']) ) {
            array_push( $categories, $subscriber_categories['key'] ); 
        }

        if ( ! in_array( $select_category, $categories ) && $type == 'add' ) {
            array_push( $categories, $select_category );
        } else if ( $type == 'remove' ) {
            $key = array_search( $select_category, $categories );
            unset( $categories[ $key ] );
        }

        if ( count($categories) == 1 ) {
            $categories = implode("", $categories);
        }

        return $categories;
    }
    
    public function send_new_user_message( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_new_user_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('New User Registration', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }
    
    public function send_reset_password_message( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_reset_password_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('Reset Password', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }
    
    public function send_delete_account_message( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_delete_account_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('Delete Account', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }
    
    public function send_change_order_status_message( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_change_order_status_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('Delete Account', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }
    
    public function send_reply_to_comment_message( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_reply_to_comment_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('Delete Account', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }

    public function send_users_data_confirmation( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_users_data_confirmation_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('Data confirmation', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }

    public function send_user_data_is_confirmed( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_user_data_is_confirmed_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('Data confirmed', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }

    public function send_back_in_stock_notifier( $data ) {

        $client = new \GuzzleHttp\Client();
        $email_id = get_field('id_back_in_stock_notifier_esputnik', 'options');
        $body = [
            'recipients' => [
                'locator' => $data['user_email'],
                'jsonParam' => json_encode($data),
            ],
            'fromName' => __('Back in stock notifier', 'natures-sunshine'),
	        'email' => true
        ];
        $response = $client->request(
            'POST', 
            $this->API_URL . '/message/' . $email_id . '/smartsend', 
            [
                'auth' => [
                    $this->user_name, 
                    $this->password
                ],
                'body' => json_encode($body),
                'headers' => [
                    'accept' => 'application/json; charset=UTF-8',
                    'content-type' => 'application/json',
                ],
            ]
        );

        if ( $response->getStatusCode() == 200 ) {
            $result = json_decode($response->getBody(),true);
        } else {
            $result = $response->getBody();
        }

        return $result;
    }

} 