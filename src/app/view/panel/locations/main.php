<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use PiecesPHP\Core\Roles;

$type_user = (int) get_config('current_user')->type;
$elements = [
    [
        'title' => __(LOCATIONS_LANG_GROUP, 'Países'),
        'description' => __(LOCATIONS_LANG_GROUP, 'Listado de los países'),
        'image' => base_url('statics/images/cards/countries.jpg'),
        'route_list' => 'locations-countries-list',
        'route_add' => 'locations-countries-forms-add',
        'has_list_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_list, $type_user);
        },
        'has_add_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_add, $type_user);
        },
    ],
    [
        'title' => __(LOCATIONS_LANG_GROUP, 'Departamentos'),
        'description' => __(LOCATIONS_LANG_GROUP, 'Listado de los departamentos'),
        'image' => base_url('statics/images/cards/states.jpg'),
        'route_list' => 'locations-states-list',
        'route_add' => 'locations-states-forms-add',
        'has_list_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_list, $type_user);
        },
        'has_add_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_add, $type_user);
        },
    ],
    [
        'title' => __(LOCATIONS_LANG_GROUP, 'Ciudades'),
        'description' => __(LOCATIONS_LANG_GROUP, 'Listado de las ciudades'),
        'image' => base_url('statics/images/cards/cities.jpg'),
        'route_list' => 'locations-cities-list',
        'route_add' => 'locations-cities-forms-add',
        'has_list_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_list, $type_user);
        },
        'has_add_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_add, $type_user);
        },
    ],
    [
        'title' => __(LOCATIONS_LANG_GROUP, 'Localidades'),
        'description' => __(LOCATIONS_LANG_GROUP, 'Listado de las localidades'),
        'image' => base_url('statics/images/cards/points.jpg'),
        'route_list' => 'locations-points-list',
        'route_add' => 'locations-points-forms-add',
        'has_list_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_list, $type_user);
        },
        'has_add_permission' => function ($stdClass) use ($type_user) {
            return Roles::hasPermissions($stdClass->route_add, $type_user);
        },
    ],
];
$elements = array_map(function ($e) {
    return (object) $e;
}, $elements);
?>

<div container-cards-locations>

    <div class="ui cards">

        <?php foreach ($elements as $element) : ?>
            <?php if (($element->has_list_permission)($element) || ($element->has_add_permission)($element)) : ?>
                <div class="card">
                    <?php if (isset($element->image)) : ?>
                        <div class="image">
                            <img src="<?= $element->image; ?>">
                        </div>
                    <?php endif; ?>
                    <div class="content">
                        <div class="header">
                            <?= $element->title; ?>
                        </div>
                        <div class="meta">
                            <?= $element->description; ?>
                        </div>
                    </div>
                    <div class="extra content">
                        <div class="ui two buttons">
                            <?php if (($element->has_list_permission)($element)) : ?>
                                <a href="<?= get_route($element->route_list); ?>" class="ui secondary button"><?= __(LOCATIONS_LANG_GROUP, 'Ver'); ?></a>
                            <?php endif; ?>
                            <?php if (($element->has_add_permission)($element)) : ?>
                                <a href="<?= get_route($element->route_add); ?>" class="ui primary button"><?= __(LOCATIONS_LANG_GROUP, 'Agregar'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>

</div>

<style>
    [container-cards-locations] {
        max-width: 700px;
    }
</style>