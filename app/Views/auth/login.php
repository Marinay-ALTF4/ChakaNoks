<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Central Admin Login</title>
  <link rel="stylesheet" href="<?= base_url('css/Login.css') ?>">
</head>
<body>

<div class="login-container active">
    <h2>Central Admin Login</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
<form action="<?= base_url('loginAuth') ?>" method="post">

        <div class="form-group">
            <label for="username">Username / Email</label>
            <input type="text" name="username" id="username" placeholder="Enter your username or email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn btn-primary">Sign In</button>
    </form>

    <div class="forgot">
        <a href="<?= base_url('forgot-password') ?>">Forgot Password?</a>
    </div>
</div>

</body>
</html>