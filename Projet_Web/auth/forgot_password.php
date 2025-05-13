<?php
session_start();
require_once '../config/connectiondb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($newPassword !== $confirmPassword) {
        header("Location: forgot_password.php?error=Passwords do not match.");
        exit();
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    if ($role === 'student') {
        $table = 'students';
    } elseif ($role === 'admin') {
        $table = 'admins';
    } else {
        header("Location: forgot_password.php?error=Invalid role selected.");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: forgot_password.php?error=Username not found.");
        exit();
    }

    $updateStmt = $conn->prepare("UPDATE $table SET password = ? WHERE username = ?");
    if ($updateStmt->execute([$hashedPassword, $username])) {
        header("Location: login.php?message=Password successfully updated.");
        exit();
    } else {
        header("Location: forgot_password.php?error=Failed to update password.");
        exit();
    }
}
?>

<!-- HTML part with the form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Forgot Password</h2>

    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="forgot_password.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <select name="role" required>
            <option value="">Select role</option>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Reset Password</button>
    </form>

    <p><a href="login.php">Back to login</a></p>
</div>
</body>
</html>
