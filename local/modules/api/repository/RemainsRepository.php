<?php

namespace ApiModule\Repository;

use ApiModule\Table\RemainsTable;

class RemainsRepository
{
    /**
     * @param array $filter
     * @return array
     */
    public function getRemainsByFilter(array $filter): array
    {
        $result = [];
        $remains = RemainsTable::getList(
            [
                'filter' => [
                    'LOGIC' => 'AND',
                    [
                        'STORAGE_UUID' => $filter['storages'],
                        'PRODUCT_UUID' => $filter['products'],
                    ]
                ],
                'select' => ['ID', 'STORAGE_UUID', 'PRODUCT_UUID']
            ]
        );

        foreach ($remains as $productRemains) {
            $result[$productRemains['PRODUCT_UUID'] . '_' . $productRemains['STORAGE_UUID']] = $productRemains['ID'];
        }

        return $result;
    }

    /**
     * @param array $remains
     * @param array $current
     * @return array
     */
    public function updateRemains(array $remains, array $current): array
    {
        $errors = [];
        $defaultStorageId = $this->getDefaultStorage();

        foreach ($remains['stocks'] as $productRemains) {
            $updateData = [
                'STORAGE_UUID' => $remains['uuid'],
                'PRODUCT_UUID' => $productRemains['uuid'],
                'QUANTITY'     => $productRemains['quantity'],
                'ID'           => $defaultStorageId,
            ];

            if (!empty($current[$productRemains['product_uuid'] . '_' . $remains['uuid']])) {
                $updateData['ID'] = $defaultStorageId;
            }

            $updateResult = RemainsTable::update($updateData['ID'], $updateData);

            if (!$updateResult->isSuccess()) {
                $errors[] = $updateResult->getErrorMessages();
            }
        }

        return $errors;
    }

    /**
     * @return int
     */
    public function getDefaultStorage(): int
    {
        return (int)RemainsTable::getRow(['filter' => ['STORAGE_UUID' => 0]])['ID'];
    }
}