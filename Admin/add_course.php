<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!$name) {
        $error = "Course name is required.";
    } else {
        // Optional: Check if course already exists
        $stmt = $conn->prepare("SELECT id FROM courses WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Course already exists.";
        } else {
            $insert_stmt = $conn->prepare("INSERT INTO courses (name, description) VALUES (?, ?)");
            $insert_stmt->bind_param("ss", $name, $description);
            if ($insert_stmt->execute()) {
                header("Location: courses.php");
                exit();
            } else {
                $error = "Failed to add course.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add New Course</title>
</head>
<body>

<h2>Add New Course</h2>

<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="add_course.php">
<label>Course Name:<br><input type="text" name="name" required></label><br><br>
<label>Description:<br><textarea name="description" rows="4" cols="40"></textarea></label><br><br>
<button type="submit">Add Course</button>
</form>

<p><a href="courses.php">Back to courses list</a></p>

</body>
</html>
