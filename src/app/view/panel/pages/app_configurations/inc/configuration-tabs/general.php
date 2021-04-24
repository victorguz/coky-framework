<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Model\AppConfigModel;

$primary_color = AppConfigModel::getConfigValue('primary_color');
$primary_color = "";

//colors
$colors = [
    "primary" => [
        'name' => 'primary_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('primary_color'))
    ],
    "secondary" => [
        'name' => 'secondary_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('secondary_color'))
    ],
    "back" => [
        'name' => 'back_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('back_color'))
    ],
    "dark" => [
        'name' => 'dark_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('dark_color'))
    ],
    "gray" => [
        'name' => 'gray_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('gray_color'))
    ],
    "danger" => [
        'name' => 'danger_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('danger_color'))
    ],
    "success" => [
        'name' => 'success_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('success_color'))
    ],
    "alert" => [
        'name' => 'alert_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('alert_color'))
    ],
    "info" => [
        'name' => 'info_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('info_color'))
    ],
    "sb" => [
        'name' => 'sidebar_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('sidebar_color'))
    ],
    "sb_selected" => [
        'name' => 'sidebar_button_selected_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('sidebar_button_selected_color'))
    ],
    "sb_hover" => [
        'name' => 'sidebar_button_hover_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('sidebar_button_hover_color'))
    ],
    "sb_txt" => [
        'name' => 'sidebar_text_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('sidebar_text_color'))
    ],
    "sb_txt_hover" => [
        'name' => 'sidebar_text_hover_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('sidebar_text_hover_color'))
    ],
    "nb" => [
        'name' => 'navbar_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('navbar_color'))
    ],
    "nb_hover" => [
        'name' => 'navbar_hover_color',
        'value' => htmlentities(AppConfigModel::getConfigValue('navbar_hover_color'))
    ],
];
?>
<?php if (mb_strlen($actionGenericURL) > 0) : ?>

    <form pcs-generic-handler-js action="<?= $actionGenericURL; ?>" method="POST">

        <input type="hidden" name="reload" value="true">

        <div class="flex column-centered no-margin gap">
            <div class="ui header no-margin">Paleta de colores</div>
            <div class="ui segment rounded no-margin flex row-centered fluid">
                <div class="flex gap overflow-x" style="width: 95%;">
                    <?php foreach ($colors as $alias => $value) : ?>
                        <?php $alias = mb_strlen($alias) > 8 ? substr($alias, 0, 6) . ".." : $alias ?>
                        <div class="flex-column-centered">
                            <input type="color" name="value[]" value="<?= $value["value"] ?>" color-picker-js>
                            <input type="hidden" name="name[]" value="<?= $value["name"] ?>">
                            <input type="hidden" name="parse[]" value="uppercase">
                            <small style="white-space: nowrap;"><?= strtitlecase(str_replace("_", " ", $alias)) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="ui primary mini button"><?= __($langGroup, 'Guardar'); ?></button>
        </div>



    </form>

<?php endif; ?>