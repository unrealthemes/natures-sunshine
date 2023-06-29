<?php 
$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
$tags = wp_get_post_tags( get_the_ID() );

if ( !$thumb_url ) {
    $thumb_url = THEME_URI . '/img/placeholder.png';
}
?>

<div class="swiper-slide news-item">
    <a href="<?php the_permalink(); ?>" class="news-item__image" title="<?php the_title(); ?>"> 
        <img src="<?php echo $thumb_url; ?>"
             loading="lazy" 
             decoding="async" 
             alt="<?php the_title(); ?>"> 
    </a>

    <?php if ( $tags ) : ?>
        <div class="news-item__tags">
            <?php foreach ( $tags as $tag ) : ?>
                <span class="news-item__tag"><?php echo $tag->name; ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h2 class="news-item__title">
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
        </a>
    </h2>
    <p class="news-item__text"><?php the_excerpt(); ?></p>
</div>