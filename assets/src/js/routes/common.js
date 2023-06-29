import {Fancybox} from "@fancyapps/ui";
import Swiper from "swiper";
import Inputmask from "inputmask";

import menu from "../utils/menu";
import search from "../utils/search";
import cards from "../utils/cards";
import footer from "../utils/footer";
import isMobile from "../utils/isMobile";
import inlineScroll from "../utils/inlineScroll";
import form from "../utils/form";
import customSelect from "../utils/select";
import frontSlider from "../utils/front-swiper";
import fancyApps from "../utils/fancyapps";
import copyBtn from "../utils/copyBtn";
import accountProfile from "../utils/account-profile";
import productCounter from "../components/productCounter";
import tooltips from "../components/tooltips";
import checkoutCities from "../components/checkoutCities";
import collapse from "../utils/collapse";
import collapseResize from "../utils/collapseResize";
import userCopyId from "../components/userCopyId";
import checkoutTabs from "../components/checkoutTabs";
import calendar from "../components/calendar";
import ordersFilter from "../components/ordersFilter";
import cardsSliderInit from "../components/cardsSlider";
import cardHeightResize from "../components/cardResize";
import cartCollapse from "../components/cartCollapse";

export default {
  init() {
    window.isMobile = isMobile();
    window.customSelect = customSelect;
    window.Fancybox = Fancybox;
    window.collapseResize = collapseResize;
    window.collapse = collapse;
    window.Swiper = Swiper;
    window.cardsSliderInit = cardsSliderInit;
    window.cardHeightResize = cardHeightResize;
    window.cartCollapse = cartCollapse;
    window.Inputmask = Inputmask;

    collapseResize();
    window.addEventListener('resize', collapseResize);

    cartCollapse();
    fancyApps();
    collapse();
    inlineScroll();
    menu();
    search();
    frontSlider();
    cardsSliderInit();
    cards();
    form();
    customSelect();
    footer();
    copyBtn();
    productCounter()
    accountProfile();
    tooltips();
    checkoutCities();
    userCopyId();
    checkoutTabs();
    calendar();
    ordersFilter();
  },
  finalize() {
  }
}
