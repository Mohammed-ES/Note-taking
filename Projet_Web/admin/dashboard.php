<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin/admin_style.css">
</head>
<body>
  <div class="dashboard-container">
    <!-- Header Section -->
    <div class="header-section">
      <div class="header-logo">
        <i class="fas fa-user-shield"></i>
        <div class="platform-name">Admin Learning Platform</div>
      </div>
      <h1 class="welcome-text">Welcome, <?php echo $_SESSION['admin_username']; ?>!</h1>
      <p class="welcome-subtitle">Your administration hub for managing students, courses and settings</p>
      <div class="admin-badge">
        <i class="fas fa-user-shield"></i>
        Administrator
        <span class="status-dot"></span>
      </div>
    </div>
    
    <!-- Buttons Section -->
    <div class="buttons-section">
      <a class="menu-card" href="../student/student.php">
        <div class="icon-container">
          <i class="fas fa-user-graduate menu-icon"></i>
        </div>
        <span class="menu-text">Manage Students</span>
      </a>
      
      <a class="menu-card" href="settings.php">
        <div class="icon-container">
          <i class="fas fa-cog menu-icon"></i>
        </div>
        <span class="menu-text">Settings</span>
      </a>
      
      <a class="logout-button" href="../auth/logout.php">
        <i class="fas fa-sign-out-alt"></i>
        Logout
      </a>
    </div>
  </div>
</body>
</html>