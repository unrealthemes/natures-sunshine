<div class="popup popup--small list-popup" id="add_list">
	<div class="popup__header">
		<h2 class="popup__title">Создать новый список желаний</h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<div class="popup__body">
		<div class="form__row">
			<label for="list_name">Название нового списка</label>
			<div class="form__input">
				<input type="text" name="list_name" id="list_name" placeholder="Мой список желаний">
			</div>
		</div>
		<div class="form__row">
			<div class="form__input">
				<input type="checkbox" name="list_main" id="list_main" checked>
				<label for="list_main">Сделать список основным</label>
			</div>
		</div>
	</div>
	<button type="button" class="popup__action btn btn-green">Добавить</button>
</div>

<div class="popup popup--small replace-popup" id="replace_list">
	<div class="popup__header">
		<h2 class="popup__title">Переместить в список</h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<label for="lists" hidden>Списки</label>
	<div class="form__input select">
		<select name="lists" id="lists">
			<option value="yura">Для Юры</option>
			<option value="andrey">Для Андрея</option>
			<option value="olga">Для Ольги</option>
		</select>
	</div>
</div>