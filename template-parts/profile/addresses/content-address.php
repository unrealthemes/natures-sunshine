<?php 
$type = $args['type'];
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
$checked = ( $default ) ? 'checked' : '';
$active = ( $default ) ? 'active' : '';
$nova_poshta_city_code = get_post_meta( get_the_ID(), 'nova_poshta_city_code_addr', true );
// $justin_city_code = get_post_meta( get_the_ID(), 'justin_city_code_addr', true );
$ukr_city_code = get_post_meta( get_the_ID(), 'ukr_city_code_addr', true );
$main_warehouse_code = get_post_meta( get_the_ID(), 'main_warehouse_code', true );
?>

<div class="profile-places__item address <?php echo $active; ?>"
     data-id="<?php echo get_the_ID(); ?>"
     data-type="<?php echo $type; ?>"
     data-city="<?php echo esc_attr($city); ?>"
     data-np-city-code="<?php echo esc_attr($nova_poshta_city_code); ?>"
     data-justin-city-code="<?php //echo esc_attr($justin_city_code); ?>"
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
     data-phone="<?php echo esc_attr($phone); ?>">
    <div class="address__top">
        <?php the_title('<h1 class="address__top-title">', '</h1>'); ?>
        <div class="navbar navbar--small address__top-controls">
            <span class="navbar__button">
                <svg width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#dots-menu'; ?>"></use>
                </svg>
            </span>
            <div class="navbar__menu">
                <a href="#" class="navbar__link js-edit-address" data-fancybox data-src="#add-address" >
                    <?php _e('Edit', 'natures-sunshine'); ?>
                </a>
                <a href="#" class="navbar__link js-delete-address" data-id="<?php echo get_the_ID(); ?>" data-type="<?php echo $type; ?>">
                    <?php _e('Delete', 'natures-sunshine'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="address__name text bold">
        <?php echo $address; ?>
    </div>
    <div class="address__current">
        <input class="address__current-input default" 
               type="radio" 
               name="default" 
               id="delivery-<?php echo get_the_ID(); ?>" 
               <?php echo $checked; ?>>
        <label class="address__current-label" for="delivery-<?php echo get_the_ID(); ?>">
            <span class="address__current-content"><?php _e('Use as default', 'natures-sunshine'); ?></span>
        </label>
    </div>
</div>