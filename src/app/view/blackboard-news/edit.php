<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Controller\BlackboardNewsController;

$langGroup = BlackboardNewsController::LANG_GROUP;
$new_types = is_string($new->types) ? json_decode($new->types) : $new->types;
?>
<div class="ui segment">

    <h3 class="ui header">
        <?= __($langGroup, 'Editar noticia'); ?>
    </h3>
    <form action="<?= $action ?>" method="POST" class="ui form" pcs-generic-handler-js>
        <input type="hidden" name="id" value="<?= $new->id; ?>">
        <input type="hidden" name="author" value="<?= $new->author->id; ?>">

        <div class="field required">
            <label><?= __($langGroup, 'Título'); ?></label>
            <input type="text" name="title" required value="<?= htmlentities($new->title); ?>">
        </div>

        <div class="field required">
            <label><?= __($langGroup, 'Mensaje'); ?></label>
            <div image-process="<?= get_route('blackboard-image-handler') ?>" image-name="image" rich-editor-js editor-target="[name='text']"><?= $new->text; ?></div>
            <textarea hidden name="text" required placeholder="<?= __($langGroup, "Escribe el mensaje aquí...") ?>" height='200px'><?= $new->text; ?></textarea>
        </div>

        <div class="two fields">
            <div class="field required">
                <label><?= __($langGroup, 'Visible para'); ?></label>
                <select required name="types[]" class="ui dropdown" multiple>
                    <option value=""><?= __($langGroup, 'Elija una opción'); ?></option>
                    <?php foreach ($types as $name => $value) : ?>
                        <?= createOption($value, $name, is_array($new_types) && in_array($value, $new_types)) ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label><?= __($langGroup, 'Fecha de inicio'); ?></label>
                <div calendar-group-js="test" start>
                    <input type="text" name="start_date" value="<?= !is_null($new->start_date) ? $new->start_date->format($date_format) : date($date_format) ?>">
                </div>
            </div>

            <div class="field">
                <label><?= __($langGroup, 'Fecha final'); ?></label>
                <div calendar-group-js="test" end>
                    <input type="text" name="end_date" value="<?= !is_null($new->end_date) ? $new->end_date->format($date_format) : ''; ?>">
                </div>
            </div>
        </div>

        <div class="field">
            <button class="ui primary button" type="submit"><?= __($langGroup, 'Guardar'); ?></button>
        </div>
    </form>
</div>