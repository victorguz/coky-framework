<?php

/**
 * TokenModel.php
 */

namespace App\Model;

use PiecesPHP\Core\BaseEntityMapper;

/**
 * TokenModel.
 * 
 * Controlador de Tokens.
 * 
 * @package     PiecesPHP\Core
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 */
class TokenModel extends BaseEntityMapper
{

    protected $table = 'pcsphp_tokens';

    protected $fields = [
        'id' => [
            'type' => 'int',
            'primary_key' => true,
        ],
        'token' => [
            'type' => 'text',
        ],
        'type' => [
            'type' => 'varchar',
            'length' => 255,

        ],
    ];

    /**
     * __construct
     *
     * @param int $value
     * @param string $field_compare
     * @return static
     */
    public function __construct(int $value = null, string $field_compare = 'primary_key')
    {
        parent::__construct($value, $field_compare);
    }

    /**
     * @inheritDoc
     */
    public function save()
    {

        return parent::save();
    }

    /**
     * @inheritDoc
     */
    public function update()
    {
        return parent::update();
    }

    /**
     * all
     *
     * @param bool $as_mapper
     *
     * @return static[]|array
     */
    public static function all(bool $as_mapper = false, int $page = null, int $perPage = null)
    {
        $model = self::model();

        $model->select()->execute(false, $page, $perPage);

        $result = $model->result();

        return $result;
    }
    /**
     * allBy
     *
     * @param bool $as_mapper
     *
     * @return static
     */
    public static function allBy(string $column, string $value, int $page = null, int $perPage = null)
    {
        $model = self::model();

        $model->select()->where("$column = $value")->execute(false, $page, $perPage);

        $result = $model->result();

        if (is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $result[$key] = new static($result[$key]->id);
            }
        }

        return $result;
    }
    /**
     * oneBy
     *
     * @param bool $as_mapper
     *
     * @return static
     */
    public static function oneBy(string $column, string $value, int $page = null, int $perPage = null)
    {
        $model = self::model();

        $model->select()->where("$column = $value")->execute(false, $page, $perPage);

        $result = $model->result();

        if (is_array($result) && count($result) > 0) {
            $result = new static($result[0]->id);
        } else {
            $result = new static();
        }

        return $result;
    }

    /**
     * model
     *
     * @return BaseModel
     */
    public static function model()
    {
        return (new static())->getModel();
    }
}
