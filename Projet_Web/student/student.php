<?php
include '../config/connectiondb.php';
session_start(); // Assurez-vous de démarrer la session

// Vérifier si l'administrateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$admin_id = $_SESSION['admin_id']; // Récupérer l'ID de l'administrateur connecté

// Fetch only students related to the connected administrator
$stmt = $conn->prepare("SELECT id, username, name FROM student WHERE id_admin = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/student_style.css">
    <script src="../assets/js/student/student.js"></script>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <i class="fas fa-user-graduate"></i> Student Management
                </div>
                <a href="../admin/dashboard.php" class="home-button">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">Student List</h2>
                <div class="button-group">
                    <a href="restore_students.php" class="btn btn-secondary">
                        <i class="fas fa-trash-restore"></i> View Deleted Students
                    </a>
                    <a href="add_student.php" class="btn btn-add">
                        <i class="fas fa-plus"></i> Add New Student
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="action-buttons">
                                        <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn btn-edit btn-table">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-danger btn-table">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-message">
                        <i class="fas fa-info-circle"></i> No students found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>