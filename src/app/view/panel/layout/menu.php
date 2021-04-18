<?php

use App\Model\UsersModel;

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>


<aside class="ui vertical menu coky sidebar-menu">

    <?= menu_sidebar_items($user); ?>

    <article class="more">
        <?= get_config('menus')['sidebar_more']->getHtml() ?>
    </article>

</aside> <!-- .ui-pcs.sidebar -->