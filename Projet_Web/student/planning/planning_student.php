<?php
session_start();
include '../../config/connectiondb.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$success_message = '';
$error_message = '';

// Add new event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $event_title = trim($_POST['event_title']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_type = trim($_POST['event_type']);
    $event_description = trim($_POST['event_description']);

    if (empty($event_title) || empty($event_date) || empty($event_time) || empty($event_type)) {
        $error_message = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO planning (student_id, event_title, event_date, event_time, event_type, event_description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $student_id, $event_title, $event_date, $event_time, $event_type, $event_description);
        
        if ($stmt->execute()) {
            $success_message = "Event added successfully!";
        } else {
            $error_message = "Error adding event: " . $conn->error;
        }
        $stmt->close();
    }
}

// Delete event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM planning WHERE id = ? AND student_id = ?");
    $stmt->bind_param("ii", $id, $student_id);
    
    if ($stmt->execute()) {
        $success_message = "Event deleted successfully!";
    } else {
        $error_message = "Error deleting event: " . $conn->error;
    }
    $stmt->close();
}

// Edit event
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM planning WHERE id = ? AND student_id = ?");
    $stmt->bind_param("ii", $edit_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_event = $result->fetch_assoc();
    $stmt->close();
}

// Update event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $event_title = trim($_POST['event_title']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_type = trim($_POST['event_type']);
    $event_description = trim($_POST['event_description']);

    if (empty($event_title) || empty($event_date) || empty($event_time) || empty($event_type)) {
        $error_message = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("UPDATE planning SET event_title = ?, event_date = ?, event_time = ?, event_type = ?, event_description = ? WHERE id = ? AND student_id = ?");
        $stmt->bind_param("sssssii", $event_title, $event_date, $event_time, $event_type, $event_description, $event_id, $student_id);
        
        if ($stmt->execute()) {
            $success_message = "Event updated successfully!";
            header("Location: planning_student.php");
            exit();
        } else {
            $error_message = "Error updating event: " . $conn->error;
        }
        $stmt->close();
    }
}

// Get today's date for highlighting
$today = date('Y-m-d');

// Get all events
$stmt = $conn->prepare("SELECT * FROM planning WHERE student_id = ? ORDER BY event_date, event_time");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Group events by date for calendar view
$calendar_events = [];
foreach ($events as $event) {
    $date = $event['event_date'];
    if (!isset($calendar_events[$date])) {
        $calendar_events[$date] = [];
    }
    $calendar_events[$date][] = $event;
}

// Get the student's name
$stmt = $conn->prepare("SELECT name FROM student WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
$student_name = $student ? $student['name'] : $_SESSION['student_username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Planning - Student Learning Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/student/planning_style.css">
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <a href="../student_dashboard.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h1><i class="fas fa-calendar-alt"></i> My Planning</h1>
            <p class="subtitle">Organize your schedule and track important dates</p>
            <div class="user-status">
                <i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($student_name); ?>
                <span class="status-dot"></span>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            <button class="close-alert"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            <button class="close-alert"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <!-- Left column - Add/Edit Event Form -->
            <section class="section-header">
                <h3>
                    <i class="fas fa-calendar-plus"></i> 
                    <?php echo isset($edit_event) ? 'Edit Event' : 'Add New Event'; ?>
                </h3>
                
                <form method="post">
                    <?php if (isset($edit_event)): ?>
                        <input type="hidden" name="event_id" value="<?php echo $edit_event['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="event_title"><i class="fas fa-heading"></i> Title</label>
                        <input type="text" id="event_title" name="event_title" class="form-control" placeholder="Event title" required
                            value="<?php echo isset($edit_event) ? htmlspecialchars($edit_event['event_title']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="event_date"><i class="fas fa-calendar"></i> Date</label>
                        <input type="date" id="event_date" name="event_date" class="form-control" required
                            value="<?php echo isset($edit_event) ? $edit_event['event_date'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="event_time"><i class="fas fa-clock"></i> Time</label>
                        <input type="time" id="event_time" name="event_time" class="form-control" required
                            value="<?php echo isset($edit_event) ? $edit_event['event_time'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="event_type"><i class="fas fa-tag"></i> Type</label>
                        <select id="event_type" name="event_type" class="form-control" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="class" <?php echo (isset($edit_event) && $edit_event['event_type'] == 'class') ? 'selected' : ''; ?>>
                                Class
                            </option>
                            <option value="assignment" <?php echo (isset($edit_event) && $edit_event['event_type'] == 'assignment') ? 'selected' : ''; ?>>
                                Assignment
                            </option>
                            <option value="exam" <?php echo (isset($edit_event) && $edit_event['event_type'] == 'exam') ? 'selected' : ''; ?>>
                                Exam
                            </option>
                            <option value="meeting" <?php echo (isset($edit_event) && $edit_event['event_type'] == 'meeting') ? 'selected' : ''; ?>>
                                Meeting
                            </option>
                            <option value="personal" <?php echo (isset($edit_event) && $edit_event['event_type'] == 'personal') ? 'selected' : ''; ?>>
                                Personal
                            </option>
                            <option value="other" <?php echo (isset($edit_event) && $edit_event['event_type'] == 'other') ? 'selected' : ''; ?>>
                                Other
                            </option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_description"><i class="fas fa-align-left"></i> Description</label>
                        <textarea id="event_description" name="event_description" class="form-control" placeholder="Event details"><?php echo isset($edit_event) ? htmlspecialchars($edit_event['event_description']) : ''; ?></textarea>
                    </div>
                    
                    <?php if (isset($edit_event)): ?>
                        <button type="submit" name="update_event" class="update-event-btn">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                        <a href="planning_student.php" class="cancel-btn">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php else: ?>
                        <button type="submit" name="add_event" class="add-event-btn">
                            <i class="fas fa-plus"></i> Add Event
                        </button>
                    <?php endif; ?>
                </form>
            </section>

            <!-- Right column - Calendar View -->
            <section class="calendar-view">
                <h3><i class="fas fa-calendar-week"></i> Calendar View</h3>
                <div class="calendar-container">
                    <?php 
                    $current_month = date('F Y');
                    $current_month_start = date('Y-m-01');
                    $current_month_end = date('Y-m-t');
                    ?>
                    <div class="calendar-header">
                        <h4><?php echo $current_month; ?></h4>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header">Mon</div>
                        <div class="calendar-day-header">Tue</div>
                        <div class="calendar-day-header">Wed</div>
                        <div class="calendar-day-header">Thu</div>
                        <div class="calendar-day-header">Fri</div>
                        <div class="calendar-day-header">Sat</div>
                        <div class="calendar-day-header">Sun</div>
                        
                        <?php
                        $first_day_of_month = date('N', strtotime($current_month_start)) - 1;
                        $days_in_month = date('t', strtotime($current_month_start));
                        
                        // Add empty cells for days before the start of the month
                        for ($i = 0; $i < $first_day_of_month; $i++) {
                            echo '<div class="calendar-day empty"></div>';
                        }
                        
                        // Add cells for each day of the month
                        for ($day = 1; $day <= $days_in_month; $day++) {
                            $date = date('Y-m-d', strtotime("$current_month_start +".($day-1)." days"));
                            $is_today = ($date == $today) ? 'today' : '';
                            $has_events = isset($calendar_events[$date]) ? 'has-events' : '';
                            
                            echo '<div class="calendar-day ' . $is_today . ' ' . $has_events . '">';
                            echo '<div class="calendar-date">' . $day . '</div>';
                            
                            if (isset($calendar_events[$date])) {
                                echo '<div class="calendar-events">';
                                foreach ($calendar_events[$date] as $event) {
                                    $event_class = 'event-' . strtolower($event['event_type']);
                                    echo '<div class="calendar-event-dot ' . $event_class . '" title="' . htmlspecialchars($event['event_title']) . ' - ' . htmlspecialchars($event['event_time']) . '"></div>';
                                }
                                echo '</div>';
                            }
                            
                            echo '</div>';
                        }
                        
                        // Add empty cells for days after the end of the month
                        $last_day_of_month = date('N', strtotime($current_month_end));
                        for ($i = $last_day_of_month; $i < 7; $i++) {
                            echo '<div class="calendar-day empty"></div>';
                        }
                        ?>
                    </div>
                </div>
            </section>
        </div>

        <section class="event-list">
            <h3><i class="fas fa-list"></i> My Events</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($events) > 0): ?>
                        <?php foreach ($events as $event): ?>
                            <tr class="<?php echo ($event['event_date'] == $today) ? 'today' : ''; ?>">
                                <td>
                                    <span class="event-type-indicator <?php echo 'event-' . strtolower($event['event_type']); ?>"></span>
                                    <?php echo htmlspecialchars($event['event_title']); ?>
                                </td>
                                <td>
                                    <i class="fas fa-calendar-day"></i>
                                    <?php echo date('d M Y', strtotime($event['event_date'])); ?>
                                </td>
                                <td>
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('H:i', strtotime($event['event_time'])); ?>
                                </td>
                                <td>
                                    <span class="event-type <?php echo 'event-' . strtolower($event['event_type']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($event['event_type'])); ?>
                                    </span>
                                </td>
                                <td class="event-description">
                                    <?php 
                                    if (!empty($event['event_description'])) {
                                        echo htmlspecialchars($event['event_description']);
                                    } else {
                                        echo '<em class="no-description">No description</em>';
                                    }
                                    ?>
                                </td>
                                <td class="actions">
                                    <a href="?edit=<?php echo $event['id']; ?>" class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn-delete" title="Delete" 
                                      onclick="confirmDelete(<?php echo $event['id']; ?>, '<?php echo htmlspecialchars($event['event_title']); ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-state-content">
                                    <i class="fas fa-calendar-xmark"></i>
                                    <p>No events found. Add your first event using the form above!</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="footer">
            <div class="footer-content">
                <span class="footer-logo">
                    <i class="fas fa-graduation-cap"></i> Student Learning Platform
                </span>
                <p>© <?= date('Y') ?> Student Planning System – All rights reserved</p>
            </div>
        </footer>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h4>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the event "<span id="eventTitle"></span>"?</p>
                    <p class="warning">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button id="cancelDelete" class="btn-secondary">Cancel</button>
                    <a id="confirmDelete" href="#" class="btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/student/planning.js"></script>
</body>
</html>