<?php 
$redirect_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$sponsors = $args['sponsors'];
$search_val = $_GET['search'] ?? '';
?>

<div class="wrap">

    <h1>Sponsor IDs</h1>

    <?php if ( isset($_GET['msg']) && !empty($_GET['msg']) ) : ?>

        <div id="message2" class="updated notice is-dismissible">
            <p><?php echo $_GET['msg']; ?></p>
        </div>

    <?php endif; ?>

    <form id="sponsors_form" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <input type="file" name="sponsors_file" accept=".csv">
                    </th>
                    <td>
                        <input type="submit" name="submit_csv_file" id="submit_csv_file" class="button button-primary" value="Импорт">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    
    <form id="sponsors_search_form" action="<?php echo $redirect_url; ?>" method="get">
        <input type="hidden" name="page" value="sponsors">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <input type="search" name="search" required value="<?php echo $search_val; ?>">
                    </th>
                    <td>
                        <input type="submit" name="submit_search" id="submit_search" class="button button-primary" value="Поиск">

                        <?php if ( isset($_GET['search']) ) : ?>
                            <a href="<?php echo home_url('/wp-admin/admin.php?page=sponsors'); ?>" class="button button-primary">
                                Сбросить
                            </a>
                        <?php endif; ?>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th>#</th>
                <th>reg_no</th>
                <th>sponsor_regno</th>
                <th>fio_1c</th>
                <th>fio_original</th>
                <th>fio</th>
                <th>sponsor_checked_ok</th>
                <th>email</th>
                <th>sponsor_error</th>
                <th>main_phone</th>
            </tr>
        </thead>
        <tbody>

            <?php if ( $sponsors ) : ?>

                <?php foreach ( $sponsors as $key => $sponsor ) : ?>

                    <tr>
                        <td><?php echo $key + 1; ?></td>

                        <?php foreach ( $sponsor as $key => $value ) : ?>

                            <td><?php echo $value; ?></td>

                        <?php endforeach; ?>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>
    </table>

    <?php echo $args['pagination']; ?>

</div>