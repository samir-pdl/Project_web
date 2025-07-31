<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome - Easy to learn</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom right, #f0f4f8, #d9e2ec);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      text-align: center;
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
    }

    .logo {
      width: 100px;
      height: 100px;
      margin-bottom: 20px;
      background-color: #e1e5ea;
      border-radius: 50%;
      display: inline-block;
      background-image: url('logo.png'); /* Replace with your logo path */
      background-size: cover;
      background-position: center;
    }

    h1 {
      margin: 10px 0;
      font-size: 32px;
      color: #333;
    }

    p.slogan {
      font-size: 16px;
      color: #666;
      margin-bottom: 30px;
    }

    .buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .btn {
      padding: 12px 24px;
      font-size: 16px;
      border-radius: 8px;
      text-decoration: none;
      color: white;
      background-color: #0077cc;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #005fa3;
    }

    @media (max-width: 480px) {
      .buttons {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo"></div>
    <h1>Easy to learn</h1>
    <p class="slogan">Learn with us for better future</p>
    <div class="buttons">
      <a href="login.php" class="btn">Login</a>
      <a href="signin.php" class="btn">Register</a>
    </div>
  </div>
</body>
</html>
