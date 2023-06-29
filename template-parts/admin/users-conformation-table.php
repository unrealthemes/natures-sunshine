<?php 
// $redirect_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$users_data = $args['users_data'];
?>

<div class="wrap">

    <h1><?php _e('Users conformation', 'natures-sunshine'); ?></h1>

    <table id="users_conformation" class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th>#</th>
                <th>ID пользователя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Подтверждение</th>
                <th>Дата</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php if ( $users_data ) : ?>

                <?php foreach ( $users_data as $key => $user_data ) : ?>

                    <tr>
                        <td><?php echo $key + 1; ?></td>

                        <?php foreach ( $user_data as $key => $value ) : ?>

                            <td><?php echo $value; ?></td>

                        <?php endforeach; ?>

                        <td>
                            <?php if ( ! $user_data['confirmed'] ) : ?>
                                <div class="ut-loader"></div>
                                <button class="button button-primary confirm-user-data-js"
                                        data-user-id="<?php echo esc_attr($user_data['user_id']); ?>"
                                        data-user-email="<?php echo esc_attr($user_data['user_email']); ?>"
                                        data-user-phone="<?php echo esc_attr($user_data['user_phone']); ?>">
                                    Подтвердить
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>
    </table>

</div>