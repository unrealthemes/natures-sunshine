<?php
/* Template Name: Navigation */
get_header();
?>

<style>
    header,
    .header__top,
    footer{
        display: none;
    }

    .page{
        text-align:center;
    }

    a{
        color: #00a88f;
        font-size: 18px;
        text-decoration: none;
        transition: opacity .3s;
    }
    
    a:hover{
        opacity: .8;
    }

    .pages-navigation{
        height: 100vh; 
        display: flex;
        flex-direction: column;
        justify-content: center;
    }


</style>

<div class="page">
    <ul class="pages-navigation">
        <li><a href="/">Главная</a></li>
        <li><a href="/login">Вход/Регистрация</a></li>
        <li><a href="/lost-password">Восстановление пароля (запрос)</a></li>
        <li><a href="/restore-password">Восстановление пароля (новый пароль)</a></li>
        <li><a href="/catalog">Категория/Каталог</a></li>
        <li><a href="/product">Страница товара</a></li>
        <li><a href="/product-empty">Страница товара (нет в наличии)</a></li>
        <li><a href="/checkout-2">Оформление заказа</a></li>
        <li><a href="/cart-2">Корзина (обычная)</a></li>
        <li><a href="/cart-2-multiple">Корзина (совместная)</a></li>
        <li><a href="/account">Личный кабинет (Мой аккаунт)</a></li>
        <li><a href="/account-public">Личный кабинет (Публичная информация)</a></li>
        <li><a href="/account-addresses">Личный кабинет (Мои адреса)</a></li>
        <li><a href="/account-orders">Личный кабинет (Мои заказы)</a></li>
        <li><a href="/account-newsletters">Личный кабинет (Рассылки)</a></li>
        <li><a href="/blog">Блог</a></li>
        <li><a href="/post">Пост</a></li>
        <li><a href="/favorites">Избранное</a></li>
        <li><a href="/compare">Сравнение</a></li>
        <li><a href="/user">Страница пользователя</a></li>
        <li><a href="/404">404</a></li>
    </ul>
</div>

<?php get_footer(); ?>
