<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\ProcessoPagamentoController;

/**
 * @var RouteCollection $routes
 */



$routes->post('exams/processTransaction', 'ProcessoPagamentoController::index');
$routes->post('processopagamento', 'ProcessoPagamentoController::index');
