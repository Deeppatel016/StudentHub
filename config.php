<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost", "root", "", "studenthub");
$conn->set_charset("utf8mb4");
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    session_start();
}
$timeoutSeconds = 300;
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $timeoutSeconds) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: secure-login.php?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}
function require_login(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: secure-login.php?required=1');
        exit;
    }
}
function require_role(string $role): void {
    require_login();
    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" href="style.css"><title>Access Denied</title></head><body><header><h1>StudentHub</h1></header><main><section><h2>Access denied</h2><p>You do not have permission to view this page.</p><a href="index.html">Return home</a></section></main></body></html>';
        exit;
    }
}
function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
