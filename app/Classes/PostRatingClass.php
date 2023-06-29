<?php

namespace App\Classes;

class PostRatingClass
{
    /**
     *
     */
    public function __construct()
    {
        $this->startSession();
        
        add_action('wp_enqueue_scripts', [$this, 'setInitScript']);
        add_action('wp_enqueue_scripts', [$this, 'setLocalizeNonce']);
        add_action('wp_ajax_set_rating', [$this, 'handlerSetRating']);
        add_action('wp_ajax_nopriv_set_rating', [$this, 'handlerSetRating']);
        add_action('wp_ajax_get_rating', [$this, 'handlerGetRating']);
        add_action('wp_ajax_nopriv_get_rating', [$this, 'handlerGetRating']);
    }
    
    /**
     * @return void
     */
    public function handlerSetRating(): void
    {
        if ( ! $this->validationSetHandler()) {
            wp_send_json_error();
        }
        
        $rating = $this->setRating($_POST['post_id'], $_POST['rating']);
        
        $_SESSION['rating'] = [ $_POST['post_id'] => $rating];
        
        wp_send_json_success(['rating' => $rating]);
    }
    
    /**
     * @return void
     */
    public function handlerGetRating(): void
    {
        if ( ! $this->validationGetHandler()) {
            wp_send_json_error();
        }
        
        wp_send_json_success([
            'rating' => self::getRating($_POST['post_id'])
        ]);
    }
    
    /**
     * @return bool
     */
    public function validationSetHandler(): bool
    {
        if ($this->checkNonce() &&
            isset($_POST['rating']) &&
            $_POST['rating'] >= 0 &&
            $_POST['rating'] <= 5 &&
            isset($_POST['post_id']) &&
            ! is_null(get_post($_POST['post_id'])) &&
            $this->userCanRate()
        ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function validationGetHandler(): bool
    {
        if (isset($_POST['post_id']) &&
            ! is_null(get_post($_POST['post_id']))
        ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param int $post_id
     * @param int $userRating
     *
     * @return int
     */
    public function setRating(int $post_id, int $userRating): int
    {
        $value   = (float)get_post_meta($post_id, 'rating_value', true);
        $counter = (int)get_post_meta($post_id, 'rating_counter', true);
        
        $value   += $userRating;
        $counter += 1;
        
        $rating = round($value / $counter);
        
        update_post_meta($post_id, 'rating', $rating);
        update_post_meta($post_id, 'rating_value', $value);
        update_post_meta($post_id, 'rating_counter', $counter);
        
        return $rating;
    }
    
    /**
     * @param $post_id
     *
     * @return int
     */
    public static function getRating($post_id): int
    {
        return (int)get_post_meta($post_id, 'rating', true);
    }
    
    /**
     * @return bool
     */
    public function userCanRate(): bool
    {
        return ! isset($_SESSION['rating'][$_POST['post_id']]);
    }
    
    /**
     * @return void
     */
    public function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * @return bool
     */
    public function checkNonce(): bool
    {
        return wp_verify_nonce($_POST['rating_nonce'], 'rating_nonce');
    }
    
    /**
     * @return void
     */
    public function setLocalizeNonce(): void
    {
        wp_localize_script('rating-js', 'rating', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id'  => get_the_ID(),
            'nonce'    => wp_create_nonce('rating_nonce'),
        ));
    }
    
    /**
     * @return void
     */
    public function setInitScript()
    {
        wp_enqueue_script('rating-js', get_template_directory_uri() . '/assets/custom/rating.js', ['jquery']);
    }
}