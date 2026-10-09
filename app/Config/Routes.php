<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->options('api/post', 'Api\PostController::options');
$routes->options('api/post/(:any)', 'Api\PostController::options');
$routes->resource('api/post', ['controller' => 'Api\PostController']);