<?php
session_start();
include '../../config/connectiondb.php';
if (!isset($_SESSION['student_username'])) {
    header("Location: ../../auth/login.php");
    exit();
}
$username = $_SESSION['student_username'];
$stmt = $conn->prepare("SELECT module, score, quiz_date FROM quizzes WHERE student_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Get student name
$stmt_name = $conn->prepare("SELECT name FROM student WHERE username = ?");
$stmt_name->bind_param("s", $username);
$stmt_name->execute();
$student_result = $stmt_name->get_result();
$student = $student_result->fetch_assoc();
$student_name = $student ? $student['name'] : $username;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quizzes | Student Learning Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/student/quiz_style.css">
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <a href="../student_dashboard.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h1><i class="fas fa-question-circle"></i> My Quizzes</h1>
            <p class="subtitle">Track your progress and review your quiz performance</p>
            <div class="user-status">
                <i class="fas fa-user-graduate"></i> <?= htmlspecialchars($student_name) ?>
                <span class="status-dot"></span>
            </div>
        </div>
    </div>
  
    <div class="container">
        <main>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-details">
                        <h3>5</h3>
                        <p>Available Quizzes</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?= $result->num_rows ?></h3>
                        <p>Completed</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon average">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-details">
                        <h3>78%</h3>
                        <p>Average Score</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon streak">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="stat-details">
                        <h3>3</h3>
                        <p>Day Streak</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Your Quizzes</h2>
                    <div class="card-tools">
                        <span class="badge"><?= $result->num_rows ?> Quiz<?= $result->num_rows != 1 ? 'zes' : '' ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="quiz-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-book"></i> Module</th>
                                    <th><i class="fas fa-star"></i> Score</th>
                                    <th><i class="fas fa-calendar-alt"></i> Date</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="module-name"><?= htmlspecialchars($row['module']) ?></td>
                                    <td>
                                        <div class="score-container">
                                            <div class="score-bar" style="width: <?= min(100, intval($row['score'])) ?>%"></div>
                                        </div>
                                        <span class="score-text"><?= htmlspecialchars($row['score']) ?>%</span>
                                    </td>
                                    <td class="quiz-date"><?= htmlspecialchars($row['quiz_date']) ?></td>
                                    <td class="actions-cell">
                                        <button class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Retry Quiz">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <!-- Example quizzes for demonstration -->
                    <div class="table-responsive">
                        <table class="quiz-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-book"></i> Module</th>
                                    <th><i class="fas fa-star"></i> Score</th>
                                    <th><i class="fas fa-calendar-alt"></i> Date</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="module-name">HTML & CSS Fundamentals</td>
                                    <td>
                                        <div class="score-container">
                                            <div class="score-bar" style="width: 85%"></div>
                                        </div>
                                        <span class="score-text">85%</span>
                                    </td>
                                    <td class="quiz-date">2025-05-05</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Retry Quiz">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="module-name">JavaScript Basics</td>
                                    <td>
                                        <div class="score-container">
                                            <div class="score-bar" style="width: 72%"></div>
                                        </div>
                                        <span class="score-text">72%</span>
                                    </td>
                                    <td class="quiz-date">2025-05-03</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Retry Quiz">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="module-name">PHP Essentials</td>
                                    <td>
                                        <div class="score-container">
                                            <div class="score-bar" style="width: 90%"></div>
                                        </div>
                                        <span class="score-text">90%</span>
                                    </td>
                                    <td class="quiz-date">2025-05-01</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Retry Quiz">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="module-name">Database Design</td>
                                    <td>
                                        <div class="score-container">
                                            <div class="score-bar" style="width: 65%"></div>
                                        </div>
                                        <span class="score-text">65%</span>
                                    </td>
                                    <td class="quiz-date">2025-04-28</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Retry Quiz">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2>Available Quizzes</h2>
                    <div class="card-tools">
                        <select class="filter-dropdown">
                            <option>All Categories</option>
                            <option>Web Development</option>
                            <option>Programming</option>
                            <option>Database</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="quiz-grid">
                        <div class="quiz-card">
                            <div class="quiz-badge">New</div>
                            <div class="quiz-icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <h3>Advanced JavaScript</h3>
                            <p>Test your knowledge of advanced JavaScript concepts including closures, promises, and async/await.</p>
                            <div class="quiz-meta">
                                <span><i class="fas fa-clock"></i> 20 minutes</span>
                                <span><i class="fas fa-question-circle"></i> 15 questions</span>
                            </div>
                            <a href="#" class="btn-action">Start Quiz</a>
                        </div>
                        
                        <div class="quiz-card">
                            <div class="quiz-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <h3>SQL Mastery</h3>
                            <p>Challenge yourself with complex SQL queries, database design principles, and optimization techniques.</p>
                            <div class="quiz-meta">
                                <span><i class="fas fa-clock"></i> 25 minutes</span>
                                <span><i class="fas fa-question-circle"></i> 18 questions</span>
                            </div>
                            <a href="#" class="btn-action">Start Quiz</a>
                        </div>
                        
                        <div class="quiz-card">
                            <div class="quiz-badge hot">Popular</div>
                            <div class="quiz-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3>Responsive Design</h3>
                            <p>Test your skills in creating responsive websites using CSS flexbox, grid, and media queries.</p>
                            <div class="quiz-meta">
                                <span><i class="fas fa-clock"></i> 15 minutes</span>
                                <span><i class="fas fa-question-circle"></i> 12 questions</span>
                            </div>
                            <a href="#" class="btn-action">Start Quiz</a>
                        </div>
                        
                        <div class="quiz-card">
                            <div class="quiz-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3>Web Security</h3>
                            <p>Learn about common security vulnerabilities and how to protect your web applications.</p>
                            <div class="quiz-meta">
                                <span><i class="fas fa-clock"></i> 30 minutes</span>
                                <span><i class="fas fa-question-circle"></i> 20 questions</span>
                            </div>
                            <a href="#" class="btn-action">Start Quiz</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <span class="footer-logo"><i class="fas fa-graduation-cap"></i> Student Learning Platform</span>
                    <p>© <?= date('Y') ?> Student Quiz System - All rights reserved</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>