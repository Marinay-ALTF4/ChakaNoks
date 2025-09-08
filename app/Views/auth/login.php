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

<style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #1e1e1e;
    color: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .login-container {
    background-color: #2a2a2a;
    padding: 40px;
    border-radius: 12px;
    width: 350px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    text-align: center;
  }

  .login-container h2 {
    margin-bottom: 20px;
    font-size: 22px;
    color: #fff;
  }

  .form-group {
    margin-bottom: 15px;
    text-align: left;
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #ccc;
    font-size: 14px;
  }

  input {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 6px;
    background-color: #1e1e1e;
    color: #fff;
    font-size: 14px;
  }

  input:focus {
    outline: none;
    border: 1px solid #666;
  }

  .btn {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border: none;
    border-radius: 6px;
    background-color: #444;
    color: #fff;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s ease;
  }

  .btn:hover {
    background-color: #666;
    transform: translateY(-2px);
  }

  .forgot {
    margin-top: 15px;
  }

  .forgot a {
    color: #bbb;
    font-size: 14px;
    text-decoration: none;
    transition: 0.3s ease;
  }

  .forgot a:hover {
    color: #fff;
  }

  .error {
    color: #f44336;
    margin-bottom: 15px;
    font-size: 14px;
  }
</style>
