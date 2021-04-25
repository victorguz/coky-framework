<?php

use App\Controller\AppConfigController;

$is_config = get_current_url() == AppConfigController::routeName('logos-favicons') ||
    get_current_url() == AppConfigController::routeName('backgrounds') ||
    get_current_url() ==  AppConfigController::routeName('generals') ||
    get_current_url() ==  AppConfigController::routeName('seo') ||
    get_current_url() ==  get_route('admin-error-log') ||
    get_current_url() == get_route('configurations-routes') ||
    get_current_url() ==  AppConfigController::routeName('generals-sitemap-create') ||
    get_current_url() ==  AppConfigController::routeName('email') ||
    get_current_url() ==  AppConfigController::routeName('os-ticket');

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>

<div class="ui-pcs topbar ui menu">

    <a href="<?= get_route("admin") ?>" class="item brand">
        <img src="<?= get_config("favicon") ?>" alt="logo">
    </a>



    <a class="coky sidebar-toggle item">
        <?= getIcon("menu-outline") ?>
    </a>

    <?php if ($is_config) : ?>
        <!-- <?= get_config('menus')['home_button']->getHTML() ?> -->
    <?php endif; ?>

    <?= get_config('menus')['languages_link']->getHTML() ?>

    <div class="blanc right menu">

        <?= menu_topbar_items($user); ?>

    </div>
</div>