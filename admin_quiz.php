<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_data");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Admin access check using role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Handle add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $_POST['question'], $_POST['option_a'], $_POST['option_b'], $_POST['option_c'], $_POST['option_d'], $_POST['correct_answer']);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE questions SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_answer=? WHERE id=?");
        $stmt->bind_param("ssssssi", $_POST['question'], $_POST['option_a'], $_POST['option_b'], $_POST['option_c'], $_POST['option_d'], $_POST['correct_answer'], $_POST['id']);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM questions WHERE id=?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
    }
}

// Fetch all questions
$questions = $conn->query("SELECT * FROM questions ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Quiz</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { text-align: center; }
        form { margin-bottom: 30px; }
        input[type=text], select { width: 100%; padding: 10px; margin: 6px 0; border: 1px solid #ccc; border-radius: 4px; }
        input[type=submit] { padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        input[type=submit]:hover { background: #218838; }
        .delete-btn { background: #dc3545; }
        .delete-btn:hover { background: #c82333; }
        .question-box { border-bottom: 1px solid #ddd; padding-bottom: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Quiz Questions</h2>

        <?php while ($q = $questions->fetch_assoc()): ?>
        <div class="question-box">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $q['id'] ?>">
                <label>Question:</label>
                <input type="text" name="question" value="<?= htmlspecialchars($q['question']) ?>" required>
                <label>Option A:</label>
                <input type="text" name="option_a" value="<?= htmlspecialchars($q['option_a']) ?>" required>
                <label>Option B:</label>
                <input type="text" name="option_b" value="<?= htmlspecialchars($q['option_b']) ?>" required>
                <label>Option C:</label>
                <input type="text" name="option_c" value="<?= htmlspecialchars($q['option_c']) ?>" required>
                <label>Option D:</label>
                <input type="text" name="option_d" value="<?= htmlspecialchars($q['option_d']) ?>" required>
                <label>Correct Answer:</label>
                <select name="correct_answer" required>
                    <option value="A" <?= $q['correct_answer'] == 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= $q['correct_answer'] == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="C" <?= $q['correct_answer'] == 'C' ? 'selected' : '' ?>>C</option>
                    <option value="D" <?= $q['correct_answer'] == 'D' ? 'selected' : '' ?>>D</option>
                </select>
                <input type="submit" name="update" value="Update">
                <input type="submit" name="delete" class="delete-btn" value="Delete">
            </form>
        </div>
        <?php endwhile; ?>

        <h3>Add New Question</h3>
        <form method="POST">
            <label>Question:</label>
            <input type="text" name="question" required>
            <label>Option A:</label>
            <input type="text" name="option_a" required>
            <label>Option B:</label>
            <input type="text" name="option_b" required>
            <label>Option C:</label>
            <input type="text" name="option_c" required>
            <label>Option D:</label>
            <input type="text" name="option_d" required>
            <label>Correct Answer:</label>
            <select name="correct_answer" required>
                <option value="">--Choose--</option>
                <option value="A">Option A</option>
                <option value="B">Option B</option>
                <option value="C">Option C</option>
                <option value="D">Option D</option>
            </select>
            <input type="submit" name="add" value="Add Question">
        </form>
    </div>
</body>
</html>
