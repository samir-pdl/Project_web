<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Updated query with new subjects
    $stmt = $conn->prepare("SELECT student_name, web_development, data_analysis, php_for_beginners FROM student_marks");
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
    <title>Student Marks</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 18px;
            text-align: center;
            border-bottom: 1px solid #ececec;
        }

        th {
            background-color: #34495e;
            color: white;
            font-size: 16px;
        }

        td {
            font-size: 15px;
        }

        tr:hover {
            background-color: #f0f8ff;
        }

        .high {
            color: #2ecc71;
            font-weight: bold;
        }

        .low {
            color: #e74c3c;
            font-weight: bold;
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

    <h1>Student Marks</h1>

    <table>
        <tr>
            <th>Student Name</th>
            <th>Web Development</th>
            <th>Data Analysis</th>
            <th>PHP for Beginners</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_name']); ?></td>
                <td class="<?= $row['web_development'] >= 75 ? 'high' : ($row['web_development'] < 50 ? 'low' : '') ?>">
                    <?= $row['web_development']; ?>
                </td>
                <td class="<?= $row['data_analysis'] >= 75 ? 'high' : ($row['data_analysis'] < 50 ? 'low' : '') ?>">
                    <?= $row['data_analysis']; ?>
                </td>
                <td class="<?= $row['php_for_beginners'] >= 75 ? 'high' : ($row['php_for_beginners'] < 50 ? 'low' : '') ?>">
                    <?= $row['php_for_beginners']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>


</body>
</html>

<?php $conn = null; ?>
