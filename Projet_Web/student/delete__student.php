<?php
include '../config/connectiondb.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Retrieve student data before deletion
    $stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student) {
        // Insert into deleted_students
        $stmt_insert = $conn->prepare("INSERT INTO deleted_students (original_id, username, name, password) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("isss", $student['id'], $student['username'], $student['name'], $student['password']);
        $stmt_insert->execute();

        // Delete from student table
        $stmt_delete = $conn->prepare("DELETE FROM student WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();

        // ✅ Redirect to restoration page
        header("Location: restore_students.php");
        exit();
    } else {
        echo "Student not found.";
    }
} else {
    echo "ID not specified.";
}
?>
