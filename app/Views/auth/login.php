<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Central Admin Login</title>
</head>
<body>

<div class="card-wrapper">

    <div class="login-card">

       
        <div class="card-left">
        
            <img src="<?= base_url('public/image/537943935_790163170374967_5997074592581386061_n.jpg') ?>" 
                 class="left-logo" alt="Logo">
            
        </div>

        
        <div class="card-right">

            <div class="login-container active">
                <h1>Welcome!</h1>
                <h2>Central Admin Login</h2>

                <?php if(session()->getFlashdata('error')): ?>
                    <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
                <?php endif; ?>

                <form action="<?= base_url('loginAuth') ?>" method="post">

                    <div class="form-group">
                        <label for="username">Username / Email</label>
                        <input type="text" name="username" id="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Sign In</button>

                </form>

                <div class="forgot">
                    <a href="<?= base_url('forgot-password') ?>">Forgot Password?</a>
                </div>

            </div>
        </div>

    </div>
</div>

</body>

</html>


<style> 

body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(to top, #d6aa57ff, #c48329ff);
    font-family: Arial, sans-serif;
}


.card-wrapper {
    width: 850px;
    max-width: 90%;
    margin: auto;
}

.login-card {
    display: flex;
    width: 100%;
    height: 420px;
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 6px 25px rgba(0,0,0,0.18);
}


.card-left {
    flex: 1;
    background: linear-gradient(to bottom right, #8d7346ff, #eaa638ff);
    padding: 35px;
    color: white;
    position: relative;
}

.left-logo {
    width: 300px;
    border-radius: 15px;
    margin-bottom: 20px;
    margin-left: 45px;
    margin-top: 30px;
}

.card-right h1 {
    font-size: 38px;
    margin-bottom: 0px;
    margin-top: -20px;
    text-align: center ;
}

.right-text {
    opacity: .9;
    font-size: 14px;
}


.card-right {
    flex: 1;
    background: #fdfbf7;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-container {
    width: 80%;
}


.login-container h2 {
    text-align: left;
    margin-bottom: 15px;
    color: #333;
}

.form-group {
    margin-bottom: 10px;
}

.form-group label {
    font-weight: bold;
    font-size: 13px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #000;
    border-radius: 6px;
}

.btn-primary {
    width: 100%;
    padding: 12px;
    background-color: #715f05ff;
    color: white;
    border: none;
    border-radius: 12px;
    margin-top: 10px;
    font-weight: bold;
}

.btn-primary:hover {
    background-color: #dda809ff;
}

.forgot {
    text-align: center;
    margin-top: 10px;
    font-weight: bold;
}

.forgot a {
    color: #000;
}


</style>
