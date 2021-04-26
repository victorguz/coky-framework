<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>

<div class="ui header"><?= __($langGroup, 'Imágenes de marca'); ?></div>

<div class="ui form">
    <div class="two fields">


        <div class="field">
            <div class="ui segment">
                <div class="ui header small"><?= __($langGroup, 'Íconos de favoritos (favicon)'); ?></div>
                <div class="flex gap-1 overflow-x container-logos-favicons" style="height: fit-content;">

                    <form action="<?= $actionURL; ?>" method="POST" class="public-favicon">

                        <div class="cropper-adapter">

                            <input type="file" accept="image/*" required>
                            <?php

                            cropperAdapterWorkSpace([
                                'withTitle' => false,
                                'image' => $publicFavicon,
                                'containerW' => '200',
                                'containerH' => '200',
                                'objectFit' => 'contain',
                                'backgroundColor' => 'white',
                                'padding' => '10px',
                                'radius' => true,
                                'shadow' => true,
                                'referenceW' => '400',
                                'referenceH' => '400',
                                'cancelButtonText' => null,
                                'saveButtonText' => __($langGroup, 'Seleccionar imagen'),
                                'submit' => __($langGroup, 'Guardar imagen'),
                                'controls' => [
                                    'rotate' => true,
                                    'flip' => true,
                                    'adjust' => true,
                                ],
                            ]);


                            ?>
                        </div>

                    </form>

                    <form action="<?= $actionURL; ?>" method="POST" class="back-favicon">

                        <div class="cropper-adapter">

                            <input type="file" accept="image/*" required>
                            <?php
                            cropperAdapterWorkSpace([
                                'withTitle' => false,
                                'image' => $backFavicon,
                                'containerW' => '200',
                                'containerH' => '200',
                                'objectFit' => 'cover',
                                'backgroundColor' => 'white',
                                'padding' => '10px',
                                'radius' => true,
                                'shadow' => true,
                                'referenceW' => '400',
                                'referenceH' => '400',
                                'cancelButtonText' => null,
                                'saveButtonText' => __($langGroup, 'Seleccionar imagen'),
                                'submit' => __($langGroup, 'Guardar imagen'),
                                'controls' => [
                                    'rotate' => true,
                                    'flip' => true,
                                    'adjust' => true,
                                ],
                            ]);

                            ?>

                        </div>

                    </form>

                </div>
            </div>
        </div>
        <div class="field">
            <div class="ui segment">
                <div class="ui header small"><?= __($langGroup, 'Logos'); ?></div>

                <div class="flex gap-1 overflow-x container-logos-favicons">

                    <form action="<?= $actionURL; ?>" method="POST" class="logo">

                        <div class="cropper-adapter">

                            <input type="file" accept="image/*" required>
                            <?php
                            cropperAdapterWorkSpace([
                                'withTitle' => false,
                                'image' => $logo,
                                'containerW' => '200',
                                'containerH' => '200',
                                'objectFit' => 'cover',
                                'backgroundColor' => 'white',
                                'padding' => '10px',
                                'radius' => true,
                                'shadow' => true,
                                'referenceW' => '400',
                                'referenceH' => '400',
                                'cancelButtonText' => null,
                                'saveButtonText' => __($langGroup, 'Seleccionar imagen'),
                                'submit' => __($langGroup, 'Guardar imagen'),
                                'controls' => [
                                    'rotate' => true,
                                    'flip' => true,
                                    'adjust' => true,
                                ],
                            ]);

                            ?>

                        </div>

                    </form>

                    <form action="<?= $actionURL; ?>" method="POST" class="white-logo">

                        <div class="cropper-adapter">

                            <input type="file" accept="image/*" required>
                            <?php
                            cropperAdapterWorkSpace([
                                'withTitle' => false,
                                'image' => $whiteLogo,
                                'containerW' => '200',
                                'containerH' => '200',
                                'objectFit' => 'cover',
                                'backgroundColor' => 'black',
                                'padding' => '10px',
                                'radius' => true,
                                'shadow' => true,
                                'referenceW' => '400',
                                'referenceH' => '400',
                                'cancelButtonText' => null,
                                'saveButtonText' => __($langGroup, 'Seleccionar imagen'),
                                'submit' => __($langGroup, 'Guardar imagen'),
                                'controls' => [
                                    'rotate' => true,
                                    'flip' => true,
                                    'adjust' => true,
                                ],
                            ]);

                            ?>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>