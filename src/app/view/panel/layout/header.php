<?php

use App\Model\UsersModel;

$user = get_config('current_user');
$user = is_object($user) ? new UsersModel($user->id) : null;

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>"); ?>

<!DOCTYPE html>
<html lang="<?= get_config('app_lang'); ?>" dlang="<?= get_config('default_lang'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <base href="<?= baseurl(); ?>">
    <?= \PiecesPHP\Core\Utilities\Helpers\MetaTags::getMetaTagsGeneric(); ?>
    <link rel="shortcut icon" href="<?= get_config('favicon-back'); ?>" type="image/x-icon">
    <?php load_css(['base_url' => "", 'custom_url' => ""]) ?>
    <style>
        :root {


            --navbar-color: <?= get_config("navbar_color"); ?>;
            --navbar-hover-color: <?= get_config("navbar_hover_color"); ?>;
            --navbar-height: <?= get_config("navbar_height"); ?>;
            --navbar-icon-size: <?= get_config("navbar_icon_size"); ?>;

            --primary-color: <?= get_config("primary_color"); ?>;
            --primary-color-hover: <?= get_config("primary_color_hover"); ?>;
            --primary-color-opacity: <?= get_config("primary_color") . "a3"; ?>;
            --secondary-color: <?= get_config("secondary_color"); ?>;
            --secondary-color-hover: <?= get_config("secondary_color_hover"); ?>;
            --secondary-color-opacity: <?= get_config("secondary_color") . "a3"; ?>;
            --back-color: <?= get_config("back_color"); ?>;
            --back-color-hover: <?= get_config("back_color_hover"); ?>;
            --back-color-opacity: <?= get_config("back_color") . "a3"; ?>;
            --gray-color: <?= get_config("gray_color"); ?>;
            --gray-color-hover: <?= get_config("gray_color_hover"); ?>;
            --gray-color-opacity: <?= get_config("gray_color") . "a3"; ?>;
            --dark-color: <?= get_config("dark_color"); ?>;
            --dark-color-hover: <?= get_config("dark_color_hover"); ?>;
            --dark-color-opacity: <?= get_config("dark_color") . "a3"; ?>;
            --danger-color: <?= get_config("danger_color"); ?>;
            --danger-color-hover: <?= get_config("danger_color_hover"); ?>;
            --danger-color-opacity: <?= get_config("danger_color") . "a3"; ?>;
            --alert-color: <?= get_config("alert_color"); ?>;
            --alert-color-hover: <?= get_config("alert_color_hover"); ?>;
            --alert-color-opacity: <?= get_config("alert_color") . "a3"; ?>;
            --success-color: <?= get_config("success_color"); ?>;
            --success-color-hover: <?= get_config("success_color_hover"); ?>;
            --success-color-opacity: <?= get_config("success_color") . "a3"; ?>;
            --info-color: <?= get_config("info_color"); ?>;
            --info-color-hover: <?= get_config("info_color_hover"); ?>;
            --info-color-opacity: <?= get_config("info_color") . "a3"; ?>;

            --default-radius: <?= get_config("default_radius"); ?>;
            --dropdown-radius: <?= get_config("dropdown_radius"); ?>;
            --card-radius: <?= get_config("card_radius"); ?>;
            --field-radius: <?= get_config("field_radius"); ?>;
            --button-radius: <?= get_config("button_radius"); ?>;

            --statics-url: <?= base_url("statics"); ?>;


            --sidebar-color: <?= get_config("sidebar_color"); ?>;
            --sidebar-icon-size: <?= get_config("sidebar_icon_size"); ?>;
            --sidebar-text-color: <?= get_config("sidebar_text_color"); ?>;
            --sidebar-text-hover-color: <?= get_config("sidebar_text-hover_color"); ?>;
            --sidebar-button-hover-color: <?= get_config("sidebar_button_hover_color"); ?>;
            --sidebar-button-selected-color: <?= get_config("sidebar_button_selected_color"); ?>;

        }
    </style>

</head>

<body>

    <?php if (ACTIVE_TIMER && !is_null($user)) : ?>
        <div timer-platform-js="<?= base64_encode(json_encode(['user_id' => $user->id, 'url' => get_route('timing-add')])); ?>">
        </div>
    <?php endif; ?>

    <?php $this->render('panel/layout/topbar'); ?>

    <div class="ui-pcs container-sidebar">

        <?php $this->render('panel/layout/menu'); ?>

        <div class="content super-content">