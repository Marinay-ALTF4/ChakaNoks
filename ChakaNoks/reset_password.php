<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
</head>
<body>

<div class="login-container">
    <h2>Reset Password</h2>

    <form action="backend/reset_password.php" method="POST">
        <div class="form-group">
            <label for="newPass">New Password</label>
            <input type="password" id="newPass" name="newPass" placeholder="Enter new password" required>
        </div>

        <div class="form-group">
            <label for="confirmPass">Confirm Password</label>
            <input type="password" id="confirmPass" name="confirmPass" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>

    <a href="Login.php" class="back-link">← Back to Login</a>
</div>

<style>
/* same styling as previous pages */
body {
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
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 20px;
    font-weight: bold;
    cursor: pointer;
    background-color: #3498db;
    color: white;
}

.btn:hover {
    background-color: #2980b9;
}

.back-link {
    display: block;
    margin-top: 20px;
    color: #2c3e50;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}
</style>
</body>
</html>
