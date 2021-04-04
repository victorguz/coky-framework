<?php

/**
 * ContactsController.php
 */

namespace App\Contacts;

use App\Controller\AdminPanelController;
use App\Model\UsersModel;
use App\Contacts\ContactsMapper;
use PiecesPHP\Core\ConfigHelpers\MailConfig;
use PiecesPHP\Core\HTML\HtmlElement;
use PiecesPHP\Core\Mailer;
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
 * ContactsController.
 *
 * @package     PiecesPHP\BuiltIn\Article\Category\Controllers
 * @author      Victorguz <victorguzber@gmail.com>
 * @copyright   Copyright (c) 2021
 */
class ContactsController extends AdminPanelController
{

    /**
     * $prefixParentEntity
     *
     * @var string
     */
    protected static $prefixParentEntity = 'pages';
    /**
     * $prefixEntity
     *
     * @var string
     */
    protected static $prefixEntity = 'contacts';
    /**
     * $prefixSingularEntity
     *
     * @var string
     */
    protected static $prefixSingularEntity = 'contact';
    /**
     * $title
     *
     * @var string
     */

    protected static $title = 'Contact';
    /**
     * $pluralTitle
     *
     * @var string
     */
    protected static $pluralTitle = 'Contacts';


    /**
     * __construct
     *
     * @return static
     */
    public function __construct()
    {
        parent::__construct(false); //No cargar ningún modelo automáticamente.

        $this->model = (new ContactsMapper())->getModel();
        set_title(self::$title . ' - ' . get_title());
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

        set_title(self::$pluralTitle);



        $process_table = self::routeName('datatables');
        //$back_link = self::routeName();
        $back_link = get_route('admin');
        $add_link = self::routeName('forms-add');

        $data = [];
        $data['process_table'] = $process_table;
        $data['back_link'] = $back_link;
        $data['add_link'] = $add_link;
        $data['has_permissions_add'] = strlen($add_link) > 0;
        $data['title'] = self::$pluralTitle;

        $this->render('panel/layout/header');
        $this->render('panel/pages/contact/list', $data);
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

        set_title("Edit " . self::$pluralTitle);


        $id = $request->getAttribute('id', null);
        $id = !is_null($id) && ctype_digit($id) ? (int) $id : null;

        $element = new ContactsMapper($id);

        if (!is_null($element->id)) {

            $action = self::routeName('actions-edit');
            $back_link = self::routeName('list');


            $data = [];

            $data['action'] = $action;
            $data['element'] = $element;
            $data['back_link'] = $back_link;
            $data['title'] = self::$title;

            $this->render('panel/layout/header');
            $this->render('panel/pages/contact/edit-form', $data);
            $this->render('panel/layout/footer');
        } else {
            throw new NotFoundException($request, $response);
        }
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

        if ($request->isXhr()) {

            $query = $this->model->select();

            $query->execute();

            return $response->withJson($query->result());
        } else {
            throw new NotFoundException($request, $response);
        }
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
                'full_name',
                'plan',
                'residence',
                'created',
            ];

            $result = DataTablesHelper::process([
                'columns_order' => $columns_order,
                'custom_order' => ['id' => 'DESC'],
                'mapper' => new ContactsMapper(),
                'request' => $request,
                'on_set_data' => function ($e) {

                    $mapper = new ContactsMapper($e->id);

                    $markAsReadButton = new HtmlElement('a', ($mapper->status == 1 ? __("general", "Readed") : __("general", "Read")));
                    $markAsReadButton->setAttribute('class', "ui " . ($mapper->status == 1 ? "" : "primary") . " label");
                    $markAsReadButton->setAttribute('href', self::routeName('forms-edit', [
                        'id' => $e->id,
                    ]));

                    if ($markAsReadButton->getAttributes(false)->offsetExists('href')) {
                        $href = $markAsReadButton->getAttributes(false)->offsetGet('href');
                        if (strlen(trim($href->getValue())) < 1) {
                            $markAsReadButton = '';
                        }
                    }

                    return [
                        $mapper->id,
                        $mapper->full_name,
                        "<label class='ui label {$mapper->plan}'>{$mapper->plan}</label>",
                        "<label class='ui label {$mapper->residence}'>{$mapper->residence}</label>",
                        date_format($mapper->created, "Y-m-d H:i:s"),
                        (string)$markAsReadButton
                    ];
                },
            ]);

            return $response->withJson($result->getValues());
        } else {
            throw new NotFoundException($request, $response);
        }
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

        $id = $request->getParsedBodyParam('id', -1);
        $is_edit = $id !== -1;

        if ($is_edit) {
            $valid_params = true;
        } else {
            $full_name = $request->getParsedBodyParam('full_name', null);
            $phone = $request->getParsedBodyParam('phone', null);
            $email = $request->getParsedBodyParam('email', null);
            $address = $request->getParsedBodyParam('address', null);
            $message = $request->getParsedBodyParam('message', null);
            $data = $request->getParsedBodyParam('data', null);
            $privacy_policy = $request->getParsedBodyParam('privacy_policy', false) == "on" ? 1 : 0;
            $send_promo = $request->getParsedBodyParam('send_promo', false) == "on" ? 1 : 0;

            $data = $data ? json_decode($data) : $data;

            $valid_params = !in_array(null, [
                $full_name,
                $phone,
                $email,
                $address,
                $message,
                $privacy_policy
            ]);
        }

        $operation_name = $is_edit ? 'Contacts' : 'Contact us';

        $result = new ResultOperations([
            new Operation($operation_name),
        ], $operation_name);

        $result->setValue('redirect', false);

        $error_parameters_message = 'Los parámetros recibidos son erróneos.';
        $not_exists_message = 'El contacto que intenta modificar no existe';
        $success_create_message = 'Message sent.';
        $success_edit_message = 'Marked as read.';
        $unknow_error_message = 'Ha ocurrido un error desconocido.';

        $redirect_url_on_create = self::routeName('list');

        if ($valid_params) {

            if (!$is_edit) {
                try {
                    $mapper = new ContactsMapper();

                    $mapper->full_name = $full_name;
                    $mapper->phone = $phone;
                    $mapper->email = $email;
                    $mapper->address = $address;
                    $mapper->message = $message;
                    $mapper->data = $data;
                    $mapper->privacy_policy = $privacy_policy;
                    $mapper->send_promo = $send_promo;
                    $mapper->created = date("Y-m-d H:i:s");

                    $saved = $mapper->save();

                    $title = get_config('title_app');

                    $title = vsprintf(__(LANG_GROUP, "Fue contactado desde: <a href='%s'>%s</a>"), [
                        baseurl(),
                        $title,
                    ]);

                    $logo = base_url(get_config('logo'));

                    $bodyMessage = $this->render('mail-templates/generic-contact-form', [
                        'title' => $title,
                        'logo' => $logo,
                        "element" => [
                            "full_name" => $full_name,
                            "phone" => $phone,
                            "email" => $email,
                            "address" => $address,
                            "message" => $message,
                            "privacy_policy" => $privacy_policy,
                            "send_promo" => $send_promo
                        ],
                    ], false);

                    $bodyMessage = utf8_decode($bodyMessage);

                    $mailer = new Mailer();
                    $mailConfig = new MailConfig;

                    $mailer->SMTPDebug = 2;
                    $mailer->isHTML(true);

                    $mailer->setFrom($mailConfig->user());
                    $mailer->addReplyTo($email, $full_name);

                    //Elegir el destinatario del mensaje
                    $recipient = get_config("mail_recipient");
                    $mailer->addAddress($recipient["mail"]);

                    $mailer->Subject = utf8_decode(get_config('title_app') . " " . __(LANG_GROUP, 'Contacto'));
                    $mailer->Body = $bodyMessage;

                    if (!$mailer->checkSettedSMTP()) {
                        $mailer->asGoDaddy();
                    }

                    $success = $mailer->send();

                    if ($saved && $success) {
                        $result->setValue('reload', true);

                        $result->setMessage($success_create_message)
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

                $mapper = new ContactsMapper((int)$id);
                $exists = $mapper->id != null;

                if ($exists) {

                    try {

                        $mapper->modified = date("Y-m-d H:i:s");
                        $mapper->status = 1;

                        $updated = $mapper->update();

                        if ($updated) {
                            $result->setMessage($success_edit_message)
                                ->operation($operation_name)
                                ->setSuccess(true);
                            // $result->setValue('reload', true);

                            $result->setValue('redirect', true);
                            $result->setValue('redirect_to', $redirect_url_on_create);
                        } else {
                            $result->setMessage($unknow_error_message);
                        }
                    } catch (\Exception $e) {
                        $result->setMessage($e->getMessage());
                        log_exception($e);
                    }
                } else {
                    $result->setMessage($not_exists_message);
                }
            }
        } else {
            $result->setMessage($error_parameters_message);
        }
        return $response->withJson($result);
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
            $name = strlen($name) > 0 ? "-{$name}" : '';
        }

        $name = !is_null($name) ? self::$prefixParentEntity . '-' . self::$prefixEntity . $name : self::$prefixParentEntity;

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
        $startRoute = $lastIsBar ? '' : '/';

        $roles_manage_permission = [
            UsersModel::TYPE_USER_ROOT,
            UsersModel::TYPE_USER_ADMIN,
        ];

        $group->active(true);
        $group->register($routes);

        //Rutas
        $group->register(
            self::genericManageRoutes($startRoute, self::$prefixParentEntity, self::class, self::$prefixEntity, $roles_manage_permission, true)
        );

        return $group;
    }
    /**
     * genericManageRoutes
     *
     * @param string $startRoute
     * @param string $namePrefix
     * @param string $handler
     * @param string $uriPrefix
     * @param array $rolesAllowed
     * @return Route[]
     */
    protected static function genericManageRoutes(string $startRoute, string $namePrefix, string $handler, string $uriPrefix, array $rolesAllowed = [], bool $withQuillHandler = false)
    {
        $namePrefix .= '-' . $uriPrefix;
        $startRoute .= $uriPrefix;
        $all_roles = array_keys(UsersModel::TYPES_USERS);

        $routes = [
            new Route(
                "{$startRoute}",
                "{$handler}:all",
                "{$namePrefix}-ajax-all",
                'GET',
            ),
            new Route(
                "{$startRoute}/datatables[/]",
                "{$handler}:dataTables",
                "{$namePrefix}-datatables",
                'GET',
            ),
            new Route(
                "{$startRoute}/list[/]",
                "{$handler}:listView",
                "{$namePrefix}-list",
                'GET',
                true,
                null,
                $rolesAllowed
            ),
            new Route(
                "{$startRoute}/forms/add[/]",
                "{$handler}:addForm",
                "{$namePrefix}-forms-add",
                'GET',
                true,
                null,
                $rolesAllowed
            ),
            new Route(
                "{$startRoute}/action/add[/]",
                "{$handler}:action",
                "{$namePrefix}-actions-add",
                'POST',
                // false,
                // null,
                // $rolesAllowed
            ),
            new Route(
                "{$startRoute}/forms/edit/{id}[/]",
                "{$handler}:editForm",
                "{$namePrefix}-forms-edit",
                'GET',
                true,
                null,
                $rolesAllowed
            ),
            new Route(
                "{$startRoute}/action/edit[/]",
                "{$handler}:action",
                "{$namePrefix}-actions-edit",
                'POST',
                true,
                null,
                $rolesAllowed
            ),
        ];

        return $routes;
    }
}