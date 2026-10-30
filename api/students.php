<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $result = $conn->query("SELECT id,name,email,course,created_at FROM users WHERE role='student' ORDER BY id DESC");
    echo json_encode(['success' => true, 'data' => $result->fetch_all(MYSQLI_ASSOC)], JSON_UNESCAPED_UNICODE);
    exit;
}
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON body']);
    exit;
}
if ($method === 'POST') {
    $name = trim($input['name'] ?? '');
    $email = strtolower(trim($input['email'] ?? ''));
    $course = trim($input['course'] ?? 'B.Tech AIML');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($course, ['B.Tech AIML','B.Tech CSE','B.Tech IT'], true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid student details']);
        exit;
    }
    try {
        $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        $username = $email;
        $stmt = $conn->prepare("INSERT INTO users (name,username,email,password,course,role) VALUES (?,?,?,?,?,'student')");
        $stmt->bind_param('sssss', $name, $username, $email, $password, $course);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } catch (mysqli_sql_exception $e) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
    }
    exit;
}
if ($method === 'DELETE') {
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Valid id is required']);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='student'");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['success' => $stmt->affected_rows === 1]);
    exit;
}
header('Allow: GET, POST, DELETE');
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
