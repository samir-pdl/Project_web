
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
require __DIR__ . '/messages_db.php';

$student_id = 1;
$teacher_id = 1;

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        $stmt = $pdo->prepare('INSERT INTO messages (sender_type, sender_id, receiver_type, receiver_id, message_text, sent_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute(['student', $student_id, 'teacher', $teacher_id, $message]);
        $success = "Message sent!";
    } else {
        $error = "Message cannot be empty.";
    }
}

// Fetch messages
$stmt = $pdo->prepare('SELECT * FROM messages WHERE (sender_type = "student" AND sender_id = ?) OR (receiver_type = "student" AND receiver_id = ?) ORDER BY sent_at DESC');
$stmt->execute([$student_id, $student_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Inbox</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        textarea { width: 100%; height: 80px; }
        button { margin-top: 10px; padding: 10px 15px; border: none; background: #28a745; color: white; cursor: pointer; }
        .message { margin-bottom: 15px; padding: 10px; background: #e0f7fa; border-left: 4px solid #28a745; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
<div class="container">
    <h1>Student Inbox</h1>

    <?php if (!empty($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <form method="POST">
        <label>Send Message to Teacher:</label>
        <textarea name="message" required></textarea><br>
        <button type="submit">Send Message</button>
    </form>

    <h2>Messages</h2>
    <?php foreach ($messages as $msg): ?>
        <div class="message">
            <strong><?= ucfirst($msg['sender_type']) ?></strong> at <?= $msg['sent_at'] ?><br>
            <?= nl2br(htmlspecialchars($msg['message_text'])) ?>
        </div>
    <?php endforeach; ?>

    <a href="student_announcements.php">📢 View Announcements</a>
</div>
</body>
</html>
