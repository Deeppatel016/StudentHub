<?php
require_once __DIR__ . '/../config.php';
require_login();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query('SELECT id,name,category,event_date,description FROM events ORDER BY event_date ASC');
    echo json_encode(['success' => true, 'data' => $result->fetch_all(MYSQLI_ASSOC)], JSON_UNESCAPED_UNICODE);
    exit;
}
require_role('admin');
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON body']);
    exit;
}
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $name = trim($input['name'] ?? '');
    $category = trim($input['category'] ?? '');
    $date = trim($input['event_date'] ?? '');
    $description = trim($input['description'] ?? '');
    $validDate = DateTime::createFromFormat('Y-m-d', $date);
    if ($name === '' || !in_array($category, ['Workshop','Seminar','Competition'], true) || !$validDate || $validDate->format('Y-m-d') !== $date) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Invalid event details']);
        exit;
    }
    $stmt = $conn->prepare('INSERT INTO events (name,category,event_date,description) VALUES (?,?,?,?)');
    $stmt->bind_param('ssss', $name, $category, $date, $description);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    exit;
}
if ($method === 'DELETE') {
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Valid id is required']);
        exit;
    }
    $stmt = $conn->prepare('DELETE FROM events WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['success' => $stmt->affected_rows === 1]);
    exit;
}
header('Allow: GET, POST, DELETE');
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
