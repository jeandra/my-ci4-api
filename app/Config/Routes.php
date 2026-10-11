<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->options('api/auth/register', 'Api\AuthController::options');
$routes->options('api/auth/login', 'Api\AuthController::options');
$routes->post('api/auth/register', 'Api\AuthController::register');
$routes->post('api/auth/login', 'Api\AuthController::login');
$routes->options('api/post', 'Api\PostController::options');
$routes->options('api/post/(:any)', 'Api\PostController::options');
$routes->resource('api/post', ['controller' => 'Api\PostController']);
$routes->options('api/posts', 'Api\PostController::options');
$routes->options('api/posts/(:any)', 'Api\PostController::options');
$routes->resource('api/posts', ['controller' => 'Api\PostController']);