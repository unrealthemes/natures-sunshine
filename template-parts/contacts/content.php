<div class="contacts__content">

	<?php the_title('<h1 class="contacts__title">', '</h1>'); ?>

	<div class="contacts__block">

		<?php if ( have_rows('blocks_contacts') ): ?>

			<?php while ( have_rows('blocks_contacts') ): the_row(); ?>

				<div class="contacts__block-item">
					<h2 class="contacts__block-title">
						<?php the_sub_field('title_blocks_contacts'); ?>
					</h2>

					<?php if ( have_rows('list_blocks_contacts') ): ?>

						<ul class="contacts__list">

							<?php while ( have_rows('list_blocks_contacts') ): the_row(); ?>

								<?php if ( get_sub_field('type_list_blocks_contacts') == 'phone' ) : ?>
									
									<li class="contacts__list-item text">
										<a class="contacts__list-link" href="tel:+<?php the_sub_field('content_list_blocks_contacts'); ?>">
											<?php the_sub_field('content_list_blocks_contacts'); ?>
										</a>
									</li>

								<?php elseif ( get_sub_field('type_list_blocks_contacts') == 'email' ) : ?>

									<li class="contacts__list-item text">
										<a class="contacts__list-link" href="mailto:<?php the_sub_field('content_list_blocks_contacts'); ?>">
											<?php the_sub_field('content_list_blocks_contacts'); ?>
										</a>
									</li>

								<?php elseif ( get_sub_field('type_list_blocks_contacts') == 'text' ) : ?>

									<li class="contacts__list-item text">
										<p>
											<?php the_sub_field('content_list_blocks_contacts'); ?>
										</p>
									</li>

								<?php endif; ?>

							<?php endwhile; ?>

						</ul>

					<?php endif; ?>

				</div>

			<?php endwhile; ?>

		<?php endif; ?>

	</div>

	<div class="contacts__block">
		<h2 class="contacts__subtitle">
			<?php the_field('title_contacts'); ?>
		</h2>
		<div class="contacts__block-description text">
			<?php the_field('desc_contacts'); ?>
		</div>
	</div>

</div>
