
<?php
session_start();
include 'db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Check if logged in and role is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle deletion if requested
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $del_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $del_stmt->bind_param("i", $delete_id);
    $del_stmt->execute();
    header("Location: users.php");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT id, name, email, role, phone FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Users Management</title>
<style>
table {border-collapse: collapse; width: 100%;}
th, td {border: 1px solid #ddd; padding: 8px;}
th {background: #2980b9; color: white;}
a.button {
  text-decoration: none;
  padding: 5px 10px;
  background: #2980b9;
  color: white;
  border-radius: 4px;
}
a.button.delete {background: #c0392b;}
</style>
</head>
<body>

<h2>Users Management (Admin)</h2>

<a href="add_user.php" class="button">Add New User</a>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Phone</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while ($user = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($user['id']) ?></td>
    <td><?= htmlspecialchars($user['name']) ?></td>
    <td><?= htmlspecialchars($user['email']) ?></td>
    <td><?= htmlspecialchars($user['role']) ?></td>
    <td><?= htmlspecialchars($user['phone']) ?></td>
    <td>
        <a href="edit_user.php?id=<?= $user['id'] ?>" class="button">Edit</a>
        <a href="users.php?delete_id=<?= $user['id'] ?>" onclick="return confirm('Delete this user?');" class="button delete">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
