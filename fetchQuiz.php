<?php
include 'connectDB.php'; // ✅ must define $conn

if (!isset($_GET['quiz_id'])) {
    die("Quiz ID not provided.");
}

$quiz_id = intval($_GET['quiz_id']);

// Get quiz title
$quiz_sql = "SELECT title FROM quizzes WHERE quiz_id = ?";
$stmt_quiz = $conn->prepare($quiz_sql);
$stmt_quiz->bind_param("i", $quiz_id);
$stmt_quiz->execute();
$quiz_result = $stmt_quiz->get_result();

if ($quiz_result->num_rows === 0) {
    die("Invalid Quiz ID");
}

$quiz_title = $quiz_result->fetch_assoc()['title'];

// Get all questions for this quiz
$question_sql = "SELECT question_id, question_text FROM question WHERE quiz_id = ?";
$stmt_question = $conn->prepare($question_sql);
$stmt_question->bind_param("i", $quiz_id);
$stmt_question->execute();
$question_result = $stmt_question->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($quiz_title); ?> | Quizzlet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <a href="homepage.html" class="logo">Quizzlet</a>
    <ul>
        <li><a href="homepage.html">Home</a></li>
        <li><a href="about.html">About</a></li>
    </ul>
</nav>

<div class="quiz-container">
    <div class="quiz-header">
        <h1><?php echo htmlspecialchars($quiz_title); ?></h1>
        <p>Answer the following questions:</p>
    </div>

    <div id="questions-area">
        <?php
        if ($question_result->num_rows > 0) {
            $option_sql = "SELECT option_text, is_correct FROM quiz_option WHERE question_id = ?";
            $stmt_option = $conn->prepare($option_sql);
            $question_index = 0;

            while ($question = $question_result->fetch_assoc()) {
                $qid = $question['question_id'];
                echo "<div class='question-wrapper' data-question-number='$question_index'>";
                echo "<div class='question'><strong>Q: " . htmlspecialchars($question['question_text']) . "</strong></div>";
                
                // Timer and progress bar
echo "<div class='quiz-status-row'>";
echo "<div class='timer time-remaining'>00:30</div>";
echo "<div class='progress-bar'><div class='progress-fill'></div></div>";
echo "</div>";

                $stmt_option->bind_param("i", $qid);
                $stmt_option->execute();
                $option_result = $stmt_option->get_result();

                echo "<div class='options'>";
                while ($option = $option_result->fetch_assoc()) {
                    $isCorrect = $option['is_correct'] === 'Y' ? 'Y' : 'N';
                    echo "<div class='option' data-correct='$isCorrect'>" . htmlspecialchars($option['option_text']) . "</div>";
                }
                echo "</div>"; // .options
                echo "</div>"; // .question-wrapper
                $question_index++;
            }

            $stmt_option->close();
        } else {
            echo "<p>No questions found for this quiz.</p>";
        }

        $stmt_question->close();
        $stmt_quiz->close();
        $conn->close();
        ?>
    </div>

    <div class="navigation-buttons">
        <button id="prev-btn" class="nav-button" disabled>&larr; Previous</button>
        <button id="next-btn" class="nav-button">Next &rarr;</button>
    </div>
</div>

<footer>
    Let’s make learning playful & powerful 💡
</footer>

<script src="quiz.js" defer></script>
</body>
</html>