<?php

/**
 * PresentationsCategoryController.php
 */

namespace App\Presentations\Controllers;

use App\Controller\AdminPanelController;
use App\Model\UsersModel;
use App\Presentations\Mappers\PresentationCategoryMapper;
use App\Presentations\Mappers\PresentationMapper;
use App\Presentations\PresentationsLang;
use App\Presentations\PresentationsRoutes;
use PiecesPHP\Core\Config;
use PiecesPHP\Core\Pagination\PageQuery;
use PiecesPHP\Core\Pagination\PaginationResult;
use PiecesPHP\Core\Roles;
use PiecesPHP\Core\Route;
use PiecesPHP\Core\RouteGroup;
use PiecesPHP\Core\Utilities\Helpers\DataTablesHelper;
use PiecesPHP\Core\Utilities\ReturnTypes\ResultOperations;
use PiecesPHP\Core\Validation\Parameters\Exceptions\InvalidParameterValueException;
use PiecesPHP\Core\Validation\Parameters\Exceptions\MissingRequiredParamaterException;
use PiecesPHP\Core\Validation\Parameters\Exceptions\ParsedValueException;
use PiecesPHP\Core\Validation\Parameters\Parameter;
use PiecesPHP\Core\Validation\Parameters\Parameters;
use Slim\Exception\NotFoundException;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

/**
 * PresentationsCategoryController.
 *
 * @package     App\Presentations\Controllers
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2020
 */
class PresentationsCategoryController extends AdminPanelController
{

    /**
     * @var string
     */
    protected static $URLDirectory = 'presentations-categories';
    /**
     * @var string
     */
    protected static $baseRouteName = 'app-presentations-category-admin';
    /**
     * @var string
     */
    protected static $title = 'Categorías de presentaciones';
    /**
     * @var string
     */
    protected static $pluralTitle = 'Categoría de presentación';

    /**
     * @var HelperController
     */
    protected $helpController = null;

    const BASE_VIEW_DIR = 'categories';
    const BASE_JS_DIR = 'js/categories';
    const LANG_GROUP = PresentationsLang::LANG_GROUP;

    public function __construct()
    {
        parent::__construct(false); //No cargar ningún modelo automáticamente.

        self::$title = __(self::LANG_GROUP, self::$title);
        self::$pluralTitle = __(self::LANG_GROUP, self::$pluralTitle);

        $this->model = (new PresentationCategoryMapper())->getModel();
        set_title(self::$title . ' - ' . get_title());

        $this->helpController = new HelperController($this->user, $this->getGlobalVariables());
        $this->setInstanceViewDir(__DIR__ . '/../Views/');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function addForm(Request $request, Response $response, array $args)
    {

        set_title("Add " . self::$pluralTitle);


        set_custom_assets([
            PresentationsRoutes::staticRoute(self::BASE_JS_DIR . '/private/forms.js'),
        ], 'js');

        import_cropper();
        import_quilljs();

        $action = self::routeName('actions-add');
        $backLink = self::routeName('list');

        $data = [];
        $data['action'] = $action;
        $data['langGroup'] = self::LANG_GROUP;
        $data['backLink'] = $backLink;
        $data['title'] = self::$title;

        $this->helpController->render('panel/layout/header');
        self::view('private/forms/add', $data);
        $this->helpController->render('panel/layout/footer');

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function editForm(Request $request, Response $response, array $args)
    {

        set_title("Edit " . self::$pluralTitle);


        $id = $request->getAttribute('id', null);
        $id = !is_null($id) && ctype_digit($id) ? (int) $id : null;

        $lang = $request->getAttribute('lang', null);
        $lang = is_string($lang) ? $lang : null;

        $allowedLangs = Config::get_allowed_langs();

        if ($lang === null || !in_array($lang, $allowedLangs)) {
            throw new NotFoundException($request, $response);
        }

        $element = new PresentationCategoryMapper($id);

        if ($element->id !== null && PresentationCategoryMapper::existsByID($element->id)) {

            set_custom_assets([
                PresentationsRoutes::staticRoute(self::BASE_JS_DIR . '/private/delete-config.js'),
                PresentationsRoutes::staticRoute(self::BASE_JS_DIR . '/private/forms.js'),
            ], 'js');

            import_cropper();
            import_quilljs();

            $action = self::routeName('actions-edit');
            $backLink = self::routeName('list');
            $allowedLangs = array_to_html_options(self::allowedLangsForSelect($lang, $element->id), $lang);

            $data = [];
            $data['action'] = $action;
            $data['element'] = $element;
            $data['deleteRoute'] = self::routeName('actions-delete', ['id' => $element->id]);
            $data['allowDelete'] = self::allowedRoute('actions-delete', ['id' => $element->id]);
            $data['langGroup'] = self::LANG_GROUP;
            $data['backLink'] = $backLink;
            $data['title'] = self::$title;
            $data['allowedLangs'] = $allowedLangs;
            $data['lang'] = $lang;

            $this->helpController->render('panel/layout/header');
            self::view('private/forms/edit', $data, true, false);
            $this->helpController->render('panel/layout/footer');

            return $response;
        } else {
            throw new NotFoundException($request, $response);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function listView(Request $request, Response $response, array $args)
    {

        set_title(self::$pluralTitle);


        $backLink = PresentationsController::routeName('list');
        $addLink = self::routeName('forms-add');
        $processTableLink = self::routeName('datatables');

        $title = self::$pluralTitle;

        $data = [];
        $data['processTableLink'] = $processTableLink;
        $data['langGroup'] = self::LANG_GROUP;
        $data['backLink'] = $backLink;
        $data['addLink'] = $addLink;
        $data['hasPermissionsAdd'] = strlen($addLink) > 0;
        $data['title'] = $title;

        set_custom_assets([
            PresentationsRoutes::staticRoute(self::BASE_JS_DIR . '/private/delete-config.js'),
            PresentationsRoutes::staticRoute(self::BASE_JS_DIR . '/private/list.js'),
        ], 'js');

        $this->helpController->render('panel/layout/header');
        self::view('private/list', $data);
        $this->helpController->render('panel/layout/footer');
    }

    /**
     * Creación/Edición
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function action(Request $request, Response $response, array $args)
    {

        //──── Entrada ───────────────────────────────────────────────────────────────────────────

        //Definición de validaciones y procesamiento
        $expectedParameters = new Parameters([
            new Parameter(
                'id',
                -1,
                function ($value) {
                    return ctype_digit($value) || is_int($value);
                },
                true,
                function ($value) {
                    return (int) $value;
                }
            ),
            new Parameter(
                'lang',
                null,
                function ($value) {
                    return is_string($value) && strlen(trim($value)) > 0;
                },
                false,
                function ($value) {
                    return clean_string($value);
                }
            ),
            new Parameter(
                'name',
                null,
                function ($value) {
                    return is_string($value) && strlen(trim($value)) > 0;
                },
                false,
                function ($value) {
                    return clean_string($value);
                }
            ),
        ]);

        //Obtención de datos
        $inputData = $request->getParsedBody();

        //Asignación de datos para procesar
        $expectedParameters->setInputValues(is_array($inputData) ? $inputData : []);

        //──── Estructura de respuesta ───────────────────────────────────────────────────────────

        $resultOperation = new ResultOperations([], __(self::LANG_GROUP, 'Categoría'));
        $resultOperation->setSingleOperation(true); //Se define que es de una única operación

        //Valores iniciales de la respuesta
        $resultOperation->setSuccessOnSingleOperation(false);
        $resultOperation->setValue('redirect', false);
        $resultOperation->setValue('redirect_to', null);
        $resultOperation->setValue('reload', false);

        //Mensajes de respuesta
        $notExistsMessage = __(self::LANG_GROUP, 'La categoría que intenta modificar no existe.');
        $successCreateMessage = __(self::LANG_GROUP, 'Categoría creada.');
        $successEditMessage = __(self::LANG_GROUP, 'Datos guardados.');
        $unknowErrorMessage = __(self::LANG_GROUP, 'Ha ocurrido un error desconocido.');
        $unknowErrorWithValuesMessage = __(self::LANG_GROUP, 'Ha ocurrido un error desconocido al procesar los valores ingresados.');
        $notAllowedLangMessage = __(self::LANG_GROUP, 'El idioma "%s" no está permitido.');

        //──── Acciones ──────────────────────────────────────────────────────────────────────────
        try {

            //Intenta validar, si todo sale bien el código continúa
            $expectedParameters->validate();

            //Información del formulario
            /**
             * @var int $id
             * @var string $lang
             * @var string $name
             */;
            $id = $expectedParameters->getValue('id');
            $lang = $expectedParameters->getValue('lang');
            $name = $expectedParameters->getValue('name');

            //Se define si es edición o creación
            $isEdit = $id !== -1;

            try {

                $allowedLangs = Config::get_allowed_langs();

                if ($isEdit) {
                    if (!in_array($lang, $allowedLangs)) {
                        throw new \Exception(vsprintf($notAllowedLangMessage, [$lang]));
                    }
                } else {
                    $lang = get_config('default_lang');
                }

                if (!$isEdit) {
                    //Nuevo

                    $mapper = new PresentationCategoryMapper();

                    $mapper->setLangData($lang, 'name', $name);

                    $saved = $mapper->save();

                    $mapper->id = $mapper->getInsertIDOnSave();

                    $resultOperation->setSuccessOnSingleOperation($saved);

                    if ($saved) {

                        $resultOperation
                            ->setMessage($successCreateMessage)
                            ->setValue('redirect', true)
                            ->setValue('redirect_to', self::routeName('forms-edit', [
                                'id' => $mapper->id,
                            ]));
                    } else {
                        $resultOperation->setMessage($unknowErrorMessage);
                    }
                } else {
                    //Existente

                    $mapper = new PresentationCategoryMapper((int) $id);
                    $exists = !is_null($mapper->id);

                    if ($exists) {

                        $mapper->setLangData($lang, 'name', $name);

                        $updated = $mapper->update();

                        $resultOperation->setSuccessOnSingleOperation($updated);

                        if ($updated) {

                            $resultOperation
                                ->setMessage($successEditMessage)
                                ->setValue('redirect', true)
                                ->setValue('redirect_to', self::routeName('list'));
                        } else {

                            $resultOperation->setMessage($unknowErrorMessage);
                        }
                    } else {

                        $resultOperation->setMessage($notExistsMessage);
                    }
                }
            } catch (\Exception $e) {

                $resultOperation->setMessage($e->getMessage());
                log_exception($e);
            }
        } catch (MissingRequiredParamaterException $e) {

            $resultOperation->setMessage($e->getMessage());
            log_exception($e);
        } catch (ParsedValueException $e) {

            $resultOperation->setMessage($unknowErrorWithValuesMessage);
            log_exception($e);
        } catch (InvalidParameterValueException $e) {

            $resultOperation->setMessage($e->getMessage());
            log_exception($e);
        } catch (\Exception $e) {

            $resultOperation->setMessage($e->getMessage());
            log_exception($e);
        }

        return $response->withJson($resultOperation);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function toDelete(Request $request, Response $response, array $args)
    {

        //──── Entrada ───────────────────────────────────────────────────────────────────────────

        //Definición de validaciones y procesamiento
        $expectedParameters = new Parameters([
            new Parameter(
                'id',
                -1,
                function ($value) {
                    return ctype_digit($value) || is_int($value);
                },
                true,
                function ($value) {
                    return (int) $value;
                }
            ),
        ]);

        //Obtención de datos
        $inputData = $args;

        //Asignación de datos para procesar
        $expectedParameters->setInputValues(is_array($inputData) ? $inputData : []);

        //──── Estructura de respuesta ───────────────────────────────────────────────────────────

        $resultOperation = new ResultOperations([], __(self::LANG_GROUP, 'Eliminar categoría'));
        $resultOperation->setSingleOperation(true); //Se define que es de una única operación

        //Valores iniciales de la respuesta
        $resultOperation->setSuccessOnSingleOperation(false);
        $resultOperation->setValue('redirect', false);
        $resultOperation->setValue('redirect_to', null);
        $resultOperation->setValue('reload', false);
        $resultOperation->setValue('received', $inputData);

        //Mensajes de respuesta
        $notExistsMessage = __(self::LANG_GROUP, 'La categoría que intenta eliminar no existe.');
        $successMessage = __(self::LANG_GROUP, 'Categoría eliminada.');
        $unknowErrorMessage = __(self::LANG_GROUP, 'Ha ocurrido un error desconocido.');
        $unknowErrorWithValuesMessage = __(self::LANG_GROUP, 'Ha ocurrido un error desconocido al procesar los valores ingresados.');

        //──── Acciones ──────────────────────────────────────────────────────────────────────────
        try {

            //Intenta validar, si todo sale bien el código continúa
            $expectedParameters->validate();

            //Información del formulario

            /**
             * @var int $id
             */;
            $id = $expectedParameters->getValue('id');

            try {

                $exists = PresentationCategoryMapper::existsByID($id);

                if ($exists) {

                    //Dirección de redirección en caso de creación
                    $redirectURLOn = self::routeName('list');

                    $tablePresentations = PresentationMapper::TABLE;
                    $table = PresentationCategoryMapper::TABLE;

                    $transactionSQLDeleteQueries = [
                        [
                            'query' => "DELETE FROM {$tablePresentations} WHERE category = :ID",
                            'aliasConfig' => [
                                ':ID' => $id,
                            ],
                        ],
                        [
                            'query' => "DELETE FROM {$table} WHERE id = :ID",
                            'aliasConfig' => [
                                ':ID' => $id,
                            ],
                        ],
                    ];

                    $pdo = PresentationCategoryMapper::model()->getDatabase();

                    try {

                        $presentationsByCategory = PresentationMapper::allBy('category', $id, true);

                        $pdo->beginTransaction();

                        foreach ($transactionSQLDeleteQueries as $sqlQueryConfig) {

                            $query = $sqlQueryConfig['query'];
                            $aliasConfig = $sqlQueryConfig['aliasConfig'];

                            $preparedStatement = $pdo->prepare($query);
                            $preparedStatement->execute($aliasConfig);
                        }

                        $pdo->commit();

                        foreach ($presentationsByCategory as $presentation) {
                            PresentationsController::deletePresentationImages($presentation);
                        }

                        $resultOperation->setSuccessOnSingleOperation(true);

                        $resultOperation
                            ->setMessage($successMessage)
                            ->setValue('redirect', true)
                            ->setValue('redirect_to', $redirectURLOn);
                    } catch (\Exception $e) {
                        $pdo->rollBack();
                        $resultOperation->setValue('transactionError', $e->getMessage());
                        $resultOperation->setMessage($unknowErrorMessage);
                        log_exception($e);
                    }
                } else {
                    $resultOperation->setMessage($notExistsMessage);
                }
            } catch (\Exception $e) {

                $resultOperation->setMessage($e->getMessage());
                log_exception($e);
            }
        } catch (MissingRequiredParamaterException $e) {

            $resultOperation->setMessage($e->getMessage());
            log_exception($e);
        } catch (ParsedValueException $e) {

            $resultOperation->setMessage($unknowErrorWithValuesMessage);
            log_exception($e);
        } catch (InvalidParameterValueException $e) {

            $resultOperation->setMessage($e->getMessage());
            log_exception($e);
        }

        return $response->withJson($resultOperation);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function all(Request $request, Response $response, array $args)
    {

        $expectedParameters = new Parameters([
            new Parameter(
                'page',
                1,
                function ($value) {
                    return ctype_digit($value) || is_int($value);
                },
                true,
                function ($value) {
                    return (int) $value;
                }
            ),
            new Parameter(
                'per_page',
                10,
                function ($value) {
                    return ctype_digit($value) || is_int($value);
                },
                true,
                function ($value) {
                    return (int) $value;
                }
            ),
        ]);

        $expectedParameters->setInputValues($request->getQueryParams());
        $expectedParameters->validate();

        /**
         * @var int $id
         * @var int $perPage
         * @var int $category
         */;
        $page = $expectedParameters->getValue('page');
        $perPage = $expectedParameters->getValue('per_page');

        $result = self::_all($page, $perPage);

        return $response->withJson($result);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function dataTables(Request $request, Response $response, array $args)
    {

        $whereString = null;

        $selectFields = PresentationCategoryMapper::fieldsToSelect();

        $columnsOrder = [
            'name',
        ];

        DataTablesHelper::setTablePrefixOnOrder(false);
        DataTablesHelper::setTablePrefixOnSearch(false);

        $result = DataTablesHelper::process([

            'where_string' => $whereString,
            'select_fields' => $selectFields,
            'columns_order' => $columnsOrder,
            'mapper' => new PresentationCategoryMapper(),
            'request' => $request,
            'on_set_data' => function ($e) {
                return [
                    '',
                    '',
                ];
            },

        ]);

        $rawData = $result->getValue('rawData');

        foreach ($rawData as $index => $element) {

            $mapper = new PresentationCategoryMapper($element->id);

            $rawData[$index] = self::view(
                'private/util/list-card',
                [
                    'mapper' => $mapper,
                    'editLink' => self::routeName('forms-edit', [
                        'id' => $mapper->id,
                    ]),
                    'hasEdit' => self::allowedRoute('forms-edit', [
                        'id' => $mapper->id,
                    ]),
                    'deleteRoute' => self::routeName('actions-delete', ['id' => $mapper->id]),
                    'hasDelete' => self::allowedRoute('actions-delete', ['id' => $mapper->id]),
                    'langGroup' => self::LANG_GROUP,
                ],
                false
            );
        }

        $result->setValue('rawData', $rawData);

        return $response->withJson($result->getValues());
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param int $category
     * @return PaginationResult
     */
    public static function _all(int $page = 1, int $perPage = 10)
    {
        $table = PresentationCategoryMapper::TABLE;
        $fields = PresentationCategoryMapper::fieldsToSelect();
        $jsonExtractExists = PresentationCategoryMapper::jsonExtractExistsMySQL();

        $whereString = null;
        $where = [];

        if (count($where) > 0) {
            $whereString = implode('', $where);
        }

        $fields = implode(', ', $fields);
        $sqlSelect = "SELECT {$fields} FROM {$table}";
        $sqlCount = "SELECT COUNT({$table}.id) AS total FROM {$table}";

        if ($whereString !== null) {
            $sqlSelect .= " WHERE {$whereString}";
            $sqlCount .= " WHERE {$whereString}";
        }

        $sqlSelect .= " ORDER BY " . implode(', ', PresentationCategoryMapper::ORDER_BY_PREFERENCE);

        $pageQuery = new PageQuery($sqlSelect, $sqlCount, $page, $perPage, 'total');

        $parser = null;
        $each = !$jsonExtractExists ? function ($element) {
            $element = PresentationCategoryMapper::translateEntityObject($element);
            return $element;
        } : null;

        $pagination = $pageQuery->getPagination($parser, $each);

        return $pagination;
    }

    /**
     * @param string $currentLang
     * @param int $elementID
     * @return array
     */
    public static function allowedLangsForSelect(string $currentLang, int $elementID)
    {

        $allowedLangsForSelect = [];

        $allowedLangs = Config::get_allowed_langs();

        $allowedLangs = array_filter($allowedLangs, function ($l) use ($currentLang) {
            return $l != $currentLang;
        });

        array_unshift($allowedLangs, $currentLang);

        foreach ($allowedLangs as $i) {

            $value = self::routeName('forms-edit', ['id' => $elementID, 'lang' => $i]);

            $allowedLangsForSelect[$value] = __('lang', $i);
        }

        return $allowedLangsForSelect;
    }

    /**
     * @param string $name
     * @param array $data
     * @param bool $mode
     * @param bool $format
     * @return void|string
     */
    public static function view(string $name, array $data = [], bool $mode = true, bool $format = true)
    {
        return (new static)->render(self::BASE_VIEW_DIR . '/' . trim($name, '/'), $data, $mode, $format);
    }

    /**
     * @param string $name
     * @param array $params
     * @return bool
     */
    public static function allowedRoute(string $name, array $params = [])
    {

        $route = self::routeName($name, $params, true);
        $allow = strlen($route) > 0;

        if ($allow) {

            if ($name == 'SAMPLE') { //do something
            }
        }

        return $allow;
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
            $name = strlen($name) > 0 ? "-{$name}" : '';
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

        $permisos_listado = $all_roles;

        $permisos_estados_gestion = [
            UsersModel::TYPE_USER_ROOT,
        ];

        $routes = [

            //──── GET ───────────────────────────────────────────────────────────────────────────────
            //HTML
            new Route( //Vista del listado
                "{$startRoute}/list[/]",
                $classname . ':listView',
                self::$baseRouteName . '-list',
                'GET',
                true,
                null,
                $permisos_listado
            ),
            new Route( //Formulario de crear
                "{$startRoute}/forms/add[/]",
                $classname . ':addForm',
                self::$baseRouteName . '-forms-add',
                'GET',
                true,
                null,
                $permisos_estados_gestion
            ),
            new Route( //Formulario de editar
                "{$startRoute}/forms/edit/{id}/{lang}[/]",
                $classname . ':editForm',
                self::$baseRouteName . '-forms-edit',
                'GET',
                true,
                null,
                $permisos_estados_gestion,
                [
                    'lang' => Config::get_default_lang(),
                ]
            ),

            //JSON
            new Route( //JSON con todos los elementos
                "{$startRoute}/all[/]",
                $classname . ':all',
                self::$baseRouteName . '-ajax-all',
                'GET'
            ),
            new Route( //Datos para datatables
                "{$startRoute}/datatables[/]",
                $classname . ':dataTables',
                self::$baseRouteName . '-datatables',
                'GET',
                true,
                null,
                $permisos_listado
            ),

            //──── POST ──────────────────────────────────────────────────────────────────────────────

            new Route( //Acción de crear
                "{$startRoute}/action/add[/]",
                $classname . ':action',
                self::$baseRouteName . '-actions-add',
                'POST',
                true,
                null,
                $rolesAllowed
            ),
            new Route( //Acción de editar
                "{$startRoute}/action/edit[/]",
                $classname . ':action',
                self::$baseRouteName . '-actions-edit',
                'POST',
                true,
                null,
                $permisos_estados_gestion
            ),
            new Route( //Acción de eliminar
                "{$startRoute}/action/delete/{id}[/]",
                $classname . ':toDelete',
                self::$baseRouteName . '-actions-delete',
                'POST',
                true,
                null,
                $permisos_estados_gestion
            ),

        ];

        $group->register($routes);

        return $group;
    }
}
