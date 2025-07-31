<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_data";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM exercises ORDER BY uploaded_on DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Exercise Module</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@heroicons/vue@2.0.16/20/outline/index.umd.js"></script>
</head>
<body class="bg-gray-100 font-sans">
  <div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex flex-col justify-between p-6">
      <div>
        <div class="flex items-center space-x-4 mb-8">
          <img src="https://i.pravatar.cc/60?img=3" alt="Avatar" class="w-12 h-12 rounded-full shadow">
          <div>
            <p class="font-bold text-gray-800">Welcome</p>
            <p class="text-sm text-gray-500">Student</p>
          </div>
        </div>
        <nav class="space-y-4">
          <a href="#" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v16l-4-4H4a1 1 0 01-1-1V4z" />
            </svg>
            <span><a href="/assessment_database/index_student.php">Home</a></span>
          </a>
          <a href="logout.php" class="flex items-center space-x-2 text-red-600 hover:text-red-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V4" />
            </svg>
            <span>Logout</span>
          </a>
        </nav>
      </div>
      <p class="text-xs text-gray-400">&copy; 2025 Student Portal</p>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-y-auto">
      <h2 class="text-2xl font-semibold mb-4">📂 Student Exercise Module</h2>

      <section class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4 flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-7-8h8a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2h2" />
          </svg>
          <span>Exercises</span>
        </h3>
        <ul class="space-y-4">
          <?php while($row = $result->fetch_assoc()): ?>
            <li class="border border-gray-200 p-4 rounded hover:bg-gray-50 flex justify-between items-center">
              <div>
                <p class="font-medium text-gray-800"><?= htmlspecialchars($row['filename']) ?></p>
                <p class="text-sm text-gray-500">📅 Uploaded: <?= $row['uploaded_on'] ?></p>
              </div>
              <a href="<?= htmlspecialchars($row['filepath']) ?>" class="inline-flex items-center px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md" download>
                ⬇ Download
              </a>
            </li>
          <?php endwhile; ?>
        </ul>
      </section>
    </main>
  </div>
</body>
</html>