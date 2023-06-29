<div class="contacts__form">
	<form class="form form-contacts send_form">
		<input type="hidden" name="contacts_recipient_email" value="<?php the_field('recipient_email_form_contacts'); ?>">
		<div class="ut-loader"></div>
		<div class="form__row">
			<h2><?php the_field('title_form_contacts'); ?></h2>
		</div>
		<div class="form__row">
			<label for="contacts_name"><?php _e('Name', 'natures-sunshine'); ?></label>
			<div class="form__input">
				<input type="text" name="contacts_name" id="contacts_name" placeholder="<?php _e('Konstantin', 'natures-sunshine'); ?>" required>
			</div>
		</div>
		<div class="form__row">
			<label for="contacts_phone"><?php _e('Phone', 'natures-sunshine'); ?></label>
			<div class="form__input">
				<input type="text" class="mask-js" name="contacts_phone" id="contacts_phone" required>
			</div>
		</div>

		<?php if ( have_rows('themes_form_contacts') ): ?>
			<div class="form__row">
				<label for="contacts_subject"><?php _e('Topic', 'natures-sunshine'); ?></label>
				<div class="select">
					<select name="contacts_subject" id="contacts_subject" required>
						<option hidden disabled selected><?php _e('Select the topic of the message', 'natures-sunshine'); ?></option>

						<?php while ( have_rows('themes_form_contacts') ): the_row(); ?>

							<option value="<?php the_sub_field('text_themes_form_contacts'); ?>">
								<?php the_sub_field('text_themes_form_contacts'); ?>
							</option>

						<?php endwhile; ?>

					</select>
				</div>
			</div>
		<?php endif; ?>

		<div class="form__row">
			<label for="contacts_message"><?php _e('Comment to the order', 'natures-sunshine'); ?></label>
			<div class="form__input">
				<textarea name="contacts_message" id="contacts_message" rows="3" placeholder="<?php _e('Message', 'natures-sunshine'); ?>"></textarea>
			</div>
		</div>
		<div class="form__row form__row--align-right">
			<button type="submit" class="form__button btn btn-green"><?php _e('Send', 'natures-sunshine'); ?></button>
		</div>
		<div id="error_msg"></div>
		<div id="success_msg"></div>
	</form>
</div>
