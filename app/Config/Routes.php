<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('pagamentos', function($routes){
    $routes->get('processar'. 'ProcessoPagamentoController::index');
    
    

});
