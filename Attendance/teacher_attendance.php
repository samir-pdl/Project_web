<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'course_db.php';

// Fetch all courses
$courses = $conn->query("SELECT * FROM courses");

$attendance_updated = false;

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id']) && isset($_POST['date'])) {
    $course_id = $_POST['course_id'];
    $date = $_POST['date'];

    foreach ($_POST['attendance'] as $student_id => $status) {
        $stmt = $conn->prepare("REPLACE INTO attendance (student_id, course_id, date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $course_id, $date, $status);
        $stmt->execute();
    }
    $attendance_updated = true;
}

// Fetch enrolled students
$students = [];
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $stmt = $conn->prepare("SELECT u.id, u.name FROM users u 
                            JOIN enrollments e ON u.id = e.user_id 
                            WHERE e.course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $students = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Attendance Panel</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9f1ff);
            margin: 0;
            padding-bottom: 100px;
        }
        .logo {
            display: block;
            margin: 20px auto;
            height: 80px;
        }
        h2 {
            text-align: center;
            color: #1f3a93;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
            color: #333;
        }
        button, input[type="submit"] {
            background-color: #1f3a93;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #163b6d;
        }
        select, input[type="date"] {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin: 10px;
        }
        .centered {
            text-align: center;
            margin-top: 20px;
        }
        .home-button {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            margin-bottom: 250px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
            z-index: 9999;
        }
        .home-button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

    <h2>Attendance Management</h2>

    <?php if ($attendance_updated): ?>
        <p class="centered" style="color:green;">✅ Attendance successfully updated!</p>
    <?php endif; ?>

    <form method="GET" class="centered">
        <label>Select Course:</label>
        <select name="course_id">
            <?php while ($row = $courses->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= (isset($_GET['course_id']) && $_GET['course_id'] == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Load Students</button>
    </form>

    <?php if ($students && $students->num_rows > 0): ?>
        <form method="POST">
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <div class="centered">
                <label>Select Date:</label>
                <input type="date" name="date" required>
            </div>

            <table>
                <tr>
                    <th>Student Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                </tr>
                <?php while ($student = $students->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><input type="radio" name="attendance[<?= $student['id'] ?>]" value="Present" required></td>
                    <td><input type="radio" name="attendance[<?= $student['id'] ?>]" value="Absent"></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <div class="centered">
                <input type="submit" value="Submit Attendance">
            </div>
        </form>
    <?php elseif (isset($_GET['course_id'])): ?>
        <p class="centered" style="color:gray;">No students enrolled in this course.</p>
    <?php endif; ?>

    <!-- Home Button -->
    <a href="/assessment_database/index_teacher.php" class="home-button">🏠 Home</a>

</body>
</html>
