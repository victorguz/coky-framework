<?php

use App\Model\UsersModel;

defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

$user = new UsersModel(get_config('current_user')->id);

?>

<div class="ui header huge">Statistics</div>

<input type="hidden" link_estadisticas value="<?= $link_estadisticas ?>">

<div class="ui form">
    <div class="two fields">
        <div class="field required">
            <label><?= __("general", "Fecha de inicio") ?></label>
            <div class="ui calendar" id="rangestart">
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="<?= __("general", "Fecha de inicio") ?>" name="start" required value="<?= date("Y-01-01") ?>">
                </div>
            </div>
        </div>
        <div class="field required">
            <label><?= __("general", "Fecha fin") ?></label>
            <div class="ui calendar" id="rangeend">
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="<?= __("general", "Fecha fin") ?>" name="end" required value="<?= date("Y-12-31") ?>">
                </div>
            </div>
        </div>
        <div class="field">
            <label>Refresh</label>
            <button type="button" class="ui fluid button" search-button>Refresh</button>
        </div>
    </div>
</div>

<div class="ui header">Statistics about amounts</div>

<div class="grid" id="grid">
    <!-- Usar statistics.js/generateChart para añadir una nueva estadistica aquí-->
</div>

<div class="ui header"> Statistics about quantity</div>

<div class="grid" id="contract_grid">
    <!-- Usar statistics.js/generateChart para añadir una nueva estadistica aquí-->
</div>

<style>
    .grid {
        display: flex;
        flex-flow: wrap;
        /* grid-template-columns: 40% 40%; */
        align-items: center;
        justify-content: center;
        width: 100%;
        gap: 20px;
    }

    .grid .item {
        position: relative;
        width: 40%;
    }

    @media screen and (max-width:768px) {

        .grid .item {
            width: 100%;
        }
    }
</style>


<script>
    window.onload = function(e) {

        $("#rangestart").calendar({
            startMode: 'year',
            type: 'date',
            endCalendar: $('#rangeend'),
            formatter: {
                date: function(date, settings) {
                    if (!date) return '';
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    return year + "-" + (month < 10 ? "0" + month : month) + '-' + (day < 10 ? "0" + day : day);
                }
            }
        })

        $("#rangeend").calendar({
            startMode: 'year',
            type: 'date',
            startCalendar: $('#rangestart'),
            formatter: {
                date: function(date, settings) {
                    if (!date) return '';
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    return year + "-" + (month < 10 ? "0" + month : month) + '-' + (day < 10 ? "0" + day : day);
                }
            }
        })
    }
</script>