<?php
include '../config/connectiondb.php';

// Individual Restore
if (isset($_GET['restore_id'])) {
    $restore_id = intval($_GET['restore_id']);

    // 1. Retrieve deleted data
    $stmt = $conn->prepare("SELECT * FROM deleted_students WHERE id = ?");
    $stmt->bind_param("i", $restore_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Check if password is NULL and provide a default value
        $password = $row['password'] ?? 'defaultpassword123';
        
        // 2. Reinsert into student table with id_admin
        $insert = $conn->prepare("INSERT INTO student (username, name, password, id_admin) VALUES (?, ?, ?, ?)");
        $admin_id = 1; // Default admin ID - adjust if needed
        $insert->bind_param("sssi", $row['username'], $row['name'], $password, $admin_id);
        $insert->execute();

        // 3. Remove from deleted_students
        $delete = $conn->prepare("DELETE FROM deleted_students WHERE id = ?");
        $delete->bind_param("i", $restore_id);
        $delete->execute();
    }

    // Redirect after restoration
    header("Location: ./restore_students.php");
    exit();
}

// Individual Delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Delete from deleted_students permanently
    $delete = $conn->prepare("DELETE FROM deleted_students WHERE id = ?");
    $delete->bind_param("i", $delete_id);
    $delete->execute();
    
    // Vérifiez la redirection après suppression
    header("Location: restore_students.php?deleted=1");

    // S'assurer que c'est bien le chemin souhaité (redirection vers soi-même)
    exit();
}

// Delete all records
if (isset($_GET['delete_all'])) {
    // Delete all records from deleted_students
    $conn->query("DELETE FROM deleted_students");
    
    header("Location: ./restore_students.php?all_deleted=1");
    exit();
}

// 4. Display all deleted students
$stmt = $conn->prepare("SELECT * FROM deleted_students ORDER BY deleted_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restore Deleted Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/restore_students_style.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to permanently delete this student record?")) {
                window.location.href = '?delete_id=' + id;
            }
        }
        
        function confirmDeleteAll() {
            if (confirm("Are you sure you want to permanently delete all deleted student records?\nThis action cannot be undone.")) {
                window.location.href = '?delete_all=1';
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <i class="fas fa-trash-restore"></i> Deleted Students
                </div>
                <a href="student.php" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Students
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">Deleted Students List</h2>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Student record has been permanently deleted.
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['all_deleted'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> All deleted student records have been permanently removed.
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['all_restored'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> All student records have been successfully restored.
                    </div>
                <?php endif; ?>
                
                <?php if ($result->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['deleted_at']) ?></td>
                                    <td class="action-buttons">
                                        <a class="btn-restore" href="?restore_id=<?= $row['id'] ?>">
                                            <i class="fas fa-trash-restore"></i> Restore
                                        </a>
                                        <a class="btn-delete" href="javascript:void(0);" onclick="confirmDelete(<?= $row['id'] ?>)">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <div class="button-group">
                        <a href="restore_all_students.php" class="btn btn-restore-all">
                            <i class="fas fa-trash-restore"></i> Restore All Students
                        </a>
                        <a href="javascript:void(0);" onclick="confirmDeleteAll()" class="btn btn-delete-all">
                            <i class="fas fa-trash-alt"></i> Delete All Students
                        </a>
                    </div>
                <?php else: ?>
                    <div class="empty-message">
                        <i class="fas fa-info-circle"></i> No deleted students found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
