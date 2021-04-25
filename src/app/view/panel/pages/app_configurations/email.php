<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use PiecesPHP\Core\ConfigHelpers\MailConfig;

/**
 * @var MailConfig $element
 */
$element;
?>


<form action="<?= $emailActionUrl; ?>" method="POST" class="ui segment form email">
    <div class="ui header"><?= __($langGroup, 'Configuración de emails'); ?></div>

    <div class="two fields">

        <div class="field required">
            <label><?= __($langGroup, 'Host'); ?></label>
            <input type="text" name="host" value="<?= $element->host(); ?>" required>
        </div>

        <div class="field required">
            <label><?= __($langGroup, 'Protocolo'); ?></label>
            <input type="text" name="protocol" value="<?= $element->protocol(); ?>" required>
        </div>

        <div class="field required">
            <label><?= __($langGroup, 'Puerto'); ?></label>
            <input type="text" name="port" value="<?= $element->port(); ?>" required>
        </div>

    </div>

    <div class="ui divider"></div>

    <div class="two fields">
        <div class="field">
            <label><?= __($langGroup, 'Nombre del remitente'); ?></label>
            <input type="text" name="name" value="<?= $element->name(); ?>">
        </div>

        <div class="field">
            <label><?= __($langGroup, 'Correo electrónico'); ?></label>
            <input type="text" name="user" value="<?= $element->user(); ?>">
        </div>

        <div class="field">
            <label><?= __($langGroup, 'Contraseña'); ?></label>
            <div class="ui icon input" show-hide-password-event>
                <input type="password" name="password" value="<?= htmlentities($element->password()); ?>">
                <i class="eye link icon"></i>
            </div>
        </div>

    </div>

    <div class="ui divider"></div>

    <div class="flex row-centered gap-1">
        <div class="flex row-centered">
            <div class="ui toggle checkbox">
                <input type="checkbox" name="auto_tls" <?= $element->autoTls() ? 'checked' : ''; ?>>
                <label><?= __($langGroup, 'Auto TLS'); ?></label>
            </div>
        </div>

        <div class="flex row-centered">
            <div class="ui toggle checkbox">
                <input type="checkbox" name="auth" <?= $element->auth() ? 'checked' : ''; ?>>
                <label><?= __($langGroup, 'Autenticar'); ?></label>
            </div>
        </div>
    </div>
    <br>
    <div class="field">
        <button type="submit" class="ui primary fluid mini button"><?= __($langGroup, 'Guardar'); ?></button>
    </div>

</form>

<div class="ui segment">
    <div class="ui header small"><?= __($langGroup, 'Configuración extra de emails'); ?></div>

    <form pcs-generic-handler-js action="<?= $actionGenericURL; ?>" method="POST" class="ui form">

        <div class="two fields">
            <div class="field">
                <label><?= __($langGroup, 'Correo electrónico de recepción'); ?></label>
                <input type="text" name="user" value="<?= get_config("mail_recipient")["mail"] ?>">
            </div>
            <div class="field">
                <label><?= __($langGroup, 'Nombre del receptor'); ?></label>
                <input type="text" name="name" value="<?= get_config("mail_recipient")["name"] ?>">
            </div>
            <div class="field">
                <label for="button"><?= __($langGroup, 'Guardar'); ?></label>
                <button type="submit" class="ui primary fluid button"><?= __($langGroup, 'Guardar'); ?></button>
            </div>
        </div>
    </form>
</div>