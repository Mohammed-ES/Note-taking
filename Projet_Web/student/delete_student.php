<?php
include '../config/connectiondb.php'; // path to the connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. Retrieve the student
    $stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        // 2. Insert into deleted_students
        $insert = $conn->prepare("INSERT INTO deleted_students (original_id, username, name, password) VALUES (?, ?, ?, ?)");
        $insert->bind_param("isss", $row['id'], $row['username'], $row['name'], $row['password']);
        $insert->execute();

        // 3. Delete from the original table
        $delete = $conn->prepare("DELETE FROM student WHERE id = ?");
        $delete->bind_param("i", $id);
        $delete->execute();

        // 4. Redirect
        header("Location: student.php");
        exit();
    } else {
        echo "Student not found.";
    }
} else {
    echo "Invalid ID.";
}
?>
