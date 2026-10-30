<?php
require_once __DIR__ . '/config.php';
require_role('student');
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="style.css"><title>StudentHub - Dashboard</title></head><body><header><h1>Student Dashboard</h1></header><nav aria-label="Dashboard Navigation"><a href="index.html">Home</a> | <a href="student-profile.php">Profile</a> | <a href="Events.html">Events</a> | <a href="FAQ.html">FAQ</a> | <a href="Contact.html">Contact</a> | <a href="logout.php">Logout</a></nav><main><section><h2>Welcome, <?= h($_SESSION['name'] ?? 'Student') ?>!</h2><p>Here you can access your student information.</p></section><section><h2>Quick Information</h2><article><h3>Attendance</h3><p>Current attendance: 85%</p></article><article><h3>Assignments</h3><p>2 assignments are pending.</p></article></section></main></body></html>
