<section class="post">
    <div class="container">

        <?php if ( $thumb_url ) : ?>
            <div class="post-header">
                <div class="post-image">
                    <img src="<?php echo $thumb_url; ?>" 
                        loading="eager" 
                        decoding="async"
                        alt="<?php the_title() ?>">
                </div>
            </div>
        <?php endif; ?>

        <div class="post-content">
            <?php the_title('<h1 class="post-title">', '</h1>'); ?>
            <?php the_content() ?>
        </div>

    </div>
</section>