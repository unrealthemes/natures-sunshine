<?php 
global $product;

$terms = wp_get_object_terms( 
    $product->get_id(),
    'health-topics',
    [ 
        'number' => $args['number'],
        'meta_query' => [
            [
                'key' => 'icon_ht',
                'value'   => [''],
                'compare' => 'NOT IN'
            ]
        ]
    ]
);
?>

<?php if ( $terms ) : ?>

    <div class="card__icons">
        
        <?php 
        foreach ( $terms as $term ) : 
            $icon_id = get_field( 'icon_ht', $term );
            $icon_url = wp_get_attachment_url( $icon_id, 'full' );
        ?>
            <div class="card__icon tooltip" data-tooltip="<?php echo $term->name; ?>">
                <?php echo file_get_contents( $icon_url ); ?>
            </div>
        <?php endforeach; ?>
        
    </div>

<?php endif; ?>