<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Admin::login');
$routes->post('/loginAuth', 'Admin::loginAuth');
$routes->get('/Central_AD', 'Admin::dashboard');
$routes->get('/logout', 'Admin::logout');
$routes->get('/forgot-password', 'Admin::forgotPassword');
$routes->post('/forgot-password', 'Admin::forgotPasswordSubmit');




