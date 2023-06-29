<?php
/*
 * Template Name: Compare
 *
 * */

get_header(); 

$product_ids = ut_help()->compare->products_list();
$products = [];

foreach ( (array)$product_ids as $product_id ) {
	$product = wc_get_product( $product_id );
		
	if ( $product && $product->exists() ) {
		$products[] = $product;
	}
}
?>

<?php // do_action( 'echo_kama_breadcrumbs' ); ?>

<section class="compare page"> 
	<div class="ut-loader"></div>
	<?php
		get_template_part( 'template-parts/compare/header', null, ['products' => $products] );
		get_template_part( 'template-parts/compare/sticky', null, ['products' => $products] );
		get_template_part( 'template-parts/compare/content', null, ['products' => $products] );
	?>
</section>

<div class="wrapper">
	<?php
		get_template_part( 'template-parts/front-page/popular' );
		get_footer();
	?>
</div>