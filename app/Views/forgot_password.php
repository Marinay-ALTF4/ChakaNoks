<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="<?= base_url('css/forgot_password.css') ?>">
</head>
<body>

<div class="login-container">
    <h2>Forgot Password</h2>

    <?php if(session()->getFlashdata('success')): ?>
        <p style="color:green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('forgot-password') ?>" method="POST">
        <div class="form-group">
            <label for="email">Enter your email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>

    <a href="<?= base_url() ?>" class="back-link">← Back to Login</a>
</div>

</body>
</html>
