<?php
/*
 * Template Name: Share Compare
 *
 * */

get_header(); 

if ( !isset($_GET['products']) || empty($_GET['products']) ) {
    return false;
}

$product_ids_str = $_GET['products'];
$product_ids = explode(',', $product_ids_str);

foreach ( (array)$product_ids as $product_id ) {
	$product = wc_get_product( $product_id );
		
	if ( $product && $product->exists() ) {
		$products[] = $product;
	}
}
?>

<?php do_action( 'echo_kama_breadcrumbs' ); ?>

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