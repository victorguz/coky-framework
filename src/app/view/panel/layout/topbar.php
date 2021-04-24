<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>

<div class="ui-pcs topbar ui menu">

    <a class="coky sidebar-toggle item">
        <?= getIcon("menu-outline") ?>
    </a>

    <?= get_config('menus')['languages_link']->getHTML() ?>

    <div class="blanc right menu">

        <?= menu_topbar_items($user); ?>

    </div>
</div>