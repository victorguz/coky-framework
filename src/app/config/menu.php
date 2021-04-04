<?php

/**
 * menu.php
 */

/**
 * Menús.
 * En este este archivo se pueden definir elementos útiles para generar menús
 */

use App\Contacts\ContactsController;
use PiecesPHP\Core\Config;
use PiecesPHP\Core\Menu\MenuGroup;
use PiecesPHP\Core\Menu\MenuGroupCollection;
use PiecesPHP\Core\Menu\MenuItem;
use PiecesPHP\Core\Menu\MenuItemCollection;
use PiecesPHP\Core\Roles;

$role = Roles::getCurrentRole();
$current_type_user = !is_null($role) ? $role['code'] : null;

$headerDropdown = new MenuItemCollection([
    'items' => [],
]);

$sidebar = new MenuGroupCollection([
    'items' => [
        new MenuGroup([
            'name' => __(ADMIN_MENU_LANG_GROUP, 'Inicio'),
            'visible' => Roles::hasPermissions('admin', $current_type_user),
            'asLink' => true,
            'href' => get_route('admin'),
        ]),
        new MenuGroup([
            'name' => __("general", "Contactos"),
            'visible' => Roles::hasPermissions('pages-contacts-list', $current_type_user),
            'asLink' => true,
            'href' => ContactsController::routeName("list"),
        ]),
        new MenuGroup([
            'name' => __('bi-shop', 'Tienda'),
            'visible' => Roles::hasPermissions('built-in-shop-private-entry-options', $current_type_user),
            'asLink' => true,
            'href' => get_route('built-in-shop-private-entry-options', [], true),
        ]),
        new MenuGroup([
            'name' => __('bi-dynamic-images', 'Imágenes'),
            'visible' => \PiecesPHP\BuiltIn\DynamicImages\EntryPointController::allowedRoute('options'),
            'asLink' => true,
            'href' => \PiecesPHP\BuiltIn\DynamicImages\EntryPointController::routeName('options', [], true),
        ]),
        new MenuGroup([
            'name' => __(ADMIN_MENU_LANG_GROUP, 'Blog'),
            'visible' =>
            Roles::hasPermissions('built-in-articles-list', $current_type_user) ||
                Roles::hasPermissions('built-in-articles-categories-list', $current_type_user),
            'items' => [
                new MenuItem([
                    'text' => __(ADMIN_MENU_LANG_GROUP, 'Artículos'),
                    'href' => get_route('built-in-articles-list', [], true),
                    'visible' => Roles::hasPermissions('built-in-articles-list', $current_type_user),
                ]),
                new MenuItem([
                    'text' => __(ADMIN_MENU_LANG_GROUP, 'Categorías'),
                    'href' => get_route('built-in-articles-categories-list', [], true),
                    'visible' => Roles::hasPermissions('built-in-articles-categories-list', $current_type_user),
                ]),
            ],
        ]),
        new MenuGroup([
            'name' => __(ADMIN_MENU_LANG_GROUP, 'Ubicaciones'),
            'visible' => Roles::hasPermissions('locations', $current_type_user),
            'asLink' => true,
            'href' => get_route('locations', [], true),
        ]),
        new MenuGroup([
            'name' => __(ADMIN_MENU_LANG_GROUP, 'Noticias'),
            'visible' => Roles::hasPermissions('blackboard-news-list', $current_type_user),
            'asLink' => true,
            'href' => get_route('blackboard-news-list', [], true),
        ]),
        new MenuGroup([
            'name' => __(ADMIN_MENU_LANG_GROUP, 'Mensajes'),
            'attributes' => [
                'unread-threads' => get_route('messages-threads-status', [], true),
            ],
            'visible' => Roles::hasPermissions('messages-inbox', $current_type_user),
            'asLink' => true,
            'href' => get_route('messages-inbox', [], true),
        ]),

    ],
]);

//Idiomas
$alternativesURL = Config::get_config('alternatives_url');

$langsItem = new MenuGroup([
    'name' => __(ADMIN_MENU_LANG_GROUP, 'Idiomas'),
    'position' => 300,
]);

foreach ($alternativesURL as $lang => $url) {

    $langItem = new MenuItem([
        'text' => __('lang', $lang),
        'visible' => true,
        'href' => $url,
    ]);
    $langsItem->addItem($langItem);
}

$sidebar->addItem($langsItem);

//Añadir menús a la configuración global
$config['menus']['header_dropdown'] = $headerDropdown;
$config['menus']['sidebar'] = $sidebar;