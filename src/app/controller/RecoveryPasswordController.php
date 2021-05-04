<?php

/**
 * RecoveryPasswordController.php
 */

namespace App\Controller;

use App\Model\RecoveryPasswordModel;
use App\Model\TicketsLogModel;
use App\Model\UsersModel;
use PiecesPHP\Core\BaseToken;
use PiecesPHP\Core\ConfigHelpers\MailConfig;
use PiecesPHP\Core\Mailer;
use PiecesPHP\Core\StringManipulate;
use PiecesPHP\Core\Utilities\ReturnTypes\Operation;
use PiecesPHP\Core\Utilities\ReturnTypes\ResultOperations;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

/**
 * RecoveryPasswordController.
 *
 * Controlador de recuperacón de contraseña
 *
 * @package     PiecesPHP\Core
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 */
class RecoveryPasswordController extends UsersController
{
    const LANG_GROUP = 'revoveryPasswordModule';

    /**
     * $userMapper
     *
     * @var UsersModel
     */
    protected $userMapper = null;

    /** @ignore */
    public function __construct()
    {
        parent::__construct();
        $this->userMapper = new UsersModel();
    }

    /**
     * recoveryPasswordForm
     *
     * No espera parámetros.
     *
     * @param Request $request Petición
     * @param Request $response Respuesta
     * @param array $args Argumentos pasados por GET
     * @return void
     */
    public function recoveryPasswordForm(Request $request, Response $response, array $args)
    {

        $lang_title = __(self::LANG_GROUP, 'Recuperación de contraseña');

        set_title($lang_title);

        $lang_title = __(self::LANG_GROUP, 'Recuperación de contraseña');
        $lang_title = __(self::LANG_GROUP, 'Recuperación de contraseña');
        $lang_title = __(self::LANG_GROUP, 'Recuperación de contraseña');

        $send_email_action = get_route("user-recovery-password-request");


        $data["lang_title"] = $lang_title;
        $data["send_email_action"] = $send_email_action;

        // set_custom_assets([
        //     base_url('statics/login-and-recovery/css/problems-form.css'),
        // ], 'css');

        // set_custom_assets([
        //     baseurl('statics/login-and-recovery/js/recovery-password.js'),
        // ], 'js');

        set_custom_assets([
            baseurl(ADMIN_MODULES_CSS . '/login-and-recovery/login.css'),
        ], 'css');

        set_custom_assets([
            baseurl(ADMIN_MODULES_JS . '/login-and-recovery/login.js'),
        ], 'js');

        $this->render('usuarios/problems/password', $data);

        return $response;
    }

    /**
     * Envía un correo para recuperar la contraseña.
     *
     * Este método espera recibir por POST: [username|email]
     *
     * @param Request $request Petición
     * @param Request $response Respuesta
     * @param array $args Argumentos pasados por GET
     * @return void
     */
    public function recoveryPasswordRequest(Request $request, Response $response, array $args)
    {

        //Parámetros
        $email = $request->getParsedBodyParam("email", null);


        //Verificar que el grupo de datos para solicitados esté completo
        $valid_params = !in_array(null, [
            $email,
        ]);

        $operation_name = __(self::LANG_GROUP, 'Recuperación de contraseña');

        $result = new ResultOperations([
            new Operation($operation_name),
        ], $operation_name);

        $result->setValue('redirect', false);

        $error_parameters_message = 'Los parámetros recibidos son erróneos.';
        $not_exists_message = 'El contacto que intenta modificar no existe';
        $success_create_message = 'Message sent.';
        $success_edit_message = 'Marked as read.';
        $unknow_error_message = 'Ha ocurrido un error desconocido.';

        $redirect_url_on_create = get_route('user-recovery-password-request-code');

        //Cuerpo de la respuesta
        $json_response = [
            'send_mail' => false,
            'error' => self::NO_ERROR,
            'message' => '',
        ];

        //Si los parámetros son válidos en nombre y en cantidad se inicia el proceso de recuperación
        if ($valid_params) {

            //Se selecciona un elemento que concuerde con el usuario
            $usuario = (new UsersModel())->getByEmail($email);

            //Verificación de existencia
            if ($usuario->id !== null) {

                //Datos del toke de recuperación
                $created_at = time(); //Fecha de creación del token
                $expire_at = $created_at + ((60 * 60) * 24); //Fecha de expiración del token
                $token = BaseToken::setToken($usuario->email, null, $created_at, $expire_at); //Token

                //Codificación de datos de la url de recuperación
                $toke_url = $this->encodeURL([
                    'action' => TokenController::TOKEN_PASSWORD_RECOVERY,
                    'token' => $token,
                ]);

                //Inserción de token en la base de datos
                $this->token_controller->newToken($toke_url, TokenController::TOKEN_PASSWORD_RECOVERY);

                //Url de recuperación
                $toke_url = baseurl($this->url_recovery . $toke_url);

                //Envío de correo de recuperación
                $json_response['send_mail'] = $this->mailRecoveryPassword($toke_url, $usuario);
                $json_response['message'] = __(self::LANG_GROUP, 'Se ha enviado un mensaje al correo proporcionado.');
            } else {

                $json_response['error'] = self::USER_NO_EXISTS;
                $json_response['message'] = vsprintf($this->getMessage($json_response['error']), [$email]);
            }
        } else {

            $json_response['error'] = self::MISSING_OR_UNEXPECTED_PARAMS;
            $json_response['message'] = $this->getMessage($json_response['error']);
        }

        return $response->withJson($json_response);
    }

    /**
     * Envía un correo para recuperar la contraseña.
     *
     * Este método espera recibir por POST: [username]
     *
     * @param Request $request Petición
     * @param Request $response Respuesta
     * @param array $args Argumentos pasados por GET
     * @return void
     */
    public function recoveryPasswordRequestCode(Request $request, Response $response, array $args)
    {

        //Parámetros
        $params = $request->getParsedBody();

        //Conjunto posible de datos para autenticación
        $requerido = [
            'username',
        ];

        //Verificar que el grupo de datos para solicitados esté completo
        $parametros_ok = require_keys($requerido, $params) === true && count($requerido) === count($params);

        //Cuerpo de la respuesta
        $json_response = [
            'send_mail' => false,
            'error' => self::NO_ERROR,
            'message' => '',
        ];

        //Si los parámetros son válidos en nombre y en cantidad se inicia el proceso de recuperación
        if ($parametros_ok) {

            //Se selecciona un elemento que concuerde con el usuario
            $usuario = null;

            $username = $params['username'];

            $usuario = $this->userMapper->getWhere([
                'username' => [
                    '=' => $username,
                    'and_or' => 'OR',
                ],
                'email' => [
                    '=' => $username,
                ],
            ]);

            //Verificación de existencia
            if ($usuario !== -1) {

                //Datos de recuperación
                $recoveryPassword = new RecoveryPasswordModel();
                $recoveryPassword->created = date('Y-m-d h:i:s');
                $recoveryPassword->expired = $recoveryPassword->created->modify('+24 hour');
                $recoveryPassword->email = $usuario->email;
                $recoveryPassword->code = generate_code(6);
                $recoveryPassword->save();

                //Envío de correo de recuperación
                $json_response['send_mail'] = $this->mailRecoveryPasswordCode($recoveryPassword->code, $usuario);
                $json_response['message'] = __(self::LANG_GROUP, 'Se ha enviado un mensaje al correo proporcionado.');

                $logRequest = new TicketsLogModel();
                $logRequest->created = $recoveryPassword->created;
                $logRequest->email = $recoveryPassword->email;
                $logRequest->information = [
                    'code' => $recoveryPassword->code,
                    'email_sended' => $json_response['send_mail'],
                    'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
                ];
                $logRequest->type = __(self::LANG_GROUP, 'Solicitud de restablecimiento de contraseña.');
                $logRequest->save();
            } else {

                $json_response['error'] = self::USER_NO_EXISTS;
                $json_response['message'] = vsprintf($this->getMessage($json_response['error']), [$username]);
            }
        } else {

            $json_response['error'] = self::MISSING_OR_UNEXPECTED_PARAMS;
            $json_response['message'] = $this->getMessage($json_response['error']);
        }

        return $response->withJson($json_response);
    }

    /**
     * Envía un correo con la nueva contraseña.
     *
     * @param Request $request Petición
     * @param Request $response Respuesta
     * @param array $args Argumentos pasados por GET. Espera $args['url_token]
     * @return void
     */
    public function newPasswordCreate(Request $request, Response $response, array $args)
    {
        //Cuerpo de respuesta
        $json_response = [
            'send_mail' => false,
            'password_changed' => false,
            'error' => self::NO_ERROR,
            'message' => '',
        ];

        //Conjunto posible de datos para autenticación
        $requerido = [
            'url_token',
        ];

        //Verificar que el grupo de datos para autenticación sea válido
        $parametros_ok = require_keys($requerido, $args) === true && count($requerido) === count($args);

        if ($parametros_ok) {
            //Verificar si el token existe
            $token_exists = $this->token_controller->tokenExists($args['url_token']);

            if ($token_exists) {

                //Decodifar url
                $data_url = $this->decodeURL($args['url_token']);

                //Obtener los valores esperados
                $action = $data_url->action;
                $token = $data_url->token;

                //Verificar si la acción es la esperada
                if ($action == TokenController::TOKEN_PASSWORD_RECOVERY) {

                    //Eliminar token de la base de datos
                    $this->token_controller->deleteToken($args['url_token']);

                    //Verificar expiración de url
                    if (!BaseToken::isExpire($token)) {

                        //Generar contraseña
                        $pass = StringManipulate::generatePass(10);
                        $new_pass = $pass['password'];
                        $encrypt_pass = $pass['encrypt'];

                        //Obtener el email desde el token
                        $mail = BaseToken::getData($token);

                        //Actualizar contraseña
                        $updated = $this->userMapper->changePassword($mail, $encrypt_pass);
                        $usuario = $this->userMapper->getByEmail($mail);

                        $json_response['updated'] = $updated;

                        //Verificar si la contraseña fue actualizada
                        if ($updated) {

                            $json_response['password_changed'] = true;

                            //Enviar contraseña por correo
                            $json_response['send_mail'] = $this->mailNewPassword($new_pass, $usuario);
                        }
                    } else {
                        $json_response['error'] = self::TOKEN_EXPIRED;
                        $json_response['message'] = $this->getMessage($json_response['error']);
                    }
                } else {
                    $json_response['error'] = self::UNEXPECTED_ACTION;
                    $json_response['message'] = $this->getMessage($json_response['error']);
                }
            } else {

                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'text/html')
                    ->write("<h1>" . __(self::LANG_GROUP, 'El recurso solicitado no existe.') . "</h1>");
            }
        } else {
            $json_response['error'] = self::MISSING_OR_UNEXPECTED_PARAMS;
            $json_response['message'] = $this->getMessage($json_response['error']);
        }
        if ($request->isXhr()) {
            return $response->withJson($json_response);
        } else {
            return $response->withRedirect(get_route('users-login-form'));
        }
    }

    /**
     * Envía un correo con la nueva contraseña con código.
     *
     * @param Request $request Petición
     * @param Request $response Respuesta
     * @param array $args Argumentos pasados por GET. Espera $args['code']
     * @return void
     */
    public function newPasswordCreateCode(Request $request, Response $response, array $args)
    {
        //Cuerpo de respuesta
        $json_response = [
            'success' => false,
            'error' => self::NO_ERROR,
            'message' => '',
        ];

        //Conjunto posible de datos para autenticación
        $requerido = [
            'code',
            'password',
            'repassword',
        ];

        $args = $request->getParsedBody();

        //Verificar que el grupo de datos para autenticación sea válido
        $parametros_ok = require_keys($requerido, $args) === true && count($requerido) === count($args);

        if ($parametros_ok) {

            $code = trim($args['code']);
            $password = trim($args['password']);
            $repassword = trim($args['repassword']);

            //Verificar que las contraseñas coincidan
            if ($password == $repassword) {

                //Verificar si existe
                $exist = RecoveryPasswordModel::exist($code);

                if ($exist) {

                    $recoveryPassword = RecoveryPasswordModel::instanceByCode($code);

                    $user = new UsersModel();
                    $user = $user->getByEmail($recoveryPassword->email);

                    $exist_user = $user !== -1;

                    //Verificar que el usuario existe
                    if ($exist_user) {

                        $now = new \DateTime();
                        $expired = $recoveryPassword->expired <= $now;

                        //Verificar expiración
                        if (!$expired) {

                            //Actualizar contraseña
                            $updated = $this->userMapper->changePassword($recoveryPassword->email, password_hash($password, \PASSWORD_DEFAULT));

                            $json_response['updated'] = $updated;

                            //Verificar si la contraseña fue actualizada
                            if ($updated) {
                                $json_response['success'] = true;
                                $json_response['message'] = __(self::LANG_GROUP, 'Contraseña cambiada.');
                                $recoveryPassword->getModel()->delete("id = '$recoveryPassword->id'")->execute();
                            }
                        } else {
                            $json_response['error'] = self::EXPIRED_OR_NOT_EXIST_CODE;
                            $json_response['message'] = $this->getMessage($json_response['error']);
                        }
                    } else {
                        $json_response['error'] = self::USER_NO_EXISTS;
                        $json_response['message'] = $this->getMessage($json_response['error']);
                    }
                } else {

                    $json_response['error'] = self::EXPIRED_OR_NOT_EXIST_CODE;
                    $json_response['message'] = $this->getMessage($json_response['error']);
                }
            } else {
                $json_response['error'] = self::NOT_MATCH_PASSWORDS;
                $json_response['message'] = $this->getMessage($json_response['error']);
            }
        } else {
            $json_response['error'] = self::MISSING_OR_UNEXPECTED_PARAMS;
            $json_response['message'] = $this->getMessage($json_response['error']);
        }

        return $response->withJson($json_response);
    }

    /**
     * Verifica la existencia del código
     *
     * @param Request $request Petición
     * @param Request $response Respuesta
     * @param array $args
     * @return void
     */
    public function verifyCode(Request $request, Response $response, array $args)
    {
        //Cuerpo de respuesta
        $json_response = [
            'success' => false,
            'error' => self::NO_ERROR,
            'message' => '',
        ];

        //Conjunto posible de dato
        $requerido = [
            'code',
        ];

        $args = $request->getParsedBody();

        //Verificar que el grupo de datos sea válido
        $parametros_ok = require_keys($requerido, $args) === true && count($requerido) === count($args);

        if ($parametros_ok) {

            $code = trim($args['code']);

            //Verificar si existe
            $exist = RecoveryPasswordModel::exist($code);

            if ($exist) {

                $json_response['success'] = true;
            } else {

                $json_response['error'] = self::EXPIRED_OR_NOT_EXIST_CODE;
                $json_response['message'] = $this->getMessage($json_response['error']);
            }
        } else {
            $json_response['error'] = self::MISSING_OR_UNEXPECTED_PARAMS;
            $json_response['message'] = $this->getMessage($json_response['error']);
        }

        return $response->withJson($json_response);
    }

    /**
     * Envía un correo de recuperación de contraseña.
     *
     * @param string $url
     * @param stdClass $usuario
     *
     * @return bool true si se envió, false si no
     */
    private function mailRecoveryPassword(string $url, \stdClass $usuario)
    {

        $mail = new Mailer();
        $mailConfig = new MailConfig;

        $to = $usuario->email;

        $to_name = $usuario->username;

        $subject = __(self::LANG_GROUP, 'Recuperación de contraseña');

        $message = $this->render('usuarios/mail/recovery_password', ['url' => $url], false);

        $mail->setFrom($mailConfig->user(), $mailConfig->name());
        $mail->addAddress($to, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        if (!$mail->checkSettedSMTP()) {
            $mail->asGoDaddy();
        }

        return $mail->send();
    }

    /**
     * Envía un correo de recuperación de contraseña.
     *
     * @param string $code
     * @param stdClass $usuario
     *
     * @return bool true si se envió, false si no
     */
    private function mailRecoveryPasswordCode(string $code, \stdClass $usuario)
    {
        $mail = new Mailer();
        $mailConfig = new MailConfig;

        $to = $usuario->email;

        $to_name = $usuario->username;

        $subject = __(self::LANG_GROUP, 'Recuperación de contraseña');

        $message = $this->render('usuarios/mail/recovery_password_code', [
            'code' => $code,
            'url' => get_route('recovery-form') . "?code=$code",
        ], false);

        $mail->setFrom($mailConfig->user(), $mailConfig->name());
        $mail->addAddress($to, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        if (!$mail->checkSettedSMTP()) {
            $mail->asGoDaddy();
        }

        return $mail->send();
    }

    /**
     * Envía un correo de nueva contraseña.
     *
     * @param string $password
     * @param stdClass $usuario
     *
     * @return bool true si se envió, false si no
     */
    private function mailNewPassword(string $password, \stdClass $usuario)
    {
        $mail = new Mailer();
        $mailConfig = new MailConfig;

        $to = $usuario->email;

        $to_name = $usuario->username;

        $subject = __(self::LANG_GROUP, 'Contraseña nueva');

        $message = $this->render('usuarios/mail/restored_password', ['password' => $password], false);

        $mail->setFrom($mailConfig->user(), $mailConfig->name());
        $mail->addAddress($to, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        if (!$mail->checkSettedSMTP()) {
            $mail->asGoDaddy();
        }

        return $mail->send();
    }
}
