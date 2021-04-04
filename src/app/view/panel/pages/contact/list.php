<?php defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>"); ?>

<div style="max-width:992px;">
    <div class="admin-title">
        <h1 class="ui header">
            <?= $title; ?>
        </h1>
        <div class="">
            <a href="<?= $back_link; ?>" class="ui icon secondary mini button"><i class="icon left arrow"></i> <?= __("general", "Volver") ?></a>
        </div>
    </div>


    <table process="<?= $process_table; ?>" style='width:100%;' class="ui table striped nowrap celled">
        <thead>
            <tr>
                <th>ID <i class="trash icon"></i> </th>
                <th>Name</th>
                <th>Date</th>
                <th order='false'>Actions</th>
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