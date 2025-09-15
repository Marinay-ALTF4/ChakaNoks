<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 🔹 Authentication & Admin routes
$routes->get('/', 'Admin::login');                 // Homepage → login form
$routes->get('login', 'Admin::login');             // /login → login form
$routes->post('loginAuth', 'Admin::loginAuth');    // Login POST

// 🔹 Admin Dashboard & Features
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('Central_AD', 'Admin::dashboard');                     
    $routes->get('Central_AD/other-branches', 'Admin::otherBranches');  
    $routes->get('Central_AD/request_stock', 'Admin::request_stock');   

    // 🔹 Fix: add Central_AD prefix
    $routes->get('Central_AD/suppliers', 'Admin::suppliers');
    $routes->get('Central_AD/orders', 'Admin::orders');
    $routes->get('Central_AD/franchising', 'Admin::franchising');
    $routes->get('Central_AD/reports', 'Admin::reports');
    $routes->get('Central_AD/settings', 'Admin::settings');
});


// 🔹 Branch Manager Dashboard & Features
$routes->group('branch', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Branch::dashboard');                   // Branch manager main dashboard
    $routes->get('monitor-inventory', 'Branch::monitorInventory');    // Monitor inventory
    $routes->match(['get', 'post'], 'purchase-request', 'Branch::purchaseRequest'); // Create purchase request
    $routes->get('approve-transfers', 'Branch::approveTransfers');    // Approve transfer requests
    $routes->get('approve-transfer/(:num)', 'Branch::approveTransferAction/$1'); // Approve transfer by ID
});

// 🔹 Admin Inventory Management
$routes->group('admin/inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AdminInventory::dashboard');           // /admin/inventory
    $routes->get('add', 'AdminInventory::addStock');
    $routes->post('add', 'AdminInventory::saveStock');        // Handle form submit
    $routes->get('edit/(:num)', 'AdminInventory::editStock/$1');
    $routes->post('update/(:num)', 'AdminInventory::updateStock/$1'); 
    $routes->get('delete/(:num)', 'AdminInventory::deleteStock/$1'); 
    $routes->get('alerts', 'AdminInventory::alerts');         // Stock alerts
    $routes->get('branches', 'AdminInventory::branchStocks'); // Inventory per branch
});

// 🔹 Inventory Staff Routes
$routes->group('inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Inventory::dashboard');
    $routes->get('add-stock', 'Inventory::addStock');
    $routes->get('edit-stock', 'Inventory::editStock');
    $routes->get('stock-list', 'Inventory::stockList');
    $routes->get('alerts', 'Inventory::alerts');
});

// 🔹 Auth & Misc
$routes->get('logout', 'Admin::logout');
$routes->get('forgot-password', 'Admin::forgotPassword');
$routes->post('forgot-password', 'Admin::forgotPasswordSubmit');
