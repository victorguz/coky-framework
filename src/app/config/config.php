<?php

defined('BASEPATH') or die();

//========================================================================================
/*                                                                                      *
 *                           CONFIGURACIONES DE LA APLICACIÓN                           *
 *                                                                                      */
//========================================================================================
/**
 *
 * $config['default_lang']: Lenguaje por defecto de la aplicación
 * $config['app_lang']: Lenguaje actual de la aplicación
 *
 * $config['title_app']: Título de la aplicación en general
 * $config['title']: Título de la sección actual (set_title() y get_title())
 *
 * $config['base_url']: URL base de la aplicación
 *
 * $config['app_key']: Llave de la aplicación usada para la encriptación de todos los token y sesiones
 *
 * $['statics_path']: Este es el directorio por defecto donde buscará todas las solicitudes de archivos
 * estáticos. Si se quiere desactivar esta opción puede borrarse la ruta /statics/ de slim
 *
 */

//──── Generales ─────────────────────────────────────────────────────────────────────────

date_default_timezone_set('America/Bogota');

$config['base_url'] = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
$config['base_url'] = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/" . mb_substr($config['base_url'], 1);

$config['default_lang'] = "en";

$config['title_app'] = "Nombre Plataforma";
$config['owner'] = "Nombre Plataforma";

$config['keywords'] = [
    'Website',
    'Application',
];

$config['meta_theme_color'] = "#ffffff";
$config['admin_menu_color'] = "#ffffff";

$config['description'] = "Descripción de la página.";

$config['osTicketAPI'] = "";
$config['osTicketAPIKey'] = "";

//──── Seguridad ─────────────────────────────────────────────────────────────────────────

$config['app_key'] = 'secret';

//──── Statics ───────────────────────────────────────────────────────────────────────────
$config['statics_path'] = __DIR__ . '/../../statics';

//──── Extras ────────────────────────────────────────────────────────────────────────────

$config['mailjet'] = [
    'email' => 'correo@correo.com',
    'name' => 'Name',
    'apiKey' => 'API_KEY',
    'secretKey' => 'SECRET_KEY',
];

$config["mail_recipient"] = [
    "mail" => "info@mtscorporation.site",
    "name" => "MTS Corporation",
];

$config["recapcha"] = [
    "url" => "https://www.google.com/recaptcha/api/siteverify",
    "site_key" => (is_local()
        ? "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"
        : "6Ld0ZoIaAAAAAPIfk6k-iOmoIqRmuUO0JO-OAxK9"),
    "secret_key" => (is_local()
        ? "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe"
        : "6Ld0ZoIaAAAAAPZQBQt7kAJxhg_4PyNyNbzC7ZNp")
];
//======Información complementaria para mostrar en la aplicación========

//Desarrollador
$config['developer'] = 'Victorguz and Ivanlnd';
