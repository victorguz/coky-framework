<?php defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>"); ?>
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
            --secondary-color: <?= get_config("secondary_color"); ?>;
            --secondary-color-hover: <?= get_config("secondary_color_hover"); ?>;
            --back-color: <?= get_config("back_color"); ?>;
            --back-color-hover: <?= get_config("back_color_hover"); ?>;
            --gray-color: <?= get_config("gray_color"); ?>;
            --gray-color-hover: <?= get_config("gray_color_hover"); ?>;
            --dark-color: <?= get_config("dark_color"); ?>;
            --dark-color-hover: <?= get_config("dark_color_hover"); ?>;
            --danger-color: <?= get_config("danger_color"); ?>;
            --danger-color-hover: <?= get_config("danger_color_hover"); ?>;
            --alert-color: <?= get_config("alert_color"); ?>;
            --alert-color-hover: <?= get_config("alert_color_hover"); ?>;
            --success-color: <?= get_config("success_color"); ?>;
            --success-color-hover: <?= get_config("success_color_hover"); ?>;
            --info-color: <?= get_config("info_color"); ?>;
            --info-color-hover: <?= get_config("info_color_hover"); ?>;

            --default-radius: <?= get_config("default_radius"); ?>;
            --dropdown-radius: <?= get_config("dropdown_radius"); ?>;
            --card-radius: <?= get_config("card_radius"); ?>;
            --field-radius: <?= get_config("field_radius"); ?>;
            --button-radius: <?= get_config("button_radius"); ?>;


            --sidebar-color: <?= get_config("sidebar_color"); ?>;
            --sidebar-icon-size: <?= get_config("sidebar_icon_size"); ?>;
            --sidebar-text-color: <?= get_config("sidebar_text_color"); ?>;
            --sidebar-text-hover-color: <?= get_config("sidebar_text-hover_color"); ?>;
            --sidebar-button-hover-color: <?= get_config("sidebar_button_hover_color"); ?>;
            --sidebar-button-selected-color: <?= get_config("sidebar_button_selected_color"); ?>;

            --statics-url: <?= base_url("statics"); ?>;
        }
    </style>
</head>

<body>

    <div class="login">
        <section class="container backgrounds-container" bg-js="<?= base64_encode(json_encode(get_config('backgrounds'))); ?>">
            <div class="overlay">
                <div class="gradient"></div>
            </div>

        </section>

        <div class="login-container">

            <div class="desc">
                <a href="<?= base_url() ?>" class="logo" vanilla-tilt data-tilt-max="30" data-tilt-scale="1.4" ilt-speed="300" data-tilt-perspective="500">
                    <img src="<?= get_config('logo'); ?>" alt="logo <?= get_config("title_app") ?>">
                </a>

                <div class="caption">
                    <small class="version"><?= strReplaceTemplate(__('general', 'Versión {ver}'), ['{ver}' => get_config("app_version"),]) ?></small>
                    <small>All rights reserved to <strong><?= get_config("title_app") ?></strong></small>
                    <a href="<?= get_config('developer_link'); ?>"><?= __('general', 'Desarrollado por'); ?> <?= get_config('developer'); ?></a>
                </div>
            </div>



            <form pcs-generic-handler-js method="POST" action='<?= $send_email_action ?>' class="ui form login-form">

                <div class="desc">
                    <h1 class="ui header"><?= $lang_title ?></h1>
                    <a href="<?= base_url() ?>" class="logo" vanilla-tilt data-tilt-max="30" data-tilt-scale="1.4" ilt-speed="300" data-tilt-perspective="500">
                        <img src="<?= get_config('favicon'); ?>" alt="logo <?= get_config("title_app") ?>">
                    </a>
                </div>

                <div class="actions">
                    <h1 class="ui header"><?= $lang_title ?></h1>
                    <br>
                    <br>
                    <div class="field">
                        <label><?= __(\App\Controller\UserProblemsController::LANG_GROUP, 'Ingrese su correo electrónico') ?></label>
                        <input required type="email" name="email" placeholder="<?= __(\App\Controller\UserProblemsController::LANG_GROUP, 'name@domain.com') ?>">
                    </div>
                    <br>

                    <button type="submit" class="ui primary fluid button">
                        <?= __(\App\Controller\UserProblemsController::LANG_GROUP, 'Siguiente') ?>
                    </button>

                    <br>

                    <a href="<?= get_route('user-problems-select'); ?>">
                        <?= __(\App\Controller\UserProblemsController::LANG_GROUP, 'Atrás') ?>
                    </a>
                </div>



                <div class="desc">
                    <div class="caption">
                        <small class="version"><?= strReplaceTemplate(__('general', 'Versión {ver}'), ['{ver}' => get_config("app_version"),]) ?></small>
                        <small>All rights reserved to <strong><?= get_config("title_app") ?></strong></small>
                        <a href="<?= get_config('developer_link'); ?>"><?= __('general', 'Desarrollado por'); ?> <?= get_config('developer'); ?></a>
                    </div>
                </div>

            </form>
        </div>


    </div>

    <?php load_js(['base_url' => "", 'custom_url' => ""]) ?>

</body>

</html>