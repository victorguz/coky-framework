<?php

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>


<div class="coky-multiple-view">
    <?php if (isset($slide) && $slide) : ?>

        <div class="buttons slider">
            <div class="content">
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
            </div>
        </div>

    <?php else : ?>

        <div class="buttons">
            <div class="content">
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
                <?= $links ?>
            </div>
        </div>

    <?php endif ?>

</div>