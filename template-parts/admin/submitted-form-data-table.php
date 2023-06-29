<?php 
$i = 1;
$emails = $args['users_data']; 
?>

<div class="wrap">
    <form id="submitted_form" action="" method="post">
        <h1><?php _e('Submitted form data', 'natures-sunshine'); ?></h1>

        <p>
            <button type="submit" class="button button-primary delete-emails-js" disabled>
                <?php _e('Delete', 'natures-sunshine'); ?>
            </button>
        </p>

        <table id="submitted_form_data" class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th><input id="sf_all" type="checkbox"></th>
                    <th>#</th>
                    <th>Имя</th>
                    <th>Телефон</th>
                    <th>Тема</th>
                    <th>Комментарий</th>
                    <th>Email отправителя</th>
                    <th>Статус отправки</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>

                <?php if ( $emails ) : ?>

                    <?php foreach ( $emails as $key => $email ) : ?>

                        <tr>
                            <td>
                                <input id="sf_<?php echo $email['id'] ?>" 
                                    type="checkbox" 
                                    name="email[]" 
                                    class="email-items"
                                    value="<?php echo $email['id'] ?>">
                            </td>
                            <td><?php echo $i; ?></td>

                            <?php 
                            foreach ( $email as $key => $value ) : 
                                 //$class = ($key == 'comment') ? 'open' : '';
                            ?>

                                <?php if ( $key != 'id' ) : ?>

	                            <td class="table-item"><?php echo stripcslashes($value); ?><span class="modal-open"></span></td>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </tr>

                    <?php $i++; endforeach; ?>

                <?php endif; ?>

            </tbody>
        </table>
    </form>
</div>