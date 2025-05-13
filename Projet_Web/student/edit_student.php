<?php
include '../config/connectiondb.php';

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch student info
    $stmt = $conn->prepare("SELECT username, password, name FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo "Student not found.";
        exit();
    }
} else {
    echo "No student ID provided.";
    exit();
}

// Update student info if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    $update = $conn->prepare("UPDATE student SET username = ?, password = ?, name = ? WHERE id = ?");
    $update->bind_param("sssi", $username, $password, $name, $id);

    if ($update->execute()) {
        header("Location: student.php");
        exit();
    } else {
        echo "Error updating student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/edit_student_style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-user-edit"></i> Edit Student</h1>
            <a href="student.php" class="home-button">
                <i class="fas fa-home"></i> Back
            </a>
        </div>
        
        <div class="content-card">
            <form method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($student['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" class="form-control" value="<?php echo htmlspecialchars($student['password']); ?>" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <script src="../assets/js/student/edit_student.js"></script>
</body>
</html>