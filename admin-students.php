<?php
require_once __DIR__ . '/config.php';
require_role('admin');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $course = trim($_POST['course'] ?? 'B.Tech AIML');
    if ($action === 'delete' && $id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='student'");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $message = $stmt->affected_rows ? 'Student deleted.' : 'Student could not be deleted.';
    } elseif (in_array($action, ['add','edit'], true) && $name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && in_array($course, ['B.Tech AIML','B.Tech CSE','B.Tech IT'], true)) {
        try {
            if ($action === 'add') {
                $password = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
                $username = $email;
                $stmt = $conn->prepare("INSERT INTO users (name,username,email,password,course,role) VALUES (?,?,?,?,?,'student')");
                $stmt->bind_param('sssss', $name, $username, $email, $password, $course);
                $stmt->execute();
                $message = 'Student added. Ask the student to use the password reset process before logging in.';
            } elseif ($id) {
                $username = $email;
                $stmt = $conn->prepare("UPDATE users SET name=?,username=?,email=?,course=? WHERE id=? AND role='student'");
                $stmt->bind_param('ssssi', $name, $username, $email, $course, $id);
                $stmt->execute();
                $message = 'Student updated.';
            }
        } catch (mysqli_sql_exception $e) {
            $message = 'That email is already in use or the record could not be saved.';
        }
    } else {
        $message = 'Enter a valid name, email, course and action.';
    }
}
$search = trim($_GET['search'] ?? '');
$like = '%' . $search . '%';
$stmt = $conn->prepare("SELECT id,name,email,course,created_at FROM users WHERE role='student' AND (name LIKE ? OR email LIKE ? OR course LIKE ?) ORDER BY id DESC");
$stmt->bind_param('sss', $like, $like, $like);
$stmt->execute();
$rows = $stmt->get_result();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="style.css"><title>Manage Students</title></head><body><header><h1>StudentHub</h1><p>Student Management</p></header><nav><a href="admin-dashboard.php">Dashboard</a> | <a href="admin-students.php">Students</a> | <a href="admin-events.php">Events</a> | <a href="logout.php">Logout</a></nav><main><section><h2>Add Student</h2><?php if ($message !== ''): ?><p role="status"><?= h($message) ?></p><?php endif; ?><form method="post"><input type="hidden" name="action" value="add"><label for="name">Full Name</label><input id="name" name="name" required><label for="email">Email</label><input id="email" name="email" type="email" required><label for="course">Course</label><select id="course" name="course"><option>B.Tech AIML</option><option>B.Tech CSE</option><option>B.Tech IT</option></select><button type="submit">Add Student</button></form></section><section><h2>Search Students</h2><form method="get"><label for="search">Name, email or course</label><input id="search" name="search" value="<?= h($search) ?>"><button type="submit">Search</button></form><?php while ($student = $rows->fetch_assoc()): ?><article><h3><?= h($student['name']) ?></h3><p>Email: <?= h($student['email']) ?></p><p>Course: <?= h($student['course']) ?></p><p>Registered: <?= h($student['created_at']) ?></p><form method="post"><input type="hidden" name="action" value="edit"><input type="hidden" name="id" value="<?= (int)$student['id'] ?>"><label>Name</label><input name="name" value="<?= h($student['name']) ?>" required><label>Email</label><input name="email" type="email" value="<?= h($student['email']) ?>" required><label>Course</label><select name="course"><option <?= $student['course']==='B.Tech AIML'?'selected':'' ?>>B.Tech AIML</option><option <?= $student['course']==='B.Tech CSE'?'selected':'' ?>>B.Tech CSE</option><option <?= $student['course']==='B.Tech IT'?'selected':'' ?>>B.Tech IT</option></select><button type="submit">Save Changes</button></form><form method="post" onsubmit="return confirm('Delete this student?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$student['id'] ?>"><button type="submit">Delete Student</button></form></article><?php endwhile; ?></section></main></body></html>
