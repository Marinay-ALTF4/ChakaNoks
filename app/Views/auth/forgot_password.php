<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
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

<style>
  
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background:linear-gradient(to bottom, #BF6B04, #A63F03);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background:linear-gradient(to bottom, #f2a61aff, #d6a03aff);
    padding: 40px;
    border-radius: 20px;
    width: 350px;
    text-align: center;
    border: 2px solid #00000052;
}

.login-container h2 {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #dcdcdc;
}

.btn {
    width: 70%;
    padding: 12px;
    border: none;
    border-radius: 20px;
    font-weight: bold;
    cursor: pointer;
    background-color: #715f05ff;
    color: white;
}

.btn:hover {
    background-color: #8b7637ff;
}

.back-link {
    display: block;
    margin-top: 20px;
    color: #2c3e50;
    text-decoration: none;
    font-weight: bold;
}

.back-link:hover {
    text-decoration: underline;
}
</style>
