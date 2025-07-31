<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Update course fee if posted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_fee'])) {
    $course_id = intval($_POST['course_id']);
    $new_fee = floatval($_POST['new_fee']);
    $update = $conn->prepare("UPDATE courses SET fee = ? WHERE id = ?");
    $update->bind_param("di", $new_fee, $course_id);
    $update->execute();
}

// Get payments and enrollment info
$sql = "SELECT e.id AS enrollment_id, u.name AS student_name, c.name AS course_name, c.fee, e.payment_status, e.payment_amount
        FROM enrollments e
        JOIN users u ON e.user_id = u.id
        JOIN courses c ON e.course_id = c.id
        ORDER BY e.id DESC";
$result = $conn->query($sql);

// Get courses for editing fees
$courses = $conn->query("SELECT id, name, fee FROM courses ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Payment Details</title>
    <style>
        table {border-collapse: collapse; width: 100%; margin-bottom: 40px;}
        th, td {border: 1px solid #ddd; padding: 8px;}
        th {background: #34495e; color: white;}
        form {display: inline;}
        input[type="number"] {width: 80px;}
        .button {padding: 5px 10px; background: #2980b9; color: white; border: none; border-radius: 4px;}
    </style>
</head>
<body>

<h2>📄 Enrollment Payment Details</h2>
<table>
    <thead>
    <tr>
        <th>Enrollment ID</th>
        <th>Student</th>
        <th>Course</th>
        <th>Fee</th>
        <th>Paid Amount</th>
        <th>Payment Status</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['enrollment_id']) ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['course_name']) ?></td>
            <td>$<?= htmlspecialchars(number_format($row['fee'] ?? 0, 2)) ?></td>
            <td>$<?= htmlspecialchars(number_format($row['payment_amount'] ?? 0, 2)) ?></td>
            <td><?= htmlspecialchars($row['payment_status']) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<h2>🛠 Edit Course Fees</h2>
<table>
    <thead>
    <tr>
        <th>Course</th>
        <th>Current Fee</th>
        <th>Update Fee</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($course = $courses->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($course['name']) ?></td>
            <td>$<?= htmlspecialchars(number_format($course['fee'] ?? 0, 2)) ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                    <input type="number" step="0.01" name="new_fee" value="<?= $course['fee'] ?? 0 ?>" required>
                    <button type="submit" name="update_fee" class="button">Update</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
