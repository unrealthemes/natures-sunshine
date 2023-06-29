<?php 
if ( ! is_user_logged_in() ) {
    return;
}

$query = new WP_Query( [ 
    'author' =>  get_current_user_id(),
    'post_type' => 'address', 
    'post_status' => 'publish', 
    'posts_per_page' => -1, 
] ); 
$option_txt = ut_num_decline( 
    $query->found_posts, 
    [ 
        __('saved addresses 1', 'natures-sunshine'),
        __('saved addresses 2', 'natures-sunshine'),
        __('saved addresses 3', 'natures-sunshine')
    ] 
); 
?>

<?php if ( $query->have_posts() ) : ?>

    <div class="form-checkout__row no-bg">
        <div class="ut-loader"></div>
        <div class="form-checkout__select form-checkout__addresses select">
            <select id="saved_addresses">
                <option value="" hidden><?php echo $option_txt; ?></option>

                <?php 
                while ( $query->have_posts() ) : $query->the_post(); 
                    $types = get_the_terms( get_the_ID(), 'type' );

                    if ( isset($types[0]) ) {
                        $type = $types[0];
                        $type_name = $type->slug;
                    } else {
                        $type_name = '';
                    }

                    $city = get_field('city_addr');
                    $code_city = get_field('code_city_addr');
                    $street = get_field('street_addr');
                    $house = get_field('house_addr');
                    $flat = get_field('flat_addr');
                    $method = get_field('method_addr');
                    $first_name = get_field('first_name_addr');
                    $last_name = get_field('last_name_addr');
                    $middle_name = get_field('middle_name_addr');
                    $email = get_field('email_addr');
                    $phone = get_field('phone_addr');
                    $address = ut_help()->address->generate_address( $city, $street, $house, $flat );
                    $default = get_field('default_addr');
                    $selected = ( $default ) ? 'selected' : '';
                    $nova_poshta_city_code = get_post_meta( get_the_ID(), 'nova_poshta_city_code_addr', true );
                    $justin_city_code = get_post_meta( get_the_ID(), 'justin_city_code_addr', true );
                    $ukr_city_code = get_post_meta( get_the_ID(), 'ukr_city_code_addr', true );
                    $main_warehouse_code = get_post_meta( get_the_ID(), 'main_warehouse_code', true );
                ?>

                    <option value="<?php echo get_the_ID(); ?>"
                            data-id="<?php echo get_the_ID(); ?>"
                            data-type="<?php echo $type_name; ?>"
                            data-city="<?php echo esc_attr($city); ?>"
                            data-np-city-code="<?php echo esc_attr($nova_poshta_city_code); ?>"
                            data-justin-city-code="<?php echo esc_attr($justin_city_code); ?>"
                            data-ukr-city-code="<?php echo esc_attr($ukr_city_code); ?>"
                            data-warehouse-code="<?php echo esc_attr($main_warehouse_code); ?>"
                            data-code-city="<?php echo esc_attr($code_city); ?>"
                            data-street="<?php echo esc_attr($street); ?>"
                            data-house="<?php echo esc_attr($house); ?>"
                            data-flat="<?php echo esc_attr($flat); ?>"
                            data-method="<?php echo esc_attr($method); ?>"
                            data-first_name="<?php echo esc_attr($first_name); ?>"
                            data-last_name="<?php echo esc_attr($last_name); ?>"
                            data-middle_name="<?php echo esc_attr($middle_name); ?>"
                            data-email="<?php echo esc_attr($email); ?>"
                            data-phone="<?php echo esc_attr($phone); ?>"
                            <?php echo $selected; ?>>
                        <?php echo $address; ?>
                    </option>

                <?php endwhile; ?>
                
            </select>
        </div>
    </div>

<?php 
endif; 
wp_reset_postdata(); 
?>