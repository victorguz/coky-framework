<?php

/**
 * BlackboardNewsController.php
 */

namespace App\Controller;

use App\Model\BlackboardNewsModel;
use App\Model\UsersModel;
use PiecesPHP\Core\HTML\HtmlElement;
use PiecesPHP\Core\Roles;
use PiecesPHP\Core\Route;
use PiecesPHP\Core\RouteGroup;
use PiecesPHP\Core\Utilities\Helpers\DataTablesHelper;
use PiecesPHP\Core\Utilities\ReturnTypes\Operation;
use PiecesPHP\Core\Utilities\ReturnTypes\ResultOperations;
use Slim\Exception\NotFoundException;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

/**
 * BlackboardNewsController.
 *
 * Controlador de tablón de noticias
 *
 * @package     App\Controller
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 */
class BlackboardNewsController extends AdminPanelController
{

    const UPLOAD_DIR = 'blackboard';
    const UPLOAD_DIR_TMP = 'blackboard/tmp';
    const FORMAT_DATETIME = 'Y-m-d h:i A';

    /**
     * $uploadDir
     *
     * @var string
     */
    protected $uploadDir = '';
    /**
     * $uploadDir
     *
     * @var string
     */
    protected $uploadTmpDir = '';
    /**
     * $uploadDirURL
     *
     * @var string
     */
    protected $uploadDirURL = '';
    /**
     * $uploadDirTmpURL
     *
     * @var string
     */
    protected $uploadDirTmpURL = '';

    const LANG_GROUP = 'news';

    /**
     * __construct
     *
     * @return static
     */
    public function __construct()
    {
        parent::__construct(false); //No cargar ningún modelo automáticamente.
        add_global_asset(BLACKBOARD_NEWS_PATH_JS . '/main.js', 'js');

        $this->uploadDir = append_to_url(get_config('upload_dir'), self::UPLOAD_DIR);
        $this->uploadTmpDir = append_to_url(get_config('upload_dir'), self::UPLOAD_DIR_TMP);
        $this->uploadDirURL = append_to_url(get_config('upload_dir_url'), self::UPLOAD_DIR);
        $this->uploadDirTmpURL = append_to_url(get_config('upload_dir_url'), self::UPLOAD_DIR_TMP);
    }

    /**
     * listView
     *
     * Vista de listado de noticias
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return void
     */
    public function listView(Request $req, Response $res, array $args)
    {
        $data = [];
        $data["process_table"] = get_route('blackboard-datatables');;
        $this->render('panel/layout/header');
        $this->render(BLACKBOARD_NEWS_PATH_VIEWS . '/list', $data);
        $this->render('panel/layout/footer');

        return $res;
    }

    /**
     * addForm
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function addForm(Request $request, Response $response, array $args)
    {

        $types = Roles::getRolesIdentifiers(true);

        import_quilljs(['videoResize', 'imageResize']);

        $this->render(ADMIN_PATH_VIEWS . '/layout/header');
        $this->render(BLACKBOARD_NEWS_PATH_VIEWS . '/create', [
            'types' => $types,
            'action' => get_route('blackboard-news-create'),
            'date_format' => self::FORMAT_DATETIME,
        ]);
        $this->render(ADMIN_PATH_VIEWS . '/layout/footer');

        return $response;
    }

    /**
     * editForm
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function editForm(Request $request, Response $response, array $args)
    {

        $id = isset($args['id']) ? $args['id'] : null;
        $id = ctype_digit($id) ? (int) $id : null;
        $types = Roles::getRolesIdentifiers(true);
        $new = new BlackboardNewsModel($id);

        if (!is_null($new->id)) {

            import_quilljs(['videoResize', 'imageResize']);

            $this->render(ADMIN_PATH_VIEWS . '/layout/header');
            $this->render(BLACKBOARD_NEWS_PATH_VIEWS . '/edit', [
                'types' => $types,
                'new' => $new,
                'action' => get_route('blackboard-news-edit'),
                'date_format' => self::FORMAT_DATETIME,
            ]);
            $this->render(ADMIN_PATH_VIEWS . '/layout/footer');

            return $response;
        } else {
            throw new NotFoundException($request, $response);
        }
    }

    /**
     * deleteNew
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteNew(Request $request, Response $response, array $args)
    {

        $id = isset($args['id']) ? $args['id'] : null;
        $id = ctype_digit($id) ? (int) $id : null;
        $new = new BlackboardNewsModel($id);

        $result = new ResultOperations([
            'deleteNew' => new Operation('deleteNew'),
        ], __(self::LANG_GROUP, 'Eliminación de noticia'));

        if (!is_null($new->id)) {

            $deleted = $new->getModel()->delete([
                'id' => $id,
            ])->execute();

            if ($deleted) {
                $directory = append_to_url($this->uploadDir, (string) $id);
                remove_directory($directory);
            }

            $result
                ->setMessage($deleted ? __(self::LANG_GROUP, 'La noticia ha sido eliminada.') : __(self::LANG_GROUP, 'La noticia no pudo ser eliminada, intente luego.'))
                ->operation('deleteNew')
                ->setSuccess($deleted);
        } else {
            $result->setMessage(__(self::LANG_GROUP, 'La noticia no existe'));
        }
        return $response->withJson($result);
    }

    /**
     * registerNew
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function registerNew(Request $request, Response $response, array $args)
    {

        $author = $request->getParsedBodyParam('author', null);
        $types = $request->getParsedBodyParam('types', null);
        $title = $request->getParsedBodyParam('title', null);
        $text = $request->getParsedBodyParam('text', null);
        $start_date = $request->getParsedBodyParam('start_date', null);
        $end_date = $request->getParsedBodyParam('end_date', null);


        $start_date = mb_strlen(trim($start_date)) > 0 ? date_create_from_format(self::FORMAT_DATETIME, $start_date) : null;
        $end_date = mb_strlen(trim($end_date)) > 0 ? date_create_from_format(self::FORMAT_DATETIME, $end_date) : null;

        $valid_params = !in_array(null, [
            $author,
            $types,
            $title,
            $text,
        ]);

        $operation_name = "Añadir noticia";

        $result = new ResultOperations(
            [
                new Operation($operation_name),
            ],
            $operation_name
        );

        $result->setValue('redirect', false);

        $error_parameters_message = 'Los parámetros recibidos son erróneos.';
        $not_exists_message = 'El registro que intenta modificar no existe';
        $success_create_message = 'Registro guardado.';
        $success_edit_message = 'Registro modificado.';
        $unknow_error_message = 'Ha ocurrido un error desconocido.';

        $redirect_url_on_create = get_route('blackboard-news-list');

        if ($valid_params) {

            try {
                $entity = new BlackboardNewsModel();

                $entity->author = $this->user->id;
                $entity->types = json_encode($types);
                $entity->title = $title;
                $entity->text = $text;
                $entity->start_date = $start_date;
                $entity->end_date = $end_date;
                $entity->created_date = date("Y-m-d H:i:s");
                $saved = $entity->update();

                if ($saved) {
                    $result->setValue('redirect', true);
                    $result->setValue('redirect_to', $redirect_url_on_create);

                    $result->setMessage($success_edit_message)
                        ->operation($operation_name)
                        ->setSuccess(true);
                } else {
                    $result->setMessage($unknow_error_message);
                }
            } catch (\Exception $e) {
                $result->setMessage($e->getMessage());
                log_exception($e);
            }
        } else {
            $result->setMessage($error_parameters_message);
        }
        return $response->withJson($result);
    }

    /**
     * editNew
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function editNew(Request $request, Response $response, array $args)
    {

        $id = $request->getParsedBodyParam('id', null);
        $author = $request->getParsedBodyParam('author', null);
        $types = $request->getParsedBodyParam('types', null);
        $title = $request->getParsedBodyParam('title', null);
        $text = $request->getParsedBodyParam('text', null);
        $start_date = $request->getParsedBodyParam('start_date', null);
        $end_date = $request->getParsedBodyParam('end_date', null);


        $start_date = mb_strlen(trim($start_date)) > 0 ? date_create_from_format(self::FORMAT_DATETIME, $start_date) : null;
        $end_date = mb_strlen(trim($end_date)) > 0 ? date_create_from_format(self::FORMAT_DATETIME, $end_date) : null;

        $valid_params = !in_array(null, [
            $author,
            $types,
            $title,
            $text,
        ]);

        $operation_name = "Editar noticia";

        $result = new ResultOperations(
            [
                new Operation($operation_name),
            ],
            $operation_name
        );

        $result->setValue('redirect', false);

        $error_parameters_message = 'Los parámetros recibidos son erróneos.';
        $not_exists_message = 'El registro que intenta modificar no existe';
        $success_create_message = 'Registro guardado.';
        $success_edit_message = 'Registro modificado.';
        $unknow_error_message = 'Ha ocurrido un error desconocido.';

        $redirect_url_on_create = get_route('blackboard-news-list');

        if ($valid_params) {

            try {
                $entity = new BlackboardNewsModel($id);
                $exist = !is_null($entity->id);
                if ($exist) {

                    $entity->author = $this->user->id;
                    $entity->types = json_encode($types);
                    $entity->title = $title;
                    $entity->text = $text;
                    $entity->start_date = $start_date;
                    $entity->end_date = $end_date;
                    $saved = $entity->update();

                    if ($saved) {
                        $result->setValue('reload', true);

                        $result->setMessage($success_edit_message)
                            ->operation($operation_name)
                            ->setSuccess(true);
                    } else {
                        $result->setMessage($unknow_error_message);
                    }
                } else {
                    $result->setMessage($not_exists_message);
                }
            } catch (\Exception $e) {
                $result->setMessage($e->getMessage());
                log_exception($e);
            }
        } else {
            $result->setMessage($error_parameters_message);
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

        if ($request->isXhr()) {

            $columns_order = [
                'id',
                'title',
                'start_date',
                'end_date',
            ];

            $result = DataTablesHelper::process([
                'columns_order' => $columns_order,
                'custom_order' => ['id' => 'DESC'],
                'mapper' => new BlackboardNewsModel(),
                'request' => $request,
                'on_set_data' => function ($e) {

                    $mapper = new BlackboardNewsModel($e->id);

                    $editButton = new HtmlElement('a', "<i class='edit icon'></i>");
                    $editButton->setAttribute('class', "ui secondary icon button");
                    $editButton->setAttribute('href', get_route('blackboard-news-edit-form', [
                        'id' => $e->id,
                    ]));

                    if ($editButton->getAttributes(false)->offsetExists('href')) {
                        $href = $editButton->getAttributes(false)->offsetGet('href');
                        if (strlen(trim($href->getValue())) < 1) {
                            $editButton = '';
                        }
                    }

                    return [
                        $mapper->id,
                        $mapper->title,
                        date_format($mapper->start_date, "Y-m-d H:i A"),
                        date_format($mapper->end_date, "Y-m-d H:i A"),
                        (string)$editButton
                    ];
                },
            ]);

            return $response->withJson($result->getValues());
        } else {
            throw new NotFoundException($request, $response);
        }
    }
    /**
     * moveTemporaryImages
     *
     * @param BlackboardNewsModel $entity
     * @param string $oldText
     * @return void
     */
    protected function moveTemporaryImages(BlackboardNewsModel &$entity, string $oldText = null)
    {
        $imagesOnText = [];
        $imagesOnOldText = [];
        $currentImagesOnText = [];

        $isEdit = !is_null($oldText) && mb_strlen($oldText) > 0;
        $id = $entity->id;

        $regex = '/https?\:\/\/[^\",]+/i';

        preg_match_all($regex, $entity->text, $imagesOnText);

        $imagesOnText = $imagesOnText[0];

        if (count($imagesOnText) > 0) {

            foreach ($imagesOnText as $url) {

                if (mb_strpos($url, $this->uploadDirTmpURL) !== false) {

                    $filename = str_replace($this->uploadDirTmpURL, '', $url);

                    $oldPath = append_to_url($this->uploadTmpDir, "$filename");

                    $newFolder = append_to_url($this->uploadDir, "$id");

                    $newPath = append_to_url($newFolder, "$filename");

                    if (!file_exists($newFolder)) {
                        make_directory($newFolder);
                    }

                    if (file_exists($oldPath)) {
                        rename($oldPath, $newPath);
                    }

                    $_url = append_to_url($this->uploadDirURL, "$id/$filename");

                    $entity->text = str_replace($url, $_url, $entity->text);

                    $currentImagesOnText[] = $_url;
                } elseif (mb_strpos($url, $this->uploadDirURL) !== false) {

                    $currentImagesOnText[] = $url;
                }
            }
        }

        $updated = $entity->update();

        if ($isEdit) {

            preg_match_all($regex, $oldText, $imagesOnOldText);
            $imagesOnOldText = $imagesOnOldText[0];

            if ($updated && count($imagesOnOldText) > 0) {

                foreach ($imagesOnOldText as $url) {

                    if (!in_array($url, $currentImagesOnText)) {

                        $filename = str_replace($this->uploadDirURL, '', $url);

                        $path = append_to_url($this->uploadDir, $filename);

                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                }
            }
        }
    }

    /**
     * getNews
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getNews(Request $request, Response $response, array $args)
    {
        $page = (int) $request->getQueryParam('page', 1);
        $perPage = (int) $request->getQueryParam('per_page', 10);
        $isList = $request->getQueryParam('is_list', null) === 'true';

        $model = (new BlackboardNewsModel())->getModel();

        $now = (new \DateTime())->format('Y-m-d h:s:i');

        $where = trim(implode(' ', [
            " start_date <= '$now' OR end_date > '$now' OR ",
            " start_date IS NULL OR end_date IS NULL",
        ]));

        $query = $model->select()->where($where)->orderBy('start_date DESC, created_date DESC');

        $queryTotal = clone $query;

        $queryTotal->select()->execute();
        $query->execute(false, $page, $perPage);

        $result = $query->result();
        $total = count($queryTotal->result());

        $filtered = [];

        foreach ($result as $element) {

            $mapper = new BlackboardNewsModel($element->id);
            $element->author = [
                'id' => $mapper->author->id,
                'username' => $mapper->author->username,
                'firstname' => $mapper->author->firstname,
                'secondname' => $mapper->author->secondname,
                'first_lastname' => $mapper->author->first_lastname,
                'second_lastname' => $mapper->author->second_lastname,
                'email' => $mapper->author->email,
                'meta' => $mapper->author->meta,
            ];
            $element->types = [
                'codes' => is_string($mapper->types) ? json_decode($mapper->types) : $mapper->types,
                'labels' => ["hola"],
            ];

            foreach ($element->types["codes"] as  $value) {
                array_push($element->types["labels"], UsersModel::TYPES_USERS[$value]);
            }

            if (!is_null($element->start_date) && !is_null($element->end_date)) {
                $element->start_date = $mapper->start_date->format('Y-m-d h:i A');
                $element->end_date = $mapper->end_date->format('Y-m-d h:i A');
            }

            $element->title = $mapper->title;
            $element->text = $mapper->text;

            $filtered[] = $element;
        }

        return $response->withJson(
            (new ResultOperations([], 'Noticias', __(self::LANG_GROUP, 'Paginado de noticias')))
                ->setValue(self::LANG_GROUP, $filtered)
                ->setValue('page', $page)
                ->setValue('perPage', $perPage)
                ->setValue('total', $total)
                ->setValue('pages', ceil($total / $perPage))
        );
    }

    /**
     * imageHandler
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function imageHandler(Request $request, Response $response, array $args)
    {
        $files_uploaded = $request->getUploadedFiles();
        $image = isset($files_uploaded['image']) ? $files_uploaded['image'] : null;

        $result = new ResultOperations([
            'uploadImage' => new Operation('uploadImage'),
        ]);

        $result->setValue('path', null);

        if (!is_null($image)) {

            $images = is_array($image) ? $image : [$image];

            foreach ($images as $image) {

                if ($image->getError() === UPLOAD_ERR_OK) {

                    $filename = move_uploaded_file_to($this->uploadTmpDir, $image, uniqid());

                    $url = append_to_url($this->uploadDirTmpURL, $filename);

                    if (!is_null($filename)) {
                        $result
                            ->operation('uploadImage')
                            ->setMessage(__(self::LANG_GROUP, 'Imagen subida'))
                            ->setSuccess(true);
                        $result->setValue('path', $url);
                    } else {
                        $result
                            ->operation('uploadImage')
                            ->setMessage(__(self::LANG_GROUP, 'La imagen no pudo ser subida, intente después.'));
                    }
                }
            }
        } else {
            $result
                ->operation('uploadImage')
                ->setMessage(__(self::LANG_GROUP, 'No se ha subido ninguna imagen.'));
        }

        return $response->withJson($result);
    }

    /**
     * routes
     *
     * @param RouteGroup $group
     * @return RouteGroup
     */
    public static function routes(RouteGroup $group)
    {
        $groupSegmentURL = $group->getGroupSegment();
        $lastIsBar = last_char($groupSegmentURL) == '/';
        $startRoute = $lastIsBar ? '' : '/';
        $classname = BlackboardNewsController::class;
        $routes = [];
        $all_roles = array_keys(UsersModel::TYPES_USERS);
        $edition_permissions = [
            UsersModel::TYPE_USER_ROOT,
            UsersModel::TYPE_USER_ADMIN,
        ];

        //──── GET ─────────────────────────────────────────────────────────────────────────
        $routes[] = new Route(
            "{$startRoute}create[/]",
            $classname . ':addForm',
            'blackboard-news-create-form',
            'GET',
            true,
            null,
            $edition_permissions
        );
        $routes[] = new Route(
            "{$startRoute}edit/{id}[/]",
            $classname . ':editForm',
            'blackboard-news-edit-form',
            'GET',
            true,
            null,
            $edition_permissions
        );
        $routes[] = new Route(
            "{$startRoute}news[/]",
            $classname . ':getNews',
            'blackboard-news-get',
            'GET',
            true,
            null,
            $all_roles
        );
        $routes[] = new Route(
            "{$startRoute}list[/]",
            $classname . ':listView',
            'blackboard-news-list',
            'GET',
            true,
            null,
            $edition_permissions
        );

        //──── POST ─────────────────────────────────────────────────────────────────────────
        $routes[] = new Route(
            "{$startRoute}datatables[/]",
            $classname . ':dataTables',
            'blackboard-datatables',
            'GET',
        );
        $routes[] = new Route(
            "{$startRoute}create[/]",
            $classname . ':registerNew',
            'blackboard-news-create',
            'POST',
            true,
            null,
            $edition_permissions
        );
        $routes[] = new Route(
            "{$startRoute}edit[/]",
            $classname . ':editNew',
            'blackboard-news-edit',
            'POST',
            true,
            null,
            $edition_permissions
        );
        $routes[] = new Route(
            "{$startRoute}delete/{id}[/]",
            $classname . ':deleteNew',
            'blackboard-news-delete',
            'POST',
            true,
            null,
            $edition_permissions
        );
        $routes[] = new Route(
            "{$startRoute}image-handler[/]",
            $classname . ':imageHandler',
            'blackboard-image-handler',
            'POST',
            true,
            null,
            $edition_permissions
        );

        $group->active(BLACKBOARD_NEWS_ENABLED);
        $group->register($routes);

        return $group;
    }
}
