<?php

/**
 * news Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'news-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'section news';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$title = get_field('title_news');
$txt_btn = get_field('txt_btn_news');
$link_btn = get_field('link_btn_news');
$sticky = get_option( 'sticky_posts' );
$count_posts = get_field('count_news');
$args = [
    'post_type' => 'post',
    'post_status' => 'publish',
];

if ( isset($sticky[0]) ) {
    $sticky_query = new WP_Query( 'p=' . $sticky[0] );
    $args['post__not_in'] = [ $sticky[0] ];
    $count_posts--;
}

$args['posts_per_page'] = $count_posts;
$query = new WP_Query( $args );
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/news-sunshine.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

    <section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
        <div class="container">

            <?php if ( $title ) : ?>
				<h2 class="news__title"><?php echo $title; ?></h2>
			<?php endif; ?>

            
                <div class="news-feed">

                    <?php if ( isset($sticky[0]) && $sticky_query->have_posts() ) : ?>

                        <?php 
                        while ( $sticky_query->have_posts() ) : $sticky_query->the_post();
                            $thumb_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                            $tags = wp_get_post_tags( $post->ID );

                            if ( !$thumb_url ) {
                                $thumb_url = THEME_URI . '/img/placeholder.png';
                            }
                        ?>
                            <div class="news-feed__item news-feed__main">
                                <a href="<?php echo get_the_permalink(); ?>" class="news-feed__link" title="<?php echo get_the_title(); ?>">
                                    <div class="news-feed__image">
                                        <img src="<?php echo $thumb_url; ?>" 
                                             loading="lazy" 
                                             decoding="async" 
                                             alt="<?php echo get_the_title(); ?>">
                                    </div>
                                    <div class="news-feed__content">

                                        <?php if ( $tags ) : ?>
                                            <div class="news-feed__tags">
                                                <?php foreach ( $tags as $tag ) : ?>
                                                    <span class="news-feed__tag"><?php echo $tag->name; ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <h2 class="news-feed__title"><?php echo get_the_title(); ?></h2>
                                        <p class="news-feed__text"><?php echo get_the_excerpt(); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; ?>

                    <?php endif; wp_reset_query(); ?>

                    <?php if ( $query->have_posts() ) : ?>

                        <?php 
                        while ( $query->have_posts() ) : $query->the_post();
                            $thumb_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                            $tags = wp_get_post_tags( $post->ID );

                            if ( !$thumb_url ) {
                                $thumb_url = THEME_URI . '/img/placeholder.png';
                            }
                        ?>
                            <div class="news-feed__item">
                                <a href="<?php echo get_the_permalink(); ?>" class="news-feed__image" title="<?php echo get_the_title(); ?>">
                                    <img src="<?php echo $thumb_url; ?>" 
                                            loading="lazy" 
                                            decoding="async" 
                                            alt="<?php echo get_the_title(); ?>">
                                </a>

                                <?php if ( $tags ) : ?>
                                    <div class="news-feed__tags">
                                        <?php foreach ( $tags as $tag ) : ?>
                                            <span class="news-feed__tag"><?php echo $tag->name; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <h2 class="news-feed__title">
                                    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                                        <?php echo get_the_title(); ?>
                                    </a>
                                </h2>
                                <p class="news-feed__text"><?php echo get_the_excerpt(); ?></p>

                            </div>
                        <?php endwhile; ?>

                    <?php endif; wp_reset_query(); ?>

                </div>

                <?php if ( $txt_btn && $link_btn ) : ?>
                    <div class="news__more text-center">
                        <a href="<?php echo get_category_link( $link_btn ); ?>" class="news__button btn btn-secondary">
                            <?php echo $txt_btn; ?>
                        </a>
                    </div>
                <?php endif; ?>

        </div>
    </section>

<?php endif; ?>