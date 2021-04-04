<?php

use App\Model\UsersModel;

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>


<aside style="--bg-color:<?= get_config('admin_menu_color'); ?>;" class="ui-pcs sidebar">

    <div class="first">
        <div class="logo">
            <div class="back">
                <img src="<?= get_config('logo'); ?>">
            </div>
        </div>

        <article class="links">
            <?= menu_sidebar_items($user); ?>
        </article>
    </div>

    <div class="logo-developed">
        <!-- <img src="<?= get_config('white-logo'); ?>"> -->
        <small class="version"><?= strReplaceTemplate(__('general', 'Versión {ver}'), ['{ver}' => APP_VERSION,]) ?></small>
        <small>All rights reserved to <strong><?= get_config("title_app") ?></strong></small>
        <small><?= __('general', 'Desarrollado por'); ?> <?= get_config('developer'); ?></small>
    </div>
</aside> <!-- .ui-pcs.sidebar -->