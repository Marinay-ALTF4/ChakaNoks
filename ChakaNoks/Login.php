<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Central Admin Login</title>
</head>
<body>

  <div class="login-container active" id="loginPage">
      <h2>Central Admin Login</h2>
      <div class="form-group">
          <label for="username">Username / Email</label>

          <input type="text" id="username" placeholder="Enter your username">
      </div>

      <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" placeholder="Enter your password">
      </div>

      <button class="btn btn-primary" onclick="signIn()">Sign In</button>

<script>

    function signIn() {

        window.location.href = 'Central AD.html';
    }
</script>

      <div class="forgot">
          <a href="#" onclick="showPage('forgotPage')">Forgot Password?</a>
      </div>
  </div>

  <div class="login-container" id="forgotPage">
      <h2>Forgot Password</h2>
      <div class="form-group">
          <label for="resetEmail">Enter your email</label>
          <input type="email" id="resetEmail" placeholder="Enter your email">
      </div>

      <button class="btn btn-primary" onclick="showPage('codePage')">Send Reset Link</button>
      <a href="#" class="back-link" onclick="showPage('loginPage')">← Back to Login</a>
  </div>

  <div class="login-container" id="codePage">
      <h2>Enter Confirmation Code</h2>
      <div class="form-group">
          <label for="code">Code</label>
          <input type="text" id="code" placeholder="Enter code from email">
      </div>

      <button class="btn btn-primary" onclick="showPage('resetPage')">Verify Code</button>
      <a href="#" class="back-link" onclick="showPage('forgotPage')">← Back</a>
  </div>

  <div class="login-container" id="resetPage">
      <h2>Reset Password</h2>
      <div class="form-group">
          <label for="newPass">New Password</label>
          <input type="password" id="newPass" placeholder="Enter new password">
      </div>

      <div class="form-group">
          <label for="confirmPass">Confirm Password</label>
          <input type="password" id="confirmPass" placeholder="Confirm new password">
      </div>

      <button class="btn btn-primary" onclick="updatePassword()">Update Password</button>
      <a href="#" class="back-link" onclick="showPage('loginPage')">← Back to Login</a>
  </div>

  <script>
      function showPage(pageId) {
          document.querySelectorAll('.login-container').forEach(div => div.classList.remove('active'));
          document.getElementById(pageId).classList.add('active');
      }

      function updatePassword() {
          showPage('loginPage');
      }
  </script>

  <style>
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
          box-shadow: 0 3px 8px rgba(0,0,0,0.1);
          width: 350px;
          text-align: center;
          display: none;
          border: 2px solid #00000052;
      }

      .login-container.active {
          display: block;
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

      .btn {
          width: 100%;
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
      }

      .back-link {
          display: inline-block;
          margin-top: 20px;
          color: #2c3e50;
          text-decoration: none;
          font-size: 14px;
      }

      .back-link:hover {
          text-decoration: underline;
      }
  </style>

</body>
</html>
