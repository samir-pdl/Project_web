<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (!$name || !$email || !$password || !$role) {
        $error = "Please fill all required fields.";
    } else {
        // Check email unique
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $phone);
            if ($insert_stmt->execute()) {
                header("Location: users.php");
                exit();
            } else {
                $error = "Failed to add user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add User</title>
</head>
<body>
<h2>Add New User</h2>

<?php if ($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="add_user.php">
<label>Name: <input type="text" name="name" required></label><br><br>
<label>Email: <input type="email" name="email" required></label><br><br>
<label>Password: <input type="password" name="password" required></label><br><br>
<label>Role:
    <select name="role" required>
        <option value="">Select role</option>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
        <option value="admin">Admin</option>
    </select>
</label><br><br>
<label>Phone: <input type="text" name="phone"></label><br><br>
<button type="submit">Add User</button>
</form>

<p><a href="users.php">Back to users list</a></p>
</body>
</html>
