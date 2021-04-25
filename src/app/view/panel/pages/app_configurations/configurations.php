<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Controller\AppConfigController;

$langGroup = AppConfigController::LANG_GROUP;
$isFirstTitle = true;
$isFirstItem = true;
?>


<?php foreach ($tabsItems as $name => $content) : ?>
    <?php if ($isFirstItem) : $isFirstItem = false; ?>
        <?= $content; ?>
    <?php else : ?>
        <?= $content; ?>
    <?php endif; ?>
<?php endforeach; ?>


<script>
    window.addEventListener('load', function(e) {

        //Inicializaciones generales
        $('.ui.top.menu .item').tab()
        $('.ui.checkbox').checkbox()
        $('.ui.dropdown.additions')
            .dropdown({
                allowAdditions: true
            })

        //Eventos

        //Formulario ssl
        genericFormHandler(
            'form[ssl-configuration-form]', {
                onSetFormData: (formData, form) => {

                    formData.set(
                        'value[auto_tls]',
                        form.find(`[name="value[auto_tls]"]`).parent().checkbox('is checked') ? true :
                        false
                    )
                    formData.set(
                        'value[auth]',
                        form.find(`[name="value[auth]"]`).parent().checkbox('is checked') ? true : false
                    )

                    return formData
                },
            }
        )

    })
</script>