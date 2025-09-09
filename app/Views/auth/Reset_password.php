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

    <form action="Login.html" method="get">
        <div class="form-group">
            <label for="newPass">New Password</label>
            <input type="password" id="newPass" name="newPass" placeholder="Enter new password" required>
        </div>

        <div class="form-group">
            <label for="confirmPass">Confirm Password</label>
            <input type="password" id="confirmPass" name="confirmPass" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn-primary">Update Password</button>
        <a href="Login.html" class="back-link">← Back to Login</a>
    </form>
</div>

</body>
</html>

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
    background: #dfdfdf;
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
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #dcdcdc;
    font-size: 14px;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 20px;
    transition: background-color 0.3s;
}

.back-link:hover {
    background-color: #2980b9;
}

.btn-primary {
    display: block;
    width: 70%;
    padding: 12px;
    margin-top: 15px;
    background-color: #3498db;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-left: 50px;
}

.btn-primary:hover {
    background-color: #2980b9;
}
