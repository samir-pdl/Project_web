<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
require __DIR__ . '/messages_db.php';

// Fetch announcements
$announcements = $pdo->query('SELECT * FROM announcements ORDER BY posted_at DESC')->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Announcements</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        .announcement {
            margin-bottom: 15px;
            padding: 10px;
            background: #fff3cd;
            border-left: 5px solid #ffc107;
        }
        h1 { color: #333; }
        small { color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teacher Announcements</h1>

        <?php if (empty($announcements)): ?>
            <p>No announcements yet.</p>
        <?php else: ?>
            <?php foreach ($announcements as $ann): ?>
                <div class="announcement">
                    <?= nl2br(htmlspecialchars($ann['content'])) ?><br>
                    <small>Posted at: <?= $ann['posted_at'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
