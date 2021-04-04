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

<div style="--bg-color:<?= get_config('admin_menu_color'); ?>;" class="ui-pcs topbar">

    <div class="ui-pcs sidebar-toggle">
        <i class="icon bars"></i>
    </div>

    <div class="blank">

        <div class="nav-buttons">

            <?php if ($canViewUserOption) : ?>
                <div class="menu">

                    <div class="icon">
                        <i class="icon user cog"></i>
                    </div>

                    <div class="items">

                        <?php if (Roles::hasPermissions('users-selection-create', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('users-selection-create'); ?>">
                                <div class="figure"><i class="icon plus"></i></div>
                                <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Agregar usuario'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (Roles::hasPermissions('users-list', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('users-list'); ?>">
                                <div class="figure"><i class="icon users"></i></div>
                                <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Listado de usuarios'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (Roles::hasPermissions('importer-form', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('importer-form', ['type' => 'users'], true); ?>">
                                <div class="figure"><i class="icon upload"></i></div>
                                <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Importar usuarios'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (Roles::hasPermissions('informes-acceso', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('informes-acceso'); ?>?attempts=yes">
                                <div class="figure"><i class="icon sign in alternate"></i></div>
                                <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Logueos de usuarios'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (Roles::hasPermissions('informes-acceso', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('informes-acceso'); ?>?not-logged=yes">
                                <div class="figure"><i class="icon user times"></i></div>
                                <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Usuarios sin logueos'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (Roles::hasPermissions('informes-acceso', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('informes-acceso'); ?>?logged=yes">
                                <div class="figure"><i class="icon chart bar outline"></i></div>
                                <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Resumen de sesiones'); ?></div>
                            </a>
                        <?php endif; ?>

                    </div>

                </div>
            <?php endif; ?>

            <?php if ($canViewConfigsOption) : ?>
                <div class="menu" title="<?= __(AppConfigController::LANG_GROUP, 'Configuraciones'); ?>">

                    <div class="icon">
                        <i class="icon cog"></i>
                    </div>

                    <div class="items">

                        <?php if (AppConfigController::allowedRoute('logos-favicons')) : ?>
                            <a class="item" href="<?= AppConfigController::routeName('logos-favicons'); ?>">
                                <div class="figure"> <i class="icon heart"></i></div>
                                <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Logotipos'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (AppConfigController::allowedRoute('backgrounds')) : ?>
                            <a class="item" href="<?= AppConfigController::routeName('backgrounds'); ?>">
                                <div class="figure"> <i class="icon image"></i></div>
                                <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Fondos del login'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if ($currenUserType == UsersModel::TYPE_USER_ROOT) : ?>
                            <a class="item" href="<?= AppConfigController::routeName('generals'); ?>">
                                <div class="figure"> <i class="icon cogs"></i></div>
                                <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Otras configuraciones'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (AppConfigController::allowedRoute('seo')) : ?>
                            <a class="item" href="<?= AppConfigController::routeName('seo'); ?>">
                                <div class="figure"> <i class="icon google"></i></div>
                                <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Ajustes SEO'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (AppConfigController::allowedRoute('generals-sitemap-create')) : ?>
                            <span class="item" data-url="<?= AppConfigController::routeName('generals-sitemap-create'); ?>" sitemap-update-trigger>
                                <div class="figure"> <i class="icon map marker alternate"></i></div>
                                <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Actualizar sitemap'); ?></div>
                            </span>
                        <?php endif; ?>

                        <?php if (AppConfigController::allowedRoute('email')) : ?>
                            <a class="item" href="<?= AppConfigController::routeName('email'); ?>">
                                <div class="figure"> <i class="icon envelope outline"></i></div>
                                <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Configuración de emails'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (Roles::hasPermissions('configurations-routes', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('configurations-routes', [], true); ?>">
                                <div class="figure"> <i class="icon shield alternate"></i></div>
                                <div class="text"><?= __(AppConfigController::LANG_GROUP, 'Rutas y permisos'); ?></div>
                            </a>
                        <?php endif; ?>

                        <?php if (Roles::hasPermissions('admin-error-log', $currenUserType)) : ?>
                            <a class="item" href="<?= get_route('admin-error-log', [], true); ?>" target="blank">
                                <div class="figure"> <i class="icon times"></i></div>
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



        </div>

        <div class="user-info">

            <div class="avatar">
                <?php if ($user->hasAvatar) : ?>
                    <img src="<?= $user->avatar; ?>">
                <?php endif; ?>
            </div>

            <div class="info-menu">
                <a class="item avatar" href="<?= get_route('users-form-profile') . '?onlyProfile=yes'; ?>">
                    <div class=" figure">
                        <?php if ($user->hasAvatar) : ?>
                            <img src="<?= $user->avatar; ?>">
                        <?php else : ?>
                            <div class="no-avatar"></div>
                        <?php endif; ?>
                    </div>
                    <div class="text">
                        <?= htmlentities(stripslashes($user->fullName)); ?>
                        <br>
                        <small><?= htmlentities(stripslashes($user->username)); ?></small>
                        <br>
                        <small><?= htmlentities(stripslashes($user->email)); ?></small>
                    </div>
                </a>
                <a class="item" href="<?= get_route('users-form-profile') . '?onlyProfile=yes'; ?>">
                    <div class="figure"><i class="icon id card outline"></i></div>
                    <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Datos de perfil'); ?></div>
                </a>
                <a class="item" href="<?= get_route('users-form-profile') . '?onlyImage=yes'; ?>">
                    <div class="figure"><i class="icon user edit"></i></div>
                    <div class="text"><?= __(AdminPanelController::LANG_GROUP, 'Imagen de perfil'); ?></div>
                </a>
                <a class="item" pcsphp-users-logout>
                    <div class="figure"><i class="icon power off"></i></div>
                    <div class="text"><?= __(ADMIN_MENU_LANG_GROUP, 'Cerrar sesión'); ?></div>
                </a>
            </div>

        </div>
    </div>
</div>