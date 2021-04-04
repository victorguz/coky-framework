<?php

/**
 * RouteGroup.php
 */
namespace PiecesPHP\Core;

use Exception;
use PiecesPHP\Core\Route;
use Slim\App;

/**
 * RouteGroup - Esquema de grupo de rutas
 *
 * @package     PiecesPHP\Core
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 */
class RouteGroup
{

    /**
     * @var App
     */
    protected static $router = null;
    /**
     * @var static[]
     */
    protected static $groups = [];
    /**
     * @var bool
     */
    protected static $isInit = false;
    /**
     * @var string
     */
    protected $routeGroup = '';
    /**
     * $routes
     *
     * @var Route[]
     */
    protected $routes = [];
    /**
     * @var App
     */
    protected $instanceRouter = null;
    /**
     * @var array<callable>|array<string>
     */
    protected $middlewares = [];
    /**
     * @var bool
     */
    protected $active = true;

    /**
     * @param string $routeGroup
     * @param App $router
     * @param bool $useClassRouter
     */
    public function __construct(string $routeGroup, App $router = null, bool $useClassRouter = true)
    {
        $routerDefined = false;

        $this->routeGroup = rtrim($routeGroup, '/');
        $this->instanceRouter = $router;

        if ($useClassRouter) {

            if (self::$router instanceof App) {
                $this->instanceRouter = self::$router;
                $routerDefined = true;
            }

        } else {

            if ($router instanceof App) {
                $this->instanceRouter = $router;
                $routerDefined = true;
            }

        }

        if (!$routerDefined) {
            throw new Exception("No hay ningún enrutador definido para el grupo {$this->routeGroup}.");
        }

    }

    /**
     * @param Route[] $routes
     * @param App $router
     * @return static
     */
    public function register($routes)
    {

        foreach ($routes as $route) {

            if ($route instanceof Route) {

                $segment = $route->routeSegment();
                $segment = str_replace([
                    '//',
                    '/[/]',
                ], [
                    '/',
                    '[/]',
                ], $segment);

                if ($segment === '[/]') {

                    $segment = "{$segment}";

                } elseif (mb_strlen($segment) > 0) {

                    if ($segment[0] != '/') {
                        $segment = "/{$segment}";
                    }

                } else {
                    $segment = "[/]";
                }

                $route->routeSegment($segment);
                $this->routes[] = $route;
            }

        }

        if (!array_key_exists($this->routeGroup, self::$groups)) {
            self::$groups[$this->routeGroup] = $this;
        }

        return $this;
    }

    /**
     * @param callable|string $callable
     * @return static
     */
    public function addMiddleware($callable)
    {
        if ($this->active) {
            if (is_callable($callable) || is_string($callable)) {
                $this->middlewares[] = $callable;
            }
        }
        return $this;
    }

    /**
     * Alias de addMiddleware
     * @param callable|string $callable
     * @return static
     */
    public function withMiddleware($callable)
    {
        return $this->addMiddleware($callable);
    }

    /**
     * @return string
     */
    public function getGroupSegment()
    {
        return $this->routeGroup;
    }

    /**
     * @param bool $active
     * @return static
     */
    public function active(bool $active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @param bool $debug
     * @return void
     */
    public static function initRoutes(bool $debug = false)
    {

        if (!self::$isInit) {

            foreach (self::$groups as $group) {

                $router = $group->instanceRouter;

                if ($group->active) {

                    if ($debug) {
                        echo '---------------<br>';
                        echo $group->getGroupSegment() . "<br>";
                    }

                    /**
                     * @var Route[] $routes
                     */
                    $routes = $group->routes;
                    /**
                     * @var callable[]|string[] $middlewares
                     */
                    $middlewares = array_reverse($group->middlewares);

                    $groupSetted = $router->group($group->getGroupSegment(), function (App $appRouter) use ($routes, $debug) {

                        if ($debug) {

                            echo "<br>";

                            foreach ($routes as $route) {
                                echo $route->routeSegment() . "<br>";
                                echo "<pre>";
                                var_dump($route->getParameters());
                                echo "</pre>";
                            }

                            echo "<br>";

                        } else {

                            foreach ($routes as $route) {
                                $route->register($appRouter);
                            }

                        }

                    });

                    foreach ($middlewares as $mw) {
                        $groupSetted->add($mw);
                    }

                }

            }

            if ($debug) {
                exit;
            }

            self::$isInit = true;

        }

    }

    /**
     * @param App $router
     * @return void
     */
    public static function setRouter(App $router)
    {
        self::$router = $router;
    }
}
