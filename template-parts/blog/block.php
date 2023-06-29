<?php

/**
 * blog Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'blog-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'section news blog-page';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$categories = get_field('posts_by_cat_blog');
$count_posts = get_field('count_posts_blog');
?>
 
<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/blog.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

    <section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

        <?php if ( $categories ) : ?>

            <div class="container">

                <?php 
                foreach ( $categories as $category ) : 
                    $query = new WP_Query( [
                        'category_name' => $category->slug, 
                        'posts_per_page' => $count_posts 
                    ] ); 
                ?>

                    <div class="blog-section">
                        <div class="blog-section__header">
                            <h1 class="blog-section-title"><?php echo $category->name; ?></h1>
                            <a href="<?php echo get_term_link( $category->term_id, $category->taxonomy ); ?>" class="blog-section__more btn btn-secondary">
                                <?php _e('Смотреть все', 'natures-sunshine') ?>
                            </a>
                        </div>

                        <?php if ( $query->have_posts() ) : ?>

                            <div class="blog-section__feed news swiper blog-swiper">
                                <div class="swiper-wrapper">

                                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>

                                        <?php get_template_part( 'template-parts/blog/content', 'slide' ); ?>

                                    <?php endwhile; ?>

                                </div>
                            </div>

                        <?php endif; wp_reset_postdata(); ?>

                    </div>

                <?php endforeach; ?>
                
            </div>

        <?php endif; ?>

    </section>

<?php endif; ?>