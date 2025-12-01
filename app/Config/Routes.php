<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 🔹 Authentication & Admin routes
$routes->get('/', 'Admin::login');                 // Homepage → login form
$routes->get('/login', 'Admin::login');            // /login → login form
$routes->post('/loginAuth', 'Admin::loginAuth');   // Login POST


// 🔹 Admin Dashboard & Features
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Unified role-aware dashboard
    $routes->get('dashboard', 'Dashboard::index');


    $routes->get('Central_AD', 'Admin::dashboard');                     
    $routes->get('Central_AD/other-branches', 'Admin::otherBranches');  
    $routes->get('Central_AD/request_stock', 'Admin::request_stock');
    $routes->post('Admin/storeStockRequest', 'Admin::storeStockRequest');


    $routes->get('Central_AD/dashboard', 'Central_AD::dashboard');
    $routes->get('Central_AD/inventory', 'Central_AD::inventory');
    $routes->get('Central_AD/branches', 'Central_AD::branches');
    $routes->get('Central_AD/branches/add', 'Central_AD::addBranch');
    $routes->post('Central_AD/branches/store', 'Central_AD::storeBranch');
    $routes->get('Central_AD/branches/edit/(:num)', 'Central_AD::editBranch/$1');
    $routes->post('Central_AD/branches/update/(:num)', 'Central_AD::updateBranch/$1');
    $routes->get('Central_AD/branches/delete/(:num)', 'Central_AD::deleteBranch/$1');
    $routes->get('Central_AD/suppliers', 'Central_AD::suppliers');
    $routes->get('Central_AD/orders', 'Central_AD::orders');
    $routes->get('Central_AD/franchising', 'Central_AD::franchising');
    $routes->get('Central_AD/reports', 'Central_AD::reports');
    $routes->get('Central_AD/settings', 'Central_AD::settings');

    // Purchase Request Approval Routes
    $routes->get('Central_AD/approvePurchaseRequest/(:num)', 'Central_AD::approvePurchaseRequest/$1'); // Approve PR
    $routes->get('Central_AD/rejectPurchaseRequest/(:num)', 'Central_AD::rejectPurchaseRequest/$1'); // Reject PR

    
    // User Management Routes
    $routes->get('admin/users', 'Admin::users'); // List users
    $routes->get('admin/users/create', 'Admin::createUser'); // Create user form
    $routes->post('admin/users/store', 'Admin::storeUser'); // Store new user
    $routes->get('admin/users/edit/(:num)', 'Admin::editUser/$1'); // Edit user form
    $routes->post('admin/users/update/(:num)', 'Admin::updateUser/$1'); // Update user
    $routes->get('admin/users/delete/(:num)', 'Admin::deleteUser/$1'); // Delete user
});

// 🔹 Branch Manager Dashboard & Features
$routes->group('branch', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'BranchManager::dashboard');                   // Branch manager main dashboard
    $routes->get('monitor-inventory', 'BranchManager::inventory');    // Monitor inventory
    $routes->get('inventory', 'BranchManager::inventory');       // Inventory view
    $routes->match(['GET', 'POST'], 'purchase-request', 'BranchManager::createPurchaseRequest'); // Create purchase request
    $routes->get('get-supplier-items/(:num)', 'BranchManager::getSupplierItems/$1'); // Get supplier items via AJAX


    // Purchase Request Approval Routes (moved outside branch group)
    $routes->get('approve-transfers', 'BranchManager::approveTransfer');    // Approve transfer requests
    $routes->get('approve-transfer/(:num)', 'BranchManager::approveTransfer/$1'); // Approve transfer by ID
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
$routes->get('inventory/edit-stock/(:num)', 'Inventory::editStock/$1');
$routes->post('inventory/edit-stock/(:num)', 'Inventory::editStock/$1');
$routes->post('inventory/quick-update', 'Inventory::quickUpdate');

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

// Central Admin Inventory Management
$routes->get('/Central_AD/addItem', 'Central_AD::addItem');
$routes->post('/Central_AD/storeItem', 'Central_AD::storeItem');
$routes->get('/Central_AD/editItem/(:num)', 'Central_AD::editItem/$1');
$routes->post('/Central_AD/updateItem/(:num)', 'Central_AD::updateItem/$1');
$routes->get('/Central_AD/deleteItem/(:num)', 'Central_AD::deleteItem/$1');

// Central Admin Order Management
$routes->get('/Central_AD/createOrder', 'Central_AD::createOrder');
$routes->post('/Central_AD/storeOrder', 'Central_AD::storeOrder');

// Supplier Order Management
$routes->get('/Central_AD/supplier-orders', 'Central_AD::supplierOrders');
$routes->get('/Central_AD/confirm-supplier-order/(:num)', 'Central_AD::confirmSupplierOrder/$1');
$routes->get('/Central_AD/mark-preparing/(:num)', 'Central_AD::markOrderPreparing/$1');
$routes->get('/Central_AD/mark-ready-delivery/(:num)', 'Central_AD::markOrderReadyForDelivery/$1');

// Central Admin Franchising Management
$routes->get('/Central_AD/addFranchise', 'Central_AD::addFranchise');
$routes->post('/Central_AD/storeFranchise', 'Central_AD::storeFranchise');
$routes->get('/Central_AD/editFranchise/(:num)', 'Central_AD::editFranchise/$1');
$routes->post('/Central_AD/updateFranchise/(:num)', 'Central_AD::updateFranchise/$1');
$routes->get('/Central_AD/deleteFranchise/(:num)', 'Central_AD::deleteFranchise/$1');

// Supplier routes
$routes->get('/supplier/dashboard', 'SupplierController::dashboard');
$routes->get('/supplier/orders', 'SupplierController::orders');
$routes->get('/supplier/confirm-order/(:num)', 'SupplierController::confirmOrder/$1');
$routes->get('/supplier/mark-preparing/(:num)', 'SupplierController::markPreparing/$1');
$routes->get('/supplier/mark-ready/(:num)', 'SupplierController::markReadyForDelivery/$1');

// API Routes for Real-time Updates
$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('purchase-requests-count', 'ApiController::getPurchaseRequestsCount');
    $routes->get('purchase-requests', 'ApiController::getPurchaseRequests');
    $routes->get('supplier-orders', 'ApiController::getSupplierOrders');
    $routes->get('ready-for-delivery', 'ApiController::getReadyForDeliveryOrders');
    $routes->get('workflow-stats', 'ApiController::getWorkflowStats');
    $routes->get('order-status/(:num)', 'ApiController::getOrderStatus/$1');
    $routes->get('delivery-status/(:num)', 'ApiController::getDeliveryStatus/$1');
});

// Logistics routes
$routes->get('/logistics/dashboard', 'LogisticsController::dashboard');
$routes->get('/logistics/schedule-delivery', 'LogisticsController::scheduleDelivery');
$routes->post('/logistics/schedule-delivery', 'LogisticsController::scheduleDelivery');
$routes->get('/logistics/update-delivery-status/(:num)', 'LogisticsController::updateDeliveryStatus/$1');
$routes->post('/logistics/update-delivery-status/(:num)', 'LogisticsController::updateDeliveryStatus/$1');
$routes->get('/logistics/deliveries', 'LogisticsController::deliveries');
$routes->get('/logistics/optimize-routes', 'LogisticsController::optimizeRoutes');
$routes->get('/logistics/track-delivery/(:any)', 'LogisticsController::trackDelivery/$1');

// Franchise routes
$routes->get('/franchise/dashboard', 'FranchiseController::dashboard');
$routes->get('/franchise/applications', 'FranchiseController::applications');
$routes->get('/franchise/approve/(:num)', 'FranchiseController::approveApplication/$1');
$routes->get('/franchise/reject/(:num)', 'FranchiseController::rejectApplication/$1');
$routes->get('/franchise/allocate-supply/(:num)', 'FranchiseController::allocateSupply/$1');
$routes->post('/franchise/allocate-supply/(:num)', 'FranchiseController::allocateSupply/$1');
$routes->get('/franchise/view/(:num)', 'FranchiseController::view/$1');
$routes->get('/franchise/calculate-royalty/(:num)', 'FranchiseController::calculateRoyalty/$1');
$routes->post('/franchise/calculate-royalty/(:num)', 'FranchiseController::calculateRoyalty/$1');
$routes->get('/franchise/allocation-report', 'FranchiseController::allocationReport');

// Alert routes
$routes->get('/alerts/send-low-stock', 'AlertController::sendLowStockAlerts');
$routes->get('/alerts/send-expiry', 'AlertController::sendExpiryAlerts');
$routes->get('/alerts/get', 'AlertController::getAlerts');
$routes->get('/api/alerts', 'AlertController::apiAlerts');


