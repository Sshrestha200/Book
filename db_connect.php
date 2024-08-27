<?php
$servername = "localhost";  // or "127.0.0.1"
$username = "root";         // Default user for XAMPP/MAMP
$password = "root";             // Default password is empty for XAMPP/MAMP
$dbname = "booknook";      // The database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
