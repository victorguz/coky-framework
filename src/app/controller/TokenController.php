<?php

/**
 * TokenController.php
 */

namespace App\Controller;

use App\Model\TokenModel;
use PiecesPHP\Core\BaseController;

/**
 * TokenController.
 *
 * TokenController.
 *
 * @package     PiecesPHP\Core
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 */
class TokenController extends BaseController
{
    /** @ignore */
    public function __construct()
    {
        parent::__construct();
    }

    public function newToken($token, $type)
    {
        $mapper = new TokenModel();
        $mapper->token = $token;
        $mapper->type = $type;

        return $mapper->save();
    }

    public function tokenExists($token)
    {
        $result = TokenModel::oneBy("token", $token);

        return ($result->id !== null);
    }

    public function tokenExistsByType($code)
    {
        $result = TokenModel::allBy("type", $code);

        return (is_array($result) && count($result) > 0);
    }

    public function deleteToken($token)
    {
        // TokenModel::
        // $this->model
        // ->delete("token = '" . $token . "'")
        // ->execute();
        return false;
    }

    const TOKEN_DELETED = 'TOKEN_DELETED';
    const TOKEN_PASSWORD_RECOVERY = 'TOKEN_PASSWORD_RECOVERY';
    const TOKEN_PASSWORD_RECOVERY_CODE = 'TOKEN_PASSWORD_RECOVERY_CODE';
    const TOKEN_GENERIC_CONTROLLER = 'TOKEN_GENERIC_CONTROLLER';
}
