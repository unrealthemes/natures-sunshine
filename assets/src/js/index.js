// FIXES
import "./fixes"

import Router from './utils/Router';
import common from './routes/common';
import home from './routes/home';
import login from './routes/login';
import catalog from './routes/catalog';
import product from './routes/product';
import checkout from './routes/checkout';
import cart from './routes/cart';
import blog from './routes/blog';
import favorites from "./routes/favorites";
import compare from "./routes/compare";

/** Populate Router instance with DOM routes */
const routes = new Router({
    common,
    home,
    login,
    catalog,
    product,
    checkout,
    cart,
    blog,
    favorites,
    compare
})

window.addEventListener("DOMContentLoaded", () => {
    routes.loadEvents()
})