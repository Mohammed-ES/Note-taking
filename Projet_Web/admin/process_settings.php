<?php
session_start();
require_once '../config/connectiondb.php'; // Corrigé selon votre organisation

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($role === 'admin') {
        $stmt = $conn->prepare("SELECT id, username, password, name FROM administrator WHERE username = ?");
    } elseif ($role === 'student') {
        $stmt = $conn->prepare("SELECT id, username, password, name FROM student WHERE username = ?");
    } else {
        header("Location: ../auth/login.php?error=invalid_role");
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $role;

        // Redirection corrigée
        if ($role === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../student/student_dashboard.php");
        }
        exit();
    } else {
        header("Location: ../auth/login.php?error=invalid_credentials");
        exit();
    }
} else {
    header("Location: ../auth/login.php");
    exit();
}
