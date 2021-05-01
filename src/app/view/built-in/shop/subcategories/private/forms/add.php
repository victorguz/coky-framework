<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

/**
 * @var string $langGroup
 * @var string $backLink
 * @var string $action
 * @var string $optionsCategories
 */;
$langGroup;
$backLink;
$action;
$optionsCategories;

?>

<div style="max-width:992px;">

    <h3><?= __($langGroup, 'Agregar'); ?>
        <?= $title; ?>
    </h3>

    <div class="">

        <a href="<?= $backLink; ?>" class="ui labeled icon button">
            <i class="icon left arrow"></i>
            <?= __($langGroup, 'Regresar'); ?>
        </a>

    </div>

    <br><br>

    <form method='POST' action="<?= $action; ?>" class="ui form shop-subcategories">

        <div class="field required">
            <label><?= __($langGroup, 'Nombre'); ?></label>
            <input required type="text" name="name" maxlength="300">
        </div>

        <div class="field required">
            <label><?= __($langGroup, 'Categoría'); ?></label>
            <select name="category" class="ui dropdown search" required>
                <?= $optionsCategories; ?>
            </select>
        </div>

        <div class="field">
            <label><?= __($langGroup, 'Descripción'); ?></label>
            <input type="text" name="description" maxlength="300">
        </div>

        <div class="ui form cropper-adapter" cropper-main-image>

            <div class="field required">
                <label><?= __($langGroup, 'Imagen'); ?></label>
                <input required type="file" accept="image/*">
            </div>

            <?php $this->_render('panel/built-in/utilities/cropper/workspace.php', [
                'referenceW' => '400',
                'referenceH' => '300',
            ]); ?>

        </div>

        <div class="field">
            <button type="submit" class="ui primary button"><?= __($langGroup, 'Guardar'); ?></button>
        </div>

    </form>
</div>