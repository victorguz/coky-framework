<?php

use App\Controller\AppConfigController;

$config_urls = [
    AppConfigController::routeName('generals'),
    AppConfigController::routeName('view-logos-favicons'),
    AppConfigController::routeName('view-backgrounds'),
    AppConfigController::routeName('seo'),
    get_route('admin-error-log'),
    get_route('configurations-routes'),
    AppConfigController::routeName('generals-sitemap-create'),
    AppConfigController::routeName('email'),
    AppConfigController::routeName('os-ticket'),
];

$current_url = get_current_url();
$is_config = false;
/**
 * Buscar si la url actual es alguna de las url de configuración
 */
foreach ($config_urls as $value) {
    if ($current_url == $value) {
        $is_config = true;
        // break; //detenerse al encontrarlo
    }
}

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>


<aside class="coky sidebar-menu">

    <div class="ui vertical menu links">
        <?= menu_sidebar_items($user, $is_config); ?>
    </div>

</aside> <!-- .ui-pcs.sidebar -->