<?php

use App\Model\UsersModel;

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>


<aside class="ui-pcs sidebar">

    <div class="first">
        <div class="logo">
            <div class="back">
                <img src="<?= get_config('logo'); ?>">
            </div>
        </div>

        <article class="links">
            <?= menu_sidebar_items($user); ?>
        </article>

        <article class="more">
            <a href="#"> <?= getIcon("grid outline") ?></a>
        </article>
    </div>

    <!-- <div class="logo-developed">
        <small class="version"><?= strReplaceTemplate(__('general', 'Versión {ver}'), ['{ver}' => APP_VERSION,]) ?></small>
        <small>All rights reserved to <strong><?= get_config("title_app") ?></strong></small>
        <small><?= __('general', 'Desarrollado por'); ?> <?= get_config('developer'); ?></small>
    </div> -->
</aside> <!-- .ui-pcs.sidebar -->