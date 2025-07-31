<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the raw JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (
        !isset($input['id']) ||
        !isset($input['web_development']) ||
        !isset($input['data_analysis']) ||
        !isset($input['php_for_beginners'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        exit;
    }

    $id = intval($input['id']);
    $web_dev = intval($input['web_development']);
    $data_analysis = intval($input['data_analysis']);
    $php_beginners = intval($input['php_for_beginners']);

    // Validate marks range (0-100)
    foreach ([$web_dev, $data_analysis, $php_beginners] as $mark) {
        if ($mark < 0 || $mark > 100) {
            echo json_encode(['success' => false, 'message' => 'Marks must be between 0 and 100']);
            exit;
        }
    }

    // Prepare update statement
    $stmt = $conn->prepare("
        UPDATE student_marks
        SET web_development = :web_dev,
            data_analysis = :data_analysis,
            php_for_beginners = :php_beginners
        WHERE id = :id
    ");

    $stmt->execute([
        ':web_dev' => $web_dev,
        ':data_analysis' => $data_analysis,
        ':php_beginners' => $php_beginners,
        ':id' => $id
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Marks updated successfully']);
    } else {
        // No rows updated (maybe invalid id)
        echo json_encode(['success' => false, 'message' => 'No record found with that ID or no change made']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
