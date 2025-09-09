<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Code</title>
</head>

<body>

<div class="login-container">
    <h2>Enter Confirmation Code</h2>

    <form action="verify_code.html" method="POST">
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" id="code" name="code" placeholder="Enter code from email" required>
        </div>

        <button type="submit" class="btn btn-primary">Verify Code</button>
    </form>

    <a href="forgot_password.html" class="back-link">← Back</a>
</div>


</body>
</html>

<style>body {
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
}</style>
