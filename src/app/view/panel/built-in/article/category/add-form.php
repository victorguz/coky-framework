<?php

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use PiecesPHP\BuiltIn\Article\Controllers\ArticleController;

$allowed_langs = get_config('allowed_langs');
$isFirst = true;

$langGroup = ArticleController::LANG_GROUP;
?>

<div style="max-width:992px;">

    <h3><?= __($langGroup, 'Agregar'); ?> <?= $title; ?></h3>

    <div class="">
        <a href="<?= $back_link; ?>" class="ui secondary mini button"><i class="icon left arrow"></i></a>
    </div>

    <br><br>

    <form method='POST' action="<?= $action; ?>" class="ui form category">

        <div class="ui top attached tabular menu">
            <?php foreach ($allowed_langs as $lang) : ?>

                <?php if ($isFirst) : ?>

                    <div class="item active" data-tab="<?= $lang; ?>"><?= __('lang', $lang); ?></div>

                <?php else : ?>

                    <div class="item" data-tab="<?= $lang; ?>"><?= __('lang', $lang); ?></div>

                <?php endif; ?>

                <?php $isFirst = false; ?>

            <?php endforeach; ?>

        </div>

        <?php $isFirst = true; ?>

        <?php foreach ($allowed_langs as $lang) : ?>

            <div class="ui bottom attached tab segment<?= $isFirst ? ' active' : ''; ?>" data-tab='<?= $lang; ?>'>

                <div class="field">
                    <label><?= __($langGroup, 'Nombre'); ?></label>
                    <input type="text" name="properties[<?= $lang; ?>][name]" maxlength="255">
                </div>

                <div class="field">
                    <label><?= __($langGroup, 'Descripción'); ?></label>
                    <input type="text" name="properties[<?= $lang; ?>][description]">
                </div>

            </div>

            <?php $isFirst = false; ?>

        <?php endforeach; ?>

        <div class="field">
            <button type="submit" class="ui primary mini button"><?= __($langGroup, 'Guardar'); ?></button>
        </div>

    </form>

</div>