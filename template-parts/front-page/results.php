<?php

/**
 * health-topics Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'health-topics-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'themes';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$title = get_field('title_mc');
$count = get_field('count_mc');
$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => $count,
    'meta_key' => 'total_sales',
    'orderby' => 'meta_value_num',
);
$wc_query = new WP_Query($args);
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/main-components.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

	<section class="results section">
		<div class="container">
			<h2 class="results__title text-center"><?php echo $title; ?></h2>

			<?php if ($wc_query->have_posts()) : ?>

				<ul class="results-list" role="list">

					<?php 
					while ($wc_query->have_posts()) :
						$wc_query->the_post();
					?>

						<li class="results-list__item">
							<a href="<?php the_permalink(); ?>" class="results-list__link">
								<?php the_title(); ?>
							</a>
						</li>
					
					<?php endwhile; ?>

				</ul>

			<?php 
			endif; 
			wp_reset_postdata();
			?>

		</div>
	</section> 

<?php endif; ?>