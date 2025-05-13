<?php
include '../config/connectiondb.php';

// Get deleted students
$result = $conn->query("SELECT * FROM deleted_students");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/deleted_students_style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-trash-alt"></i> Deleted Students</h2>
            <a href="student.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <?php if (isset($_GET['restored'])): ?>
            <div class="message">
                <i class="fas fa-check-circle"></i> Student restored successfully.
            </div>
        <?php elseif (isset($_GET['all_restored'])): ?>
            <div class="message">
                <i class="fas fa-check-circle"></i> All students restored successfully.
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="restore_all_students.php" class="btn btn-restore" onclick="return confirm('Restore all deleted students?')">
                <i class="fas fa-trash-restore"></i> Restore All
            </a>
        </div>

        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Username</th>
                            <th><i class="fas fa-id-card"></i> Name</th>
                            <th><i class="fas fa-calendar"></i> Deleted At</th>
                            <th><i class="fas fa-cog"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= $row['deleted_at'] ?></td>
                            <td>
                                <a class="btn btn-action" href="restore_student.php?id=<?= $row['id'] ?>" onclick="return confirm('Restore this student?')">
                                    <i class="fas fa-trash-restore"></i> Restore
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>No deleted students found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
