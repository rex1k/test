<?php

use ApiModule\Controllers\RemainsController;
use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->post('/api/remains/update', [RemainsController::class, 'updateRemains']);
};