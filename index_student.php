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
<title>Dashboard</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
    background-size: cover;
    overflow-x: hidden;
    color: #333;
}

/* Glassmorphism effect */
.glass {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.3);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

/* Sidebar */
.sidebar {
    height: 100%;
    width: 200px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: rgba(34, 34, 34, 0.85);
    overflow-x: hidden;
    transition: left 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 20px;
    z-index: 100;
}

.sidebar.hidden {
    left: -200px;
}

.sidebar .logo {
    width: 120px;
    margin-bottom: 30px;
}

.sidebar a {
    padding: 12px 20px 12px 100px;
    text-decoration: none;
    font-size: 18px;
    color: white;
    display: block;
    width: 100%;
    text-align: left;
    transition: background 0.3s;
}

.sidebar a:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Top nav */
.topnav {
    height: 60px;
    background-color: rgba(0, 0, 0, 0.65);
    color: white;
    display: flex;
    align-items: center;
    padding: 0 20px;
    position: fixed;
    top: 0;
    left: 200px;
    right: 0;
    transition: left 0.3s ease;
    z-index: 1000;
}

.topnav.full {
    left: 0;
}

.topnav .hamburger {
    font-size: 24px;
    margin-right: 20px;
    cursor: pointer;
    user-select: none;
}

.topnav .welcome {
    font-size: 18px;
}

/* Container */
.container {
    margin-top: 60px;
    margin-left: 200px;
    padding: 20px;
    transition: margin-left 0.3s ease;
    box-sizing: border-box;
}

.container.full {
    margin-left: 0;
}

/* Content area holding main and todo */
.content-area {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-start;
    box-sizing: border-box;
}

/* To Do List */
.todo {
    flex: 1 1 300px;
    max-width: 350px;
    padding: 15px;
    border-radius: 8px;
    min-width: 250px;
    box-sizing: border-box;
}

.todo h3 {
    margin-top: 0;
    text-align: center;
}

.todo ul {
    list-style: none;
    padding-left: 0;
}

.todo ul li {
    margin: 10px 0;
    padding: 10px;
    background-color: rgba(255, 255, 255, 0.4);
    border-radius: 4px;
}

.todo ul li a {
    text-decoration: none;
    color: #000;
}

.todo ul li a:hover {
    text-decoration: underline;
}

/* Main Content */
.main-content {
    flex: 3 1 600px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    min-width: 300px;
    box-sizing: border-box;
}

.course-box {
    width: 250px;
    height: 300px;
    text-align: center;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.course-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.icon-box {
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px 12px 0 0;
    transition: background 0.3s ease;
}

.icon-box svg {
    width: 80px;
    height: 80px;
    fill: #fff;
}

.course-box a {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
}

.course-box a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <img src="logo.png" alt="Logo" class="logo">
    <a href="#">Dashboard</a>
    <a href="/assessment_database/Enrollment/course_enroll.php">Enrollment</a>
    <a href="/assessment_database/Assessment/upload_assignment.php">ASSESSMENTS</a>
     <a href="/Assessment_Database/messages/student_announcements.php">Announcements</a>
      <a href="/Assessment_Database/messages/student_inbox.php">Messages</a>
    <a href="login.php">Logout</a>
      <a href="quiz.php">Take a quiz</a>
</div>

<!-- Top Navigation -->
<div class="topnav" id="topnav">
    <span class="hamburger" onclick="toggleSidebar()">&#9776;</span>
    <div class="welcome"><strong>Welcome to Dashboard</strong></div>
</div>

<!-- Main Layout -->
<div class="container" id="main-container">
    <div class="content-area">
        <!-- Main Content (Courses) -->
        <div class="main-content">
            <div class="course-box glass">
                <div class="icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                        <path d="M8 12v40c0 2.2 1.8 4 4 4h40v-4H12V12H8zm48-4H20c-2.2 0-4 1.8-4 4v36c0 2.2 1.8 4 4 4h36V8zm-4 32H24V12h28v28z"/>
                    </svg>
                </div>
                <a href="course1.php">Course 1</a>
            </div>

            <div class="course-box glass">
                <div class="icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                        <path d="M24 20l-12 12 12 12 4-4-8-8 8-8-4-4zm16 0l-4 4 8 8-8 8 4 4 12-12-12-12z"/>
                    </svg>
                </div>
                <a href="#">Course 2</a>
            </div>
        </div>

        <!-- To Do List -->
        <div class="todo glass">
            <h3>To Do List</h3>
            <ul>
                <li><a href="/assessment_database/Assessment/upload_assignment.php">Assessment 1: Database Design</a></li>
                <li><a href="/assessment_database/Assessment/upload_assignment.php">Assessment 2: Web Interface</a></li>
            </ul>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const container = document.getElementById('main-container');
    const topnav = document.getElementById('topnav');

    sidebar.classList.toggle('hidden');
    container.classList.toggle('full');
    topnav.classList.toggle('full');
}
</script>

</body>
</html>
