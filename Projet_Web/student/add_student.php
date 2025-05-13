<?php
include '../config/connectiondb.php';
session_start(); // Assurez-vous de démarrer la session

// Vérifier si l'administrateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_admin = $_SESSION['admin_id']; // Utilisez l'ID de l'admin connecté

    // Vérifier si le nom d'utilisateur existe déjà
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM student WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        $error_message = "Ce nom d'utilisateur existe déjà.";
    } else {
        // Insérer le nouvel étudiant avec l'ID de l'admin connecté
        $stmt = $conn->prepare("INSERT INTO student (username, password, name, id_admin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $password, $name, $id_admin);
        
        if ($stmt->execute()) {
            header("Location: student.php?success=Student added successfully");
            exit();
        } else {
            $error_message = "Error adding student: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/add_student_style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <i class="fas fa-user-graduate"></i> Student Management
                </div>
                <a href="../dashboard.php" class="home-button">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">Add New Student</h2>
                <a href="student.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Add New Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>