<?php
$image = isset($image) && is_string($image) ? $image : '';

if (isset($withTitle)) {
    $withTitle = $withTitle === true;
} else {
    $withTitle = true;
}

if (isset($radius)) {
    if (is_bool($radius) && $radius) {
        $radius = " border-radius:5px; ";
    } else if (is_string($radius)  && ctype_digit($radius)) {
        $radius = " border-radius:{$radius}px; ";
    }
}

if (isset($shadow)) {
    if (is_bool($shadow) && $shadow) {
        $shadow = " box-shadow:0 1px 2px rgba(0, 0, 0, 0.2); ";
    } else if (is_string($shadow)  && mb_strlen($shadow) > 0) {
        $shadow = " box-shadow:$shadow; ";
    }
}

if (isset($objectFit)) {
    if (is_bool($objectFit) && $objectFit) {
        $objectFit = " object-fit:cover; ";
    } else if (is_string($objectFit)  && mb_strlen($objectFit) > 0) {
        $objectFit = " object-fit:$objectFit; ";
    }
}

if (isset($submit)) {
    if (is_bool($submit) && $submit) {
        $submit = "<button type='submit' class='ui primary mini button'>" . __($langGroup, 'Guardar imagen') . "</button>";
    } else if (is_string($submit)  && mb_strlen($submit) > 0) {
        $submit = "<button type='submit' class='ui primary mini button'>$submit</button>";
    }
}

if (isset($containerW)) {
    if (is_string($containerW) && is_numeric($containerW)) {
        $containerW = (int) $containerW;
    }
}

if (isset($containerH)) {
    if (is_string($containerH) && is_numeric($containerH)) {
        $containerH = (int) $containerH;
    }
}

$containerW = !is_int($containerW) ? 200 : $containerW;
$containerH = !is_int($containerH) ? 150 : $containerH;

if (isset($referenceW)) {
    if (is_string($referenceW) && is_numeric($referenceW)) {
        $referenceW = (int) $referenceW;
    }
}

if (isset($referenceH)) {
    if (is_string($referenceH) && is_numeric($referenceH)) {
        $referenceH = (int) $referenceH;
    }
}

$referenceW = !is_int($referenceW) ? 1920 : $referenceW;
$referenceH = !is_int($referenceH) ? 1080 : $referenceH;

?>

<div class="preview" style="width: <?= $containerW ?>px;height: <?= $containerH ?>px;">
    <img src="<?= "img-gen/$referenceW/$referenceH"; ?>" style="<?= isset($objectFit) ? $objectFit : "" ?> <?= isset($shadow) ? $shadow : "" ?> <?= isset($radius) ? $radius : "" ?> width: <?= $containerW ?>px;height: <?= $containerH ?>px;">
    <div class="flex row-centered">
        <?= isset($submit) ? $submit : "" ?>
        <button class="ui secondary mini button" type="button" start></button>
    </div>
</div>

<div class="workspace">

    <div class="steps">

        <div class="step add" cropper-step-add>

            <div class="ui header medium centered"><?= __(CROPPER_ADAPTER_LANG_GROUP, 'Agregar imagen'); ?></div>

            <div class="placeholder">

                <div class="content">
                    <div>
                        <i class="upload icon"></i>
                        <button class="ui secondary mini button fluid" type="button" load-image><?= __(CROPPER_ADAPTER_LANG_GROUP, 'Seleccionar imagen'); ?></button>
                    </div>
                </div>

            </div>

        </div>

        <div class="ui mini modal step edit" cropper-step-edit>
            <i class="close icon" cancel></i>
            <?php $this->_render('panel/built-in/utilities/cropper/inc/notes.php', [
                'notes' => isset($notes) && is_array($notes) ? $notes : null,
            ]); ?>
            <?php if ($withTitle) : ?>
                <div class="field required">
                    <label><?= __(CROPPER_ADAPTER_LANG_GROUP, 'Título de la imagen'); ?></label>
                    <input type="text" cropper-title-export>
                </div>
            <?php else : ?>
                <input type="hidden" cropper-title-export>
            <?php endif; ?>

            <div class="field">
                <canvas data-image='<?= $image ?>'></canvas>
            </div>

            <div class="actions">
                <?php $this->_render('panel/built-in/utilities/cropper/controls.php', [
                    'notes' => isset($notes) && is_array($notes) ? $notes : null,
                    'controls' => isset($controls) && is_array($controls) ? $controls : null,
                ]); ?>
                <?php $this->_render('panel/built-in/utilities/cropper/main-buttons.php', [
                    'saveButtonText' => isset($saveButtonText) && is_string($saveButtonText) ? $saveButtonText : __(CROPPER_ADAPTER_LANG_GROUP, 'Guardar imagen'),
                ]); ?>
            </div>

        </div>

    </div>




</div>