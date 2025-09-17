<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Settings
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h3 class="mb-3">Settings Dashboard</h3>

<div class="page-card p-3">
    <!-- Tabs -->
    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                👤 Profile
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab" aria-controls="preferences" aria-selected="false">
                ⚙️ Preferences
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                🔒 Security
            </button>
        </li>
    </ul>

    <div class="tab-content pt-3">
        <!-- Profile Tab -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <h5 class="mb-2">Profile Information</h5>
            <form>
                <div class="mb-2">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control form-control-sm" value="John Doe">
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control form-control-sm" value="john@example.com">
                </div>
                <div class="mb-2">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control form-control-sm" value="+63 912 345 6789">
                </div>
                <button type="button" class="btn btn-primary btn-sm">Save Changes</button>
            </form>
        </div>

        <!-- Preferences Tab -->
        <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
            <h5 class="mb-2">System Preferences</h5>
            <form>
                <div class="mb-2">
                    <label class="form-label">Theme</label>
                    <select class="form-select form-select-sm">
                        <option selected>Light</option>
                        <option>Dark</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Language</label>
                    <select class="form-select form-select-sm">
                        <option selected>English</option>
                        <option>Filipino</option>
                    </select>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" checked>
                    <label class="form-check-label">Enable Notifications</label>
                </div>
                <button type="button" class="btn btn-primary btn-sm">Save Preferences</button>
            </form>
        </div>

        <!-- Security Tab -->
        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
            <h5 class="mb-2">Security Settings</h5>
            <form>
                <div class="mb-2">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control form-control-sm">
                </div>
                <button type="button" class="btn btn-warning btn-sm">Update Password</button>
            </form>
            <hr class="my-2">
            <button class="btn btn-danger btn-sm">Deactivate Account</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
