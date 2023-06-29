<?php 
$redirect_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<div class="wrap">

    <h1>Час доставки</h1>

    <?php if ( isset($_GET['msg']) && !empty($_GET['msg']) ) : ?>

        <div id="message2" class="updated notice is-dismissible">
            <p><?php echo $_GET['msg']; ?></p>
        </div>

    <?php endif; ?>

    <p>Максимальна кількість замовлень по Києву на день:</p>

    <form id="time_delivery_form" action="" method="post">
        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">

        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th>Дата</th>

                    <?php for ($i = 1; $i <= $args['count']; $i++) : ?>

                        <th>
                            <input  type="text" 
                                    name="<?php echo esc_attr($args['key_date'] . $i); ?>"
                                    value="<?php echo esc_attr(get_option($args['key_date'] . $i)); ?>">
                        </th>

                    <?php endfor; ?>

                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>11-15</td>

                    <?php for ($i = 1; $i <= $args['count']; $i++) : ?>

                        <td>
                            <input  type="text" 
                                    name="<?php echo esc_attr($args['key_11_15'] . $i); ?>" 
                                    value="<?php echo esc_attr(get_option($args['key_11_15'] . $i)); ?>">
                        </td>

                    <?php endfor; ?>

                </tr>
                <tr>
                    <td>13-18</td>

                    <?php for ($i = 1; $i <= $args['count']; $i++) : ?>

                        <td>
                            <input  type="text" 
                                    name="<?php echo esc_attr($args['key_13_18'] . $i); ?>" 
                                    value="<?php echo esc_attr(get_option($args['key_13_18'] . $i)); ?>">
                        </td>

                    <?php endfor; ?>

                </tr>
                <tr>
                    <td>15-20</td>

                    <?php for ($i = 1; $i <= $args['count']; $i++) : ?>

                        <td>
                            <input  type="text" 
                                    name="<?php echo esc_attr($args['key_15_20'] . $i); ?>" 
                                    value="<?php echo esc_attr(get_option($args['key_15_20'] . $i)); ?>">
                        </td>

                    <?php endfor; ?>

                </tr>
                <tr>
                    <td>11-19</td>

                    <?php for ($i = 1; $i <= $args['count']; $i++) : ?>

                        <td>
                            <input  type="text" 
                                    name="<?php echo esc_attr($args['key_11_19'] . $i); ?>" 
                                    value="<?php echo esc_attr(get_option($args['key_11_19'] . $i)); ?>">
                        </td>

                    <?php endfor; ?>

                </tr>

            </tbody>
        </table>

        <p>
            <input type="submit" name="save_time_delivery" id="save_time_delivery" class="button button-primary" value="Сохранить">
        </p>

    </form>

</div>