<?php

namespace ApiModule\Controllers;

use ApiModule\Repository\RemainsRepository;
use Bitrix\Main\Engine\ActionFilter\Authorization;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Request;

class RemainsController extends Controller
{
    /**
     * @return \array[][]
     */
    public function configureActions(): array
    {
        return [
            'updateRemains' => [
                'prefilters' => [
                    new HttpMethod(HttpMethod::METHOD_POST),
                    new Authorization(false),
                ]
            ]
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function updateRemainsAction(Request $request): array
    {
        $repository = new RemainsRepository();
        $storages = array_column($request->getPostList()->toArray(), 'uuid');
        $products = [];

        foreach ($request->getPostList()->toArray() as $remains) {
            $products = array_merge($products, array_column($remains['stocks'], 'uuid'));
        }

        $currentRemains = $repository->getRemainsByFilter(['products' => $products, 'storages' => $storages]);

        foreach ($request->getPostList()->toArray() as $remains) {
            $repository->updateRemains($remains, $currentRemains);
        }

        return [];
    }
}