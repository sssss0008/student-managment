<?php
$host = "localhost";
$user = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "student_system";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
