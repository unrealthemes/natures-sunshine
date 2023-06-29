<?php

new App\Classes\GutenbergInitClass();
new App\Classes\MailFormsClass();
new App\Controllers\FrontPageController();
new App\Controllers\BodyClassController();
new App\Controllers\Admin\StaticStringsController();
new App\Controllers\Admin\OptionsPageController();

foreach (glob(__DIR__ . "/Helpers/*.php") as $filename) {
    include $filename;
}