<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['course_id']) || !isset($_POST['fee'])) {
    header('Location: course_enroll.php');
    exit;
}

$course_id = $_POST['course_id'];
$fee = $_POST['fee'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mock Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f8fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .payment-card {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .course-icon {
            width: 80px;
            margin-bottom: 15px;
        }

        .payment-logo {
            width: 120px;
            margin-top: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 16px;
        }

        .pay-btn {
            margin-top: 25px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .pay-btn:hover {
            background-color: #218838;
        }

        .home-btn {
            display: inline-block;
            margin-top: 20px;
            color: #555;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid #555;
            padding: 8px 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .home-btn:hover {
            background-color: #555;
            color: white;
        }

        @media (max-width: 480px) {
            .payment-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="payment-card">
        <img src="images/course-icon.png" alt="Course Icon" class="course-icon">
        <h2>Confirm Payment</h2>
        <p>You are about to pay <strong>$<?= htmlspecialchars($fee) ?></strong> for <br> <strong>Course ID:</strong> <?= htmlspecialchars($course_id) ?>.</p>

        <form action="course_confirm.php" method="POST">
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <button type="submit" class="pay-btn">💳 Pay Now</button>
        </form>

        <img src="images/payment-logo.png" alt="Secure Payment" class="payment-logo">
        <p style="font-size: 12px; color: #999;">This is a demo payment page</p>

        <a href="index.php" class="home-btn">← Back to Home Page</a>
    </div>

</body>
</html>
