<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

?>
<div class="ui header"><?= __($langGroup, 'Fondos'); ?></div>

<div class="ui segment ">
    <div class="ui header small"><?= __($langGroup, 'Fondos del login'); ?></div>


    <!-- <div style="width: 100%; height: 400px;">Cropper</div> -->

    <div class="ui special cards flex gap-1 overflow-x">

        <?php foreach (get_config('backgrounds') as $index => $background) : ?>

            <!-- <div class="card">
                <div class="blurring dimmable image" style="height: 100px; width: 200px;">
                    <div class="ui dimmer">
                        <div class="content">
                            <div class="center">
                                <div class="ui secondary mini button">Change image</div>
                            </div>
                        </div>
                    </div>
                    <img src="<?= $background ?>" alt="background<?= $index ?>" style="height: 100px; width: 200px;">
                </div>
            </div> -->


            <form bg="<?= ($index + 1); ?>" action="<?= $actionURL; ?>" method="POST">

                <div class="ui form cropper-adapter">

                    <input type="file" accept="image/*" required>
                    <?php
                    cropperAdapterWorkSpace([
                        'withTitle' => false,
                        'image' => $background,
                        'containerW' => '200',
                        'containerH' => '150',
                        'objectFit' => 'cover',
                        'radius' => true,
                        'shadow' => true,
                        'referenceW' => '1920',
                        'referenceH' => '1080',
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
        <?php endforeach; ?>
    </div>

</div>