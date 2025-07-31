<?php
session_start();
require 'course_db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];

$sql = "SELECT a.date, a.status, c.name AS course_name 
        FROM attendance a 
        JOIN courses c ON a.course_id = c.id 
        WHERE a.student_id = ?
        ORDER BY a.date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Attendance</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #eef2f3, #8e9eab);
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }

        table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 16px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease-in-out;
        }

        .status-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .present img {
            width: 24px;
            height: 24px;
        }

        .absent img {
            width: 24px;
            height: 24px;
        }

        .present {
            color: #2e7d32;
            font-weight: bold;
        }

        .absent {
            color: #c62828;
            font-weight: bold;
        }

        p {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Your Attendance Record</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Course</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['course_name']) ?></td>
                <td class="<?= strtolower($row['status']) ?>">
                    <div class="status-icon">
                        <?php if (strtolower($row['status']) === 'present'): ?>
                            <img src="images/icon-present.png" alt="Present">
                        <?php else: ?>
                            <img src="images/icon-absent.png" alt="Absent">
                        <?php endif; ?>
                        <?= htmlspecialchars($row['status']) ?>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No attendance records found.</p>
    <?php endif; ?>
</body>
</html>
