<?php 
$account_pages = ut_help()->account->get_account_sidebar_pages();
?>

<div class="profile-sidebar">
	<ul class="profile-sidebar__list">

		<?php foreach ( $account_pages as $account_page ) : ?>

			<li class="profile-sidebar__list-item sidebar-item <?php echo $account_page['active'] ?>">
				<a class="sidebar-item__link" href="<?php echo $account_page['url'] ?>">
					<?php echo $account_page['icon'] ?>
					<span class="sidebar-item__link-text"><?php echo $account_page['title'] ?></span>
					<span class="sidebar-item__link-text sidebar-item__link-text--mobile"><?php echo $account_page['short_title'] ?></span>
				</a>
			</li>

		<?php endforeach; ?>
		
	</ul>
</div>
