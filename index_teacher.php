<?php
session_start();

// If user is NOT logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Prevent browser caching
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Teacher Dashboard</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    color: #333;
}

/* Top Navigation */
.topnav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2c3e50;
    color: white;
    padding: 10px 20px;
    height: 50px; /* Fixed height */
    position: fixed; /* Make it fixed so it stays on top */
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000; /* Stay on top */
}

.topnav .title {
    font-size: 22px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.topnav .title img {
    height: 30px;
    width: 30px;
}

.topnav .nav-links a {
    color: white;
    text-decoration: none;
    margin-left: 25px;
    font-weight: 600;
    position: relative;
    transition: color 0.3s ease;
}

.topnav .nav-links a:hover {
    color: #1abc9c;
}

.topnav .nav-links a::after {
    content: "";
    position: absolute;
    width: 0%;
    height: 2px;
    bottom: -4px;
    left: 0;
    background-color: #1abc9c;
    transition: width 0.3s;
}

.topnav .nav-links a:hover::after {
    width: 100%;
}

/* Sidebar */
.sidebar {
    width: 200px;
    height: calc(100vh - 50px); /* Full height minus topnav */
    background-color: #34495e;
    padding-top: 20px;
    position: fixed;
    top: 50px; /* Right below the fixed topnav */
    left: 0;
    color: white;
    overflow-y: auto; /* Scroll if sidebar is longer */
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 25px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.sidebar a:hover {
    background-color: #3d5d75;
    cursor: pointer;
}

.sidebar a svg {
    fill: white;
    width: 18px;
    height: 18px;
}

/* Main Content Area */
.main {
    margin-left: 220px; /* Same as sidebar width + some space */
    padding: 80px 30px 30px 30px; /* Top padding to clear fixed topnav */
}

.dashboard-boxes {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    justify-content: flex-start;
}

/* Each box */
.box {
    flex: 0 0 250px;
    background-color: white;
    border-radius: 10px;
    border: 1px solid #ddd;
    text-align: center;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    cursor: pointer;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.box:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(26, 188, 156, 0.3);
}

.box h3 {
    margin-bottom: 15px;
    font-weight: 700;
    font-size: 20px;
    color: #2c3e50;
}

.box h3 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.box h3 a:hover {
    color: #1abc9c;
}

/* Images inside boxes */
.box img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.box:hover img {
    transform: scale(1.05);
}

/* Courses List */
#course-list {
    max-height: 0;
    overflow: hidden;
    margin-top: 12px;
    text-align: left;
    transition: max-height 0.4s ease;
}

#course-list.show {
    max-height: 300px; /* enough to show full list */
}

#course-list ul {
    list-style-type: none;
    padding-left: 15px;
}

#course-list li {
    padding: 8px 0;
    font-weight: 600;
}

#course-list li a {
    color: #34495e;
    text-decoration: none;
    transition: color 0.3s ease;
}

#course-list li a:hover {
    color: #1abc9c;
}

/* Scrollbar for course list if overflow */
#course-list ul {
    max-height: 200px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #1abc9c #eee;
}

/* Scrollbar for Webkit browsers */
#course-list ul::-webkit-scrollbar {
    width: 6px;
}

#course-list ul::-webkit-scrollbar-thumb {
    background-color: #1abc9c;
    border-radius: 3px;
}
</style>
</head>
<body>

<!-- Top Navigation -->
<div class="topnav">
    <div class="title">
        <img src="https://cdn-icons-png.flaticon.com/512/1995/1995529.png" alt="Logo" />
        Teacher Dashboard
    </div>
    <div class="nav-links">
        <a href="/assessment_database/Attendance/teacher_attendance.php">Attendance</a>
        <a href="login.php">Logout</a>
        <a href="/Assessment_Database/messages/teacher_inbox.php">Message</a>
    </div>
</div>

<!-- Main Content -->
<div class="main">
    <div class="dashboard-boxes">

        <!-- Box 1: Courses -->
        <div class="box" onclick="toggleCourses()" role="button" tabindex="0" aria-expanded="false" aria-controls="course-list">
            <h3>Courses</h3>
            <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=400&q=80" alt="Courses Image">

            <!-- Hidden Course List -->
            <div id="course-list" aria-live="polite">
                <ul>
                    <li><a href="/assessment_database/Module/teacher_module.php">Web Development</a></li>
                    <li><a href="/assessment_database/Module/teacher_module.php">Data Analysis</a></li>
                    <li><a href="/assessment_database/Module/teacher_module.php">PHP for Beginners</a></li>
                </ul>
            </div>
        </div>

        <!-- Box 2: Student Activity -->
        <div class="box">
            <h3><a href="/assessment_database/student/edit_marks.php">Student Activity</a></h3>
            <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=400&q=80" alt="Student Activity Image">
        </div>

        <!-- Box 3: Quick Tools -->
        <div class="box">
            <h3><a href="/assessment_database/Assessment/view_assignments.php">Assessment</a></h3>
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=400&q=80" alt="Assessment Tools Image">
        </div>

    </div>
</div>

<!-- Script to toggle course list -->
<script>
function toggleCourses() {
    const list = document.getElementById('course-list');
    const isShown = list.classList.contains('show');
    if (isShown) {
        list.classList.remove('show');
        document.querySelector('.box[onclick]').setAttribute('aria-expanded', 'false');
    } else {
        list.classList.add('show');
        document.querySelector('.box[onclick]').setAttribute('aria-expanded', 'true');
    }
}
</script>

</body>
</html>
