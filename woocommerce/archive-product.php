<?php


get_header(); ?>

    <div class="wrapper">
        <main class="main">

            <?php do_action( 'echo_kama_breadcrumbs' ); ?>

            <?php
            get_template_part( 'template-parts/catalog/banner', null, [
                'post_id' => 450,
            ]  );
            get_template_part( 'template-parts/catalog/catalog-archive' );
            ?>

        </main>
    </div>

<?php
get_footer();
