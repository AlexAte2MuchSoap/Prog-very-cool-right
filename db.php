<?php

$host = 'Tjener: b-studentsql-1.usn.no';
$port = '3306';
$dbname = 'alamu3205';
$username = 'alamu3205';
$password = 'bfdealamu3205';

$conn = new mysqli($host . ':' . $port, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

echo "Database connection successful!";
?>