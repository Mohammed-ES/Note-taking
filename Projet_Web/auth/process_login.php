<?php
session_start();
include '../config/connectiondb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    
    // Basic validation
    if (empty($username) || empty($password) || empty($role)) {
        header("Location: login.php?error=All fields are required");
        exit();
    }
    
    // Processing based on role
    if ($role == 'student') {
        // For students
        $stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // Support both hashed passwords and plain text passwords
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                // Create session
                $_SESSION['student_username'] = $username;
                $_SESSION['student_id'] = $row['id'];
                $_SESSION['role'] = 'student';
                $_SESSION['name'] = $row['name'] ?? $username;
                
                // If password was stored as plain text, update it to hashed version
                if ($password === $row['password']) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $update_stmt = $conn->prepare("UPDATE student SET password = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $hashed_password, $row['id']);
                    $update_stmt->execute();
                }
                
                // Redirect to student dashboard
                header("Location: ../student/student_dashboard.php");
                exit();
            } else {
                header("Location: login.php?error=Incorrect password");
                exit();
            }
        } else {
            header("Location: login.php?error=User not found");
            exit();
        }
    } else if ($role == 'administrator') {
        // For administrators
        $stmt = $conn->prepare("SELECT * FROM administrator WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // Support both hashed passwords and plain text passwords
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                // Create session
                $_SESSION['admin_username'] = $username;
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['role'] = 'admin';
                $_SESSION['name'] = $row['name'] ?? $username;
                
                // If password was stored as plain text, update it to hashed version
                if ($password === $row['password']) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $update_stmt = $conn->prepare("UPDATE administrator SET password = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $hashed_password, $row['id']);
                    $update_stmt->execute();
                }
                
                // Redirect to admin dashboard
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
                header("Location: login.php?error=Incorrect password");
                exit();
            }
        } else {
            header("Location: login.php?error=Administrator not found");
            exit();
        }
    } else {
        header("Location: login.php?error=Invalid role");
        exit();
    }
}
?>
