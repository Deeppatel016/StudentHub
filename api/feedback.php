<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) $input = $_POST;
$name = trim($input['name'] ?? '');
$email = strtolower(trim($input['email'] ?? ''));
$message = trim($input['message'] ?? '');
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '' || strlen($message) > 5000) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Enter a name, valid email and message']);
    exit;
}
$stmt = $conn->prepare('INSERT INTO feedback (name,email,message) VALUES (?,?,?)');
$stmt->bind_param('sss', $name, $email, $message);
$stmt->execute();
http_response_code(201);
echo json_encode(['success' => true, 'message' => 'Feedback submitted']);
