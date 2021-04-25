<?php

use App\Controller\AppConfigController;
use PiecesPHP\Core\Roles;

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


<aside class="coky sidebar-menu">

    <div class="ui vertical menu links">
        <?= menu_sidebar_items($user, $is_config); ?>
    </div>

</aside> <!-- .ui-pcs.sidebar -->