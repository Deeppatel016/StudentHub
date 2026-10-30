<?php
require_once __DIR__ . '/config.php';
require_role('student');
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="style.css"><title>StudentHub - Profile</title></head><body><header><h1>Student Profile</h1></header><nav aria-label="Main Navigation"><a href="index.html">Home</a> | <a href="student-dashboard.php">Dashboard</a> | <a href="student-profile.php">Profile</a> | <a href="Contact.html">Contact</a> | <a href="logout.php">Logout</a></nav><main><section><h2>Personal Information</h2><p><strong>Name:</strong> <?= h($_SESSION['name']) ?></p><p><strong>Email:</strong> <?= h($_SESSION['email']) ?></p><p><strong>Course:</strong> <?= h($_SESSION['course']) ?></p><p><strong>Semester:</strong> 3rd Semester</p></section></main></body></html>
