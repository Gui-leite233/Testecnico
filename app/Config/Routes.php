<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\ProcessoPagamentoController;

/**
 * @var RouteCollection $routes
 */



$routes->post('exams/processTransaction', 'ProcessoPagamentoController::processTransaction');
