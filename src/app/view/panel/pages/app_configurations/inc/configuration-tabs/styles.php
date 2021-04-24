<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Model\AppConfigModel;

$color1 = htmlentities(AppConfigModel::getConfigValue('meta_theme_color'));
$color1 = htmlentities(AppConfigModel::getConfigValue('meta_theme_color'));
$color1 = htmlentities(AppConfigModel::getConfigValue('meta_theme_color'));
$color1 = htmlentities(AppConfigModel::getConfigValue('meta_theme_color'));
$color1 = htmlentities(AppConfigModel::getConfigValue('meta_theme_color'));
$color1 = htmlentities(AppConfigModel::getConfigValue('meta_theme_color'));
?>
<?php if (mb_strlen($actionGenericURL) > 0) : ?>

    <form pcs-generic-handler-js action="<?= $actionGenericURL; ?>" method="POST" class="ui form">

        <div class="flex-column-centered  no-margin">
            <div class="ui header">Paleta de colores</div>
            <div class="ui segment rounded no-margin flex-row-centered fluid">
                <input type="color" name="value" value="<?= $color1 ?>" color-picker-js>
            </div>
        </div>


        <input type="hidden" name="name" value="meta_theme_color">
        <input type="hidden" name="parse" value="uppercase">
        <button type="submit" class="ui primary mini button"><?= __($langGroup, 'Guardar'); ?></button>
        <label><?= __($langGroup, 'Color de barra superior en navegadores móviles'); ?></label>

    </form>

    <br><br>

    <form pcs-generic-handler-js action="<?= $actionGenericURL; ?>" method="POST" class="ui form">

        <div class="field">
            <label><?= __($langGroup, 'Color del menú'); ?></label>
            <input type="color" name="value" value="<?= htmlentities(AppConfigModel::getConfigValue('admin_menu_color')); ?>" color-picker-js>
            <input type="hidden" name="name" value="admin_menu_color">
            <input type="hidden" name="parse" value="uppercase">
        </div>

        <div class="field">
            <button type="submit" class="ui primary mini button"><?= __($langGroup, 'Guardar'); ?></button>
        </div>

    </form>

    <br><br>

<?php endif; ?>