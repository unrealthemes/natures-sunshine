<?php 
$redirect_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$cities_data = $args['cities_data'];
$pagination_html = "";
?>

<div class="wrap">

    <h1><?php _e('UkrPoshta перевод', 'natures-sunshine'); ?></h1>

    <?php if ( isset($_GET['message']) && ! empty($_GET['message']) ) : ?>

        <div id="setting-error-settings_updated" class="notice notice-error settings-error"> 
            <p>
                <strong><?php echo $_GET['message']; ?></strong>
            </p>
        </div>

    <?php endif; ?>

    <form id="ukr_cities_api_key_form" action="" method="post">
        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">
        <h4 style="margin-bottom: 0; margin-left: 10px;">Google Translate API ключ</h4>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <input type="text" name="google_translate_api_key" value="<?php echo esc_attr(get_option('ut_google_translate_api_key')); ?>">
                    </th>
                    <td>
                        <input type="submit" name="ukr_cities_api_key" id="ukr_cities_api_key" class="button button-primary" value="Сохранить">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    
    <form id="ukr_cities_translate_form" action="" method="post">
        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        Автоматический перевод с Украинского на Русский язык <br>
                        <i>(переводит только те города которые без русского перевода)</i>
                    </th>
                    <td>
                        <input type="submit" name="ukr_cities_translate_start" id="ukr_cities_translate_start" class="button button-primary" value="Старт">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <div class="clear"></div>

    <form id="ukr_poshta_token_form" action="" method="post">
        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">
        <h4 style="margin-bottom: 0; margin-left: 10px;">UkrPoshta Token</h4>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <input type="text" name="ukr_poshta_token" value="<?php echo esc_attr(get_option('ukr_poshta_token')); ?>">
                    </th>
                    <td>
                        <input type="submit" name="ukr_poshta_token_btn" id="ukr_poshta_token_btn" class="button button-primary" value="Сохранить">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    
    <form id="ukr_cities_update_form" action="" method="post">
        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        Обновление городов <br>
                        API UkrPoshta <br>
                        <h4>Всего <?php echo $args['total']; ?></h4>
                    </th>
                    <td>
                        <input type="submit" name="ukr_cities_update_start" id="ukr_cities_update_start" class="button button-primary" value="Обновить">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    
    <form id="ukr_warehouses_update_form" action="" method="post">
        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        Обновление отделений <br>
                        API UkrPoshta <br>
                        <h4>Всего <?php echo $args['total_warehouses']; ?></h4>
                    </th>
                    <td>
                        <input type="submit" name="ukr_warehouses_update_start" id="ukr_warehouses_update_start" class="button button-primary" value="Обновить">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <div class="clear"></div>

    <table id="ukr_poshta_cities" class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th>ID</th>
                <th>City UA</th>
                <th>City RU</th>
            </tr>
        </thead>
        <tbody>

            <?php if ( $cities_data ) : ?>

                <?php foreach ( $cities_data as $key => $city_data ) : ?>

                    <tr>

                        <?php foreach ( $city_data as $key => $value ) : ?>

                            <td><?php echo $value; ?></td>

                        <?php endforeach; ?>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>
    </table>

    <?php echo $args['pagination']; ?>

</div>