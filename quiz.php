<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_data");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$submitted = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $score = 0;
    $answers = $_POST;

    $result = $conn->query("SELECT id, correct_answer FROM questions");
    $total = $result->num_rows;

    while ($row = $result->fetch_assoc()) {
        $qid = $row['id'];
        $correct = $row['correct_answer'];
        $given = $answers["q$qid"] ?? '';
        if ($given === $correct) $score++;
    }

    // Save the score
    $stmt = $conn->prepare("INSERT INTO quiz_results (user_id, username, score) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $username, $score);
    $stmt->execute();

    $message = "Thank you, $username! You scored $score / $total.";
    $submitted = true;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Quiz</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; padding: 20px; }
    .quiz-container { background: white; padding: 20px; max-width: 600px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
    .question { margin-bottom: 20px; }
    label { display: block; margin-left: 20px; }
    button { background: #007bff; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #0056b3; }
  </style>
</head>
<body>
  <div class="quiz-container">
    <h2>Take the Quiz</h2>

    <?php if ($submitted): ?>
      <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php else: ?>
      <form method="POST">
        <?php
        $questions = $conn->query("SELECT * FROM questions");
        while ($q = $questions->fetch_assoc()):
        ?>
          <div class="question">
            <p><strong><?= $q['id'] . '. ' . htmlspecialchars($q['question']) ?></strong></p>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="A" required> <?= htmlspecialchars($q['option_a']) ?></label>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="B"> <?= htmlspecialchars($q['option_b']) ?></label>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="C"> <?= htmlspecialchars($q['option_c']) ?></label>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="D"> <?= htmlspecialchars($q['option_d']) ?></label>
          </div>
        <?php endwhile; ?>
        <button type="submit">Submit</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
