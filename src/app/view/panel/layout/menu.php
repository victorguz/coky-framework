<?php

use App\Model\UsersModel;

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>


<aside class="coky sidebar-menu">

    <div class="ui vertical menu links">
        <?= menu_sidebar_items($user); ?>
    </div>

</aside> <!-- .ui-pcs.sidebar -->