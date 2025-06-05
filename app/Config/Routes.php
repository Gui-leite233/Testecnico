<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\ProcessoPagamentoController;

/**
 * @var RouteCollection $routes
 */



$routes->group('pagamentos', function($routes){
    $routes->get('processar', 'ProcessoPagamentoController::index');
});

