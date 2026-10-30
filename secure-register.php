<?php
require_once __DIR__ . '/config.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin-dashboard.php' : 'student-dashboard.php'));
    exit;
}
$error = '';
$success = '';
$name = '';
$email = '';
$course = 'B.Tech AIML';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $course = trim($_POST['course'] ?? 'B.Tech AIML');
    if ($name === '' || !preg_match("/^[\p{L} .'-]{2,100}$/u", $name)) {
        $error = 'Enter a valid full name.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must contain at least 8 characters.';
    } elseif (!in_array($course, ['B.Tech AIML', 'B.Tech CSE', 'B.Tech IT'], true)) {
        $error = 'Select a valid course.';
    } else {
        $username = $email;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare('INSERT INTO users (name, username, email, password, course, role) VALUES (?, ?, ?, ?, ?, \'student\')');
            $stmt->bind_param('sssss', $name, $username, $email, $hash, $course);
            $stmt->execute();
            $success = 'Registration successful. You can now log in.';
            $name = '';
            $email = '';
            $course = 'B.Tech AIML';
        } catch (mysqli_sql_exception $e) {
            $error = $e->getCode() === 1062 ? 'This email is already registered.' : 'Registration could not be completed.';
        }
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="style.css"><title>StudentHub - Registration</title></head><body><header><h1>StudentHub</h1></header><nav aria-label="Main Navigation"><a href="index.html">Home</a> | <a href="secure-login.php">Login</a> | <a href="secure-register.php">Register</a></nav><main><section><h2>Student Registration</h2><?php if ($error !== ''): ?><p role="alert"><?= h($error) ?></p><?php endif; ?><?php if ($success !== ''): ?><p role="status"><?= h($success) ?> <a href="secure-login.php">Login</a></p><?php endif; ?><form method="post" action="secure-register.php"><label for="name">Full Name:</label><input type="text" id="name" name="name" maxlength="100" value="<?= h($name) ?>" required><br><br><label for="email">Email:</label><input type="email" id="email" name="email" maxlength="190" value="<?= h($email) ?>" required><br><br><label for="password">Password:</label><input type="password" id="password" name="password" minlength="8" required><br><br><label for="course">Course:</label><select id="course" name="course"><option <?= $course === 'B.Tech AIML' ? 'selected' : '' ?>>B.Tech AIML</option><option <?= $course === 'B.Tech CSE' ? 'selected' : '' ?>>B.Tech CSE</option><option <?= $course === 'B.Tech IT' ? 'selected' : '' ?>>B.Tech IT</option></select><br><br><button type="submit">Register</button></form></section></main></body></html>
