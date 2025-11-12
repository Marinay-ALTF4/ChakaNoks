# SCMS Implementation Plan - Aiming for 100% Rubrics

## 1. Database Schema & Migrations (System Architecture - 25%)
- [ ] Create/update migrations for all tables with proper relationships, constraints, and indexes
- [ ] Tables: users (roles), branches, inventory (with barcode, expiry), suppliers, purchase_requests, purchase_orders, transfers, deliveries, franchises, logs, alerts
- [ ] Add foreign keys, timestamps, and validation rules
- [ ] Run migrations and seed initial data (users, branches, suppliers)

## 2. Models (Core Modules)
- [ ] InventoryModel: Add barcode scanning, expiry tracking, real-time updates, alerts for low stock
- [ ] SupplierModel: Add contracts, performance tracking
- [ ] PurchaseOrderModel: Workflow for requests, approvals, tracking
- [ ] BranchModel: Manage branches, transfers
- [ ] UserModel: Role-based access (Branch Manager, Staff, Central Admin, etc.)
- [ ] FranchiseModel: Applications, allocations, royalties
- [ ] LogisticsModel: Deliveries, routes
- [ ] Implement relationships, validation, and custom methods

## 3. Controllers (Modules & Integration - 30-40%)
- [ ] BranchManagerController: Monitor inventory, create requests, approve transfers
- [ ] InventoryStaffController: Update stock, receive deliveries, report damage
- [ ] Central_ADController: Dashboard, approve orders, manage suppliers, reports
- [ ] LogisticsController: Schedule deliveries, track routes
- [ ] FranchiseController: Process applications, allocate supplies
- [ ] AdminController: User management, security, backups
- [ ] Implement all methods with validation, error handling, and redirects

## 4. Views (UI & Integration)
- [ ] Create responsive views using Bootstrap for all roles
- [ ] Branch: Dashboard, inventory monitor, purchase request form
- [ ] Central: Consolidated dashboard, reports, approvals
- [ ] Staff: Stock updates, barcode scanner integration
- [ ] Include real-time elements (AJAX for updates), alerts, and notifications

## 5. Routes & Security (Security & User Management - 20%)
- [ ] Define all routes with role-based filters
- [ ] Implement authentication (login/logout), session management
- [ ] Add SSL, encryption, backups, activity logs
- [ ] Role-based access control with middleware

## 6. Features Implementation (Core Features)
- [ ] Inventory: Real-time tracking, alerts, barcode (integrate library), expiry notifications
- [ ] Purchasing: Request creation, approval workflow, supplier integration
- [ ] Logistics: Delivery scheduling, route optimization (basic), tracking
- [ ] Dashboard: Real-time reports, analytics
- [ ] Franchising: Application processing, supply allocation, royalty tracking
- [ ] Alerts: Email/SMS for low stock, expiry, approvals

## 7. Integration & Testing (System Integration - 15%, Code Quality - 10-20%)
- [ ] Ensure modules connect seamlessly (e.g., inventory updates trigger alerts)
- [ ] Add unit tests, validation tests
- [ ] Optimize code: Modular, clean, version control (Git commits)
- [ ] Test scalability (5 branches + future), performance
