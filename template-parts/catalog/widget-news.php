<?php 
$title = get_field('title_news_catalog', 'option');
$count = (int)get_field('count_news_catalog', 'option');
$args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $count-1,
    'orderby' => 'menu_order title',
    'order' => 'DESC',
];
$loop = new WP_Query( $args ); 
?>

<div class="catalog-news">

    <?php if ( $title ) : ?>
        <h3 class="catalog-news__title"><?php echo $title; ?></h3>
    <?php endif; ?>

    <?php if ( $loop->have_posts() ) : ?>

        <ul class="catalog-news__list" role="list">

            <?php 
            while ( $loop->have_posts() ) : $loop->the_post(); 
                $img_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );

                if ( !$img_url ) {
                    $img_url = wc_placeholder_img_src();
                }
            ?>

                <li class="catalog-news__list-item">
                    <a href="<?php the_permalink(); ?>" 
                       class="catalog-news__list-link" 
                       title="<?php the_title(); ?>">
                        <div class="catalog-news__list-image">
                            <img src="<?php echo $img_url; ?>" 
                                 loading="lazy" 
                                 decoding="async" 
                                 alt="<?php the_title(); ?>">
                        </div>
                        <div class="catalog-news__list-content">
                            <?php the_title('<p class="catalog-news__list-title">', '</p>'); ?>
                            <span class="catalog-news__list-date"><?php echo get_the_date('j F'); ?></span>
                        </div>
                    </a>
                </li>
            
            <?php endwhile; ?>

        </ul>

    <?php endif; wp_reset_postdata(); ?>

</div>