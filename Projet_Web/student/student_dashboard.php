<?php
session_start();
if (!isset($_SESSION['student_username'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/student-dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="platform-title">
                <i class="fas fa-graduation-cap"></i>
                <h1>Student Learning Platform</h1>
            </div>
            <h1 class="dashboard-title">Welcome, <?php echo htmlspecialchars($_SESSION['student_username']); ?>!</h1>
            <p class="dashboard-subtitle">Your academic hub for notes, quizzes and planning</p>
            <div class="profile-badge">
                <i class="fas fa-user-graduate"></i>
                <span>Student</span>
                <span class="profile-status"></span>
            </div>
        </div>
        
        <div class="dashboard-body">
            <div class="action-buttons">
                <a class="action-button" href="../student/notes/notes_student.php">
                    <i class="fas fa-book"></i> View My Notes
                </a>
                <a class="action-button" href="../student/quiz/quiz_student.php">
                    <i class="fas fa-question-circle"></i> View Quizzes
                </a>
                <a class="action-button" href="../student/planning/planning_student.php">
                    <i class="fas fa-calendar"></i> View Planning
                </a>
                <a class="action-button logout" href="../auth/logout_students.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</body>
</html>