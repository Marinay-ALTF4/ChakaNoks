    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Admin Login</title>
    </head>
    <body>

    <div class="login-container active">
    
    <img src="<?= base_url('public/image/537943935_790163170374967_5997074592581386061_n.jpg') ?>" alt="Logo" class="logo">

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
    
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(to top, #e7ca93ff, #eeb769ff); 
}


.login-container {
    background-color: #e9ddc7ff;
    padding: 40px;
    width: 350px;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}


.login-container h2 {
    margin-bottom: 20px;
    color: #000000ff;
    font-size: 22px;
    text-align: center;
}

.login-container input[type="text"],
.login-container input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s;
}

.login-container input:focus {
    border-color: #060605ff;
    outline:auto;
    box-shadow: 0 0 5px rgba(255, 152, 0, 0.6);
}


.login-container button {
    width: 100%;
    padding: 12px;
    background:linear-gradient(to bottom,  #ff9800,  #c68c36ff);
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 8px;
    margin-top: 15px;
    cursor: pointer;
    transition: 0.3s;
}

.login-container button:hover {
    background: #e68900;
}


.login-container a {
    display: block;
    margin-top: 15px;
    font-size: 14px;
    color: #ff9800;
    text-decoration: none;
    transition: 0.3s;
}

.login-container a:hover {
    text-decoration: underline;
}  
    .form-group {
        margin-bottom: 2px;
        text-align: left;
    }
    .form-group label {
        display: block;
        color: #000000ff;
        font-size: 14px;
        font-weight: bold;
        margin-top: 5px;
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
        background-color: #715f05ff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #8b7637ff;
    }
    .forgot {
        margin-top: 15px;
        font-size: 14px;
        text-align: center;
        font-weight: bold;
    }
    .forgot a {
        color: #000000ff;
        text-decoration: none;
    }
    .forgot a:hover {
        text-decoration: underline;
    }
    .logo {
    width: 150px;          
    margin-bottom: 15px;   
    border-radius: 50px;
    margin-left: 20%;
}

    </style>

