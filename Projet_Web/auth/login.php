<?php include '../config/connectiondb.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/auth/login_style.css">
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
        
        <form action="process_login.php" method="POST">
            <?php if(isset($_GET['error'])): ?>
            <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['success'])): ?>
            <div class="success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
            <?php endif; ?>
            
            <h2>Login</h2>
            
            <div class="form-group">
                <label for="role">Sign in as:</label>
                <div class="input-with-icon">
                    <i class="fas fa-user-shield input-icon"></i>
                    <select name="role" id="role" required>
                        <option value="">-- Select a role --</option>
                        <option value="administrator">Administrator</option>
                        <option value="student">Student</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="username">Username:</label>
                <div class="input-with-icon">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" required placeholder="Enter your username">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">
                    <div class="checkbox-custom"></div>
                    Remember me
                </label>
            </div>
            
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i>
                Log in
            </button>
            
            <div class="additional-links">
                <a href="register.php">Create an account</a>
            </div>
            
            <div class="divider">or log in with</div>
            
            <div class="social-login">
                <div class="social-btn google">
                    <i class="fab fa-google"></i>
                </div>
                <div class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                </div>
                <div class="social-btn twitter">
                    <i class="fab fa-twitter"></i>
                </div>
            </div>
        </form>
    </div>
    
    <script src="../assets/js/auth/login.js"></script>
</body>
</html>