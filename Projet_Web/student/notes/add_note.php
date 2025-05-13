<?php
session_start();
include '../../config/connectiondb.php';
// Check if student is logged in
if (!isset($_SESSION['student_username'])) {
    header("Location: ../../auth/login.php");
    exit();
}
$username = $_SESSION['student_username'];
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module = trim($_POST['module']);
    $note = $_POST['note'];
    if (!empty($module) && is_numeric($note)) {
        $stmt = $conn->prepare("INSERT INTO notes (student_username, module, note) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $username, $module, $note);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            header("Location: ./notes_student.php");
            exit();
        } else {
            $message = "Error while saving the note.";
        }
    } else {
        $message = "Please fill all fields correctly.";
    }
}

// Get student name
$stmt = $conn->prepare("SELECT name FROM student WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
$student_name = $student ? $student['name'] : 'Student';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Note - Student Learning Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/student/add_note_style.css">
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <a href="notes_student.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Notes
            </a>
            <h1><i class="fas fa-plus-circle"></i> Add a Note</h1>
            <p class="subtitle">Save your notes for each module</p>
            <div class="user-status">
                <i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($student_name); ?>
                <span class="status-dot"></span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit"></i> Enter note details</h3>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="module" class="form-label">Module Name:</label>
                        <input type="text" name="module" id="module" class="form-control" required placeholder="Ex: Mathematics, Computer Science, Physics...">
                    </div>

                    <div class="form-group">
                        <label for="note" class="form-label">Grade (out of 20):</label>
                        <input type="number" name="note" id="note" step="0.01" min="0" max="20" class="form-control" required placeholder="Ex: 14.5">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Grade
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <span class="footer-icon"><i class="fas fa-graduation-cap"></i> Student Learning Platform</span>
                <span class="footer-copyright">© 2025 Student Note System – All rights reserved</span>
            </div>
        </div>
    </footer>
</body>
</html>