<?php
include 'connectDB.php'; // Changed to connectDB.php to match your file name

$sql = "SELECT quiz_id, title FROM quizzes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Platform</title>
    </head>
<body>
    <h1>Quiz Platform</h1>
    
    <?php
    if ($result->num_rows > 0) {
        // Loop through each quiz and display
        while($row = $result->fetch_assoc()) {
            echo "<a class='quiz-link' href='fetchQuiz.php?quiz_id=" . $row["quiz_id"] . "'>" . htmlspecialchars($row["title"]) . "</a>";
        }
    } else {
        echo "<p>No quizzes available</p>";
    }
    ?>
    </body>
</html>

<?php
$conn->close();
?>