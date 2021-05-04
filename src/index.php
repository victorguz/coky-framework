<?php

use App\Model\AppConfigModel;
use App\Model\UsersModel;
use PiecesPHP\Core\BaseToken;
use PiecesPHP\Core\Config;
use PiecesPHP\Core\ConfigHelpers\MailConfig;
use PiecesPHP\Core\Roles;
use PiecesPHP\Core\RouteGroup;
use PiecesPHP\Core\SessionToken;

require __DIR__ . '/app/core/bootstrap.php';

/**Assets globales */
require_once basepath("app/config/assets.php");

/**Rutas */

//Containers
require_once basepath("app/config/containers.php");

//Instancia del enrutador
set_config(
    'slim_container',
    new \Slim\Container($container_configurations)
);

if (get_config('control_access_login') === true) {
    add_global_requireds_assets([
        base_url('statics/core/js/user-system/PiecesPHPSystemUserHelper.js'),
        base_url('statics/core/js/user-system/main_system_user.js'),
    ], 'js');
}

if (APP_CONFIGURATION_MODULE) {

    $default_configurations_values = [
        'favicon' => 'statics/images/favicon.png',
        'favicon-back' => 'statics/images/favicon-back.png',
        'logo' => 'statics/images/logo.png',
        'white-logo' => 'statics/images/white-logo.png',
        'backgrounds' => [
            'statics/login-and-recovery/images/login/bg1.jpg',
            'statics/login-and-recovery/images/login/bg2.jpg',
            'statics/login-and-recovery/images/login/bg3.jpg',
            'statics/login-and-recovery/images/login/bg4.jpg',
            'statics/login-and-recovery/images/login/bg5.jpg',
        ],
        'open_graph_image' => 'statics/images/open_graph.jpg',
    ];

    $default_configurations_values['title_app'] = get_config('title_app');
    $default_configurations_values['mail'] = get_config('mail');
    $default_configurations_values['mail'] = $default_configurations_values['mail'] !== false ? $default_configurations_values['mail'] : [
        'auto_tls' => true,
        'protocol' => 'ssl',
        'host' => 'smtp.zoho.com',
        'auth' => true,
        'user' => 'correo@correo.com',
        'password' => '123456',
        'port' => 465,
    ];
    $default_configurations_values['owner'] = get_config('owner') !== false ? get_config('owner') : '';
    $default_configurations_values['description'] = get_config('description') !== false ? get_config('description') : 'Descripción de la página.';
    if (get_config('osTicketAPI') !== false) {
        $default_configurations_values['osTicketAPI'] = get_config('osTicketAPI');
    }
    if (get_config('osTicketAPIKey') !== false) {
        $default_configurations_values['osTicketAPIKey'] = get_config('osTicketAPIKey');
    }
    $default_configurations_values['meta_theme_color'] = get_config('meta_theme_color') !== false ? get_config('meta_theme_color') : '#13436C';
    $default_configurations_values['admin_menu_color'] = get_config('admin_menu_color') !== false ? get_config('admin_menu_color') : '#111213';
    $default_configurations_values['keywords'] = get_config('keywords') !== false ? get_config('keywords') : [
        'Website',
    ];

    ksort($default_configurations_values);
    AppConfigModel::initializateConfigurations($default_configurations_values);
}

$app = new \Slim\App(get_config('slim_container'));

//Acciones antes de mostrar una ruta
$app->add(function (\Slim\Http\Request $request, \Slim\Http\Response $response, callable $next) {

    //──── Idiomas ───────────────────────────────────────────────────────────────────────────
    $allowedLangs = Config::get_allowed_langs();
    $currenLang = Config::get_lang();
    $alternativesURL = [];

    foreach ($allowedLangs as $lang) {

        if ($currenLang != $lang) {

            $alternativesURL[$lang] = get_lang_url($currenLang, $lang);

        }

    }

    Config::set_config('alternatives_url', $alternativesURL);

    //──── Validaciones de sesión y redirecciones ────────────────────────────────────────────

    $route = $request->getAttribute('route');

    if (empty($route)) {
        throw new \Slim\Exception\NotFoundException($request, $response);
    }

    $JWT = SessionToken::getJWTReceived();
    $name_route = $route->getName(); //Nombre de la ruta
    $methods = $route->getMethods(); //Métodos que acepta la ruta solicitada
    $arguments = $route->getArguments(); //Argumentos pasados en la url
    $user = null; //Usuario activo

    //Control de acceso por login
    $control_access_login = get_config('control_access_login');

    //Verifica validez del JWT
    $isActiveSession = SessionToken::isActiveSession($JWT);

    //Verifica la validez del usuario activo si hay una sesion activa
    if ($isActiveSession) {

        $user = BaseToken::getData($JWT); //Información del usuario
        $validationUserObject = new class($user)
        {
            /**
             * El usuario entrante
             *
             * @var \stdClass
             */
            private $element = null;
            /**
             * El usuario
             *
             * @param \stdClass|mixed $user
             */
            function __construct($user)
            {
                $this->element = $user;
            }
            /**
             * Trata de buscar al usuario en la base de datos
             *
             * @return \stdClass|null
             */
            function getUserFromDatabase()
            {
                $user = null;

                if ($this->isValid()) {

                    $usersModel = UsersModel::model()->select()->where(['id' => $this->element->id]);
                    $usersModel->execute();
                    $user = $usersModel->result();
                    $user = count($user) > 0 ? $user[0] : null;

                    if ($user !== null) {
                        $fullname = [
                            trim(is_string($user->firstname) ? $user->firstname : ''),
                            trim(is_string($user->first_lastname) ? $user->first_lastname : ''),
                            trim(is_string($user->secondname) ? $user->secondname : ''),
                            trim(is_string($user->second_lastname) ? $user->second_lastname : ''),
                        ];
                        $user->fullName = trim(implode(' ', $fullname));
                    }

                }

                return $user;
            }
            /**
             * Valida que la variable de entrada sea un objeto con las
             * propiedades id y type válidas
             *
             * @return bool
             */
            function isValid()
            {
                return $this->hasID() && $this->validType();
            }
            /**
             * Valida que sea un objeto
             *
             * @return bool
             */
            function isObject()
            {
                return $this->element instanceof \stdClass;
            }
            /**
             * Valida que tenga un ID válido
             *
             * @return bool
             */
            function hasID()
            {
                $e = $this->element;
                return $this->isObject() && isset($e->id) && $this->isInteger($e->id);
            }
            /**
             * Valida que tenga un type de tipo válido
             *
             * @return bool
             */
            function hasType()
            {
                $e = $this->element;
                return $this->isObject() && isset($e->type) && $this->isInteger($e->type);
            }
            /**
             * Valida que el type exista
             *
             * @return bool
             */
            function validType()
            {
                $e = $this->element;
                return $this->hasType() && in_array((int) $e->type, array_keys(UsersModel::TYPES_USERS));
            }
            /**
             * Valida que sea un entero válido
             *
             * @param string|int $value
             * @return bool
             */
            function isInteger($value)
            {
                return (is_string($value) && ctype_digit($value)) || is_int($value);
            }

        };

        $user = $validationUserObject->getUserFromDatabase();

        if ($user !== null) {
            set_config('current_user', $user);
            Roles::setCurrentRole($user->type); //Se establece el rol
        } else {
            $isActiveSession = false;
            SessionToken::setMinimumDateCreated(new \DateTime());
        }

    }

    //Verifica si el control automático de acceso por login está activado
    if ($control_access_login) {

        $info_route = get_route_info($name_route); //Información de la ruta actual

        //Verifica si la ruta requiere estar logueado
        if ($info_route['require_login']) {

            //Acciones en caso de no estar logueado
            if (!$isActiveSession) {

                if ($name_route != 'users-login-form') {

                    if ($request->isXhr()) {

                        $url_login = remove_last_char_on('/', get_route('users-login-form'));
                        $referer = $request->getHeader('HTTP_REFERER');
                        $referer = isset($referer[0]) ? $referer[0] : '';
                        $referer = remove_last_char_on('/', $referer);

                        if ($referer != $url_login) {
                            return $response->withJson([
                                'error' => 'RESTRICTED_AREA',
                                'message' => __('errors', 'RESTRICTED_AREA'),
                            ]);
                        }

                    } else {
                        set_flash_message('requested_uri', get_current_url());
                        return $response->withRedirect(get_route('users-login-form'));
                    }

                }
            }

        }

        //Redirección al area administrativa desde formulario de logueo en caso de haber una session
        $login_redirect = get_config('admin_url');
        $relative_url = $login_redirect !== false ? (isset($login_redirect['relative']) ? $login_redirect['relative'] : true) : true;
        $relative_url = !is_bool($relative_url) ? true : $relative_url;
        $admin_url = $login_redirect !== false ? (isset($login_redirect['url']) ? $login_redirect['url'] : '') : '';
        if ($relative_url) {
            $admin_url = baseurl($admin_url);
        }

        $admin_url = convert_lang_url($admin_url, get_config('default_lang'), get_config('app_lang'));

        //Verifica que esté logueado
        if ($isActiveSession) {

            if ($name_route == 'users-login-form') {
                return $response->withRedirect($admin_url);
            }

        }

        //Control de permisos por roles
        $roles_control = get_config('roles');
        $active_roles_control = isset($roles_control['active']) ? $roles_control['active'] : false;
        $current_role = $user !== null ? Roles::getCurrentRole() : null;
        $has_permissions = null;

        //Verifica si está activada la comprobación automática de roles
        if ($current_role !== null && $active_roles_control === true) {

            $has_permissions = Roles::hasPermissions($name_route, $current_role['name']);

        }

        //Acciones en caso de no tener permisos
        if ($has_permissions !== null && !$has_permissions && $info_route['require_login']) {
            return (function ($request, $response) {

                $response = $response->withStatus(403);

                if (!$request->isXhr()) {
                    $controller = new PiecesPHP\Core\BaseController(false);
                    $controller->render('pages/403');
                } else {
                    $response = $response->withJson("403 Forbidden");
                }

                return $response;

            })($request, $response);
        }

    }

    //Definición de menús
    $silentModeRolesSetted = Roles::getSilentMode();
    Roles::setSilentMode(true);
    require_once basepath("app/config/menu.php");
    if (isset($config['menus']) && is_array($config['menus'])) {
        set_config('menus', $config['menus']);
    }
    Roles::setSilentMode($silentModeRolesSetted);

    if (APP_CONFIGURATION_MODULE) {
        //Configuraciones de la aplicación tomadas desde la base de datos
        $configurations = AppConfigModel::getConfigurations();

        foreach ($configurations as $name => $value) {
            set_config($name, $value);
        }

        (function ($config) {
            if (!is_scalar($config)) {
                set_config('mail', (new MailConfig)->toArray());
            }
        })(get_config('mail'));

        if (mb_strlen(get_title()) == 0) {
            set_title(AppConfigModel::getConfigValue('title_app'));
        }
    }

    return $next($request, $response);
});

set_config('upload_dir', basepath('statics/uploads'));
set_config('upload_dir_url', baseurl('statics/uploads'));
set_config('slim_app', $app);

//Definición de rutas
require_once basepath("app/config/routes.php");

//Configuraciones finales
require_once basepath("app/config/final-configurations.php");

/** Activar enrutador */
RouteGroup::initRoutes(false);
get_config('slim_app')->run();


