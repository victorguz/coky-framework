<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Model\AppConfigModel;

//colors
$colors = [
    "primary" => [
        'name' => 'primary_color',
        'value' => htmlentities(get_config('primary_color'))
    ],
    "primary hv" => [
        'name' => 'primary_color_hover',
        'value' => htmlentities(get_config('primary_color_hover'))
    ],
    "secondary" => [
        'name' => 'secondary_color',
        'value' => htmlentities(get_config('secondary_color'))
    ],
    "secondary hv" => [
        'name' => 'secondary_color_hover',
        'value' => htmlentities(get_config('secondary_color_hover'))
    ],
    "back" => [
        'name' => 'back_color',
        'value' => htmlentities(get_config('back_color'))
    ],
    "back hv" => [
        'name' => 'back_color_hover',
        'value' => htmlentities(get_config('back_color_hover'))
    ],
    "dark" => [
        'name' => 'dark_color',
        'value' => htmlentities(get_config('dark_color'))
    ],
    "dark hv" => [
        'name' => 'dark_color_hover',
        'value' => htmlentities(get_config('dark_color_hover'))
    ],
    "gray" => [
        'name' => 'gray_color',
        'value' => htmlentities(get_config('gray_color'))
    ],
    "gray hv" => [
        'name' => 'gray_color_hover',
        'value' => htmlentities(get_config('gray_color_hover'))
    ],
    "danger" => [
        'name' => 'danger_color',
        'value' => htmlentities(get_config('danger_color'))
    ],
    "danger hv" => [
        'name' => 'danger_color_hover',
        'value' => htmlentities(get_config('danger_color_hover'))
    ],
    "success" => [
        'name' => 'success_color',
        'value' => htmlentities(get_config('success_color'))
    ],
    "success hv" => [
        'name' => 'success_color_hover',
        'value' => htmlentities(get_config('success_color_hover'))
    ],
    "alert" => [
        'name' => 'alert_color',
        'value' => htmlentities(get_config('alert_color'))
    ],
    "alert hv" => [
        'name' => 'alert_color_hover',
        'value' => htmlentities(get_config('alert_color_hover'))
    ],
    "info" => [
        'name' => 'info_color',
        'value' => htmlentities(get_config('info_color'))
    ],
    "info hv" => [
        'name' => 'info_color_hover',
        'value' => htmlentities(get_config('info_color_hover'))
    ],
    "sb" => [
        'name' => 'sidebar_color',
        'value' => htmlentities(get_config('sidebar_color'))
    ],
    "sb_selected" => [
        'name' => 'sidebar_button_selected_color',
        'value' => htmlentities(get_config('sidebar_button_selected_color'))
    ],
    "sb_hover" => [
        'name' => 'sidebar_button_hover_color',
        'value' => htmlentities(get_config('sidebar_button_hover_color'))
    ],
    "sb_txt" => [
        'name' => 'sidebar_text_color',
        'value' => htmlentities(get_config('sidebar_text_color'))
    ],
    "sb_txt_hover" => [
        'name' => 'sidebar_text_hover_color',
        'value' => htmlentities(get_config('sidebar_text_hover_color'))
    ],
    "nb" => [
        'name' => 'navbar_color',
        'value' => htmlentities(get_config('navbar_color'))
    ],
    "nb_hover" => [
        'name' => 'navbar_hover_color',
        'value' => htmlentities(get_config('navbar_hover_color'))
    ],
];
?>
<?php if (mb_strlen($action) > 0) : ?>



    <div class="ui form segment">

        <div class="ui header">Temas</div>

        <div class="field">
            <label>Temas predefinidos</label>
            <div class="flex gap-1 overflow-x" style="width: 95%; margin-bottom: 15px;">
                <div class="flex column-centered" style="cursor: pointer;">
                    <div class="color-square">
                        <div class="primary"></div>
                        <div class="secondary"></div>
                        <div class="back"></div>
                    </div>
                    <small style="white-space: nowrap; cursor:pointer;" data-content="Tema 1">Tema 1</small>
                </div>
                <div class="flex column-centered" style="cursor: pointer;">
                    <div class="color-square active">
                        <div class="primary"></div>
                        <div class="secondary"></div>
                        <div class="back"></div>
                    </div>
                    <small style="white-space: nowrap; cursor:pointer;" data-content="Tema 2">Tema 2</small>
                </div>
            </div>
        </div>


        <form pcs-generic-handler-js action="<?= $action; ?>" method="POST">

            <input type="hidden" name="reload" value="true">

            <div class="field ">
                <label>Paleta de colores actual</label>
                <div class="flex overflow-x" style="width: 95%; margin-bottom: 15px;">
                    <?php foreach ($colors as $name => $value) : ?>
                        <?php $alias = mb_strlen($name) > 8 ? substr($name, 0, 6) . ".." : $name ?>
                        <div class="flex column-centered">
                            <input type="color" name="value[]" value="<?= $value["value"] ?>" color-picker-js>
                            <input type="hidden" name="name[]" value="<?= $value["name"] ?>">
                            <input type="hidden" name="parse[]" value="uppercase">
                            <small style="white-space: nowrap; cursor:pointer;" data-content="<?= strtitlecase(str_replace("_", " ", $value["name"])) ?>"><?= strtitlecase(str_replace("_", " ", $alias)) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="ui primary fluid button"><?= __($langGroup, 'Guardar'); ?></button>
            </div>



        </form>
    </div>

<?php endif; ?>