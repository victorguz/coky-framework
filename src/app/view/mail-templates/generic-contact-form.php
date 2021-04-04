<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
$element = (object) $element;
?>


<h4><?= $title; ?></h4>
<table class="ui definition table" style="width:100%">
    <tbody>
        <tr>
            <td><strong>Name</strong></td>
            <td><?= $element->full_name ?></td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td><?= $element->email ?></td>
        </tr>
        <tr>
            <td><strong>Address</strong></td>
            <td><?= $element->address ?></td>
        </tr>
        <tr>
            <td><strong>Phone</strong></td>
            <td><?= $element->full_name ?></td>
        </tr>
        <tr>
            <td><strong>Privacy policy</strong></td>
            <td><?= $element->privacy_policy ? "Accepted" : "Not Accepted" ?></td>
        </tr>
        <tr>
            <td><strong>Send promo</strong></td>
            <td><?= $element->send_promo ? "Accepted" : "Not Accepted" ?></td>
        </tr>
        <tr>
            <td><strong>About</strong></td>
            <td>
                <?= $element->plan ? "<label class='ui label {$element->plan}'>{$element->plan}</label>" : "" ?>
                <?= $element->residence ? "<label class='ui label {$element->residence}'>{$element->residence}</label>" : "" ?>
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