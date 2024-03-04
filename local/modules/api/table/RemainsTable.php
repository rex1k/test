<?php

namespace ApiModule\Table;

use Bitrix\Main\Entity\Datamanager;

class RemainsTable extends Datamanager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'remains';
    }

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            'ID' => [
                'primary' => true,
                'autocomplete' => true,
            ],
            'STORAGE_UUID' => [
                'type' => 'string',
                'required' => true,
            ],
            'QUANTITY' => [
                'type' => 'int',
                'required' => true,
            ],
            'PRODUCT_UUID' => [
                'type' => 'string',
                'required' => true,
            ],
        ];
    }
}