<?php
require_once __DIR__ . '/config.php';
require_role('admin');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($action === 'delete' && $id) {
        $stmt = $conn->prepare('DELETE FROM events WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $message = 'Event deleted.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $date = $_POST['event_date'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $validDate = DateTime::createFromFormat('Y-m-d', $date);
        if ($name === '' || strlen($name) > 150 || !in_array($category, ['Workshop','Seminar','Competition'], true) || !$validDate || $validDate->format('Y-m-d') !== $date) {
            $message = 'Enter valid event details.';
        } else {
            if ($action === 'edit' && $id) {
                $stmt = $conn->prepare('UPDATE events SET name=?,category=?,event_date=?,description=? WHERE id=?');
                $stmt->bind_param('ssssi', $name, $category, $date, $description, $id);
                $stmt->execute();
                $message = 'Event updated.';
            } else {
                $stmt = $conn->prepare('INSERT INTO events (name,category,event_date,description) VALUES (?,?,?,?)');
                $stmt->bind_param('ssss', $name, $category, $date, $description);
                $stmt->execute();
                $message = 'Event added.';
            }
        }
    }
}
$rows = $conn->query('SELECT id,name,category,event_date,description FROM events ORDER BY event_date ASC');
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="style.css"><title>Manage Events</title></head><body><header><h1>StudentHub</h1><p>Event Management</p></header><nav><a href="admin-dashboard.php">Dashboard</a> | <a href="admin-students.php">Students</a> | <a href="admin-events.php">Events</a> | <a href="logout.php">Logout</a></nav><main><section><h2>Add Event</h2><?php if ($message !== ''): ?><p role="status"><?= h($message) ?></p><?php endif; ?><form method="post"><input type="hidden" name="action" value="add"><label for="name">Event Name</label><input id="name" name="name" maxlength="150" required><label for="category">Category</label><select id="category" name="category"><option>Workshop</option><option>Seminar</option><option>Competition</option></select><label for="event_date">Date</label><input id="event_date" name="event_date" type="date" required><label for="description">Description</label><textarea id="description" name="description"></textarea><button type="submit">Add Event</button></form></section><section><h2>Current Events</h2><?php while ($event = $rows->fetch_assoc()): ?><article><h3><?= h($event['name']) ?></h3><p>Category: <?= h($event['category']) ?></p><p>Date: <?= h($event['event_date']) ?></p><p><?= h($event['description']) ?></p><form method="post"><input type="hidden" name="action" value="edit"><input type="hidden" name="id" value="<?= (int)$event['id'] ?>"><label>Name</label><input name="name" value="<?= h($event['name']) ?>" required><label>Category</label><select name="category"><option <?= $event['category']==='Workshop'?'selected':'' ?>>Workshop</option><option <?= $event['category']==='Seminar'?'selected':'' ?>>Seminar</option><option <?= $event['category']==='Competition'?'selected':'' ?>>Competition</option></select><label>Date</label><input type="date" name="event_date" value="<?= h($event['event_date']) ?>" required><label>Description</label><textarea name="description"><?= h($event['description']) ?></textarea><button type="submit">Save Changes</button></form><form method="post" onsubmit="return confirm('Delete this event?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$event['id'] ?>"><button type="submit">Delete Event</button></form></article><?php endwhile; ?></section></main></body></html>
