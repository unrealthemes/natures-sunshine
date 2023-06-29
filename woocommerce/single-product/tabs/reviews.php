<?php 
global $product;
$cpage = get_query_var('cpage') ? get_query_var('cpage') : 1;
$per_page = get_option('comments_per_page');
$max_depth = get_option('thread_comments_depth');
$args = [
    'post_id' => $product->get_id(),
    'status' => 'approve', 
];
$comments_query = new WP_Comment_Query;
$comments = $comments_query->query( $args );
$count = ut_help()->product->parent_comment_counter( $product->get_id() );
$total = ceil($count / $per_page);
?>

<div class="tabs-panel__content testimonials__comments comments">
    <div class="ut-loader"></div>
    <div class="comments__header">
        <h2 class="comments__title hidden-mobile"><?php _e('Customer reviews', 'natures-sunshine'); ?></h2>

        <?php if ( is_user_logged_in() ) : ?>
            <button class="comments__button btn btn-green open-comment-form-js" data-fancybox="" data-src="#comment-form" data-commentid="">
                <?php _e('Leave feedback', 'natures-sunshine'); ?>
            </button>
        <?php else : ?>
            <a href="<?php echo ut_get_permalik_by_template('template-login.php'); ?>" class="comments__button btn btn-green">
                <?php _e('Leave feedback', 'natures-sunshine'); ?>
            </a>
        <?php endif; ?>

    </div>
    <div class="comments__body">

        <?php 
        wp_list_comments( 
            [   
                'walker' => new UT_Walker_Comment(),
                'style' => 'div',
                'max_depth' => $max_depth,
                'page' => $cpage, 
		        'per_page' => $per_page,
            ], 
            $comments 
        ); 
        ?>

    </div>
    <div class="catalog-footer text-center">
        <div class="commentPagination">
            <?php
            echo paginate_links( [
                'base' => add_query_arg( 'cpage', '%#%' ),
                'format' => '',
                'prev_text' => '<svg width="24" height="24">
                                    <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-left"></use>
                                </svg>',
                'next_text' => '<svg width="24" height="24">
                                    <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right"></use>
                                </svg>',
                'total' => $total,
                'current' => $cpage
            ] );
            ?>
        </div>
    </div>
</div>