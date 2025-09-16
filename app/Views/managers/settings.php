<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8fbff;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #dcdcdc;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            min-height: 100vh;
        }
        /* Main Content */
        .content {
            flex: 1;
            padding: 20px;
        }
        .page-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .form-section-title {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h5>Welcome, superadmin</h5>
        <a href="<?= base_url('Central_AD/dashboard') ?>" class="btn btn-dark w-100">Dashboard</a>
        <a href="<?= base_url('Central_AD/inventory') ?>" class="btn btn-dark w-100">Inventory</a>
        <a href="<?= base_url('Central_AD/suppliers') ?>" class="btn btn-dark w-100">Suppliers</a>
        <a href="<?= base_url('Central_AD/orders') ?>" class="btn btn-dark w-100">Orders</a>
        <a href="<?= base_url('Central_AD/franchising') ?>" class="btn btn-dark w-100">Franchising</a>
        <a href="<?= base_url('Central_AD/reports') ?>" class="btn btn-dark w-100">Reports</a>
        <a href="<?= base_url('Central_AD/settings') ?>" class="btn btn-dark w-100">Settings</a>
        <a href="<?= base_url('logout') ?>" class="btn btn-danger w-100">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h3>Settings Dashboard</h3>

        <div class="page-card">
            <!-- Settings Tabs -->
            <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                        👤 Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">
                        ⚙️ Preferences
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                        🔒 Security
                    </button>
                </li>
            </ul>

            <div class="tab-content p-3">
                <!-- Profile Settings -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <h5 class="form-section-title">Profile Information</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="John Doe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="john@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" value="+63 912 345 6789">
                        </div>
                        <button type="button" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <!-- Preferences Settings -->
                <div class="tab-pane fade" id="preferences" role="tabpanel">
                    <h5 class="form-section-title">System Preferences</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Theme</label>
                            <select class="form-select">
                                <option selected>Light</option>
                                <option>Dark</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Language</label>
                            <select class="form-select">
                                <option selected>English</option>
                                <option>Filipino</option>
                            </select>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Enable Notifications</label>
                        </div>
                        <button type="button" class="btn btn-primary">Save Preferences</button>
                    </form>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <h5 class="form-section-title">Security Settings</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control">
                        </div>
                        <button type="button" class="btn btn-warning">Update Password</button>
                    </form>
                    <hr>
                    <button class="btn btn-danger mt-2">Deactivate Account</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
