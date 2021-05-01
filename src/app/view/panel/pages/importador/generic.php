<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Controller\ImporterController;

$langGroup = ImporterController::LANG_GROUP;
?>
<div class="ui header large"><?= $title; ?></div>
<div class="ui divider"></div>
<p class="section-description"><?= $text; ?></p>
<div class="ui placeholder segment">
    <div class="ui two column very relaxed stackable grid">
        <div class="column">
            <div import-result-js>
                <br>
                <div class="ui header medium"><?= __($langGroup, 'Resultado de la importación'); ?></div>
                <div class="ui statistics">
                    <div class="statistic">
                        <div class="value">
                            <i class="cloud upload icon"></i>
                            <span class="number total">0</span>
                        </div>
                        <div class="label"><?= __($langGroup, 'Total'); ?></div>
                    </div>
                    <div class="statistic">
                        <div class="value">
                            <i class="check icon"></i>
                            <span class="number success">0</span>
                        </div>
                        <div class="label"><?= __($langGroup, 'Exitosos'); ?></div>
                    </div>
                    <div class="statistic">
                        <div class="value">
                            <i class="close icon"></i>
                            <span class="number errors">0</span>
                        </div>
                        <div class="label"><?= __($langGroup, 'Errores'); ?></div>
                    </div>
                </div>
                <div>
                    <br>
                    <button view-detail class="ui primary button"><i class="icon eye"></i> <?= __($langGroup, 'Ver detalle'); ?></button>
                    <br>
                </div>
                <div class="ui modal messages">
                    <div class="header"><?= __($langGroup, 'Detalles de la importación'); ?></div>
                    <div class="content"></div>
                </div>
                <br><br>
            </div>
            <br>
            <form action="<?= $action ?>" method="POST" class="ui form" enctype="multipart/form-data" importer-js>
                <div class="field">
                    <label><?= __($langGroup, 'Subir archivo excel'); ?></label>
                    <input type="file" name="archivo" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv">
                </div>
                <div class="field">
                    <button type="submit" class="ui primary button positive">
                        <i class="upload icon"></i>
                        <?= __($langGroup, 'Subir'); ?>
                    </button>
                </div>
            </form>
        </div>
        <div class="middle right aligned column">
            <br>
            <a class="ui huge header" href="<?= $template; ?>" download>
                <i class="file excel icon"></i>
                <div class="content">
                    <div class="sub header"><?= __($langGroup, 'Descargar'); ?></div>
                    <?= __($langGroup, 'Plantilla'); ?>
                </div>
            </a>
        </div>
    </div>
    <div class="ui vertical divider hidden-767"></div>
</div>