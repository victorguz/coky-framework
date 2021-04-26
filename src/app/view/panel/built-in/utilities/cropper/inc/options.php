<?php

$defaultControls = [
    'rotate' => true,
    'flip' => true,
    'adjust' => true,
];

$controls = isset($controls) && is_array($controls) ? $controls : [];

$settedControls = [];

$control = 'rotate';
$settedControls[$control] = isset($controls[$control]) && is_bool($controls[$control]) ? $controls[$control] : $defaultControls[$control];

$control = 'flip';
$settedControls[$control] = isset($controls[$control]) && is_bool($controls[$control]) ? $controls[$control] : $defaultControls[$control];

$control = 'adjust';
$settedControls[$control] = isset($controls[$control]) && is_bool($controls[$control]) ? $controls[$control] : $defaultControls[$control];

?>
<div class="options" options>

    <div class="text"></div>

    <div class="text"></div>


    <div class="text"></div>


    <div class="text"></div>

    <?php if ($settedControls['rotate']) : ?>
        <div class="ui mini icon button option" option data-content="rotate" data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Girar'); ?>" data-option="rotate">


            <?= getIcon("sync-outline") ?>

        </div>
    <?php endif; ?>

    <?php if ($settedControls['flip']) : ?>
        <div class="ui mini icon button option" option data-content="flip" data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Voltear'); ?>" data-option="flip">
            <?= getIcon("swap-horizontal-outline") ?>
        </div>
    <?php endif; ?>

    <?php if ($settedControls['adjust']) : ?>
        <div class="ui mini icon button option" option data-content="adjust" data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Ajustar'); ?>" data-option="adjust">

            <?= getIcon("expand-outline") ?>

        </div>
    <?php endif; ?>

    <div class="ui mini icon button option" data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Cambiar'); ?>" option load-image>

        <?= getIcon("image-outline") ?>

    </div>

</div>

<div class="sub-options active" data-name="rotate">

    <div class="ui mini icon button option" option back-options data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Atrás'); ?>">
        <i class="arrow left icon"></i>
    </div>

    <div class="ui mini icon button option" option action-rotate-left data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Izquierda'); ?>">
        <i class="undo icon"></i>
    </div>

    <div class="ui mini icon button option" option action-rotate-right data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Derecha'); ?>">
        <i class="redo icon"></i>
    </div>

</div>

<div class="sub-options active" data-name="flip">

    <div class="ui mini icon button option" option back-options data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Atrás'); ?>">
        <i class="arrow left icon"></i>
    </div>

    <div class="ui mini icon button option" option action-flip-horizontal data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Horizontal'); ?>">
        <i class="arrows alternate horizontal icon"></i>
    </div>

    <div class="ui mini icon button option" option action-flip-vertical data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Vertical'); ?>">
        <i class="arrows alternate vertical icon"></i>
    </div>

</div>

<div class="sub-options active" data-name="adjust">

    <div class="ui mini icon button option" option back-options data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Atrás'); ?>">
        <i class="arrow left icon"></i>
    </div>

    <div class="ui mini icon button option" option action-move-up data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Arriba'); ?>">
        <i class="arrow alternate circle up outline icon"></i>
    </div>

    <div class="ui mini icon button option" option action-move-down data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Abajo'); ?>">
        <i class="arrow alternate circle down outline icon"></i>
    </div>

    <div class="ui mini icon button option" option action-move-left data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Izquierda'); ?>">
        <i class="arrow alternate circle left outline icon"></i>
    </div>

    <div class="ui mini icon button option" option action-move-right data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Derecha'); ?>">
        <i class="arrow alternate circle right outline icon"></i>
    </div>

    <div class="ui mini icon button option" option action-zoom-out data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Alejar'); ?>">
        <i class="search minus icon"></i>
    </div>

    <div class="ui mini icon button option" option action-zoom-in data-content="<?= __(CROPPER_ADAPTER_LANG_GROUP, 'Acercar'); ?>">
        <i class="search plus icon"></i>
    </div>

</div>