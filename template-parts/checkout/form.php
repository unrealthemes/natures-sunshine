<form class="form form-checkout">
	<div class="form-checkout__inner">
		<div class="form-checkout__fields">
			<div class="form-checkout__block">
				<h1 class="form-checkout__title">Оформление заказа</h1>
				<h2 class="form-checkout__subtitle">Контактые данные</h2>
				<div class="form-checkout__row form-checkout__tabs">
					<a href="#client" class="form-checkout__tabs-item active">Я постоянный клиент</a>
					<a href="#order" class="form-checkout__tabs-item">Заказать на ID</a>
				</div>
				<div class="form-checkout__row form-checkout__tabs-panels">
					<div class="form-checkout__tabs-panel active" id="client">
						<div class="form-checkout__row">
							<label for="account_email">Электропочта</label>
							<div class="form__input">
								<input type="email" name="account_email" id="account_email" placeholder="example@domain.com" required>
							</div>
						</div>
						<div class="form-checkout__row">
							<label for="account_password">Пароль</label>
							<div class="form__input">
								<input type="password" name="account_password" id="account_password" placeholder="******" required>
							</div>
						</div>
						<div class="form-checkout__row text-center">
							<button class="form-checkout__button btn btn-green w-100" type="button">Войти</button>
							<a href="/lost-password" class="form-checkout__forget text">Восстановить пароль</a>
						</div>
					</div>
					<div class="form-checkout__tabs-panel" id="order">
						<div class="form-checkout__row">
							<label for="order_id">Заказать на ID</label>
							<div class="form__input">
								<input type="text" name="order_id" id="order_id" placeholder="12345" required>
							</div>
						</div>
						<div class="form-checkout__row">
							<button class="form-checkout__button btn btn-green w-100" type="button">Подтвердить</button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-checkout__block">
				<h2 class="form-checkout__subtitle">Как вы хотите получить заказ?</h2>
				<div class="form-checkout__row">
					<div class="form-checkout__input">
						<label for="city">Выберите город</label>
						<button type="button" class="form-checkout__city btn w-100" data-city="Киев">Киев</button>
					</div>
				</div>
				<div class="form-checkout__row form-checkout__tabs">
					<a href="#self" class="form-checkout__tabs-item form-checkout__tabs-item--icon active">
						<svg class="form-checkout__tabs-icon" width="24" height="24">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#store'; ?>"></use>
						</svg>
						<span class="form-checkout__tabs-content">
							Самовывоз <span>Сегодня, бесплатно</span>
						</span>
					</a>
					<a href="#courier" class="form-checkout__tabs-item form-checkout__tabs-item--icon">
						<svg class="form-checkout__tabs-icon" width="24" height="24">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#motocycle'; ?>"></use>
						</svg>
						<span class="form-checkout__tabs-content">
							Доставка <span>Сегодня 399 ₴</span>
						</span>
					</a>
				</div>
				<div class="form-checkout__row">
					<div class="form-checkout__select form-checkout__addresses select">
						<select>
							<option hidden>15 сохраненных адресов</option>
							<option value="address-1">Адрес 1</option>
							<option value="address-2">Адрес 2</option>
							<option value="address-3">Адрес 3</option>
							<option value="address-4">Адрес 4</option>
							<option value="address-5">Адрес 5</option>
						</select>
					</div>
				</div>
				<div class="form-checkout__row form-checkout__tabs-panels">
					<div class="form-checkout__tabs-panel active" id="self">
						<div class="form-checkout__row">
							<div class="form-checkout__radio">
								<input class="form-checkout__radio-input" type="radio" name="pickup" id="new_post">
								<label class="form-checkout__radio-label" for="new_post">
									<span class="form-checkout__radio-content">Самовывоз из Новой Почты</span>
								</label>
							</div>
							<div class="form-checkout__radio">
								<input class="form-checkout__radio-input" type="radio" name="pickup" id="self_delivery">
								<label class="form-checkout__radio-label" for="self_delivery">
									<span class="form-checkout__radio-content">Самовывоз из почтомата Новой Почты</span>
								</label>
							</div>
							<div class="form-checkout__radio">
								<input class="form-checkout__radio-input" type="radio" name="pickup" id="justin">
								<label class="form-checkout__radio-label" for="justin">
									<span class="form-checkout__radio-content">
										Самовывоз из Justin
										<span>Отправим завтра</span>
									</span>
								</label>
							</div>
							<div class="form-checkout__radio">
								<input class="form-checkout__radio-input" type="radio" name="pickup" id="ua_post" checked>
								<label class="form-checkout__radio-label" for="ua_post">
									<span class="form-checkout__radio-content">Самовывоз из почтомата Новой Почты</span>
								</label>
							</div>
							<div class="form-checkout__select select">
								<select>
									<option hidden>Выберите отделение</option>
									<option value="office-1">Отделение 1</option>
									<option value="office-2">Отделение 2</option>
									<option value="office-3">Отделение 3</option>
									<option value="office-4">Отделение 4</option>
									<option value="office-5">Отделение 5</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-checkout__tabs-panel" id="courier">
						<div class="form-checkout__row">
							<div class="form-checkout__radio">
								<input class="form-checkout__radio-input" type="radio" name="delivery" id="store"> <label
									class="form-checkout__radio-label" for="store"> <span class="form-checkout__radio-content">Доставка нашими курьерами</span>
								</label>
							</div>
							<div class="form-checkout__radio">
								<input class="form-checkout__radio-input" type="radio" name="delivery" id="sdek" checked> <label
									class="form-checkout__radio-label" for="sdek"> <span class="form-checkout__radio-content">Доставка СДЭК</span>
								</label>
							</div>
							<div class="form-checkout__radio">
								<input class="form-checkout__radio-input" type="radio" name="delivery" id="express"> <label
									class="form-checkout__radio-label" for="express">
							<span class="form-checkout__radio-content">
								Экспресс доставка
								<span>Сегодня 399 ₴</span>
							</span>
								</label>
							</div>
						</div>
						<h2 class="form-checkout__subtitle no-number">Адрес доставки</h2>
						<div class="form-checkout__row">
							<label for="street">Улица</label>
							<div class="form__input">
								<input type="text" name="street" id="street" required>
							</div>
						</div>
						<div class="form-checkout__row form-checkout__row--half">
							<div class="form-checkout__input">
								<label for="house">Дом</label> <input type="text" name="house" id="house" required>
							</div>
							<div class="form-checkout__input">
								<label for="app">Квартира</label> <input type="text" name="app" id="app" required>
							</div>
						</div>
						<div class="form-checkout__row form-checkout__row--half">
							<div class="form-checkout__input">
								<label for="delivery_date">Дата доставки</label>
								<div class="form-checkout__select select">
									<select name="delivery_date" id="delivery_date">
										<option value="jan24">Сегодня, 24 января</option>
										<option value="jan25">25 января</option>
										<option value="jan26">26 января</option>
										<option value="jan27">27 января</option>
										<option value="jan28">28 января</option>
									</select>
								</div>
							</div>
							<div class="form-checkout__input">
								<label for="delivery_time">Время доставки</label>
								<div class="form-checkout__select select">
									<select name="delivery_time" id="delivery_time">
										<option value="9-11">09:00-11:00, 399 ₴</option>
										<option value="11-13">11:00-13:00, 399 ₴</option>
										<option value="13-15">13:00-15:00, 399 ₴</option>
										<option value="15-17">15:00-17:00, бесплатно</option>
										<option value="17-19">17:00-19:00, 449 ₴</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-checkout__block">
				<h2 class="form-checkout__subtitle">Как удобнее оплатить заказ</h2>
				<div class="form-checkout__row form-checkout__notice form-checkout__notice--green">
					<svg width="20" height="20">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
					</svg>
					При доставке службой такси доступна только онлайн-оплата
				</div>
				<div class="form-checkout__row">
					<div class="form-checkout__radio">
						<input class="form-checkout__radio-input" type="radio" name="payment" id="cash"> <label
							class="form-checkout__radio-label" for="cash">
							<span class="form-checkout__radio-content">
								Оплата при получении товара
								<span>Наличными, картой, по СМС</span>
							</span> </label>
					</div>
					<div class="form-checkout__radio">
						<input class="form-checkout__radio-input" type="radio" name="payment" id="online" checked>
						<label class="form-checkout__radio-label" for="online">
							<span class="form-checkout__radio-content">
								Онлайн оплата
								<span>Картами Visa, MasterCard</span>
							</span> </label>
					</div>
					<div class="form-checkout__radio">
						<input class="form-checkout__radio-input" type="radio" name="payment" id="cashless"> <label
							class="form-checkout__radio-label" for="cashless"> <span
								class="form-checkout__radio-content">Безналичными для физических лиц</span> </label>
					</div>
					<div class="form-checkout__radio">
						<input class="form-checkout__radio-input" type="radio" name="payment" id="statement"> <label
							class="form-checkout__radio-label" for="statement"> <span
								class="form-checkout__radio-content">Стейтмент</span> </label>
					</div>
				</div>
			</div>
			<div class="form-checkout__block">
				<h2 class="form-checkout__subtitle">Скидки по заказу</h2>
				<div class="form-checkout__row">
					<div class="form-checkout__checkbox">
						<input class="form-checkout__checkbox-input" type="checkbox" name="promo" id="promo">
						<label class="form-checkout__checkbox-label" for="promo">Промокод</label>
					</div>
					<div class="form-checkout__collapse">
						<div class="form-checkout__code">
							<label for="code" hidden>Ввести номер промокода</label>
							<input type="text" name="code" id="code" placeholder="Ввести номер промокода">
							<button type="button" class="form-checkout__code-button btn btn-green">Применить</button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-checkout__block">
				<h2 class="form-checkout__subtitle">Укажите данные получателя заказа</h2>
				<div class="form-checkout__row">
					<label for="name">Имя</label>
					<div class="form__input">
						<input type="text" name="name" id="name" placeholder="Константин" required>
					</div>
				</div>
				<div class="form-checkout__row">
					<label for="surname">Фамилия</label>
					<div class="form__input">
						<input type="text" name="surname" id="surname" placeholder="Константинопольский" required>
					</div>
				</div>
				<div class="form-checkout__row">
					<label for="middlename">Отчество</label>
					<div class="form__input">
						<input type="text" name="middlename" id="middlename" placeholder="Константинович" required>
					</div>
				</div>
				<div class="form-checkout__row form-checkout__row--half">
					<div class="form-checkout__input">
						<label for="email">Почта</label>
						<div class="form__input">
							<input type="text" name="email" id="email" placeholder="example@domain.ru" required>
						</div>
					</div>
					<div class="form-checkout__input">
						<label for="phone">Телефон</label>
						<div class="form__input">
							<input type="tel" name="phone" id="phone" required>
						</div>
					</div>
				</div>
				<div class="form-checkout__row form-checkout__notice form-checkout__notice--pt">
					<svg width="20" height="20">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
					</svg>
					При оплате покупки на сайте, пожалуйста укажите ФИО получателя. Если товар будет забирать другой
					человек (не заказчик) – укажите его ФИО. Заказы выдаются при предъявлении любого документа,
					удостоверяющего личность, или банковской карты, с которой оплачен заказ.
				</div>
			</div>
			<div class="form-checkout__block">
				<h2 class="form-checkout__subtitle">Дополнительно</h2>
				<div class="form-checkout__row">
					<div class="form-checkout__checkbox">
						<input class="form-checkout__checkbox-input" type="checkbox" name="callback" id="callback">
						<label class="form-checkout__checkbox-label" for="callback">Звонок менеджера</label>
					</div>
				</div>
				<div class="form-checkout__row form-checkout__notice">
					<svg width="20" height="20">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
					</svg>
					Внимание! Если галочка не установлена, заказ будет оформлен и отправлен без звонка менеджера!
				</div>
				<div class="form-checkout__row">
					<label for="message">Комментарий к заказу</label>
					<div class="form__input">
						<textarea name="message" id="message" rows="5" placeholder="Сообщение"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="form-checkout__sidebar">
			<a href="/cart-2" class="form-checkout__link btn btn-white">Вернуться в корзину</a>
			<div class="form-checkout__total">
				<p class="form-checkout__total-title text">Итого</p>
				<div class="form-checkout__results">
					<p class="form-checkout__results-item">
						<span class="text color-mono-64">1 товар на сумму</span> <span class="text">1789 ₴</span>
					</p>
					<p class="form-checkout__results-item">
						<span class="text color-mono-64">Стоимость доставки</span> <span class="text">89 ₴</span>
					</p>
				</div>
				<div class="form-checkout__price">
					<span class="text color-mono-64">К оплате</span> <span
						class="form-checkout__price-total">1898 ₴</span>
				</div>
				<button type="submit" class="form-checkout__submit btn btn-green w-100">Заказ подтверждаю</button>
				<div class="form-checkout__info text color-mono-64">
					<p>Получение заказа от 5000 ₴ только по паспорту (Закон от 06.12.2019 № 361-IX)</p>
					<p>Подтверждая заказ, я принимаю условия:</p>
					<ul>
						<li>Положения о сборе и защите персональных <br>данных</li>
						<li>Пользовательского соглашения</li>
					</ul>
				</div>
			</div>
			<div class="form-checkout__points w-100">
				<div class="card__price-points">
					<img src="<?php echo DIST_URI; ?>/images/icons/nsp-logo.svg"
					     loading="lazy" decoding="async" alt=""> 150 PV
				</div>
				<div class="tooltip">
					<svg width="20" height="20">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
					</svg>
					<span class="tooltip__content tooltip__content--bottom">Some content</span>
				</div>
			</div>
		</div>
	</div>
</form>
