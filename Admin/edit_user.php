<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: users.php");
    exit();
}

$error = '';
// Fetch user data
$stmt = $conn->prepare("SELECT id, name, email, role, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    header("Location: users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (!$name || !$email || !$role) {
        $error = "Please fill all required fields.";
    } else {
        // Check if email belongs to another user
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $email, $id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            $error = "Email already in use by another user.";
        } else {
            $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, phone = ? WHERE id = ?");
            $update_stmt->bind_param("ssssi", $name, $email, $role, $phone, $id);
            if ($update_stmt->execute()) {
                header("Location: users.php");
                exit();
            } else {
                $error = "Failed to update user.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit User</title>
</head>
<body>
<h2>Edit User #<?= htmlspecialchars($user['id']) ?></h2>

<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="edit_user.php?id=<?= $user['id'] ?>">
<label>Name: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required></label><br><br>
<label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></label><br><br>
<label>Role:
    <select name="role" required>
        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
        <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>
</label><br><br>
<label>Phone: <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"></label><br><br>

<button type="submit">Update User</button>
</form>

<p><a href="users.php">Back to users list</a></p>
</body>
</html>
