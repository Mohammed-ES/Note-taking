<?php
session_start();
include '../../config/connectiondb.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if (!isset($_GET['id'])) {
    echo "No note ID provided.";
    exit();
}

$note_id = $_GET['id'];

// Récupération de la note à modifier
$stmt = $conn->prepare("SELECT * FROM student_notes WHERE id = ? AND student_id = ?");
$stmt->bind_param("ii", $note_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$note = $result->fetch_assoc()) {
    echo "Note not found.";
    exit();
}

// Récupérer le nom de l'étudiant
$stmt = $conn->prepare("SELECT name FROM student WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
$student_name = $student ? $student['name'] : 'Student';

// Mise à jour après soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module = $_POST['module'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $update = $conn->prepare("UPDATE student_notes SET module = ?, title = ?, content = ? WHERE id = ? AND student_id = ?");
    $update->bind_param("sssii", $module, $title, $content, $note_id, $student_id);
    $update->execute();

    header("Location: notes_student.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note - Student Learning Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/student/edit_note_style.css">
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <a href="notes_student.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Notes
            </a>
            <h1><i class="fas fa-edit"></i> Edit Note</h1>
            <p class="subtitle">Update your note details</p>
            <div class="user-status">
                <i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($student_name); ?>
                <span class="status-dot"></span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-pen-to-square"></i> Edit Note Information</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label" for="module">Module:</label>
                        <input type="text" id="module" name="module" class="form-control" value="<?= htmlspecialchars($note['module']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="title">Title:</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($note['title']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="content">Content:</label>
                        <textarea id="content" name="content" class="form-control" required><?= htmlspecialchars($note['content']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Note
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