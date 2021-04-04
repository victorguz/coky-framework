<?php

/**
 * EntryPointController.php
 */

namespace PiecesPHP\BuiltIn\DynamicImages;

use App\Controller\AdminPanelController;
use App\Model\UsersModel;
use PiecesPHP\BuiltIn\DynamicImages\Informative\Controllers\HeroController;
use PiecesPHP\Core\Roles;
use PiecesPHP\Core\Route;
use PiecesPHP\Core\RouteGroup;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

/**
 * EntryPointController.
 *
 * @package     PiecesPHP\BuiltIn\DynamicImages
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2020
 */
class EntryPointController extends AdminPanelController
{

    /**
     * $URLDirectory
     *
     * @var string
     */
    protected static $URLDirectory = 'dynamic-images/private';

    /**
     * $baseRouteName
     *
     * @var string
     */
    protected static $baseRouteName = 'built-in-dynamic-images-private-entry';

    /**
     * $title
     *
     * @var string
     */
    protected static $title = 'Opciones';

    /**
     * $options
     *
     * @var array
     */
    private $options = [];

    const BASE_VIEW_DIR = 'built-in/dynamic-images';
    const BASE_JS_DIR = 'statics/js/built-in/dynamic-images';
    const LANG_GROUP = 'bi-dynamic-images';

    /**
     * __construct
     *
     * @return static
     */
    public function __construct()
    {
        parent::__construct(false); //No cargar ningún modelo automáticamente.

        self::$title = __(self::LANG_GROUP, self::$title);
        set_title(self::$title);

        $this->options = [
            [
                'title' => __(self::LANG_GROUP, 'Imágenes principales'),
                'link' => HeroController::routeName('list'),
            ],
        ];
    }

    /**
     * optionsView
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function optionsView(Request $request, Response $response, array $args)
    {

        $backLink = get_route('admin');

        $options = $this->getAllowedOptions();

        $data = [];
        $data['langGroup'] = self::LANG_GROUP;
        $data['title'] = self::$title;
        $data['backLink'] = $backLink;
        $data['options'] = $options;

        $this->render('panel/layout/header');
        self::view('private/options', $data);
        $this->render('panel/layout/footer');

    }

    /**
     * getAllowedOptions
     *
     * @return \stdClass[]
     */
    protected function getAllowedOptions()
    {

        $options = [];

        foreach ($this->options as $option) {
            $option = (object) $option;
            $allowedOption = mb_strlen($option->title) > 0;
            $allowedOption = $allowedOption && mb_strlen($option->link) > 0;
            if ($allowedOption) {
                $options[] = $option;
            }
        }

        return $options;

    }

    /**
     * view
     *
     * @param string $name
     * @param array $data
     * @param bool $mode
     * @param bool $format
     * @return void|string
     */
    public static function view(string $name, array $data = [], bool $mode = true, bool $format = true)
    {
        return (new static )->render(self::BASE_VIEW_DIR . '/' . trim($name, '/'), $data, $mode, $format);
    }

    /**
     * allowedRoute
     *
     * @param string $name
     * @param array $params
     * @return bool
     */
    public static function allowedRoute(string $name, array $params = [])
    {

        $route = self::routeName($name, $params, true);
        $allow = mb_strlen($route) > 0;

        if ($allow) {

            if ($name == 'options') {

                $allow = count((new static )->getAllowedOptions()) > 0;

            }

        }

        return $allow;
    }

    /**
     * routeName
     *
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

        $name = !is_null($name) ? self::$baseRouteName . $name : self::$baseRouteName;

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
     * routes
     *
     * @param RouteGroup $group
     * @return RouteGroup
     */
    public static function routes(RouteGroup $group)
    {
        $routes = [];

        $groupSegmentURL = $group->getGroupSegment();

        $lastIsBar = last_char($groupSegmentURL) == '/';
        $startRoute = ($lastIsBar ? '' : '/') . self::$URLDirectory;

        $classname = self::class;

        $all_roles = array_keys(UsersModel::TYPES_USERS);
        $roles_view_options = [
            UsersModel::TYPE_USER_ROOT,
            UsersModel::TYPE_USER_ADMIN,
            UsersModel::TYPE_USER_GENERAL,
        ];

        $routes = [

            //──── GET ───────────────────────────────────────────────────────────────────────────────

            //HTML
            new Route( //Vista del listado de opciones de este módulo
                "{$startRoute}/options[/]",
                $classname . ':optionsView',
                self::$baseRouteName . '-options',
                'GET',
                true,
                null,
                $roles_view_options
            ),

        ];

        $group->register($routes);

        return $group;
    }
}
