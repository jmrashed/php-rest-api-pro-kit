<?php
$servername = "db";
$username   = "root";
$password   = "password";
$database   = "test_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully to test_db"; // Removed echo for API context