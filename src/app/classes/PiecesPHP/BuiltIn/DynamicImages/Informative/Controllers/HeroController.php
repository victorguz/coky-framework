<?php

/**
 * HeroController.php
 */

namespace PiecesPHP\BuiltIn\DynamicImages\Informative\Controllers;

use App\Controller\AdminPanelController;
use App\Model\UsersModel;
use PiecesPHP\BuiltIn\DynamicImages\EntryPointController;
use PiecesPHP\BuiltIn\DynamicImages\Informative\Mappers\ImageMapper;
use PiecesPHP\Core\Config;
use PiecesPHP\Core\Forms\FileUpload;
use PiecesPHP\Core\Forms\FileValidator;
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
use PiecesPHP\Core\Validation\Validator;
use Slim\Exception\NotFoundException;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

/**
 * HeroController.
 *
 * @package     PiecesPHP\BuiltIn\DynamicImages\Informative\Controllers
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2020
 */
class HeroController extends AdminPanelController
{

    /**
     * $URLDirectory
     *
     * @var string
     */
    protected static $URLDirectory = 'dynamic-images/private/hero';

    /**
     * $baseRouteName
     *
     * @var string
     */
    protected static $baseRouteName = 'built-in-dynamic-images-private-hero';

    /**
     * $title
     *
     * @var string
     */
    protected static $title = 'Imagen';
    /**
     * $pluralTitle
     *
     * @var string
     */
    protected static $pluralTitle = 'Imágenes';

    /**
     * $uploadDir
     *
     * @var string
     */
    protected $uploadDir = '';
    /**
     * $uploadDirURL
     *
     * @var string
     */
    protected $uploadDirURL = '';

    const BASE_VIEW_DIR = 'built-in/dynamic-images/hero';
    const BASE_JS_DIR = 'statics/js/built-in/dynamic-images/hero';
    const UPLOAD_DIR = 'general/dynamic-images/hero';
    const LANG_GROUP = 'bi-dynamic-images-hero';

    /**
     * __construct
     *
     * @return static
     */
    public function __construct()
    {
        parent::__construct(false); //No cargar ningún modelo automáticamente.

        self::$title = __(self::LANG_GROUP, self::$title);
        self::$pluralTitle = __(self::LANG_GROUP, self::$pluralTitle);

        $this->model = (new ImageMapper())->getModel();
        set_title(self::$title);

        $baseURL = base_url();
        $pcsUploadDir = get_config('upload_dir');
        $pcsUploadDirURL = get_config('upload_dir_url');

        $this->uploadDir = append_to_url($pcsUploadDir, self::UPLOAD_DIR);
        $this->uploadDirURL = str_replace($baseURL, '', append_to_url($pcsUploadDirURL, self::UPLOAD_DIR));
    }

    /**
     * addForm
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function addForm(Request $request, Response $response, array $args)
    {

        set_custom_assets([
            self::BASE_JS_DIR . '/private/forms.js',
        ], 'js');

        import_cropper();

        $action = self::routeName('actions-add');
        $backLink = self::routeName('list');

        $data = [];
        $data['action'] = $action;
        $data['langGroup'] = self::LANG_GROUP;
        $data['backLink'] = $backLink;
        $data['title'] = self::$title;

        $this->render('panel/layout/header');
        self::view('private/forms/add', $data);
        $this->render('panel/layout/footer');

    }

    /**
     * editForm
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function editForm(Request $request, Response $response, array $args)
    {

        $id = $request->getAttribute('id', null);
        $id = !is_null($id) && ctype_digit($id) ? (int) $id : null;

        $element = new ImageMapper($id);

        if (!is_null($element->id)) {

            set_custom_assets([
                self::BASE_JS_DIR . '/private/delete-config.js',
                self::BASE_JS_DIR . '/private/forms.js',
            ], 'js');

            import_cropper();

            $action = self::routeName('actions-edit');
            $backLink = self::routeName('list');

            $data = [];
            $data['action'] = $action;
            $data['element'] = $element;
            $data['deleteRoute'] = self::routeName('actions-delete', ['id' => $element->id]);
            $data['allowDelete'] = self::allowedRoute('actions-delete', ['id' => $element->id]);
            $data['langGroup'] = self::LANG_GROUP;
            $data['backLink'] = $backLink;
            $data['title'] = self::$title;

            $this->render('panel/layout/header');
            self::view('private/forms/edit', $data, true, false);
            $this->render('panel/layout/footer');

        } else {
            throw new NotFoundException($request, $response);
        }

    }

    /**
     * listView
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function listView(Request $request, Response $response, array $args)
    {

        $backLink = EntryPointController::routeName('options');
        $addLink = self::routeName('forms-add');

        $processTableLink = self::routeName('datatables');

        $title = self::$pluralTitle;

        $data = [];
        $data['processTableLink'] = $processTableLink;
        $data['langGroup'] = self::LANG_GROUP;
        $data['backLink'] = $backLink;
        $data['addLink'] = $addLink;
        $data['hasPermissionsAdd'] = mb_strlen($addLink) > 0;
        $data['title'] = $title;

        set_custom_assets([
            self::BASE_JS_DIR . '/private/delete-config.js',
            self::BASE_JS_DIR . '/private/list.js',
        ], 'js');

        $this->render('panel/layout/header');
        self::view('private/list', $data);
        $this->render('panel/layout/footer');

    }

    /**
     * action
     *
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
                'title',
                [],
                function ($value) {

                    $valid = false;
                    $allowedLangs = get_config('allowed_langs');

                    if (is_array($value)) {

                        foreach ($value as $lang => $text) {

                            if (in_array($lang, $allowedLangs) && is_string($text)) {
                                $valid = true;
                            } else {
                                $valid = false;
                                break;
                            }

                        }

                    }

                    return $valid;
                },
                true,
                function ($value) {
                    foreach ($value as $lang => $text) {
                        $value[$lang] = clean_string($text);
                    }
                    return $value;
                }
            ),
            new Parameter(
                'description',
                [],
                function ($value) {

                    $valid = false;
                    $allowedLangs = get_config('allowed_langs');

                    if (is_array($value)) {

                        foreach ($value as $lang => $text) {

                            if (in_array($lang, $allowedLangs) && is_string($text) && mb_strlen(trim($text)) > 0) {
                                $valid = true;
                            } else {
                                $valid = false;
                                break;
                            }

                        }

                    }

                    return $valid;
                },
                true,
                function ($value) {
                    foreach ($value as $lang => $text) {
                        $value[$lang] = clean_string($text);
                    }
                    return $value;
                }
            ),
            new Parameter(
                'link',
                [],
                function ($value) {

                    $valid = false;
                    $allowedLangs = get_config('allowed_langs');

                    if (is_array($value)) {

                        foreach ($value as $lang => $text) {

                            if (in_array($lang, $allowedLangs) && is_string($text) && mb_strlen(trim($text)) > 0) {

                                $originalInput = $text;
                                $text = HeroController::parseURL($text);
                                $valid = filter_var($text, \FILTER_VALIDATE_URL, \FILTER_FLAG_PATH_REQUIRED) !== false;

                                if (!$valid) {
                                    throw new \Exception(__(self::LANG_GROUP, 'La URL del enlace tiene un formato inválido') . "({$originalInput})");
                                }

                            } else {
                                $valid = false;
                                break;
                            }

                        }

                    }

                    return $valid;

                },
                true,
                function ($value) {
                    foreach ($value as $lang => $text) {
                        $value[$lang] = HeroController::parseURL($text);
                    }
                    return $value;
                }
            ),
            new Parameter(
                'start_date',
                null,
                function ($value) {
                    return $value === null || Validator::isDate($value, 'd-m-Y h:i A');
                },
                true,
                function ($value) {
                    return $value === null ? $value : \DateTime::createFromFormat('d-m-Y h:i A', $value);
                }
            ),
            new Parameter(
                'end_date',
                null,
                function ($value) {
                    return $value === null || Validator::isDate($value, 'd-m-Y h:i A');
                },
                true,
                function ($value) {
                    return $value === null ? $value : \DateTime::createFromFormat('d-m-Y h:i A', $value);
                }
            ),
            new Parameter(
                'order',
                0,
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
        $inputData = $request->getParsedBody();

        //Asignación de datos para procesar
        $expectedParameters->setInputValues(is_array($inputData) ? $inputData : []);

        //──── Estructura de respuesta ───────────────────────────────────────────────────────────

        $resultOperation = new ResultOperations([], __(self::LANG_GROUP, 'Imagen'));
        $resultOperation->setSingleOperation(true); //Se define que es de una única operación

        //Valores iniciales de la respuesta
        $resultOperation->setSuccessOnSingleOperation(false);
        $resultOperation->setValue('redirect', false);
        $resultOperation->setValue('redirect_to', null);
        $resultOperation->setValue('reload', false);

        //Mensajes de respuesta
        $notExistsMessage = __(self::LANG_GROUP, 'La imagen que intenta modificar no existe.');
        $successCreateMessage = __(self::LANG_GROUP, 'Imagen creada.');
        $successEditMessage = __(self::LANG_GROUP, 'Datos guardados.');
        $unknowErrorMessage = __(self::LANG_GROUP, 'Ha ocurrido un error desconocido.');
        $unknowErrorWithValuesMessage = __(self::LANG_GROUP, 'Ha ocurrido un error desconocido al procesar los valores ingresados.');
        $noImageMessage = __(self::LANG_GROUP, 'No ha sido subida ninguna imagen.');

        //──── Acciones ──────────────────────────────────────────────────────────────────────────
        try {

            //Intenta validar, si todo sale bien el código continúa
            $expectedParameters->validate();

            //Información del formulario
            /**
             * @var int $id
             * @var string[] $title
             * @var string[] $description
             * @var string[] $link
             * @var \DateTime|null $start_date
             * @var \DateTime|null $end_date
             * @var int $order
             */;
            $id = $expectedParameters->getValue('id');
            $title = $expectedParameters->getValue('title');
            $description = $expectedParameters->getValue('description');
            $link = $expectedParameters->getValue('link');
            $startDate = $expectedParameters->getValue('start_date');
            $endDate = $expectedParameters->getValue('end_date');
            $order = $expectedParameters->getValue('order');

            //Se define si es edición o creación
            $isEdit = $id !== -1;

            //Dirección de redirección en cadso de creación
            $redirectURLOnCreate = self::routeName('list');

            $defaultLang = get_config('default_lang');
            $allowedLangs = get_config('allowed_langs');

            try {

                if (!$isEdit) {
                    //Nuevo

                    $mapper = new ImageMapper();

                    $folder = (new \DateTime)->format('Y/m/d/') . str_replace('.', '', uniqid());
                    $imagePrefix = 'image-';

                    $images = [];

                    foreach ($allowedLangs as $lang) {

                        $imageLangName = "{$imagePrefix}{$lang}";

                        if (array_key_exists($imageLangName, $_FILES)) {

                            $images[$lang] = self::handlerUploadImage($imageLangName, $folder);

                        }

                    }

                    $mapper->title = array_key_exists($defaultLang, $title) ? $title[$defaultLang] : '';
                    $mapper->description = array_key_exists($defaultLang, $description) ? $description[$defaultLang] : '';
                    $mapper->link = array_key_exists($defaultLang, $link) ? $link[$defaultLang] : '';
                    $mapper->image = array_key_exists($defaultLang, $images) ? $images[$defaultLang] : '';
                    $mapper->start_date = $startDate !== null ? $startDate->format('Y-m-d H:i:') . '00' : null;
                    $mapper->end_date = $endDate !== null ? $endDate->format('Y-m-d H:i:') . '00' : null;
                    $mapper->order = $order;

                    foreach ($allowedLangs as $lang) {

                        if ($lang == $defaultLang) {
                            continue;
                        }

                        if (array_key_exists($lang, $title)) {
                            $mapper->setLangData($lang, 'title', $title[$lang]);
                        }

                        if (array_key_exists($lang, $description)) {
                            $mapper->setLangData($lang, 'description', $description[$lang]);
                        }

                        if (array_key_exists($lang, $link)) {
                            $mapper->setLangData($lang, 'link', $link[$lang]);
                        }

                        if (array_key_exists($lang, $images)) {
                            $mapper->setLangData($lang, 'image', $images[$lang]);
                        } else {

                            $defaultLangImage = $images[$defaultLang];
                            $defaultLangImageBasename = basename($defaultLangImage);
                            $copyBasename = $lang . '-' . $defaultLangImageBasename;
                            $copyDest = str_replace($defaultLangImageBasename, $copyBasename, $defaultLangImage);
                            copy(basepath($defaultLangImage), basepath($copyDest));
                            $mapper->setLangData($lang, 'image', $copyDest);
                        }

                    }

                    if (mb_strlen($mapper->image) > 0) {

                        $saved = $mapper->save();

                        $resultOperation->setSuccessOnSingleOperation($saved);

                        if ($saved) {

                            $resultOperation
                                ->setMessage($successCreateMessage)
                                ->setValue('redirect', true)
                                ->setValue('redirect_to', $redirectURLOnCreate);

                        } else {
                            $resultOperation->setMessage($unknowErrorMessage);
                        }

                    } else {
                        $resultOperation->setMessage($noImageMessage);
                    }

                } else {
                    //Existente

                    $mapper = new ImageMapper((int) $id);
                    $exists = !is_null($mapper->id);

                    if ($exists) {

                        $imagePrefix = 'image-';

                        $images = [];

                        foreach ($allowedLangs as $lang) {

                            $imageLangName = "{$imagePrefix}{$lang}";

                            if (array_key_exists($imageLangName, $_FILES)) {

                                $images[$lang] = self::handlerUploadImage($imageLangName, '', $mapper->getLangData($lang, 'image'));

                            }

                        }

                        $mapper->title = array_key_exists($defaultLang, $title) ? $title[$defaultLang] : '';
                        $mapper->description = array_key_exists($defaultLang, $description) ? $description[$defaultLang] : '';
                        $mapper->link = array_key_exists($defaultLang, $link) ? $link[$defaultLang] : '';

                        $imageDefault = array_key_exists($defaultLang, $images) ? $images[$defaultLang] : '';
                        $mapper->image = mb_strlen($imageDefault) > 0 ? $imageDefault : $mapper->image;

                        $mapper->start_date = $startDate !== null ? $startDate->format('Y-m-d H:i:') . '00' : null;
                        $mapper->end_date = $endDate !== null ? $endDate->format('Y-m-d H:i:') . '00' : null;
                        $mapper->order = $order;

                        foreach ($allowedLangs as $lang) {

                            if ($lang == $defaultLang) {
                                continue;
                            }

                            if (array_key_exists($lang, $title)) {
                                $mapper->setLangData($lang, 'title', $title[$lang]);
                            }

                            if (array_key_exists($lang, $description)) {
                                $mapper->setLangData($lang, 'description', $description[$lang]);
                            }

                            if (array_key_exists($lang, $link)) {
                                $mapper->setLangData($lang, 'link', $link[$lang]);
                            }

                            if (array_key_exists($lang, $images)) {
                                $mapper->setLangData($lang, 'image', $images[$lang]);
                            }

                        }

                        $updated = $mapper->update();

                        $resultOperation->setSuccessOnSingleOperation($updated);

                        if ($updated) {

                            $resultOperation
                                ->setMessage($successEditMessage)
                                ->setValue('redirect', true)
                                ->setValue('redirect_to', $redirectURLOnCreate);

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
     * toDelete
     *
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

        $resultOperation = new ResultOperations([], __(self::LANG_GROUP, 'Eliminar imagen'));
        $resultOperation->setSingleOperation(true); //Se define que es de una única operación

        //Valores iniciales de la respuesta
        $resultOperation->setSuccessOnSingleOperation(false);
        $resultOperation->setValue('redirect', false);
        $resultOperation->setValue('redirect_to', null);
        $resultOperation->setValue('reload', false);
        $resultOperation->setValue('received', $inputData);

        //Mensajes de respuesta
        $notExistsMessage = __(self::LANG_GROUP, 'La imagen que intenta eliminar no existe.');
        $successMessage = __(self::LANG_GROUP, 'Imagen eliminada.');
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

            //Dirección de redirección en cadso de creación
            $redirectURLOn = self::routeName('list');

            try {

                $exists = ImageMapper::existsByID($id);

                if ($exists) {

                    $imageTable = ImageMapper::TABLE;

                    $transactionSQLDeleteQueries = [
                        [
                            'query' => "DELETE FROM {$imageTable} WHERE id = :ID",
                            'aliasConfig' => [
                                ':ID' => $id,
                            ],
                        ],
                    ];

                    $pdo = ImageMapper::model()::getDb(Config::app_db('default')['db']);

                    try {

                        $imageMapper = new ImageMapper($id);
                        $imageToDelete = basepath($imageMapper->image);

                        $pdo->beginTransaction();

                        foreach ($transactionSQLDeleteQueries as $sqlQueryConfig) {

                            $query = $sqlQueryConfig['query'];
                            $aliasConfig = $sqlQueryConfig['aliasConfig'];

                            $preparedStatement = $pdo->prepare($query);
                            $preparedStatement->execute($aliasConfig);

                        }

                        $pdo->commit();

                        if (file_exists($imageToDelete)) {
                            unlink($imageToDelete);
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
     * all
     *
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

        $inputData = $request->getQueryParams();
        $expectedParameters->setInputValues(is_array($inputData) ? $inputData : []);
        $expectedParameters->validate();

        /**
         * @var int $id
         * @var int $perPage
         */;
        $page = $expectedParameters->getValue('page');
        $perPage = $expectedParameters->getValue('per_page');

        $model = ImageMapper::model();

        $table = $model->getTable();

        $defaultLang = get_config('default_lang');
        $currentLang = Config::get_lang();

        $selects = [];

        $selects[] = "{$table}.id";
        $selects[] = "{$table}.meta";

        $jsonExtractExists = ImageMapper::jsonExtractExistsMySQL();

        if ($jsonExtractExists) {

            if ($defaultLang == $currentLang) {
                $selects[] = "{$table}.title";
                $selects[] = "{$table}.description";
                $selects[] = "{$table}.link";
                $selects[] = "{$table}.image";
            } else {
                $selects[] = "IF(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.title') = 'null', {$table}.title, JSON_UNQUOTE(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.title'))) AS title";
                $selects[] = "IF(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.description') = 'null', {$table}.description, JSON_UNQUOTE(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.description'))) AS description";
                $selects[] = "IF(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.link') = 'null', {$table}.link, JSON_UNQUOTE(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.link'))) AS link";
                $selects[] = "IF(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.image') = 'null', {$table}.image, JSON_UNQUOTE(JSON_EXTRACT({$table}.meta, '$.lang_data.{$currentLang}.image'))) AS image";
            }

            $selects[] = "IF(JSON_EXTRACT({$table}.meta, '$.start_date') = 'null', NULL, JSON_UNQUOTE(JSON_EXTRACT({$table}.meta, '$.start_date'))) AS start_date";
            $selects[] = "IF(JSON_EXTRACT({$table}.meta, '$.end_date') = 'null', NULL, JSON_UNQUOTE(JSON_EXTRACT({$table}.meta, '$.end_date'))) AS end_date";
            $selects[] = "IF(JSON_EXTRACT({$table}.meta, '$.order') = 'null', NULL, JSON_EXTRACT({$table}.meta, '$.order')) AS `order`";

            $model->select($selects)->orderBy("`order` ASC");

            $now = date('Y-m-d H:i:') . '00';

            $having = [
                "(start_date IS NULL OR start_date <= '{$now}')",
                "AND (end_date IS NULL OR end_date >= '{$now}')",
            ];

            $having = implode(' ', $having);

            $model->having($having);

            $model->execute(false, $page, $perPage);

            $result = $model->result();

        } else {

            $selects[] = "{$table}.title";
            $selects[] = "{$table}.description";
            $selects[] = "{$table}.link";
            $selects[] = "{$table}.image";

            $model->select($selects);

            $model->execute(false, $page, $perPage);

            $result = $model->result();

            foreach ($result as $k => $i) {

                $meta = json_decode($i->meta);

                $result[$k]->meta = $meta;
                $result[$k]->end_date = $meta->end_date;
                $result[$k]->order = $meta->order;
                $result[$k]->order = $meta->order;

                $lang_data = $meta->lang_data;

                if ($defaultLang != $currentLang && is_object($lang_data)) {

                    $lang_data = isset($lang_data->$currentLang) ? $lang_data->$currentLang : null;

                    if ($lang_data !== null) {
                        $result[$k]->title = isset($lang_data->title) ? $lang_data->title : '';
                        $result[$k]->description = isset($lang_data->description) ? $lang_data->description : '';
                        $result[$k]->link = isset($lang_data->link) ? $lang_data->link : '';
                        $result[$k]->image = $lang_data->image;
                    }

                }

            }

        }

        return $response->withJson($result);
    }

    /**
     * dataTables
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function dataTables(Request $request, Response $response, array $args)
    {

        $whereString = null;

        $table = ImageMapper::TABLE;

        $selectFields = [
            "{$table}.id",
            "{$table}.title",
            "{$table}.description",
            "{$table}.link",
            "{$table}.image",
            "{$table}.meta",
        ];

        $columnsOrder = [
            'title',
            'description',
        ];

        DataTablesHelper::setTablePrefixOnOrder(false);
        DataTablesHelper::setTablePrefixOnSearch(false);

        $result = DataTablesHelper::process([

            'where_string' => $whereString,
            'select_fields' => $selectFields,
            'columns_order' => $columnsOrder,
            'mapper' => new ImageMapper(),
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

            $mapper = new ImageMapper($element->id);

            $rawData[$index] = self::view(
                'private/util/list-card',
                [
                    'mapper' => $mapper,
                    'editLink' => self::routeName('forms-edit', [
                        'id' => $mapper->id,
                    ]),
                    'hasEdit' => self::allowedRoute('forms-edit', ['id' => $mapper->id]),
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
     * parseURL
     *
     * @param string $value
     * @param bool $autoSchema
     * @return string
     */
    protected static function parseURL(string $value)
    {

        if (mb_strlen($value) > 0) {

            $value = str_replace(' ', '', $value);
            $value = trim($value, '.');
            $value = trim($value);
            $value = rtrim($value, '/');
            $value = mb_strtolower($value);
            $https = false;

            $hasPoints = mb_strpos($value, '.') !== false;

            if ($hasPoints) {

                if (mb_strpos($value, 'https://') !== false) {
                    $https = true;
                }

                $value = str_replace([
                    'http://',
                    'https://',
                ], '', $value);

                $value = $https ? "https://{$value}" : "http://{$value}";

            } else {
                $value = '';
            }

            $urlSegments = parse_url($value);

            $scheme = array_key_exists('scheme', $urlSegments) ? $urlSegments['scheme'] : null;
            $host = array_key_exists('host', $urlSegments) ? $urlSegments['host'] : null;
            $path = array_key_exists('path', $urlSegments) ? $urlSegments['path'] : null;
            $query = array_key_exists('query', $urlSegments) ? $urlSegments['query'] : null;

            if ($scheme !== null && $host !== null) {

                $value = "{$scheme}://{$host}";

                if ($path !== null) {
                    $path = trim($path, '/');
                    $value .= "/{$path}/";
                } else {
                    $value .= '/';
                }

                if ($query !== null) {
                    $value .= "?{$query}";
                }

            } else {
                $value = '';
            }

        }

        return $value;

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

            if ($name == 'SAMPLE') { //do something
            }

        }

        return $allow;
    }

    /**
     * handlerUploadImage
     *
     * @param string $nameOnFiles
     * @param string $folder
     * @param string $currentRoute
     * @param bool $setNameByInput
     * @return string
     * @throws \Exception
     */
    protected static function handlerUploadImage(string $nameOnFiles, string $folder, string $currentRoute = null, bool $setNameByInput = true)
    {
        $handler = new FileUpload($nameOnFiles, [
            FileValidator::TYPE_ALL_IMAGES,
        ]);
        $valid = false;
        $relativeURL = '';

        $name = 'file_' . uniqid();
        $oldFile = null;

        if ($handler->hasInput()) {

            try {

                $valid = $handler->validate();

                $uploadDirPath = (new static )->uploadDir;
                $uploadDirRelativeURL = (new static )->uploadDirURL;

                if ($setNameByInput && $valid) {

                    $name = $_FILES[$nameOnFiles]['name'];
                    $lastPointIndex = mb_strrpos($name, '.');

                    if ($lastPointIndex !== false) {
                        $name = mb_substr($name, 0, $lastPointIndex);
                    }

                }

                if (!is_null($currentRoute)) {
                    //Si ya existe
                    $oldFile = append_to_url(basepath(), $currentRoute);
                    $oldFile = file_exists($oldFile) ? $oldFile : null;

                    if (mb_strlen(trim($folder)) < 1) {
                        //Si folder está vacío
                        $folder = str_replace($uploadDirRelativeURL, '', $currentRoute);
                        $folder = str_replace(basename($currentRoute), '', $folder);
                        $folder = trim($folder, '/');
                    }

                }

                $uploadDirPath = append_to_url($uploadDirPath, $folder);
                $uploadDirRelativeURL = append_to_url($uploadDirRelativeURL, $folder);

                if ($valid) {

                    $locations = $handler->moveTo($uploadDirPath, $name, null, false, true);

                    if (count($locations) > 0) {

                        $url = $locations[0];
                        $nameCurrent = basename($url);
                        $relativeURL = trim(append_to_url($uploadDirRelativeURL, $nameCurrent), '/');

                        //Eliminar archivo anterior
                        if (!is_null($oldFile)) {

                            if (basename($oldFile) != $nameCurrent) {
                                unlink($oldFile);
                            }

                        }

                        //Se elimina cualquier otro archivo
                        foreach ($locations as $file) {
                            if ($url != $file) {
                                if (is_string($file) && file_exists($file)) {
                                    unlink($file);
                                }
                            }
                        }

                    }

                } else {
                    throw new \Exception(implode('<br>', $handler->getErrorMessages()));
                }

            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

        }

        return $relativeURL;
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

        $permisos_listado = $all_roles;

        $permisos_estados_gestion = [
            UsersModel::TYPE_USER_ROOT,
            UsersModel::TYPE_USER_ADMIN,
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
                "{$startRoute}/forms/edit/{id}[/]",
                $classname . ':editForm',
                self::$baseRouteName . '-forms-edit',
                'GET',
                true,
                null,
                $permisos_estados_gestion
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
                $all_roles
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
