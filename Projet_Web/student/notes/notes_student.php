<?php
session_start();
include '../../config/connectiondb.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Add a note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_note'])) {
    $module = $_POST['module'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO student_notes (student_id, module, title, content) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $student_id, $module, $title, $content);
    $stmt->execute();
}

// Delete a note
if (isset($_GET['delete_id'])) {
    $note_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM student_notes WHERE id = ? AND student_id = ?");
    $stmt->bind_param("ii", $note_id, $student_id);
    $stmt->execute();
    header("Location: notes_student.php");
    exit();
}

// Get all notes
$stmt = $conn->prepare("SELECT * FROM student_notes WHERE student_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$notes_result = $stmt->get_result();
$notes = $notes_result->fetch_all(MYSQLI_ASSOC);

// Get student name - Fix: Using correct table name and column
$stmt = $conn->prepare("SELECT name FROM student WHERE id = ?");
$stmt->bind_param("i", $student_id);
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
    <title>My Notes - Student Learning Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/student/notes_style.css">
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <a href="../student_dashboard.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h1><i class="fas fa-book-open"></i> My Notes</h1>
            <p class="subtitle">Organize your thoughts and studies efficiently</p>
            <div class="user-status">
                <i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($student_name); ?>
                <span class="status-dot"></span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus-circle"></i> Add New Note</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="module" placeholder="Module (e.g. Web, Maths...)" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" placeholder="Note title" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="content" placeholder="Your note here..." rows="5" required></textarea>
                    </div>
                    <button type="submit" name="add_note" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Note
                    </button>
                </form>
            </div>
        </div>

        <?php if (count($notes) > 0): ?>
            <div class="notes-container">
                <?php foreach ($notes as $note): ?>
                    <div class="note">
                        <div class="note-header">
                            <h3 class="note-title"><?php echo htmlspecialchars($note['title']); ?></h3>
                            <span class="module-badge">
                                <i class="fas fa-book"></i> <?php echo htmlspecialchars($note['module']); ?>
                            </span>
                        </div>
                        <div class="note-content">
                            <?php echo nl2br(htmlspecialchars($note['content'])); ?>
                        </div>
                        <div class="note-footer">
                            <div class="note-meta">
                                <i class="far fa-calendar-alt"></i> Created: <?php echo date('M j, Y, g:i a', strtotime($note['created_at'])); ?>
                            </div>
                            <div class="note-actions">
                                <a href="edit_note_student.php?id=<?php echo $note['id']; ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?delete_id=<?php echo $note['id']; ?>" onclick="return confirm('Are you sure you want to delete this note?');" class="action-btn delete-btn">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-notes">
                <i class="fas fa-sticky-note"></i>
                <h3>No Notes Yet</h3>
                <p>Create your first note above to get started!</p>
            </div>
        <?php endif; ?>
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