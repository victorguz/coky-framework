<?php defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>"); ?>

<div style="max-width:992px;">

    <h3><?= $title; ?></h3>

    <div class="">
        <a href="<?= $back_link; ?>" class="ui secondary mini button"><i class="icon left arrow"></i></a>
        <?php if ($has_add_link_permissions) : ?>
            <a href="<?= $add_link; ?>" class="ui primary mini button"><?= __(LOCATIONS_LANG_GROUP, 'Agregar'); ?></a>
        <?php endif; ?>
    </div>

</div>

<br>
<br>

<div style="max-width:100%;">

    <table process="<?= $process_table; ?>" style='width:100%;' class="ui table striped sponsive celled nowrap">
        <thead>
            <tr>
                <th name='id' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'ID'); ?></th>
                <th name='name' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'Nombre'); ?></th>
                <th name='country' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'País'); ?></th>
                <th name='state' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'Departamento'); ?></th>
                <th name='city' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'Ciudad'); ?></th>
                <th name='address' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'Dirección'); ?></th>
                <th name='coords' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'Coordenadas'); ?></th>
                <th name='active' order='true' search='true'><?= __(LOCATIONS_LANG_GROUP, 'Activo/Inactivo'); ?></th>
                <th order='false' search='false'><?= __(LOCATIONS_LANG_GROUP, 'Acciones'); ?></th>
            </tr>
        </thead>
    </table>

</div>

<script>
    window.onload = () => {

        let table = $(`[process]`)
        let processURL = table.attr('process')
        dataTableServerProccesing(table, processURL, 10)

    }
</script>