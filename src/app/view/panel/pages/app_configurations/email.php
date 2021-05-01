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
        <button type="submit" class="ui primary fluid button"><?= __($langGroup, 'Guardar'); ?></button>
    </div>

</form>

<?php if (mb_strlen($actionGenericURL) > 0) : ?>

    <div class="ui form">

        <div class="field">
            <div class="ui segment">
                <form pcs-generic-handler-js action="<?= $actionGenericURL; ?>" method="POST">
                    <div class="ui header small"><?= __($langGroup, 'Configuración extra de emails'); ?></div>
                    <strong><?= __($langGroup, 'Correo electrónico de recepción'); ?></strong>
                    <div class="ui action input">
                        <input type="text" name="value" value="<?= get_config("mail_recipient") ?>">
                        <input type="hidden" name="name" value="mail_recipient">
                        <input type="hidden" name="parse" value="lowercase">
                        <button type="submit" class="ui primary button"><?= __($langGroup, 'Guardar'); ?></button>
                    </div>
                </form>

                <form pcs-generic-handler-js action="<?= $actionGenericURL; ?>" method="POST">
                    <strong><?= __($langGroup, 'Nombre de receptor'); ?></strong>
                    <div class="ui action input">
                        <input type="text" name="value" value="<?= get_config("mail_recipient_name") ?>">
                        <input type="hidden" name="name" value="mail_recipient_name">
                        <input type="hidden" name="parse" value="lowercase">
                        <button type="submit" class="ui primary button"><?= __($langGroup, 'Guardar'); ?></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
<?php endif; ?>