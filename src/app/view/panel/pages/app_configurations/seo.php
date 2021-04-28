<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>


<div class="container-seo">


    <form action="<?= $actionURL; ?>" method="POST" class="ui form seo">

        <div class="ui segment">
            <div class="ui header no-margin"><?= __($langGroup, 'Ajustes SEO'); ?></div>

            <div class="two fields">

                <div class="field required">
                    <label><?= __($langGroup, 'Título del sitio'); ?></label>
                    <input type="text" name="titleApp" value="<?= $titleApp; ?>" placeholder="<?= __($langGroup, 'Nombre'); ?>" required>
                </div>

                <div class="field required">
                    <label><?= __($langGroup, 'Propietario'); ?></label>
                    <input type="text" name="owner" value="<?= $owner; ?>" placeholder="<?= __($langGroup, 'Propietario'); ?>" required>
                </div>

            </div>


            <div class="field">
                <label><?= __($langGroup, 'Palabras clave'); ?></label>
                <select name="keywords[]" multiple class="ui dropdown multiple search selection keywords">
                    <?= $keywords; ?>
                </select>
            </div>


            <div class="field">
                <label for="opengraph">Opengraph (Imagen de 1200x630px)</label>

                <div class="content">

                    <div class="ui form cropper-adapter">

                        <input type="file" accept="image/*">

                        <?php
                        cropperAdapterWorkSpace([
                            'withTitle' => false,
                            'image' => $openGraph,
                            'containerW' => '100%',
                            'containerH' => '200',
                            'fullWidth' => true,
                            'objectFit' => 'cover',
                            'backgroundColor' => 'white',
                            'radius' => true,
                            'shadow' => true,
                            'referenceW' => '1200',
                            'referenceH' => '630',
                            'cancelButtonText' => null,
                            'saveButtonText' => __($langGroup, 'Seleccionar imagen'),
                            'controls' => [
                                'rotate' => true,
                                'flip' => true,
                                'adjust' => true,
                            ],
                        ]);
                        ?>
                    </div>

                </div>

            </div>

            <div class="field required">
                <label><?= __($langGroup, 'Descripción'); ?></label>
                <textarea required name="description" placeholder="<?= __($langGroup, 'Descripción de la página.'); ?>" required rows="3"><?= $description; ?></textarea>
            </div>
        </div>


        <div class="ui segment">
            <div class="field">
                <label><?= __($langGroup, 'Scripts adicionales'); ?></label>
                <textarea name="extraScripts" placeholder="<?= __($langGroup, "<script src='ejemplo.js'></script>"); ?>"><?= $extraScripts; ?></textarea>
            </div>
        </div>


        <div class="field">
            <button class="ui primary mini fluid button" type="submit">
                <?= __($langGroup, 'Guardar'); ?>
            </button>
        </div>


    </form>

</div>