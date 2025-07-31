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

// Handle new file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['exercise_file'])) {
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $file = $_FILES['exercise_file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = basename($file['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $filepath = "uploads/" . $filename;
            $stmt = $conn->prepare("INSERT INTO exercises (filename, filepath, uploaded_on) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $filename, $filepath);
            $stmt->execute();
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Failed to move uploaded file.";
        }
    } else {
        $error = "Upload error code: " . $file['error'];
    }
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("SELECT filepath FROM exercises WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($filepath);
    if ($stmt->fetch()) {
        $stmt->close();
        if (file_exists(__DIR__ . "/" . $filepath)) {
            unlink(__DIR__ . "/" . $filepath);
        }
        $stmt = $conn->prepare("DELETE FROM exercises WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt->close();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$sql = "SELECT * FROM exercises ORDER BY uploaded_on DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Teacher Exercise Module</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Heroicons CDN for icons -->
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-100 font-sans">
  <div class="flex h-screen flex-col">
    <!-- Header Image Banner -->
    <header class="bg-blue-600 p-6 flex items-center gap-4 text-white">
      <img src="https://cdn-icons-png.flaticon.com/512/1671/1671468.png" alt="Teacher Icon" class="w-12 h-12" />
      <h1 class="text-4xl font-extrabold select-none">👩‍🏫 Teacher Exercise Module</h1>
    </header>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-y-auto max-w-4xl mx-auto">
      <!-- Upload form -->
      <section class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Add New Exercise
        </h3>
        <?php if (!empty($error)): ?>
          <p class="mb-4 text-red-600"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data" class="flex gap-4 items-center">
          <input type="file" name="exercise_file" required class="border border-gray-300 rounded px-3 py-2" />
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0-8L8 8m4 4l4-4" />
            </svg>
            Upload
          </button>
        </form>
      </section>

      <!-- List exercises -->
      <section class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m-6-8h6M5 6h14M5 18h14" />
          </svg>
          Existing Exercises
        </h3>
        <?php if ($result->num_rows === 0): ?>
          <p>No exercises uploaded yet.</p>
        <?php else: ?>
          <ul class="space-y-4">
            <?php while ($row = $result->fetch_assoc()): ?>
              <li class="border border-gray-200 p-4 rounded hover:bg-gray-50 flex justify-between items-center">
                <div>
                  <p class="font-medium"><?= htmlspecialchars($row['filename']) ?></p>
                  <p class="text-sm text-gray-500">Uploaded: <?= $row['uploaded_on'] ?></p>
                </div>
                <div class="flex gap-4 items-center">
                  <a href="<?= htmlspecialchars($row['filepath']) ?>" download class="text-blue-600 hover:underline flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0-8L8 8m4 4l4-4" />
                    </svg>
                    Download
                  </a>
                  <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this exercise?')" class="text-red-600 hover:underline flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Delete
                  </a>
                </div>
              </li>
            <?php endwhile; ?>
          </ul>
        <?php endif; ?>
      </section>
    </main>
  </div>

  <script>
    feather.replace()
  </script>
</body>
</html>
