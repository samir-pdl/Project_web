<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

// Check if user is a student
$user_id = $_SESSION['user_id'] ?? 0;
$user_role = $_SESSION['role'] ?? '';

if ($user_role !== 'student') {
    die("Access denied.");
}

$upload_status = "";
$error = "";

// Fetch courses for dropdown
$courses = [];
$result = $conn->query("SELECT id, name FROM courses");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    $error = "Failed to load courses.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate course selection
    $selected_course = intval($_POST['course_id'] ?? 0);
    if ($selected_course <= 0) {
        $error = "Please select a valid course.";
    } elseif (!isset($_FILES["assignment"]) || $_FILES["assignment"]["error"] != UPLOAD_ERR_OK) {
        $error = "Please upload a valid assignment file.";
    } else {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Rename file with timestamp
        $filename = time() . "_" . basename($_FILES["assignment"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["assignment"]["tmp_name"], $target_file)) {
            $stmtDel = $conn->prepare("DELETE FROM assignments WHERE user_id = ? AND course_id = ?");
            $stmtDel->bind_param("ii", $user_id, $selected_course);
            $stmtDel->execute();

            $stmt = $conn->prepare("INSERT INTO assignments (user_id, course_id, file_name) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $user_id, $selected_course, $filename);
            $stmt->execute();

            $upload_status = "✅ Assignment uploaded successfully.";
        } else {
            $error = "❌ Failed to upload assignment.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Assignment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .status {
            margin-bottom: 20px;
            color: green;
            font-weight: bold;
        }
        .error {
            margin-bottom: 20px;
            color: red;
            font-weight: bold;
        }
        input[type="file"], select {
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            padding: 12px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button i {
            margin-right: 8px;
        }
        label {
            font-weight: bold;
        }
        h2 {
            text-align: center;
        }
        .illustration {
            text-align: center;
            margin-bottom: 20px;
        }
        .illustration img {
            max-width: 200px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="illustration">
        <img src="https://cdn-icons-png.flaticon.com/512/1055/1055646.png" alt="Assignment Icon">
    </div>

    <h2><i class="fas fa-upload"></i> Submit Your Assignment</h2>

    <?php if ($upload_status): ?>
        <div class="status"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($upload_status) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="course_id"><i class="fas fa-book"></i> Select Course:</label>
        <select name="course_id" id="course_id" required>
            <option value="">-- Select Course --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course['id']) ?>"
                    <?= (isset($_POST['course_id']) && $_POST['course_id'] == $course['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($course['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="assignment"><i class="fas fa-file-upload"></i> Select Assignment File:</label>
        <input type="file" name="assignment" id="assignment" required>

        <button type="submit"><i class="fas fa-paper-plane"></i> Upload Assignment</button>
    </form>
</div>
</body>
</html>
