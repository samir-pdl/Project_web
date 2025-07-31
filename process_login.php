<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

// Prepare statement to get user info by email and role
$sql = "SELECT * FROM users WHERE email = ? AND role = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        // Correct password — create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        

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
        echo "Incorrect password.";
    }
} else {
    echo "No user found with that email and role.";
}

$conn->close();
?>
