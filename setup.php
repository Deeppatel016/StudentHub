<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli('localhost', 'root', '');
$conn->set_charset('utf8mb4');
$conn->query('CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$conn->select_db('studenthub');
$conn->query("CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 username VARCHAR(100) NOT NULL UNIQUE,
 email VARCHAR(190) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 course VARCHAR(100) NOT NULL DEFAULT 'B.Tech AIML',
 role ENUM('student','admin') NOT NULL DEFAULT 'student',
 last_login DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$conn->query("CREATE TABLE IF NOT EXISTS events (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 category VARCHAR(60) NOT NULL,
 event_date DATE NOT NULL,
 description TEXT NULL,
 poster VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$conn->query("CREATE TABLE IF NOT EXISTS feedback (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL,
 message TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$adminEmail = 'admin@studenthub.local';
$check = $conn->prepare('SELECT id FROM users WHERE email = ?');
$check->bind_param('s', $adminEmail);
$check->execute();
if (!$check->get_result()->fetch_assoc()) {
    $name = 'StudentHub Admin';
    $username = 'admin';
    $password = password_hash('Admin@123', PASSWORD_DEFAULT);
    $course = 'Administration';
    $role = 'admin';
    $insert = $conn->prepare('INSERT INTO users (name, username, email, password, course, role) VALUES (?, ?, ?, ?, ?, ?)');
    $insert->bind_param('ssssss', $name, $username, $adminEmail, $password, $course, $role);
    $insert->execute();
}
$check->close();
echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" href="style.css"><title>StudentHub Setup</title></head><body><header><h1>StudentHub</h1></header><main><section><h2>Setup completed</h2><p>The StudentHub database and required tables are ready.</p><p>Admin email: <strong>admin@studenthub.local</strong></p><p>Admin password: <strong>Admin@123</strong></p><p>Delete or rename setup.php after the first successful setup.</p><p><a href="secure-login.php">Open login</a></p></section></main></body></html>';
