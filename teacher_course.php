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
background-color: #f4f4f4;
}

/* Top Navigation */
.topnav {
display: flex;
justify-content: space-between;
align-items: center;
background-color: #2c3e50;
color: white;
padding: 10px 20px;
}

.topnav .title {
font-size: 20px;
font-weight: bold;
}

.topnav .nav-links a {
color: white;
text-decoration: none;
margin-left: 20px;
font-weight: bold;
}

.topnav .nav-links a:hover {
text-decoration: underline;
}

/* Sidebar */
.sidebar {
width: 200px;
height: 100vh;
background-color: #34495e;
padding-top: 20px;
position: fixed;
top: 50px;
left: 0;
color: white;
}

.sidebar a {
display: block;
padding: 12px 20px;
color: white;
text-decoration: none;
font-weight: bold;
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
background-color: white;
padding: 20px;
border: 1px solid #ccc;
box-shadow: 0 0 8px rgba(0,0,0,0.1);
}

.section-box h3 {
margin-bottom: 15px;
}

.section-box p {
font-size: 14px;
color: #555;
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
<a href="/assessment_database/messages/student_inbox.php">Messages</a>
<a href="#">Settings</a>
</div>
</div>

<!-- Sidebar -->
<div class="sidebar">
<a href="/assessment_database/Attendance/student_attendance.php">Attendance</a>
<a href="#">My Courses</a>
<a href="/assessment_database/messages/student_inbox.php">Messages</a>
<a href="#">Settings</a>
</div>

<!-- Main Content -->
<div class="main">
<div class="section-grid">
<div class="section-box">
<h3><a href="/assessment_database/student/student_view_marks.php"> Marks</a></h3>
<p>Check and update student marks here.</p>
</div>
<div class="section-box">
<h3>Assignments</h3>
<p>View, upload or grade assignments.</p>
</div>
<div class="section-box">
<h3>Modules</h3>
<p>Organize course modules and content.</p>
</div>
<div class="section-box">
<h3><a href="/assessment_database/messages/student_announcements.php">Announcements</a></h3>
<p>Post updates and notices for students.</p>
</div>
</div>
</div>

</body>
</html>