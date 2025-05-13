<?php
session_start();
include '../../config/connectiondb.php';

if (!isset($_SESSION['student_username'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['student_username'];
$message = '';
$messageType = '';

// Processing event addition form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_title = trim($_POST['event_title']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_type = $_POST['event_type'];
    $event_description = trim($_POST['event_description']);
    
    // Combine date and time
    $event_datetime = $event_date . ' ' . $event_time . ':00';
    
    // Simple validation
    if (empty($event_title) || empty($event_date)) {
        $message = "Title and date are required.";
        $messageType = "error";
    } else {
        // Insert event into database
        $stmt = $conn->prepare("INSERT INTO planning (student_username, event_title, event_date, event_type, event_description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $event_title, $event_datetime, $event_type, $event_description);
        
        if ($stmt->execute()) {
            $message = "Event added successfully!";
            $messageType = "success";
            
            // Redirect after 2 seconds
            header("refresh:2;url=planning_student.php");
        } else {
            $message = "Error adding event: " . $conn->error;
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="planning_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="user-welcome">
                <i class="fas fa-calendar-plus"></i>
                <h2>Add <span>New Event</span></h2>
            </div>
            <nav>
                <a href="planning_student.php" class="back-btn">Back to Schedule</a>
            </nav>
        </header>
        
        <main>
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="event_title">
                            <i class="fas fa-heading"></i> Event Title
                        </label>
                        <input type="text" id="event_title" name="event_title" placeholder="Ex: Mathematics exam" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date">
                                <i class="fas fa-calendar-day"></i> Date
                            </label>
                            <input type="date" id="event_date" name="event_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_time">
                                <i class="fas fa-clock"></i> Time
                            </label>
                            <input type="time" id="event_time" name="event_time" value="08:00">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_type">
                            <i class="fas fa-tag"></i> Event Type
                        </label>
                        <select id="event_type" name="event_type">
                            <option value="exam">Exam</option>
                            <option value="assignment">Assignment Due</option>
                            <option value="meeting">Meeting</option>
                            <option value="study">Study Session</option>
                            <option value="appointment">Appointment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_description">
                            <i class="fas fa-align-left"></i> Description (optional)
                        </label>
                        <textarea id="event_description" name="event_description" rows="4" placeholder="Additional event details..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="window.location.href='planning_student.php'">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Save Event
                        </button>
                    </div>
                </form>
            </div>
        </main>
        
        <footer>
            <p>&copy; <?= date('Y') ?> Student Planning System</p>
        </footer>
    </div>
</body>
</html>