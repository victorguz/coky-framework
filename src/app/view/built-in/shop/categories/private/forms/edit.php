<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use PiecesPHP\BuiltIn\Shop\Category\Controllers\CategoryMapper;

/**
 * @var CategoryMapper $element
 */
$element;

/**
 * @var string $langGroup
 * @var string $backLink
 * @var string $action
 */;
$langGroup;
$backLink;
$action;

?>

<div style="max-width:992px;">

    <h3><?= __($langGroup, 'Editar'); ?>
        <?= $title; ?>
    </h3>

    <div class="">

        <a href="<?= $backLink; ?>" class="ui labeled icon button">
            <i class="icon left arrow"></i>
            <?= __($langGroup, 'Regresar'); ?>
        </a>

    </div>

    <br><br>

    <form method='POST' action="<?= $action; ?>" class="ui form shop-categories">

        <input type="hidden" name="id" value="<?= $element->id; ?>">

        <div class="field required">
            <label><?= __($langGroup, 'Nombre'); ?></label>
            <input required type="text" name="name" maxlength="300" value="<?= $element->name; ?>">
        </div>

        <div class="field">
            <label><?= __($langGroup, 'Descripción'); ?></label>
            <input type="text" name="description" maxlength="300" value="<?= $element->description; ?>">
        </div>

        <div class="ui form cropper-adapter" cropper-main-image>

            <div class="field required">
                <label><?= __($langGroup, 'Imagen'); ?></label>
                <input type="file" accept="image/*">
            </div>

            <?php $this->_render('panel/built-in/utilities/cropper/workspace.php', [
                'referenceW' => '400',
                'referenceH' => '300',
                'image' => $element->image,
            ]); ?>

        </div>

        <div class="field">
            <button type="submit" class="ui primary mini button"><?= __($langGroup, 'Guardar'); ?></button>
        </div>

    </form>
</div>