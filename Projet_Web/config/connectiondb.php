<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "notes";
$port = 3307; // Ajout du port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
