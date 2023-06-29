<div class="popup popup-checkout" id="cities-popup">
	<div class="popup__header">
		<h2 class="popup__title">Выберите город</h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<form class="form location form-cities" autocomplete="off">
		<ul class="location-list">
			<li class="location-list__item">
				<a href="#" class="location-list__item-link active">Киев</a>
			</li>
			<li class="location-list__item">
				<a href="#" class="location-list__item-link">Харьков</a>
			</li>
			<li class="location-list__item">
				<a href="#" class="location-list__item-link">Одесса</a>
			</li>
			<li class="location-list__item">
				<a href="#" class="location-list__item-link">Днепр</a>
			</li>
			<li class="location-list__item">
				<a href="#" class="location-list__item-link">Запорожье</a>
			</li>
			<li class="location-list__item">
				<a href="#" class="location-list__item-link">Львов</a>
			</li>
		</ul>
		<div class="form__row">
			<label for="city">Введите населенный пункт Украины</label>
			<div class="form__input location-city">
				<input type="text" name="city" id="city" value="Киев" placeholder="Выберите город">
				<ul class="location-cities"></ul>
			</div>
			<div class="location-example">
				<p class="location-example__text">Например, <span class="location-example__place">Крым</span></p>
			</div>
		</div>
		<div class="form__row" style="text-align: right;">
			<button type="button" class="form__button btn btn-green js-choose-city">Применить</button>
		</div>
	</form>
</div>
