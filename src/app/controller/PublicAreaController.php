<?php

/**
 * PublicAreaController.php
 */

namespace App\Controller;

use App\Contacts\ContactsController;
use App\Model\AvatarModel;
use PiecesPHP\BuiltIn\Article\Controllers\ArticleController;
use PiecesPHP\BuiltIn\Article\Controllers\ArticleControllerPublic;
use PiecesPHP\BuiltIn\DynamicImages\Informative\Controllers\HeroController;
use PiecesPHP\Core\BaseHashEncryption;
use PiecesPHP\Core\Roles;
use PiecesPHP\Core\Route;
use PiecesPHP\Core\RouteGroup;
use PiecesPHP\Core\Utilities\OsTicket\OsTicketAPI;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

/**
 * PublicAreaController.
 *
 * Controlador del área pública
 *
 * @package     App\Controller
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2019
 */
class PublicAreaController extends \PiecesPHP\Core\BaseController
{

    /**
     * @var string
     */
    private static $prefixNameRoutes = 'public';

    /**
     * @var string
     */
    private static $startSegmentRoutes = '';

    /**
     * Usuario logueado
     *
     * @var \stdClass
     */
    protected $user = null;

    /**
     * @return static
     */
    public function __construct()
    {
        parent::__construct(false); //No cargar ningún modelo automáticamente



        set_global_assets(
            [
                'statics/css/style.css',
                'statics/plugins/bootstrap/css/bootstrap.min.css',
            ],
            'css'
        );

        set_global_assets(
            [
                'statics/js/global.js',
                'statics/plugins/bootstrap/js/bootstrap.min.js',
            ],
            'js'
        );

        $this->init();

        import_jquery();
        import_izitoast();
        import_app_libraries();
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function indexView(Request $req, Response $res, array $args)
    {

        set_title(__(LANG_GROUP, 'Home'));

        set_custom_assets([
            'statics/css/home.css',
        ], 'css');

        $this->render('layout/header');
        $this->render('pages/home');
        $this->render('layout/footer');

        return $res;
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function servicesView(Request $req, Response $res, array $args)
    {

        set_title(__(LANG_GROUP, 'Services'));

        set_custom_assets(
            [
                'statics/css/services.css',
                'statics/css/home.css',
            ],
            'css'
        );

        $this->render('layout/header');
        $this->render('pages/services');
        $this->render('layout/footer');

        return $res;
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function plansView(Request $req, Response $res, array $args)
    {

        set_title(__(LANG_GROUP, 'Plans'));

        set_custom_assets(
            [
                'statics/css/plans.css',
                'statics/css/home.css',
            ],
            'css'
        );

        $plans = ["basic", "standard", "premium", "ultra"];

        $microservices = [
            ["name" => "General cleaning", "basic" => true, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Painting", "basic" => true, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Electricity", "basic" => true, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Plumbing", "basic" => true, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Cleaning consumables", "basic" => true, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Sanitary Consumables", "basic" => true, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Laundry service for sheets and towels", "basic" => true, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Civil works and bricklaying", "basic" => false, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Locksmith", "basic" => false, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Access control", "basic" => false, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Fire Detection System", "basic" => false, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Automation in general", "basic" => false, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Gardening", "basic" => false, "standard" => true, "premium" => true, "ultra" => true],
            ["name" => "Fumigation", "basic" => false, "standard" => false, "premium" => true, "ultra" => true],
            ["name" => "Resident personnel for VIP attention", "basic" => false, "standard" => false, "premium" => true, "ultra" => true],
            ["name" => "AA Units, pumps, electrical generators and frequently used equipament are covered up to total loss", "basic" => false, "standard" => false, "premium" => true, "ultra" => true],
        ];

        $this->render('layout/header');
        $this->render('pages/plans', ["plans" => $plans, "microservices" => $microservices]);
        $this->render('layout/footer');

        return $res;
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function aboutView(Request $req, Response $res, array $args)
    {

        set_title(__(LANG_GROUP, 'About'));

        set_custom_assets(
            [
                'statics/css/about.css',
                'statics/css/home.css',
            ],
            'css'
        );

        $this->render('layout/header');
        $this->render('pages/about');
        $this->render('layout/footer');

        return $res;
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function contactView(Request $req, Response $res, array $args)
    {

        set_title(__(LANG_GROUP, 'Contact us'));

        set_custom_assets(
            [
                'https://www.google.com/recaptcha/api.js?render=' . get_config("recapcha")["site_key"],
                'statics/js/contact.js',
            ],
            'js'
        );
        set_custom_assets(
            [
                'statics/css/contact.css',
            ],
            'css'
        );



        $this->render('layout/header');
        $this->render('pages/contact', [
            "action" => ContactsController::routeName("actions-add"),
            "is_local" => is_local()
        ]);
        $this->render('layout/footer');

        return $res;
    }
    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function termsView(Request $req, Response $res, array $args)
    {

        set_title(__(LANG_GROUP, 'Terms'));

        set_custom_assets([
            'statics/css/terms.css',
        ], 'css');

        $this->render('layout/header');
        $this->render('pages/home');
        $this->render('layout/footer');

        return $res;
    }
    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function policyView(Request $req, Response $res, array $args)
    {

        set_title(__(LANG_GROUP, 'Policy'));

        set_custom_assets([
            'statics/css/policy.css',
        ], 'css');

        $this->render('layout/header');
        $this->render('pages/home');
        $this->render('layout/footer');

        return $res;
    }
    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function genericViews(Request $req, Response $res, array $args)
    {

        $folder = $req->getAttribute('folder', null);
        $name = $req->getAttribute('name', null);

        $folder = is_string($folder) && mb_strlen(trim($folder)) > 0 ? trim($folder) : null;
        $name = mb_strlen(trim($name)) > 0 ? trim($name) : null;

        if ($folder !== null) {
            $name = "{$folder}/{$name}";
        }

        $css = [
            'statics/css/style.css',
        ];
        $js = [
            'statics/js/CustomNamespace.js',
            'statics/js/generic-views/generic-views.js',
            'statics/js/default-template.js',
        ];

        $data = [
            'withSocialBar' => true,
            'withRecents' => true,
            'req' => $req,
            'res' => $res,
            'args' => $args,
        ];

        $availableView = [
            'tabs-sample' => [
                'title' => __(LANG_GROUP, 'Ejemplo de tabs'),
            ],
            'about-us' => [
                'title' => __(LANG_GROUP, 'Quiénes somos'),
            ],
        ];

        $viewHeader = 'layout/header-template';
        $viewFooter = 'layout/footer-template';

        if (is_string($name) && array_key_exists($name, $availableView)) {

            $viewConfig = $availableView[$name];
            $file = isset($viewConfig['file']) && $viewConfig['file'] !== null ? $viewConfig['file'] : $name;
            $viewHeader = isset($viewConfig['header']) ? $viewConfig['header'] : $viewHeader;
            $viewFooter = isset($viewConfig['footer']) ? $viewConfig['footer'] : $viewFooter;
            $viewTitle = isset($viewConfig['title']) ? $viewConfig['title'] : null;
            $viewData = isset($viewConfig['data']) ? $viewConfig['data'] : [];
            $prependAssets = isset($viewConfig['prependAssets']) ? $viewConfig['prependAssets'] : [];
            $appendAssets = isset($viewConfig['appendAssets']) ? $viewConfig['appendAssets'] : [];
            $prependCss = isset($prependAssets['css']) ? $prependAssets['css'] : [];
            $prependJs = isset($prependAssets['js']) ? $prependAssets['js'] : [];
            $appendCss = isset($appendAssets['css']) ? $appendAssets['css'] : [];
            $appendJs = isset($appendAssets['js']) ? $appendAssets['js'] : [];

            foreach ($viewData as $k => $i) {
                $data[$k] = $i;
            }
            foreach ($prependCss as $i) {
                array_unshift($css, $i);
            }
            foreach ($prependJs as $i) {
                array_unshift($js, $i);
            }
            foreach ($appendCss as $i) {
                array_push($css, $i);
            }
            foreach ($appendJs as $i) {
                array_push($js, $i);
            }

            if ($viewTitle !== null) {
                set_title($viewTitle);
            }
        }

        set_custom_assets($css, 'css');
        set_custom_assets($js, 'js');

        $this->setVariables($data);

        if (isset($file) && $file !== null) {

            $this->render($viewHeader);
            $this->render("pages/generic-views/{$file}");
            $this->render($viewFooter);
        } else {
            throw new NotFoundException($req, $res);
        }

        return $res;
    }

    /**
     * @param string $name
     * @param array $params
     * @param bool $silentOnNotExists
     * @return string
     */
    public static function routeName(string $name = null, array $params = [], bool $silentOnNotExists = false)
    {
        if (!is_null($name)) {
            $name = trim($name);
            $name = mb_strlen($name) > 0 ? "-{$name}" : '';
        }

        $name = !is_null($name) ? self::$prefixNameRoutes . $name : self::$prefixNameRoutes;

        $allowed = false;
        $current_user = get_config('current_user');

        if ($current_user != false) {
            $allowed = Roles::hasPermissions($name, (int) $current_user->type);
        } else {
            $allowed = true;
        }

        if ($allowed) {
            return get_route(
                $name,
                $params,
                $silentOnNotExists
            );
        } else {
            return '';
        }
    }

    /**
     * @param RouteGroup $group
     * @return RouteGroup
     */
    public static function routes(RouteGroup $group)
    {

        $groupSegmentURL = $group->getGroupSegment();

        $lastIsBar = last_char($groupSegmentURL) == '/';
        $startRoute = $lastIsBar ? '' : '/';

        //Otras rutas
        $namePrefix = self::$prefixNameRoutes;

        if (mb_strlen(self::$startSegmentRoutes) > 0) {
            $startRoute .= self::$startSegmentRoutes;
        } else {
            $startRoute = '';
        }

        //──── GET ─────────────────────────────────────────────────────────────────────────

        //Generales
        $group->register([
            new Route(
                $lastIsBar ? "" : "[/]",
                self::class . ":indexView",
                "{$namePrefix}-index",
                'GET'
            ),
            new Route(
                "{$lastIsBar}/services[/]",
                self::class . ":servicesView",
                "{$namePrefix}-services",
                'GET'
            ),
            new Route(
                "{$lastIsBar}/plans[/]",
                self::class . ":plansView",
                "{$namePrefix}-plans",
                'GET'
            ),
            new Route(
                "{$lastIsBar}/about[/]",
                self::class . ":aboutView",
                "{$namePrefix}-about",
                'GET'
            ),
            new Route(
                "{$lastIsBar}/contact/{type}[/]",
                self::class . ":contactView",
                "{$namePrefix}-contact",
                'GET'
            ),
            new Route(
                "{$lastIsBar}/policy[/]",
                self::class . ":policyView",
                "{$namePrefix}-policy",
                'GET'
            ),
            new Route(
                "{$lastIsBar}/terms[/]",
                self::class . ":termsView",
                "{$namePrefix}-terms",
                'GET'
            ),
            new Route(
                "{$startRoute}/{name}[/]",
                self::class . ":genericViews",
                "{$namePrefix}-generic",
                'GET'
            ),
            new Route(
                "{$startRoute}/{folder}/{name}[/]",
                self::class . ":genericViews",
                "{$namePrefix}-generic-2",
                'GET'
            ),
        ]);

        //──── POST ─────────────────────────────────────────────────────────────────────────

        //Otros controladores asociados

        $group = ContactFormsController::routes($group);

        return $group;
    }

    /**
     * @return void
     */
    protected function init()
    {
        /* JQuery */
        import_jquery();

        $api_url = get_config('osTicketAPI');
        $api_key = get_config('osTicketAPIKey');

        OsTicketAPI::setBaseURL($api_url);
        OsTicketAPI::setBaseAPIKey($api_key);

        $view_data = [];
        $this->user = get_config('current_user');

        if ($this->user instanceof \stdClass) {
            $view_data['user'] = $this->user;
            $this->user->avatar = AvatarModel::getAvatar($this->user->id);
            $this->user->hasAvatar = !is_null($this->user->avatar);
            $this->user->id = BaseHashEncryption::encrypt(base64_encode($this->user->id), self::class);
            unset($this->user->password);
        }

        $this->setVariables($view_data);
    }
}
