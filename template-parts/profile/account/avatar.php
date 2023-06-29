<?php 
$user = $args['user'];
$avatar_id = $args['avatar_id'];

if ( $avatar_id ) : 
    $avatar_url = ( $avatar_id ) ? wp_get_attachment_url( $avatar_id, 'full' ) : THEME_URI . '/img/user-avatar.png';
?>

    <img class="profile-content__account-photo-image image-absolute" 
        src="<?php echo esc_attr( $avatar_url ); ?>" 
        alt="<?php echo $user->first_name; ?>">

<?php else : ?>

    <div class="user-content__image" data-letter="<?php echo mb_substr($user->first_name, 0, 1); ?>"></div>

<?php endif; ?>

<label class="profile-content__account-photo-change">
    <input id="file_avatar" type="file" name="file_avatar" value="" class="visually-hidden">
    <svg width="24" height="24" >
        <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-image-change'; ?>"></use>
    </svg>
</label>