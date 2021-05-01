<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
/**
 * @var PiecesPHP\BuiltIn\Article\Category\Mappers\CategoryMapper $element
 */
$element;
?>




<form pcs-generic-handler-js method='POST' action="<?= $action; ?>" class="ui form category">

    <div class="admin-title">
        <h1 class="ui header">
            <?= strphrasecase(__('general', 'Editar') . " " . $title); ?>
        </h1>
        <div class="">
            <button class="ui icon secondary button" coky-go-back-button>
                <i class="icon left arrow"></i>
                <?= __("general", "Volver") ?>
            </button>
            <?php if ($element->status != 1) : ?>
                <button type="submit" class="ui icon primary button"><?= __("general", "Marcar como leído") ?></button>
            <?php endif; ?>
        </div>
    </div>



    <input type="hidden" name="id" value="<?= $element->id; ?>">

    <table class="ui definition table" style="width:100%">
        <tbody>
            <tr>
                <td>Name</td>
                <td><?= $element->full_name ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= $element->email ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?= $element->address ?></td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><?= $element->full_name ?></td>
            </tr>
            <tr>
                <td>Privacy policy</td>
                <td><?= $element->privacy_policy ? "Accepted" : "Not Accepted" ?></td>
            </tr>
            <tr>
                <td>Send promo</td>
                <td><?= $element->send_promo ? "Accepted" : "Not Accepted" ?></td>
            </tr>
            <tr>
                <td>About</td>
                <td>
                    <?= $element->data && $element->data->plan ? "<label class='ui label {$element->data->plan}'>{$element->data->plan}</label>" : "" ?>
                    <?= $element->data && $element->data->residence ? "<label class='ui label {$element->data->residence}'>{$element->data->residence}</label>" : "" ?>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="ui message">
        <div class="header">
            Message
        </div>
        <p><?= $element->message ?></p>
    </div>
</form>