<?php
session_start();
include '../config/connectiondb.php';
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../auth/login.php");
    exit();
}
$username = $_SESSION['admin_username'];
$stmt = $conn->prepare("SELECT * FROM administrator WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Settings</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/admin/settings_style.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <div class="header-container">
          <h2><i class="fas fa-user-cog"></i> Account Settings</h2>
          <a href="dashboard.php" class="btn-back">
            <i class="fas fa-home"></i> Home
          </a>
        </div>
      </div>
      <div class="card-body">
        <!-- Success message if needed -->
        <?php if(isset($_SESSION['settings_updated']) && $_SESSION['settings_updated']): ?>
        <div class="feedback feedback-success">
          <i class="fas fa-check-circle"></i>
          <span>Your settings have been updated successfully.</span>
        </div>
        <?php 
          // Clear the flag
          unset($_SESSION['settings_updated']);
          endif; 
        ?>

        <form action="process_settings.php" method="POST">
          <div class="form-group">
            <label for="new_username" class="form-label">Username</label>
            <input type="text" id="new_username" name="new_username" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
          </div>
          
          <div class="form-group">
            <label for="new_password" class="form-label">Password</label>
            <div class="password-field">
              <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter a new password (optional)">
              <button type="button" class="password-toggle" onclick="togglePasswordVisibility()">
                <i class="fas fa-eye" id="password-icon"></i>
              </button>
            </div>
          </div>
          
          <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-lg pulse-animation">
              <i class="fas fa-save"></i>
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function togglePasswordVisibility() {
      const passwordInput = document.getElementById('new_password');
      const passwordIcon = document.getElementById('password-icon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>