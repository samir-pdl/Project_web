<?php
session_start();
require 'course_db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Available courses
$sql = "SELECT * FROM courses WHERE id NOT IN (SELECT course_id FROM enrollments WHERE user_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$available_courses = $stmt->get_result();

// Enrolled courses
$sql2 = "SELECT c.* FROM courses c JOIN enrollments e ON c.id = e.course_id WHERE e.user_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$enrolled_courses = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Enrollment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: #f7f9fc;
            padding: 30px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .course-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .course-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 300px;
            padding: 20px;
            transition: transform 0.3s;
        }

        .course-card:hover {
            transform: scale(1.03);
        }

        .course-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .course-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .course-fee {
            color: #555;
            margin-bottom: 15px;
        }

        .enroll-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .enroll-button:hover {
            background-color: #388e3c;
        }

        .enrolled-section {
            margin-top: 60px;
            text-align: center;
        }

        .enrolled-list {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .enrolled-list li {
            font-size: 16px;
            padding: 8px 0;
            color: #444;
        }

        .success {
            text-align: center;
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            margin: 20px auto;
            width: fit-content;
            animation: fadeOut 5s forwards;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; display: none; }
        }

        @media (max-width: 768px) {
            .course-card {
                width: 90%;
            }
        }
        .home-btn {
    display: inline-block;
    margin: 40px auto 0;
    margin-left: 600px;
    padding: 10px 20px;
    background-color: #2196F3;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    text-align: center;
    transition: background-color 0.3s ease;
}

.home-btn:hover {
    background-color: #0b7dda;
}

    </style>
</head>
<body>

    <h2>Available Courses for Enrollment</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="success">✅ Enrollment successful!</div>
    <?php endif; ?>

    <div class="course-section">
        <?php if ($available_courses->num_rows > 0): ?>
            <?php while ($row = $available_courses->fetch_assoc()): ?>
                <div class="course-card">
                    <img src="images/course-icon.png" alt="Course Icon" class="course-icon">
                    <div class="course-title"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="course-fee">Fee: $<?= $row['fee'] ?></div>
                    <form action="course_payment.php" method="POST">
                        <input type="hidden" name="course_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="fee" value="<?= $row['fee'] ?>">
                        <button type="submit" class="enroll-button">Enroll</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">You have enrolled in all available courses.</p>
        <?php endif; ?>
    </div>

    <div class="enrolled-section">
        <h2>Your Enrolled Courses</h2>
        <?php if ($enrolled_courses->num_rows > 0): ?>
            <ul class="enrolled-list">
                <?php while ($row = $enrolled_courses->fetch_assoc()): ?>
                    <li>📘 <?= htmlspecialchars($row['name']) ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>You haven't enrolled in any courses yet.</p>
        <?php endif; ?>
    </div>
    <a href="/assessment_database/index_student.php" class="home-btn">← Back to Home Page</a>

</body>
</html>
