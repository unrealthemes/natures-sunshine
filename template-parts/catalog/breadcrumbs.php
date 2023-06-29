<div class="breadcrumbs">
	<div class="container">
		<ul class="breadcrumbs-list" role="list">
			<li class="breadcrumbs-list__item">
				<a href="<?= get_site_url(); ?>" class="breadcrumbs-list__link" title="На главную">
					<svg class="breadcrumbs-list__home" width="24" height="24">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#home'; ?>"></use>
					</svg>
				</a>
				<svg class="breadcrumbs-list__divider" width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right'; ?>"></use>
				</svg>
			</li>
			<li class="breadcrumbs-list__item breadcrumbs-list__item--current"><?php the_title(); ?></li>
		</ul>
	</div>
</div>
