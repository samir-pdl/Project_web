<?php
session_start();
require 'db_connection.php';

$user_role = $_SESSION['role'] ?? '';
if ($user_role !== 'teacher') {
    die("Access denied.");
}

$sql = "SELECT u.id, u.name, a.file_name 
        FROM users u
        LEFT JOIN assignments a ON u.id = a.user_id
        WHERE u.role = 'student'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Student Submissions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f9fafb;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #2563eb;
            padding: 20px;
            color: white;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        header img {
            height: 50px;
        }
        h2 {
            margin: 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        tr:hover {
            background-color: #f0f9ff;
        }
        .download-link {
            color: #2563eb;
            font-weight: bold;
            text-decoration: none;
        }
        .download-link:hover {
            text-decoration: underline;
        }
        .home-button {
            margin-top: 40px;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .home-button:hover {
            background-color: #1d4ed8;
        }
        .status-icon {
            font-size: 18px;
        }
    </style>
</head>
<body>

<header>
    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" alt="Teacher Logo">
    <h2>Student Assignment Submissions</h2>
</header>

<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Submitted?</th>
            <th>Download File</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td class="status-icon"><?= $row['file_name'] ? '✅ Yes' : '❌ No' ?></td>
                <td>
                    <?php if ($row['file_name'] && file_exists('uploads/' . $row['file_name'])): ?>
                        <a class="download-link" href="uploads/<?= urlencode($row['file_name']) ?>" download>Download</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div style="text-align:center;">
    <a href="/assessment_database/index_teacher.php" class="home-button">🏠 Back to Home</a>
</div>

</body>
</html>
