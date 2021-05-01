<?php
$image = isset($image) && is_string($image) ? $image : '';

if (isset($withTitle)) {
    $withTitle = $withTitle === true;
} else {
    $withTitle = true;
}

if (isset($backgroundColor)) {
    if (is_bool($backgroundColor) && $backgroundColor) {
        $backgroundColor = " background-color:white; ";
    } else if (is_string($backgroundColor)  && mb_strlen($backgroundColor) > 0) {
        $backgroundColor = " background-color:{$backgroundColor}; ";
    }
} else {
    $backgroundColor = " background-color:transparent; ";
}
if (isset($padding)) {
    if (is_bool($padding) && $padding) {
        $padding = " padding:5px; ";
    } else if (is_string($padding)) {
        $padding = " padding:{$padding}; ";
    }
} else {
    $padding = " padding:0; ";
}

if (isset($radius)) {
    if (is_bool($radius) && $radius) {
        $radius = " border-radius:5px; ";
    } else if (is_string($radius)  && ctype_digit($radius)) {
        $radius = " border-radius:{$radius}px; ";
    } else {
        $radius = " border-radius:0px; ";
    }
} else {
    $radius = " border-radius:5px; ";
}

if (isset($shadow)) {
    if (is_bool($shadow) && $shadow) {
        $shadow = " box-shadow:0 1px 2px rgba(0, 0, 0, 0.2); ";
    } else if (is_string($shadow)  && mb_strlen($shadow) > 0) {
        $shadow = " box-shadow:$shadow; ";
    }
} else {
    $shadow = " box-shadow:0 1px 2px rgba(0, 0, 0, 0.2); ";
}

if (isset($objectFit)) {
    if (is_bool($objectFit) && $objectFit) {
        $objectFit = " object-fit:cover; ";
    } else if (is_string($objectFit)  && mb_strlen($objectFit) > 0) {
        $objectFit = " object-fit:$objectFit; ";
    }
} else {
    $objectFit = " object-fit:cover; ";
}

if (isset($submit)) {
    if (is_bool($submit) && $submit) {
        $submit = "<button type='submit' class='ui primary mini button'>" . __($langGroup, 'Guardar imagen') . "</button>";
    } else if (is_string($submit)  && mb_strlen($submit) > 0) {
        $submit = "<button type='submit' class='ui primary mini button'>$submit</button>";
    }
} else {
    $submit = "";
}

if (isset($containerW)) {
    if (is_string($containerW) && ctype_digit($containerW)) {
        $containerW = (int) $containerW;
    }
} else {
    $containerW = 200;
}

if (isset($containerH)) {
    if (is_string($containerH) && ctype_digit($containerH)) {
        $containerH = (int) $containerH;
    }
} else {
    $containerH = 150;
}


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

$referenceW = isset($referenceW) && is_int($referenceW) ?   $referenceW : 1920;
$referenceH = isset($referenceH) && is_int($referenceH) ?   $referenceH : 1080;

$containerBackW = is_numeric($containerW) ? ($containerW + 5) . "px" : $containerW;
$containerBackH = is_numeric($containerH) ? ($containerH + 45) . "px" : $containerH;

$containerImgW = is_numeric($containerW) ? $containerW . "px" : $containerW;
$containerImgH = is_numeric($containerH) ? $containerH . "px" : $containerH;

$backStyle = "style='width: $containerBackW;height: $containerBackH;min-height:fit-content;'";
$imageStyle = 'style="' . $padding .  $backgroundColor . $objectFit . $shadow . $radius . "width: 100%;height: $containerImgH;" . '"';
?>

<div class="preview" <?= $backStyle ?>>
    <img src="<?= "img-gen/$referenceW/$referenceH"; ?>" <?= $imageStyle ?>>
    <div class="flex row-centered">
        <?= isset($submit) ? $submit : "" ?>
        <button class="ui secondary mini button" type="button" start></button>
    </div>
</div>

<div class="workspace">

    <div class="steps">

        <div class="step add" cropper-step-add>

            <div class="ui header medium centered"><?= __(CROPPER_ADAPTER_LANG_GROUP, 'Agregar'); ?></div>

            <div class="placeholder">

                <div class="content">
                    <div>
                        <i class="upload icon"></i>
                        <button class="ui secondary mini button fluid" type="button" load-image><?= __(CROPPER_ADAPTER_LANG_GROUP, 'Seleccionar'); ?></button>
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
                    'saveButtonText' => isset($saveButtonText) && is_string($saveButtonText) ? $saveButtonText : __(CROPPER_ADAPTER_LANG_GROUP, 'Guardar'),
                ]); ?>
            </div>

        </div>

    </div>




</div>