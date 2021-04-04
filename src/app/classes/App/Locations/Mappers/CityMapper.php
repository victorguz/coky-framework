<?php

/**
 * CityMapper.php
 */

namespace App\Locations\Mappers;

use PiecesPHP\Core\BaseEntityMapper;
use PiecesPHP\Core\Database\ActiveRecordModel;

/**
 * CityMapper.
 *
 * Mapper de estados
 *
 * @package     App\Locations\Mappers
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2019
 * @property int $id
 * @property string|null $code
 * @property int|StateMapper $state
 * @property string $name
 * @property int $active
 */
class CityMapper extends BaseEntityMapper
{
    const PREFIX_TABLE = 'locations_';
    const TABLE = 'cities';

    const ACTIVE = 1;
    const INACTIVE = 0;

    const STATUS = [
        self::ACTIVE => 'Activa',
        self::INACTIVE => 'Inactiva',
    ];

    protected $fields = [
        'id' => [
            'type' => 'int',
            'primary_key' => true,
        ],
        'code' => [
            'type' => 'varchar',
            'null' => true,
        ],
        'state' => [
            'type' => 'int',
            'reference_table' => StateMapper::PREFIX_TABLE . StateMapper::TABLE,
            'reference_field' => 'id',
            'reference_primary_key' => 'id',
            'human_readable_reference_field' => 'name',
            'mapper' => StateMapper::class,
        ],
        'name' => [
            'type' => 'varchar',
        ],
        'active' => [
            'type' => 'int',
            'default' => self::ACTIVE,
        ],
    ];

    /**
     * $table
     *
     * @var string
     */
    protected $table = self::PREFIX_TABLE . self::TABLE;

    /**
     * __construct
     *
     * @param int $value
     * @param string $field_compare
     * @return static
     */
    public function __construct(int $value = null, string $field_compare = 'id')
    {
        parent::__construct($value, $field_compare);
    }

    /**
     * getBy
     *
     * @param mixed $value
     * @param string $column
     * @param boolean $as_mapper
     * @return static|object|null
     */
    public static function getBy($value, string $column = 'id', bool $as_mapper = false)
    {
        $model = self::model();

        $where = [
            $column => $value,
        ];

        $model->select()->where($where);

        $model->execute();

        $result = $model->result();

        $result = count($result) > 0 ? $result[0] : null;

        if (!is_null($result) && $as_mapper) {
            $result = new static($result->id);
        }

        return $result;
    }

    /**
     * getByState
     *
     * @param int $state_id
     * @param bool $as_mapper
     * @return array|static[]
     */
    public static function getByState(int $state_id, bool $as_mapper = false)
    {

        $query = self::model()->select();

        $query->where([
            'state' => $state_id,
        ]);

        $query->execute();

        $result = $query->result();

        if ($as_mapper) {
            $result = array_map(function ($i) {
                return new static($i->id);
            }, $result);
        }

        return $result;
    }

    /**
     * getByName
     *
     * @param string $name
     * @param bool $as_mapper
     * @param int $state_id
     * @return array|static[]
     */
    public static function getByName(string $name, bool $as_mapper = false, int $state_id = null)
    {

        $query = self::model()->select();

        $where = [];

        $where['name'] = trim($name);

        if ($state_id !== null) {
            $where['state'] = $state_id;
        }

        $query->where($where);

        $query->execute();

        $result = $query->result();

        if ($as_mapper) {
            $result = array_map(function ($i) {
                return new static($i->id);
            }, $result);
        }

        return $result;
    }

    /**
     * isDuplicateName
     *
     * @param string $name
     * @param int $state_id
     * @param int $ignore_id
     * @return bool
     */
    public static function isDuplicateName(string $name, int $state_id, int $ignore_id)
    {
        $model = self::model();
        $name = escapeString($name);

        $where = trim(implode(' ', [
            "name = '$name' AND ",
            "state = $state_id AND ",
            "id != $ignore_id",
        ]));

        $model->select()->where($where)->execute();

        $result = $model->result();

        return count($result) > 0;
    }

    /**
     * isDuplicateCode
     *
     * @param string $code
     * @param int $state_id
     * @param int $ignore_id
     * @return bool
     */
    public static function isDuplicateCode(string $code = null, int $state_id, int $ignore_id)
    {

        if ($code !== null) {

            $model = self::model();
            $code = escapeString($code);

            $where = trim(implode(' ', [
                "code = '$code' AND ",
                "state = $state_id AND ",
                "id != $ignore_id",
            ]));

            $model->select()->where($where)->execute();

            $result = $model->result();

            return count($result) > 0;

        } else {

            return false;

        }

    }

    /**
     * model
     *
     * @return ActiveRecordModel
     */
    public static function model()
    {
        return (new static )->getModel();
    }

}
