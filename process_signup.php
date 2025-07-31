<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

$name = $_POST['name'] ?? ''; // Make sure your form sends this
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];
$phone = $_POST['phone'] ?? ''; // Optional

// Check if user already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "User already exists with this email.";
    exit();
}

// Insert new user
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$insert_sql = "INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $phone);

if ($insert_stmt->execute()) {
    $_SESSION['user_id'] = $insert_stmt->insert_id;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;

    // Redirect based on role
    if ($role === 'student') {
        header("Location: index_student.php");
    } elseif ($role === 'teacher') {
        header("Location: index_teacher.php");
    } elseif ($role === 'admin') {
        header("Location: /assessment_database/admin/admin_index.php");
    } else {
        echo "Unknown role.";
    }
    exit();
} else {
    echo "Failed to register user.";
}

$conn->close();
?>
