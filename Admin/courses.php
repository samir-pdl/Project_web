<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $del_stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $del_stmt->bind_param("i", $delete_id);
    $del_stmt->execute();
    header("Location: courses.php");
    exit();
}

// Fetch courses
$result = $conn->query("SELECT id, name, description FROM courses ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Courses Management</title>
<style>
table {border-collapse: collapse; width: 100%;}
th, td {border: 1px solid #ddd; padding: 8px;}
th {background: #27ae60; color: white;}
a.button {
  text-decoration: none;
  padding: 5px 10px;
  background: #27ae60;
  color: white;
  border-radius: 4px;
}
a.button.delete {background: #c0392b;}
</style>
</head>
<body>

<h2>Courses Management (Admin)</h2>

<a href="add_course.php" class="button">Add New Course</a>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Course Name</th>
    <th>Description</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while ($course = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($course['id']) ?></td>
    <td><?= htmlspecialchars($course['name']) ?></td>
<td><?php echo htmlspecialchars($course['description'] ?? ''); ?></td>

    <td>
        <a href="courses.php?delete_id=<?= $course['id'] ?>" onclick="return confirm('Delete this course?');" class="button delete">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
