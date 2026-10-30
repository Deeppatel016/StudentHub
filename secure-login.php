<?php
require_once __DIR__ . '/config.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin-dashboard.php' : 'student-dashboard.php'));
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $password !== '') {
        $stmt = $conn->prepare('SELECT id, name, username, email, password, course, role FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['course'] = $user['course'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();
            $update = $conn->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
            $update->bind_param('i', $_SESSION['user_id']);
            $update->execute();
            header('Location: ' . ($user['role'] === 'admin' ? 'admin-dashboard.php' : 'student-dashboard.php'));
            exit;
        }
    }
    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="style.css"><title>StudentHub - Login</title></head><body><header><h1>StudentHub</h1></header><nav aria-label="Main Navigation"><a href="index.html">Home</a> | <a href="secure-register.php">Register</a> | <a href="secure-login.php">Login</a></nav><main><section><h2>Student Login</h2><?php if ($error !== ''): ?><p role="alert"><?= h($error) ?></p><?php endif; ?><?php if (isset($_GET['timeout'])): ?><p role="alert">Your session expired after five minutes of inactivity. Please log in again.</p><?php endif; ?><?php if (isset($_GET['required'])): ?><p role="alert">Please log in to access that page.</p><?php endif; ?><form method="post" action="secure-login.php"><label for="email">Email:</label><input type="email" id="email" name="email" required><br><br><label for="password">Password:</label><input type="password" id="password" name="password" required><br><br><button type="submit">Login</button></form><p><a href="secure-register.php">Create a student account</a></p></section></main></body></html>
