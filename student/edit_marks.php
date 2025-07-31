<?php
session_start();

// Optional login check
// if (!isset($_SESSION['teacher_logged_in']) || $_SESSION['teacher_logged_in'] !== true) {
//     header("Location: login.php");
//     exit();
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 1: Get all students from users table
    $students = $conn->query("SELECT id, name FROM users WHERE role = 'student'")->fetchAll(PDO::FETCH_ASSOC);

    // Step 2: Insert students into student_marks if not already there
    $insertStmt = $conn->prepare("
        INSERT INTO student_marks (user_id, student_name, web_development, data_analysis, php_for_beginners)
        SELECT :user_id, :student_name, 0, 0, 0
        WHERE NOT EXISTS (SELECT 1 FROM student_marks WHERE user_id = :user_id)
    ");

    foreach ($students as $student) {
        $insertStmt->execute([
            ':user_id' => $student['id'],
            ':student_name' => $student['name']
        ]);
    }

    // Step 3: Fetch updated marks data from student_marks
    $stmt = $conn->prepare("
        SELECT id, student_name, web_development, data_analysis, php_for_beginners
        FROM student_marks
        ORDER BY student_name
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student Marks</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ececec;
        }
        th {
            background-color: #3498db;
            color: white;
            font-size: 16px;
        }
        td input[type="number"] {
            width: 70px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }
        .update-btn {
            padding: 8px 16px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .update-btn:hover {
            background-color: #27ae60;
        }
        .message {
            margin-left: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        .success {
            color: #27ae60;
        }
        .error {
            color: #e74c3c;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            font-size: 16px;
            color: #3498db;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Edit Student Marks</h1>

    <table>
        <tr>
            <th>Student Name</th>
            <th>Web Development</th>
            <th>Data Analysis</th>
            <th>PHP for Beginners</th>
            <th>Action</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr data-id="<?= $row['id']; ?>">
                <td><?= htmlspecialchars($row['student_name']); ?></td>
                <td><input type="number" class="mark" name="web_development" min="0" max="100" value="<?= $row['web_development']; ?>"></td>
                <td><input type="number" class="mark" name="data_analysis" min="0" max="100" value="<?= $row['data_analysis']; ?>"></td>
                <td><input type="number" class="mark" name="php_for_beginners" min="0" max="100" value="<?= $row['php_for_beginners']; ?>"></td>
                <td>
                    <button class="update-btn">Update</button>
                    <span class="message"></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="marks.php">← Back to Summary</a>

    <script>
    document.querySelectorAll('.update-btn').forEach(button => {
        button.addEventListener('click', async function () {
            const row = this.closest('tr');
            const id = row.dataset.id;
            const web_development = row.querySelector('input[name="web_development"]').value;
            const data_analysis = row.querySelector('input[name="data_analysis"]').value;
            const php_for_beginners = row.querySelector('input[name="php_for_beginners"]').value;

            const messageSpan = row.querySelector('.message');
            messageSpan.textContent = 'Saving...';
            messageSpan.className = 'message';

            try {
                const response = await fetch('update_marks.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, web_development, data_analysis, php_for_beginners })
                });
                const data = await response.json();

                if (data.success) {
                    messageSpan.textContent = '✔ Updated!';
                    messageSpan.className = 'message success';
                } else {
                    messageSpan.textContent = '✖ Error: ' + data.message;
                    messageSpan.className = 'message error';
                }
            } catch (err) {
                messageSpan.textContent = '✖ Request failed';
                messageSpan.className = 'message error';
            }
        });
    });
    </script>
    <style>
    .home-button {
      position: fixed;
      bottom: 20px;       /* distance from bottom */
      left: 50%;
      transform: translateX(-50%);
      background-color: #2563eb; /* Tailwind blue-600 */
      color: white;
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      margin-bottom: 280px;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: background-color 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }
    .home-button:hover {
      background-color: #1d4ed8; /* darker blue */
    }

  </style>
    <a href="/assessment_database/index_teacher.php" class="home-button">Home</a>
</body>
</html>

<?php $conn = null; ?>
