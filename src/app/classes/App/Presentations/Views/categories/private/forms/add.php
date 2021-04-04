<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

/**
 * @var string $langGroup
 * @var string $backLink
 * @var string $action
 */;
$langGroup;
$backLink;
$action;

?>

<div style="max-width:500px;">

    <h3><?= __($langGroup, 'Agregar'); ?>
        <?= $title; ?>
    </h3>

    <div class="ui buttons">

        <a href="<?= $backLink; ?>" class="ui labeled icon button">
            <i class="icon left arrow"></i>
            <?= __($langGroup, 'Regresar'); ?>
        </a>

    </div>

    <br><br>

    <form method='POST' action="<?= $action; ?>" class="ui form app-presentations-categories">

        <input type="hidden" name="lang" value="<?= \PiecesPHP\Core\Config::get_lang(); ?>">

        <div class="field required">
            <label><?= __($langGroup, 'Nombre'); ?></label>
            <input required type="text" name="name" maxlength="300">
        </div>

        <br><br>

        <div class="field">
            <button type="submit" class="ui button green"><?= __($langGroup, 'Guardar'); ?></button>
        </div>

    </form>
</div>
