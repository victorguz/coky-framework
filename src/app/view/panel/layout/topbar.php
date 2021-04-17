<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Controller\AdminPanelController;
use App\Controller\AppConfigController;
use App\Model\UsersModel;
use PiecesPHP\Core\Roles;

$role = Roles::getCurrentRole();
$currenUserType = !is_null($role) ? $role['code'] : null;
$canViewUserOption = array_reduce([
    Roles::hasPermissions('users-selection-create', $currenUserType),
    Roles::hasPermissions('users-list', $currenUserType),
    Roles::hasPermissions('importer-form', $currenUserType),
    Roles::hasPermissions('informes-acceso', $currenUserType),
    Roles::hasPermissions('informes-acceso', $currenUserType),
    Roles::hasPermissions('informes-acceso', $currenUserType),
], function ($a, $b) {
    return $a || $b;
}, false);

$canViewUserOption = array_reduce([
    Roles::hasPermissions('users-selection-create', $currenUserType),
    Roles::hasPermissions('users-list', $currenUserType),
    Roles::hasPermissions('importer-form', $currenUserType),
    Roles::hasPermissions('informes-acceso', $currenUserType),
    Roles::hasPermissions('informes-acceso', $currenUserType),
    Roles::hasPermissions('informes-acceso', $currenUserType),
], function ($a, $b) {
    return $a || $b;
}, false);
$canViewConfigsOption = array_reduce([
    AppConfigController::allowedRoute('logos-favicons'),
    AppConfigController::allowedRoute('backgrounds'),
    AppConfigController::allowedRoute('generals'),
    AppConfigController::allowedRoute('seo'),
    Roles::hasPermissions('admin-error-log', $currenUserType),
    Roles::hasPermissions('configurations-routes', $currenUserType),
    AppConfigController::allowedRoute('generals-sitemap-create'),
    AppConfigController::allowedRoute('email'),
    AppConfigController::allowedRoute('os-ticket'),
], function ($a, $b) {
    return $a || $b;
}, false);

?>

<div class="ui-pcs topbar ui mini menu">

    <a class="ui-pcs sidebar-toggle item">
        <?= getIcon("menu-outline") ?>
    </a>

    <div class="blanc right menu">

        <?php if ($canViewUserOption) : ?>

            <div class="ui dropdown item">
                <?= getIcon("people-outline") ?>
                <div class="menu">

                    <?php if (Roles::hasPermissions('users-selection-create', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('users-selection-create'); ?>">
                            <div class="figure"><?= getIcon("person-add-outline") ?></div>
                            <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Agregar usuario'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (Roles::hasPermissions('users-list', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('users-list'); ?>">
                            <div class="figure"><?= getIcon("list-outline") ?></div>
                            <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Listado de usuarios'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (Roles::hasPermissions('importer-form', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('importer-form', ['type' => 'users'], true); ?>">
                            <div class="figure"><?= getIcon("upload-outline") ?></div>
                            <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Importar usuarios'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (Roles::hasPermissions('informes-acceso', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('informes-acceso'); ?>?attempts=yes">
                            <div class="figure"><?= getIcon("log-in-outline") ?></div>
                            <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Logueos de usuarios'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (Roles::hasPermissions('informes-acceso', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('informes-acceso'); ?>?not-logged=yes">
                            <div class="figure"><?= getIcon("alert-circle-outline") ?></i></div>
                            <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Usuarios sin logueos'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (Roles::hasPermissions('informes-acceso', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('informes-acceso'); ?>?logged=yes">
                            <div class="figure"><?= getIcon("document-text-outline") ?></div>
                            <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Resumen de sesiones'); ?></div>
                        </a>
                    <?php endif; ?>

                </div>

            </div>
        <?php endif; ?>

        <?php if ($canViewConfigsOption) : ?>
            <div class="ui dropdown item">
                <?= getIcon("settings-outline") ?>
                <div class="menu">

                    <?php if (AppConfigController::allowedRoute('logos-favicons')) : ?>
                        <a class="item" href="<?= AppConfigController::routeName('logos-favicons'); ?>">
                            <div class="figure"><?= getIcon("heart-outline") ?></i></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Logotipos'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (AppConfigController::allowedRoute('backgrounds')) : ?>
                        <a class="item" href="<?= AppConfigController::routeName('backgrounds'); ?>">
                            <div class="figure"><?= getIcon("image-outline") ?></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Fondos del login'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if ($currenUserType == UsersModel::TYPE_USER_ROOT) : ?>
                        <a class="item" href="<?= AppConfigController::routeName('generals'); ?>">
                            <div class="figure"><?= getIcon("hammer-outline") ?></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Otras configuraciones'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (AppConfigController::allowedRoute('seo')) : ?>
                        <a class="item" href="<?= AppConfigController::routeName('seo'); ?>">
                            <div class="figure"><?= getIcon("search-outline") ?></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Ajustes SEO'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (AppConfigController::allowedRoute('generals-sitemap-create')) : ?>
                        <span class="item" data-url="<?= AppConfigController::routeName('generals-sitemap-create'); ?>" sitemap-update-trigger>
                            <div class="figure"><?= getIcon("location-outline") ?></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Actualizar sitemap'); ?></div>
                        </span>
                    <?php endif; ?>

                    <?php if (AppConfigController::allowedRoute('email')) : ?>
                        <a class="item" href="<?= AppConfigController::routeName('email'); ?>">
                            <div class="figure"><?= getIcon("mail-outline") ?></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Configuración de emails'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (Roles::hasPermissions('configurations-routes', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('configurations-routes', [], true); ?>">
                            <div class="figure"><?= getIcon("arrow-forward-circle-outline") ?></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Rutas y permisos'); ?></div>
                        </a>
                    <?php endif; ?>

                    <?php if (Roles::hasPermissions('admin-error-log', $currenUserType)) : ?>
                        <a class="item" href="<?= get_route('admin-error-log', [], true); ?>" target="blank">
                            <div class="figure"><?= getIcon("bug-outline") ?></div>
                            <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Log de errores'); ?></div>
                        </a>
                    <?php endif; ?>

                </div>

            </div>
        <?php endif; ?>

        <?php if (false && Roles::hasPermissions('tickets-create', $currenUserType)) : ?>
            <div class="menu" title="<?= __(ADMIN_MENU_LANG_GROUP, 'Soporte técnico'); ?>" support-button-js>

                <div class="icon">
                    <i class="icon question"></i>
                </div>

            </div>
        <?php endif; ?>

        <div class="ui dropdown item user">
            <div class="avatar">
                <?php if ($user->hasAvatar) : ?>
                    <img src="<?= $user->avatar; ?>">
                <?php else : ?>
                    <img src="<?= base_url("statics/images/default-avatar.png") ?>">
                <?php endif; ?>
            </div>
            <div class="menu">
                <a class="item user" href="<?= get_route('users-form-profile') . '?onlyProfile=yes'; ?>">
                    <div class=" figure">
                        <?php if ($user->hasAvatar) : ?>
                            <img src="<?= $user->avatar; ?>">
                        <?php else : ?>
                            <img src="<?= base_url("statics/images/default-avatar.png") ?>">
                        <?php endif; ?>
                    </div>
                    <div class="text">
                        <?= htmlentities(stripslashes($user->fullName)); ?>
                        <br>
                        <small><?= htmlentities(stripslashes($user->email)); ?></small>
                        <br>
                        <small><?= htmlentities(stripslashes(UsersModel::TYPES_USERS[$user->type])); ?></small>
                    </div>
                </a>
                <a class="item" href="<?= get_route('users-form-profile') . '?onlyImage=yes'; ?>">
                    <div class="figure"><?= getIcon("create-outline") ?></div>
                    <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Imagen de perfil'); ?></div>
                </a>
                <a class="item" pcsphp-users-logout>
                    <div class="figure"><?= getIcon("power-outline") ?></div>
                    <div class="text"><?= __(ADMIN_MENU_LANG_GROUP, 'Cerrar sesión'); ?></div>
                </a>
            </div>
        </div>
    </div>
</div>