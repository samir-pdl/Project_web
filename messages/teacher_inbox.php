<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
require __DIR__ . '/messages_db.php';

$teacher_id = $_SESSION['user_id'];
$student_id = $_SESSION['user_id'];

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_message'])) {
        $message = trim($_POST['message']);
        if ($message !== '') {
            $stmt = $pdo->prepare('INSERT INTO messages (sender_type, sender_id, receiver_type, receiver_id, message_text, sent_at) VALUES (?, ?, ?, ?, ?, NOW())');
            $stmt->execute(['teacher', $teacher_id, 'student', $student_id, $message]);
            $success = "Message sent!";
        } else {
            $error = "Message cannot be empty.";
        }
    }

    // Handle announcement
    if (isset($_POST['post_announcement'])) {
        $announcement = trim($_POST['announcement']);
        if ($announcement !== '') {
            $stmt = $pdo->prepare('INSERT INTO announcements (teacher_id, content) VALUES (?, ?)');
            $stmt->execute([$teacher_id, $announcement]);
            $success = "Announcement posted!";
        } else {
            $error = "Announcement cannot be empty.";
        }
    }
}

// Fetch messages
$stmt = $pdo->prepare('SELECT * FROM messages WHERE (sender_type = "teacher" AND sender_id = ?) OR (receiver_type = "teacher" AND receiver_id = ?) ORDER BY sent_at DESC');
$stmt->execute([$teacher_id, $teacher_id]);
$messages = $stmt->fetchAll();

// Fetch announcements
$announcements = $pdo->query('SELECT * FROM announcements ORDER BY posted_at DESC')->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Inbox</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        textarea { width: 100%; height: 80px; }
        button { margin-top: 10px; padding: 10px 15px; border: none; background: #007bff; color: white; cursor: pointer; }
        .message, .announcement { margin-bottom: 15px; padding: 10px; background: #eef; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
<div class="container">
    <h1>Teacher Inbox</h1>

    <?php if (!empty($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <form method="POST">
        <label>Send Message to Student:</label>
        <textarea name="message" required></textarea><br>
        <button type="submit" name="send_message">Send Message</button>
    </form>

    <form method="POST">
        <label>Post Announcement:</label>
        <textarea name="announcement" required></textarea><br>
        <button type="submit" name="post_announcement">Post Announcement</button>
    </form>

    <h2>Messages</h2>
    <?php foreach ($messages as $msg): ?>
        <div class="message">
            <strong><?= ucfirst($msg['sender_type']) ?></strong> at <?= $msg['sent_at'] ?><br>
            <?= nl2br(htmlspecialchars($msg['message_text'])) ?>
        </div>
    <?php endforeach; ?>

    <h2>Announcements</h2>
    <?php foreach ($announcements as $ann): ?>
        <div class="announcement">
            <?= nl2br(htmlspecialchars($ann['content'])) ?><br>
            <small>Posted at: <?= $ann['posted_at'] ?></small>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
