<?php

/**
 * HelperController.php
 */

namespace App\Presentations\Controllers;

use PiecesPHP\Core\BaseController;

/**
 * HelperController.
 *
 * @package     App\Presentations\Controllers
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2020
 */
class HelperController extends BaseController
{

    /**
     *
     * @var \stdClass Usuario logueado
     */
    protected $user = null;

    /**
     * @param \stdClass $user Usuario logueado
     * @param array $globalVariables
     */
    public function __construct(\stdClass $user, array $globalVariables)
    {
        set_config('lock_assets', true);
        parent::__construct(false);
        $this->user = $user;
        $this->setVariables($globalVariables);
        set_config('lock_assets', false);
    }

}
