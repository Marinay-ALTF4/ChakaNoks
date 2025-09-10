<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 🔹 Authentication & Admin routes
$routes->get('/', 'Admin::login');        // homepage → login form
$routes->get('login', 'Admin::login');    // /login → login form
$routes->post('loginAuth', 'Admin::loginAuth'); // login POST

// Admin dashboard
$routes->get('Central_AD', 'Admin::dashboard', ['filter' => 'auth']);
$routes->get('Central_AD/other-branches', 'Admin::otherBranches', ['filter' => 'auth']);
$routes->get('Central_AD/request_stock', 'Admin::request_stock');







// 🔹 Admin Inventory Management
$routes->group('admin/inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AdminInventory::dashboard');          // /admin/inventory
    $routes->get('add', 'AdminInventory::addStock');         // /admin/inventory/add
    $routes->post('add', 'AdminInventory::saveStock');       // handle form submit
    $routes->get('edit/(:num)', 'AdminInventory::editStock/$1');   // /admin/inventory/edit/5
    $routes->post('update/(:num)', 'AdminInventory::updateStock/$1'); 
    $routes->get('delete/(:num)', 'AdminInventory::deleteStock/$1'); 
    $routes->get('alerts', 'AdminInventory::alerts');        // stock alerts
    $routes->get('branches', 'AdminInventory::branchStocks'); // inventory per branch
});

// 🔹 Staff Inventory routes
$routes->group('inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Inventory::dashboard');
    $routes->get('add-stock', 'Inventory::addStock');
    $routes->get('edit-stock', 'Inventory::editStock');
    $routes->get('stock-list', 'Inventory::stockList');
    $routes->get('alerts', 'Inventory::alerts');
});

// Auth & Misc
$routes->get('logout', 'Admin::logout');
$routes->get('forgot-password', 'Admin::forgotPassword');
$routes->post('forgot-password', 'Admin::forgotPasswordSubmit');
