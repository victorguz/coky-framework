<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
/**
 * @var \App\Locations\Mappers\PointMapper $element
 */
$element;
?>

<div style="max-width:992px;">

    <h3><?= __(LOCATIONS_LANG_GROUP, 'Editar'); ?> <?= $title; ?></h3>

    <div class="">
        <a href="<?= $back_link; ?>" class="ui secondary mini button"><i class="icon left arrow"></i></a>
    </div>

    <br><br>

    <form pcs-generic-handler-js method='POST' action="<?= $action; ?>" class="ui form">

        <input type="hidden" name="id" value="<?= $element->id; ?>">

        <div class="field required">
            <label><?= __(LOCATIONS_LANG_GROUP, 'País'); ?></label>
            <select required name="country" locations-component-auto-filled-country="<?= $element->city->state->country->id; ?>"></select>
        </div>

        <div class="field required">
            <label><?= __(LOCATIONS_LANG_GROUP, 'Departamento'); ?></label>
            <select required name="state" locations-component-auto-filled-state="<?= $element->city->state->id; ?>"></select>
        </div>

        <div class="field required">
            <label><?= __(LOCATIONS_LANG_GROUP, 'Ciudad'); ?></label>
            <select required name="city" locations-component-auto-filled-city="<?= $element->city->id; ?>"></select>
        </div>

        <div class="field required">
            <label><?= __(LOCATIONS_LANG_GROUP, 'Dirección'); ?> <small><?= __(LOCATIONS_LANG_GROUP, '(localidad, caserío, barrio, etc...)'); ?></small></label>
            <input type="text" name="address" required value="<?= htmlentities($element->address); ?>">
        </div>

        <div class="field required">
            <label class=''><?= __(LOCATIONS_LANG_GROUP, 'Ubicación en el mapa de la localidad'); ?></label>
            <input longitude-mapbox-handler name='longitude' type='hidden' required value="<?= $element->longitude; ?>">
            <input latitude-mapbox-handler name='latitude' type='hidden' required value="<?= $element->latitude; ?>">
        </div>

        <div class="field">
            <button set-satelital-view class="ui mini danger mini button"><?= __(LOCATIONS_LANG_GROUP, 'Vista satelital'); ?></button>
            <button set-draw-view class="ui mini danger mini button"><?= __(LOCATIONS_LANG_GROUP, 'Vista de dibujo'); ?></button>
            <button set-center-view class="ui mini danger mini button"><?= __(LOCATIONS_LANG_GROUP, 'Centrar'); ?></button>
            <small><?= __(LOCATIONS_LANG_GROUP, '(para mayor precisión, puede mover el cursor de posición)'); ?></small>
        </div>

        <div class="field">
            <div id="map">
            </div>
        </div>

        <div class="field required">
            <label><?= __(LOCATIONS_LANG_GROUP, 'Nombre'); ?></label>
            <input type="text" name="name" maxlength="255" value="<?= htmlentities($element->name); ?>">
        </div>

        <div class="field required">
            <label><?= __(LOCATIONS_LANG_GROUP, 'Activo/Inactivo'); ?></label>
            <select required name="active">
                <?= $status_options; ?>
            </select>
        </div>

        <div class="field">
            <button type="submit" class="ui primary mini button"><?= __(LOCATIONS_LANG_GROUP, 'Guardar'); ?></button>
        </div>

    </form>
</div>