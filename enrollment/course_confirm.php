<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'course_db.php';

// Check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['course_id'])) {
    header("Location: course_enroll.php");
    exit;
}

$course_id = $_POST['course_id'];
$user_id = $_SESSION['user_id'];

// Prevent duplicate enrollments
$sql_check = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
$stmt_check = $conn->prepare($sql_check);
if (!$stmt_check) {
    die("Prepare failed (check): (" . $conn->errno . ") " . $conn->error);
}
$stmt_check->bind_param("ii", $user_id, $course_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows == 0) {
    // Not enrolled yet — insert enrollment
    $sql_insert = "INSERT INTO enrollments (user_id, course_id, payment_amount, payment_status) VALUES (?, ?, 0, 'pending')";
    $stmt_insert = $conn->prepare($sql_insert);
    if (!$stmt_insert) {
        $stmt_check->close();
        $conn->close();
        die("Prepare failed (insert): (" . $conn->errno . ") " . $conn->error);
    }
    $stmt_insert->bind_param("ii", $user_id, $course_id);
    if (!$stmt_insert->execute()) {
        $stmt_check->close();
        $stmt_insert->close();
        $conn->close();
        die("Insert failed: " . $stmt_insert->error);
    }
}

// Get course fee
$sql_fee = "SELECT fee FROM courses WHERE id = ?";
$stmt_fee = $conn->prepare($sql_fee);
if (!$stmt_fee) {
    if (isset($stmt_insert)) $stmt_insert->close();
    $stmt_check->close();
    $conn->close();
    die("Prepare failed (fee): (" . $conn->errno . ") " . $conn->error);
}
$stmt_fee->bind_param("i", $course_id);
$stmt_fee->execute();
$result_fee = $stmt_fee->get_result();

if ($result_fee->num_rows > 0) {
    $row_fee = $result_fee->fetch_assoc();
    $fee = $row_fee['fee'];
} else {
    // Cleanup
    $stmt_check->close();
    if (isset($stmt_insert)) $stmt_insert->close();
    $stmt_fee->close();
    $conn->close();
    die("Course not found.");
}

// Update payment info
$sql_update = "UPDATE enrollments SET payment_amount = ?, payment_status = ? WHERE user_id = ? AND course_id = ?";
$stmt_update = $conn->prepare($sql_update);
if (!$stmt_update) {
    // Cleanup
    $stmt_check->close();
    if (isset($stmt_insert)) $stmt_insert->close();
    $stmt_fee->close();
    $conn->close();
    die("Prepare failed (update): (" . $conn->errno . ") " . $conn->error);
}

$payment_status = "completed";
$stmt_update->bind_param("dsii", $fee, $payment_status, $user_id, $course_id);

if ($stmt_update->execute()) {
    // Success — clean up and redirect
    $stmt_check->close();
    if (isset($stmt_insert)) $stmt_insert->close();
    $stmt_fee->close();
    $stmt_update->close();
    $conn->close();
    header("Location: course_enroll.php?status=success");
    exit;
} else {
    // Log error
    error_log("Error updating payment: " . $stmt_update->error);

    // Cleanup
    $stmt_check->close();
    if (isset($stmt_insert)) $stmt_insert->close();
    $stmt_fee->close();
    $stmt_update->close();
    $conn->close();

    // Redirect with error
    header("Location: course_enroll.php?status=error");
    exit;
}
?>
