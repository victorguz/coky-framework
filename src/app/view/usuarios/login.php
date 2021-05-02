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
            --primary-color: <?= get_config("primary_color"); ?>;
            --secondary-color: <?= get_config("secondary_color"); ?>;

            --back-color: <?= get_config("back_color"); ?>;

            --navbar-color: <?= get_config("navbar_color"); ?>;
            --navbar-hover-color: <?= get_config("navbar_hover_color"); ?>;
            --navbar-height: <?= get_config("navbar_height"); ?>;
            --navbar-icon-size: <?= get_config("navbar_icon_size"); ?>;

            --gray-color: <?= get_config("gray_color"); ?>;
            --dark-color: <?= get_config("dark_color"); ?>;

            --danger-color: <?= get_config("danger_color"); ?>;
            --alert-color: <?= get_config("alert_color"); ?>;
            --success-color: <?= get_config("success_color"); ?>;
            --info-color: <?= get_config("info_color"); ?>;

            --default-radius: <?= get_config("default_radius"); ?>;
            --dropdown-radius: <?= get_config("dropdown_radius"); ?>;
            --card-radius: <?= get_config("card_radius"); ?>;
            --field-radius: <?= get_config("field_radius"); ?>;


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

    <div class="login" style="--bg-color:<?= get_config('admin_menu_color'); ?>;">
        <section class="container" bg-js="<?= base64_encode(json_encode(get_config('backgrounds'))); ?>"></section>

        <section class="form">
            <div class="overlay"></div>

            <article class="form-container">

                <div class="problems-message-container">

                    <div class="content">

                        <div class="title">
                            <span class="mark"></span>
                            <span class="text"></span>
                        </div>

                        <div>
                            <p class="message"></p>

                            <span class="ui secondary mini button retry">
                                <?= __(USER_LOGIN_LANG_GROUP, 'Intentar nuevamente'); ?>
                            </span>
                        </div>

                        <div>
                            <p class="message-bottom"></p>

                            <a href="<?= get_route('user-problems-list') ?>" class="ui danger mini button">
                                <?= __(USER_LOGIN_LANG_GROUP, 'Ayuda para ingresar'); ?>
                            </a>
                        </div>

                    </div>

                </div>

                <div class="desc">
                    <div class="caption">
                        <img src="<?= get_config('logo'); ?>">
                    </div>

                    <div class="logo-developed">
                        <small class="version"><?= strReplaceTemplate(__('general', 'Versión {ver}'), ['{ver}' => APP_VERSION,]) ?></small>
                        <small>All rights reserved to <strong><?= get_config("title_app") ?></strong></small>
                        <small><?= __('general', 'Desarrollado por'); ?> <?= get_config('developer'); ?></small>
                    </div>
                </div>

                <form login-form-js last-uri='<?= $requested_uri; ?>' class="ui form">

                    <h1 class="ui header">Log in</h1>

                    <div class="field">
                        <label><?= __(USER_LOGIN_LANG_GROUP, 'Usuario'); ?></label>
                        <input type="text" required name='username' placeholder="<?= __(USER_LOGIN_LANG_GROUP, 'name@domain.com'); ?>">
                    </div>

                    <div class="field">
                        <label><?= __(USER_LOGIN_LANG_GROUP, 'Contraseña'); ?></label>
                        <input type="password" required name='password'>
                    </div>

                    <div class="field">
                        <button type="submit" class="ui primary fluid button"><?= __(USER_LOGIN_LANG_GROUP, 'Ingresar'); ?></button>
                    </div>

                    <a href="<?= get_route('user-problems-list') ?>">
                        <?= __(USER_LOGIN_LANG_GROUP, 'Ayuda para ingresar'); ?>
                    </a>



                </form>


            </article>


        </section>
    </div>

    <?php load_js(['base_url' => "", 'custom_url' => ""]) ?>

</body>

</html>