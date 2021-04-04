<?php

/**
 * ContactsMapper.php
 */

namespace App\Contacts;

use PiecesPHP\Core\BaseEntityMapper;

/**
 * ContactsMapper.
 *
 * 
 * @author    Victorguz
 * @copyright   Copyright (c) 2021
 * @property int $id

 * @property int|ContactsMapper $category
 * @property string $title
 * @property string $friendly_url
 * @property string $content
 * @property array|object|null $meta
 * @property string|\DateTime|null $start_date
 * @property string|\DateTime|null $end_date
 * @property string|\DateTime $created
 * @property string|\DateTime $updated
 */
class ContactsMapper extends BaseEntityMapper
{
    const TABLE = 'coky_contacts';

    /**
     * @var string $table
     */
    protected $table = self::TABLE;

    protected $fields = [
        'id' => [
            'type' => 'int',
            'primary_key' => true,
        ],
        'full_name' => [
            'type' => 'varchar',
            'length' => 50,
            'null' => false
        ],
        'phone' => [
            'type' => 'varchar',
            'length' => 50,
            'null' => false
        ],
        'email' => [
            'type' => 'varchar',
            'length' => 50,
            'null' => false
        ],
        'address' => [
            'type' => 'varchar',
            'length' => 150,
            'null' => false
        ],
        'message' => [
            'type' => 'TEXT',
            'null' => false
        ],
        'data' => [
            'type' => 'json',
            'null' => true
        ],
        'privacy_policy' => [
            'type' => 'int',
            'null' => false
        ],
        'send_promo' => [
            'type' => 'int',
            'null' => false
        ],
        'status' => [
            'type' => 'int',
            'default' => 0
        ],
        'created' => [
            'type' => 'datetime',
            'null' => true
        ],
        'modified' => [
            'type' => 'datetime',
            'null' => true
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
     * model
     *
     * @return BaseModel
     */
    public static function model()
    {
        return (new static())->getModel();
    }
}