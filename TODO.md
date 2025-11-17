# Purchase Request System and User Management Implementation

## Purchase Request System Fixes
- [x] Update BranchManager.php: Change createPurchaseRequest to use PurchaseRequestModel instead of PurchaseOrderModel
- [x] Update Dashboard.php: Fix metrics for branch_manager to count PRs from purchase_requests table
- [x] Update branch_managers/dashboard.php: Show dynamic PR count instead of hardcoded 0
- [x] Add approve/reject methods in Central_AD.php for purchase_requests
- [x] Update admin dashboard (dashboard/index.php): Add pending PRs display with approve/reject buttons

## User Management Implementation
- [x] Add user management methods in Admin.php (users list, create, store, edit, update, delete)
- [x] Create app/Views/managers/users.php view
- [x] Create app/Views/managers/create_user.php view
- [x] Update Routes.php: Add routes for user management

## Testing and Finalization
- [ ] Test PR creation, approval, rejection workflow
- [ ] Test user creation and branch assignment
- [ ] Update TODO.md with completed tasks
