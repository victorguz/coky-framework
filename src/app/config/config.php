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

$config["mail_recipient"] = "info@mtscorporation.site";
$config["mail_recipient_name"] = "info@mtscorporation.site";

$config["recapcha"] = [
    "url" => "https://www.google.com/recaptcha/api/siteverify",
    "site_key" => (is_local()
        ? "xxxxxxxx"
        : "xxxxxxxx"),
    "secret_key" => (is_local()
        ? "xxxxxxxx"
        : "xxxxxxxx")
];
//======Información complementaria para mostrar en la aplicación========

//Desarrollador
$config['developer'] = 'Victorguz and Ivanlnd';
$config['developer_link'] = 'mailto:victorguzber@gmail.com';
$config['app_version'] = '1.0';


//New Styles -- Sizes are PX
$config["navbar_color"] = "#ffffff";
$config["navbar_hover_color"] = "#dadde1";
$config["navbar_height"] = "48px";
$config["navbar_icon_size"] = "25px";

$config["back_color"] = "#F0F2F5";
$config["back_color_hover"] = "#F0F2F5";
$config["primary_color"] = "#A6C634";
$config["primary_color_hover"] = "#A6C634";
$config["secondary_color"] = "#343a40";
$config["secondary_color_hover"] = "#343a40";
$config["gray_color"] = "#DBE0DC";
$config["gray_color_hover"] = "#DBE0DC";
$config["dark_color"] = "#15214B";
$config["dark_color_hover"] = "#15214B";
$config["danger_color"] = "#FA6F6F";
$config["danger_color_hover"] = "#FA6F6F";
$config["alert_color"] = "#f1bc2a";
$config["alert_color_hover"] = "#f1bc2a";
$config["success_color"] = "#4cca79";
$config["success_color_hover"] = "#4cca79";
$config["info_color"] = "#1999c0";
$config["info_color_hover"] = "#1999c0";



$config["sidebar_color"] = "#F0F2F5";
$config["sidebar_icon_size"] = "25px";
$config["sidebar_button_selected_color"] = "#ffffff";
$config["sidebar_button_hover_color"] = "#dadde1";
$config["sidebar_text_color"] = "#000000";
$config["sidebar_text_hover_color"] = "#000000";



$config["default_radius"] = "5px";
$config["dropdown_radius"] = "5px";
$config["card_radius"] = "5px";
$config["field_radius"] = "8px";
$config["button_radius"] = "50px";

//Old styles
$config['meta_theme_color'] = "#ffffff";
$config['admin_menu_color'] = "#ffffff";
