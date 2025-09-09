<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Central Admin Login</title>
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

<style> body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #ececec;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-container {
    background: rgb(223, 223, 223);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    width: 350px;
    text-align: center;
    border: 2px solid #00000052;
}
.login-container h2 {
    color: #2c3e50;
    margin-bottom: 20px;
}
.form-group {
    margin-bottom: 20px;
    text-align: left;
}
.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #34495e;
    font-size: 14px;
}
.form-group input {
    width: 90%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #000000;
    font-size: 14px;
}
.btn {
    width: 70%;
    padding: 12px;
    border: none;
    border-radius: 20px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}
.btn-primary {
    background-color: #3498db;
    color: white;
}
.btn-primary:hover {
    background-color: #2980b9;
}
.forgot {
    margin-top: 15px;
    font-size: 14px;
}
.forgot a {
    color: #3498db;
    text-decoration: none;
}
.forgot a:hover {
    text-decoration: underline;
}</style>

