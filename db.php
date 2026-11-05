<?php 

$host = 'Tjener: b-studentsql-1.usn.no';
$port = '3306';
$dbname = 'alamu3205';
$username = 'alamu3205';
$password = 'bfdealamu3205';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    echo "Database connection successful!";

} catch (PDOException $e) {
    echo "Database connection failed.";
}