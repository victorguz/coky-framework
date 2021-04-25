<?php

/**
 * menu.php
 */

/**
 * Menús.
 * En este este archivo se pueden definir elementos útiles para generar menús
 */

use App\Contacts\ContactsController;
use App\Controller\AdminPanelController;
use App\Controller\AppConfigController;
use App\Model\UsersModel;
use PiecesPHP\Core\Config;
use PiecesPHP\Core\Menu\MenuGroup;
use PiecesPHP\Core\Menu\MenuGroupCollection;
use PiecesPHP\Core\Menu\MenuItem;
use PiecesPHP\Core\Menu\MenuItemCollection;
use PiecesPHP\Core\Roles;

if (is_object($user)) :
    /**
     * Current role
     */
    $role = Roles::getCurrentRole();
    /**
     * Checking role
     */
    $current_type_user = !is_null($role) ? $role['code'] : null;
    /**
     * IDK
     */
    $headerDropdown = new MenuItemCollection([
        'items' => [],
    ]);
    /**
     * If you want to see the text in all the sidebar buttons
     * (allways see a tooltip)
     */
    $show_text = true;
    $show_icon = true;
    $as_row = true;

    $home_button = new MenuGroup([
        'name' => __(ADMIN_MENU_LANG_GROUP, 'Inicio'),
        'icon' => getIcon("home outline"),
        'show_text' => $show_text,
        'show_icon' => $show_icon,
        'as_row' => $as_row,
        'visible' => Roles::hasPermissions('admin', $current_type_user),
        'asLink' => true,
        'href' => get_route('admin'),
    ]);


    /**
     * Sidebar container
     */
    $sidebar = new MenuGroupCollection([
        'items' => [
            $home_button,
            new MenuGroup([
                'name' => __("general", "Contactos"),
                'icon' => getIcon("call outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'visible' => Roles::hasPermissions('pages-contacts-list', $current_type_user),
                'asLink' => true,
                'href' => ContactsController::routeName("list"),
            ]),
            new MenuGroup([
                'name' => __('bi-shop', 'Tienda'),
                'icon' => getIcon("bag handle outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'visible' => Roles::hasPermissions('built-in-shop-private-entry-options', $current_type_user),
                'asLink' => true,
                'href' => get_route('built-in-shop-private-entry-options', [], true),
            ]),
            new MenuGroup([
                'name' => __('bi-dynamic-images', 'Imágenes'),
                'icon' => getIcon("images outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'visible' => \PiecesPHP\BuiltIn\DynamicImages\EntryPointController::allowedRoute('options'),
                'asLink' => true,
                'href' => \PiecesPHP\BuiltIn\DynamicImages\EntryPointController::routeName('options', [], true),
            ]),
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Blog'),
                'icon' => getIcon("create outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'visible' => Roles::hasPermissions('built-in-articles-list', $current_type_user),
                'asLink' => true,
                'href' => get_route('built-in-articles-list', [], true),
            ]),
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Ubicaciones'),
                'icon' => getIcon("location outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'visible' => Roles::hasPermissions('locations', $current_type_user),
                'asLink' => true,
                'href' => get_route('locations', [], true),
            ]),
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Noticias'),
                'icon' => getIcon("newspaper outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'visible' => Roles::hasPermissions('blackboard-news-list', $current_type_user),
                'asLink' => true,
                'href' => get_route('blackboard-news-list', [], true),
            ]),
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Mensajes'),
                'icon' => getIcon("chatbox ellipses outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'attributes' => [
                    'unread-threads' => get_route('messages-threads-status', [], true),
                ],
                'visible' => Roles::hasPermissions('messages-inbox', $current_type_user),
                'asLink' => true,
                'href' => get_route('messages-inbox', [], true),
            ]),
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Vista múltiple'),
                'icon' => getIcon("grid outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'visible' => Roles::hasPermissions('admin', $current_type_user),
                'asLink' => true,
                'href' => get_route('admin-multiple-view'),
                'position' => 3000,
            ])

        ],
    ]);

    $sidebar_config = new MenuGroupCollection([
        'items' => [
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'General'),
                'visible' => $current_type_user == UsersModel::TYPE_USER_ROOT,
                'href' =>  AppConfigController::routeName('generals'),
                'icon' => getIcon("settings-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'Logotipos'),
                'visible' => AppConfigController::allowedRoute('logos-favicons'),
                'href' =>  AppConfigController::routeName('logos-favicons'),
                'icon' => getIcon("heart-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'Fondos del login'),
                'visible' => AppConfigController::allowedRoute('backgrounds'),
                'href' =>  AppConfigController::routeName('backgrounds'),
                'icon' => getIcon("images-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'Ajustes SEO'),
                'visible' => AppConfigController::allowedRoute('seo'),
                'href' =>  AppConfigController::routeName('seo'),
                'icon' => getIcon("search-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'Actualizar sitemap'),
                'visible' => AppConfigController::allowedRoute('generals-sitemap-create'),
                'attributes' => [
                    "data-url" => AppConfigController::routeName('generals-sitemap-create'), "sitemap-update-trigger" => ""
                ],
                'icon' => getIcon("location-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'Configuración de emails'),
                'visible' => AppConfigController::allowedRoute('email'),
                'href' =>  AppConfigController::routeName('email'),
                'icon' => getIcon("mail-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'Rutas y permisos'),
                'visible' => Roles::hasPermissions('configurations-routes', $current_type_user),
                'href' =>  get_route('configurations-routes', [], true),
                'icon' => getIcon("person-add-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(AppConfigController::LANG_GROUP, 'Log de errores'),
                'visible' => Roles::hasPermissions('admin-error-log', $current_type_user),
                'href' =>  get_route('admin-error-log', [], true),
                'icon' => getIcon("bug-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Soporte técnico'),
                'visible' => Roles::hasPermissions('tickets-create', $current_type_user),
                'attributes' => [
                    'support-button-js' => ""
                ],
                'icon' => getIcon("help-outline"),
                'show_text' => $show_text,
                'show_icon' => $show_icon,
                'as_row' => $as_row,
                'asLink' => true
            ]),

        ]
    ]);

    ////////////////////////About topbar

    $topbar_show_icon = true;


    /**
     * topbar container
     */
    $topbar = new MenuGroupCollection([
        'items' => [
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Usuarios'),
                'icon' => getIcon("people-outline"),
                'show_text' => false,
                'show_icon' => $topbar_show_icon,
                'visible' =>
                Roles::hasPermissions('users-selection-create', $current_type_user) ||
                    Roles::hasPermissions('users-list', $current_type_user) ||
                    Roles::hasPermissions('importer-form', $current_type_user) ||
                    Roles::hasPermissions('informes-acceso', $current_type_user) ||
                    Roles::hasPermissions('informes-acceso', $current_type_user) ||
                    Roles::hasPermissions('informes-acceso', $current_type_user),
                'items' => [
                    new MenuItem([
                        'text' => __(AdminPanelController::LANG_GROUP, 'Agregar usuario'),
                        'icon' => getIcon("person-add-outline"),
                        'visible' => Roles::hasPermissions('users-selection-create', $current_type_user),
                        'href' =>  get_route('users-selection-create', [], true),
                        'show_icon' => $topbar_show_icon,
                    ]),
                    new MenuItem([
                        'text' => __(AdminPanelController::LANG_GROUP, 'Listado de usuarios'),
                        'icon' => getIcon("list-outline"),
                        'visible' => Roles::hasPermissions('users-list', $current_type_user),
                        'href' =>  get_route('users-list', [], true),
                        'show_icon' => $topbar_show_icon,
                    ]),
                    new MenuItem([
                        'text' => __(AdminPanelController::LANG_GROUP, 'Importar usuarios'),
                        'icon' => getIcon("cloud-upload-outline"),
                        'visible' => Roles::hasPermissions('importer-form', $current_type_user),
                        'href' =>  get_route('importer-form', ['type' => 'users'], true),
                        'show_icon' => $topbar_show_icon,
                    ]),
                    new MenuItem([
                        'text' => __(AdminPanelController::LANG_GROUP, 'Logueos de usuarios'),
                        'icon' => getIcon("log-in-outline"),
                        'visible' => Roles::hasPermissions('informes-acceso', $current_type_user),
                        'href' =>  get_route('informes-acceso') . "?attempts=yes",
                        'show_icon' => $topbar_show_icon,
                    ]),
                    new MenuItem([
                        'text' => __(AdminPanelController::LANG_GROUP, 'Usuarios sin logueos'),
                        'icon' => getIcon("alert-circle-outline"),
                        'visible' => Roles::hasPermissions('informes-acceso', $current_type_user),
                        'href' =>  get_route('informes-acceso') . "?not-logged=yes",
                        'show_icon' => $topbar_show_icon,
                    ]),
                    new MenuItem([
                        'text' => __(AdminPanelController::LANG_GROUP, 'Resumen de sesiones'),
                        'icon' => getIcon("document-text-outline"),
                        'visible' => Roles::hasPermissions('informes-acceso', $current_type_user),
                        'href' =>  get_route('informes-acceso') . "?logged=yes",
                        'show_icon' => $topbar_show_icon,
                    ]),
                ]
            ]),
            new MenuGroup([
                'name' => __(ADMIN_MENU_LANG_GROUP, 'Configuraciones'),
                'icon' => getIcon("settings-outline"),
                'show_text' => false,
                'show_icon' => $topbar_show_icon,
                'visible' =>
                AppConfigController::allowedRoute('logos-favicons') ||
                    AppConfigController::allowedRoute('backgrounds') ||
                    AppConfigController::allowedRoute('generals') ||
                    AppConfigController::allowedRoute('seo') ||
                    Roles::hasPermissions('admin-error-log', $current_type_user) ||
                    Roles::hasPermissions('configurations-routes', $current_type_user) ||
                    AppConfigController::allowedRoute('generals-sitemap-create') ||
                    AppConfigController::allowedRoute('email') ||
                    AppConfigController::allowedRoute('os-ticket'),
                'href' =>  AppConfigController::routeName('generals'),
                'asLink' => true
            ]),
            new MenuGroup([
                // 'name' => __(ADMIN_MENU_LANG_GROUP, 'Configuraciones'),
                'icon' => '<div class="avatar">' .
                    (is_object($user) && $user->hasAvatar ?
                        '<img src="<?= $user->avatar; ?>">'
                        : '<img src="statics/images/default-avatar.png">') .
                    '</div>',
                'show_text' => false,
                'show_icon' => $topbar_show_icon,
                'items' => [
                    new MenuItem([
                        'href' =>  get_route('users-form-profile') . '?onlyProfile=yes',
                        'icon' => '<div class="avatar">' .
                            (is_object($user) && $user->hasAvatar ?
                                '<img src="<?= $user->avatar; ?>">'
                                : '<img src="statics/images/default-avatar.png">') .
                            '</div>',
                        'text' => is_object($user) ? $user->fullName  .
                            "<small>$user->email</small>" .
                            "<small>" . UsersModel::TYPES_USERS[$user->type] . "</small>" : "",
                        'show_icon' => $topbar_show_icon,
                        'attributes' => ["user-avatar" => ""],
                    ]),
                    new MenuItem([
                        'href' =>  get_route('users-form-profile') . '?onlyImage=yes',
                        'icon' => getIcon("create-outline"),
                        'text' =>  __(AdminPanelController::LANG_GROUP, 'Imagen de perfil'),
                        'show_icon' => $topbar_show_icon,
                    ]),
                    new MenuItem([
                        'attributes' => ["pcsphp-users-logout" => ""],
                        'icon' => getIcon("power-outline"),
                        'text' => __(ADMIN_MENU_LANG_GROUP, 'Cerrar sesión'),
                        'show_icon' => $topbar_show_icon,
                    ]),
                ]
            ]),
        ],
    ]);

    //Idiomas
    $alternativesURL = Config::get_config('alternatives_url');

    $langsItem = new MenuGroup([
        'name' => __(ADMIN_MENU_LANG_GROUP, 'Idiomas'),
        'icon' => getIcon("language outline"),
        'show_text' => false,
        'show_icon' => $topbar_show_icon,
    ]);

    foreach ($alternativesURL as $lang => $url) {
        $langItem = new MenuItem([
            'text' => __('lang', $lang),
            'visible' => true,
            'href' => $url,
        ]);
        $langsItem->addItem($langItem);
    }
    // $home_button->setShowText(false);
    //Añadir menús a la configuración global
    $config['menus']['header_dropdown'] = $headerDropdown;
    $config['menus']['sidebar'] = $sidebar;
    $config['menus']['topbar'] = $topbar;
    $config['menus']['languages_link'] = $langsItem;
    $config['menus']['sidebar_config'] = $sidebar_config;
    $config['menus']['home_button'] = $home_button;

endif;
