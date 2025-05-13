<?php
session_start();
require_once '../config/connectiondb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'] ?? $username; // Use name if provided, otherwise use username
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        header("Location: register.php?error=All fields are required.");
        exit();
    }

    // For a real application, use password_hash, but since your DB has plain passwords:
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        if ($role === 'student') {
            // Check if username already exists
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM student WHERE username = ?");
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                header("Location: register.php?error=Username already exists.");
                exit();
            }

            // Insert new student - id_admin is required
            $admin_id = 1; // Default admin ID
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Use the hashed password
            $insert = $conn->prepare("INSERT INTO student (username, password, name, id_admin) VALUES (?, ?, ?, ?)");
            $insert->bind_param("sssi", $username, $hashed_password, $name, $admin_id);
            
        } elseif ($role === 'administrator') {
            // Check if username already exists
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM administrator WHERE username = ?");
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                header("Location: register.php?error=Username already exists.");
                exit();
            }

            // Insert new administrator
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Use the hashed password
            $insert = $conn->prepare("INSERT INTO administrator (username, password, name) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashed_password, $name);
            
        } else {
            header("Location: register.php?error=Invalid role selected.");
            exit();
        }

        // Execute the insert
        if ($insert->execute()) {
            header("Location: login.php?message=Account created successfully.");
            exit();
        } else {
            header("Location: register.php?error=Failed to create account: " . $conn->error);
            exit();
        }
    } catch (Exception $e) {
        header("Location: register.php?error=Database error: " . $e->getMessage());
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/auth/register_style.css">
</head>
<body>
    <div class="register-container">
        <div class="card">
            <div class="header-section">
                <i class="fas fa-user-plus header-icon"></i>
                <h1>Create Account</h1>
                <p>Join our learning platform today</p>
            </div>
            
            <div class="form-section">
                <?php if (isset($_GET['error'])): ?>
                    <div class="feedback error">
                        <i class="fas fa-exclamation-circle"></i> 
                        <span><?= htmlspecialchars($_GET['error']) ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['message'])): ?>
                    <div class="feedback success">
                        <i class="fas fa-check-circle"></i>
                        <span><?= htmlspecialchars($_GET['message']) ?></span>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Choose a username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <div class="input-group">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Account Type</label>
                        <div class="input-group">
                            <i class="fas fa-user-tag input-icon"></i>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">Select account type</option>
                                <option value="student">Student</option>
                                <option value="administrator">Administrator</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>

                <div class="form-footer">
                    <a href="login.php">
                        <i class="fas fa-sign-in-alt"></i> Already have an account? Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>