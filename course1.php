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
<title>Course Dashboard</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: url('https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
}

/* Top Navigation */
.topnav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: rgba(44, 62, 80, 0.95);
    color: white;
    padding: 10px 20px;
    backdrop-filter: blur(3px);
}

.topnav .title {
    font-size: 24px;
    font-weight: bold;
}

.topnav .nav-links a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.topnav .nav-links a:hover {
    text-decoration: underline;
    color: #1abc9c;
}

/* Sidebar */
.sidebar {
    width: 200px;
    height: 100vh;
    background-color: rgba(52, 73, 94, 0.95);
    padding-top: 20px;
    position: fixed;
    top: 50px;
    left: 0;
    color: white;
    backdrop-filter: blur(3px);
}

.sidebar a {
    display: block;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.sidebar a:hover {
    background-color: #3d5d75;
}

/* Main Content Area */
.main {
    margin-left: 220px;
    padding: 30px;
}

.section-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.section-box {
    background-color: rgba(255, 255, 255, 0.85);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #ccc;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    color: #333;
    position: relative;
    overflow: hidden;
}

.section-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 20px rgba(0,0,0,0.3);
}

.section-box h3 a {
    color: #2c3e50;
    text-decoration: none;
}

.section-box h3 a:hover {
    color: #1abc9c;
}

.section-box p {
    font-size: 14px;
    color: #555;
}

.section-box::before {
    content: "";
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    background-size: contain;
    background-repeat: no-repeat;
    opacity: 0.1;
}

.section-box:nth-child(1)::before {
    background-image: url('https://img.icons8.com/ios-filled/50/000000/grade.png');
}

.section-box:nth-child(2)::before {
    background-image: url('https://img.icons8.com/ios-filled/50/000000/upload.png');
}

.section-box:nth-child(3)::before {
    background-image: url('https://img.icons8.com/ios-filled/50/000000/modules.png');
}
</style>
</head>
<body>

<!-- Top Navigation -->
<div class="topnav">
    <div class="title">Course Dashboard</div>
    <div class="nav-links">
        <a href="/assessment_database/Attendance/student_attendance.php">Attendance</a>
        <a href="#">My Courses</a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <a href="/assessment_database/Attendance/student_attendance.php">Attendance</a>
    <a href="#">My Courses</a>
</div>

<!-- Main Content -->
<div class="main">
    <div class="section-grid">
        <div class="section-box">
            <h3><a href="/assessment_database/student/student_view_marks.php"> Marks</a></h3>
            <p>Check and update student marks here.</p>
        </div>
        <div class="section-box">
            <h3><a href=/assessment_database/Assessment/upload_assignment.php>Assignment</a></h3>
            <p>View, upload or grade assignments.</p>
        </div>
        <div class="section-box">
            <h3><a href=/assessment_database/Module/student_module.php>Modules</a></h3>
            <p>Organize course modules and content.</p>
        </div>
    </div>
</div>

</body>
</html>
