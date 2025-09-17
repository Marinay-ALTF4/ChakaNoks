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
    

  $routes->get('Central_AD/dashboard', 'Central_AD::dashboard');
    $routes->get('Central_AD/inventory', 'Central_AD::inventory');
    $routes->get('Central_AD/suppliers', 'Central_AD::suppliers');
    $routes->get('Central_AD/orders', 'Central_AD::orders');
    $routes->get('Central_AD/franchising', 'Central_AD::franchising');
    $routes->get('Central_AD/reports', 'Central_AD::reports');
    $routes->get('Central_AD/settings', 'Central_AD::settings');
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

// 📦 Inventory routes
$routes->get('inventory', 'Inventory::dashboard');

// Stock management
$routes->get('inventory/stock-list', 'Inventory::stockList');
$routes->get('inventory/add-stock', 'Inventory::addStock');
$routes->post('inventory/add-stock', 'Inventory::addStock');
$routes->get('inventory/edit-stock', 'Inventory::editStock');
$routes->post('inventory/edit-stock', 'Inventory::editStock');

// ✅ Update stock (matches updateStock method)
$routes->get('inventory/update_stock', 'Inventory::updateStock');
$routes->post('inventory/update_stock', 'Inventory::updateStock');
$routes->get('inventory/receive-delivery', 'Inventory::receive_delivery');
$routes->post('inventory/receive-delivery', 'Inventory::receive_delivery');


$routes->get('inventory/report-damage', 'Inventory::report_damage');
$routes->post('inventory/report-damage', 'Inventory::report_damage');


// Alerts
$routes->get('inventory/alerts', 'Inventory::alerts');


// 🔹 Auth & Misc
$routes->get('logout', 'Admin::logout');
$routes->get('forgot-password', 'Admin::forgotPassword');
$routes->post('forgot-password', 'Admin::forgotPasswordSubmit');

// sa create ni sa suppliers 
$routes->get('/Central_AD/suppliers', 'Central_AD::suppliers');
$routes->get('/Central_AD/createsupplier', 'Central_AD::addSupplier');
$routes->post('/Central_AD/storeSupplier', 'Central_AD::storeSupplier');
$routes->get('/Central_AD/editsupplier/(:num)', 'Central_AD::editSupplier/$1');
$routes->post('/Central_AD/updateSupplier/(:num)', 'Central_AD::updateSupplier/$1');
$routes->get('/Central_AD/deleteSupplier/(:num)', 'Central_AD::deleteSupplier/$1');


