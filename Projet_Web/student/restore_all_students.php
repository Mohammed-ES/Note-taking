<?php
include '../config/connectiondb.php';

$result = $conn->query("SELECT * FROM deleted_students");

while ($row = $result->fetch_assoc()) {
    // Include id_admin when restoring students
    $stmt = $conn->prepare("INSERT INTO student (username, password, name, id_admin) VALUES (?, ?, ?, ?)");
    $admin_id = 1; // Default admin ID - adjust if needed
    // Using the saved password from deleted_students
    $stmt->bind_param("sssi", $row['username'], $row['password'], $row['name'], $admin_id);
    
    // Check for duplicate username before inserting
    $check = $conn->prepare("SELECT COUNT(*) FROM student WHERE username = ?");
    $check->bind_param("s", $row['username']);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();
    
    if ($count == 0) {
        $stmt->execute();
    }
}

$conn->query("DELETE FROM deleted_students");

header("Location: restore_students.php?all_restored=1");
exit();
?>
