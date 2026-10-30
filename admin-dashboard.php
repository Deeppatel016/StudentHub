<?php
require_once __DIR__ . '/config.php';
require_role('admin');
$students = (int)$conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'")->fetch_assoc()['total'];
$events = (int)$conn->query('SELECT COUNT(*) AS total FROM events')->fetch_assoc()['total'];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="style.css"><title>StudentHub - Admin Dashboard</title></head><body><header><h1>StudentHub Admin</h1><p>Administration Dashboard</p></header><nav><a href="admin-dashboard.php">Dashboard</a> | <a href="admin-students.php">Manage Students</a> | <a href="admin-events.php">Manage Events</a> | <a href="api/students.php">Student API</a> | <a href="logout.php">Logout</a></nav><main><section><h2>Welcome, <?= h($_SESSION['name']) ?></h2><p>Signed in as administrator.</p><article><h3>Registered Students</h3><p><?= $students ?></p></article><article><h3>Events in Database</h3><p><?= $events ?></p></article></section><section><h2>Management</h2><p><a href="admin-students.php">Add, search, edit and delete student records</a></p><p><a href="admin-events.php">Add, edit and delete events</a></p></section></main></body></html>
