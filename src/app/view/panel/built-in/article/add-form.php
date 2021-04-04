<?php

use PiecesPHP\BuiltIn\Article\Controllers\ArticleController;

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
$currentLang = is_string($lang) ? $lang : get_config('app_lang');
$allowedLangs = get_config('allowed_langs');
$allowedLangsWithoutCurrent = array_filter($allowedLangs, function ($e) use ($currentLang) {
    return $e != $currentLang;
});
$allowedLangsWithoutCurrent = array_map(function ($lang) {
    $link = ArticleController::routeName('forms-add-lang', [
        'lang' => $lang,
    ]);
    return  "<div class='item' data-value='$link'>" . __('lang', $lang) . "</div>";
}, $allowedLangsWithoutCurrent);

$langGroup = ArticleController::LANG_GROUP;
?>

<div style="max-width:992px;">

    <h3><?= __($langGroup, 'Agregar'); ?> <?= $title; ?></h3>

    <br>

    <form pcsphp-articles method='POST' action="<?= $action; ?>" class="ui form" quill="<?= $quill_proccesor_link; ?>">

        <input type="hidden" name="lang" value="<?= $currentLang; ?>">

        <div class="">

            <a href="<?= $back_link; ?>" class="ui secondary mini button"><i class="icon left arrow"></i></a>
            <button type="submit" class="ui primary mini button"><?= __($langGroup, 'Guardar'); ?></button>

        </div>

        <br><br>

        <?php if (count($allowedLangsWithoutCurrent) > 0) : ?>

            <div>
                <div>
                    <small><?= __($langGroup, 'Cambiar idioma'); ?>:</small>
                </div>
                <div class="ui selection dropdown lang-selector">
                    <div class="text"><?= __('lang', $currentLang); ?></div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?= implode(' ', $allowedLangsWithoutCurrent); ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <div class="ui top attached tabular menu">
            <div class="item active" data-tab="content"><?= __($langGroup, 'Contenido'); ?></div>
            <div class="item" data-tab="images"><?= __($langGroup, 'Imágenes'); ?></div>
            <div class="item" data-tab="details"><?= __($langGroup, 'Detalles'); ?></div>
            <div class="item" data-tab="seo"><?= __($langGroup, 'SEO'); ?></div>
        </div>

        <div class="ui bottom attached tab segment active" data-tab='content'>

            <div class="field required">
                <label><?= __($langGroup, 'Título'); ?></label>
                <input required type="text" name="title" maxlength="255">
            </div>

            <div class="field required">
                <label><?= __($langGroup, 'Contenido'); ?></label>
                <div quill-editor></div>
                <textarea name="content" required></textarea>
            </div>

        </div>

        <div class="ui bottom attached tab segment" data-tab='images'>

            <div class="ui form cropper-adapter" cropper-image-main>

                <div class="field required">
                    <label><?= __($langGroup, 'Imagen principal'); ?></label>
                    <input type="file" accept="image/*" required>
                </div>

                <?php $this->_render('panel/built-in/utilities/cropper/workspace.php', [
                    'referenceW' => '800',
                    'referenceH' => '600',
                ]); ?>

            </div>

            <div class="ui form cropper-adapter" cropper-image-thumb>

                <div class="field required">
                    <label><?= __($langGroup, 'Imagen miniatura'); ?></label>
                    <input type="file" accept="image/*" required>
                </div>

                <?php $this->_render('panel/built-in/utilities/cropper/workspace.php', [
                    'referenceW' => '400',
                    'referenceH' => '300',
                ]); ?>

            </div>

        </div>

        <div class="ui bottom attached tab segment" data-tab='details'>

            <div class="field required">
                <label><?= __($langGroup, 'Categoría'); ?></label>
                <select required class='ui dropdown' name="category"><?= $options_categories; ?></select>
            </div>

            <div class="two fields">

                <div class="field" calendar-group-js='periodo' start>
                    <label><?= __($langGroup, 'Iniciar'); ?></label>
                    <input type="text" name="start_date" autocomplete="off">
                </div>

                <div class="field" calendar-group-js='periodo' end>
                    <label><?= __($langGroup, 'Finalizar'); ?></label>
                    <input type="text" name="end_date" autocomplete="off">
                </div>

            </div>

        </div>

        <div class="ui bottom attached tab segment" data-tab='seo'>

            <div class="ui form cropper-adapter" cropper-image-og>

                <div class="field">
                    <label><?= __($langGroup, 'Imagen'); ?></label>
                    <input type="file" accept="image/*">
                </div>

                <?php $this->_render('panel/built-in/utilities/cropper/workspace.php', [
                    'referenceW' => '1200',
                    'referenceH' => '600',
                ]); ?>

            </div>

            <div class="field">
                <label><?= __($langGroup, 'Descripción'); ?></label>
                <textarea name="seo_description"></textarea>
            </div>

        </div>

    </form>

</div>