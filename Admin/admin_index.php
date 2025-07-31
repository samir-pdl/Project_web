<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-rO4xXYeH4Z9HiKq3vIz2eK5ckzmcZXAkc0vtrHzkG+Yq1uklLq3v5vJ1zHzcJ4e0R2kZ2xvU0Im/8sm5NSxXaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style> 
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1e3a8a;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        .logout-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #dc2626;
        }

        .dashboard {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 40px;
            margin: 50px auto;
            max-width: 1000px;
        }

        .card {
            background-color: white;
            width: 220px;
            height: 150px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card i {
            font-size: 36px;
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            background-color: #e0f2fe;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #888;
        }
    </style>
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
    <a href="/assessment_database/logout.php" class="logout-btn">Logout</a>
</header>

<div class="dashboard">
    <a href="users.php" class="card">
        <i class="fas fa-users"></i>
        Users
    </a>
    <a href="system_settings.php" class="card">
        <i class="fas fa-cogs"></i>
        System Settings
    </a>
    <a href="courses.php" class="card">
        <i class="fas fa-book"></i>
        Courses
    </a>
    <a href="/assessment_database/admin_quiz.php" class="card">
        <i class="fas fa-question-circle"></i>
        Quiz
    </a>
</div>

<footer>
    &copy; <?= date("Y") ?> Admin Panel
</footer>

</body>
</html>
