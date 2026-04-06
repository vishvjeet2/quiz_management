<?php
session_start();
require 'config.php';

// 1. Security Check
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['submit_quiz'])) {
    header("Location: user_quiz.php");
    exit;
}

$quiz_id = intval($_POST['quiz_id']);
$user_id = $_SESSION['user_id'];
$user_answers = $_POST['answer'] ?? [];

$total_questions = 0;
$correct_score = 0;

// 2. Fetch Quiz Name
$quiz_stmt = $conn->prepare("SELECT quiz_name FROM quizs WHERE quiz_id = ?");
$quiz_stmt->bind_param("i", $quiz_id);
$quiz_stmt->execute();
$quiz = $quiz_stmt->get_result()->fetch_assoc();

if (!$quiz) {
    die("Quiz not found.");
}

// 3. Calculate Score
if (!empty($user_answers)) {
    foreach ($user_answers as $q_id => $submitted_answer) {
        $total_questions++;

        $stmt = $conn->prepare("SELECT correct_answer FROM mcq_questions WHERE id = ?");
        $stmt->bind_param("i", $q_id);
        $stmt->execute();
        $db_row = $stmt->get_result()->fetch_assoc();

        if ($db_row && $db_row['correct_answer'] === $submitted_answer) {
            $correct_score++;
        }
    }

    $percentage = ($total_questions > 0) ? ($correct_score / $total_questions) * 100 : 0;

    // 4. SAVE TO DATABASE
    // This allows the user to see their history later in my_results.php
    $save_stmt = $conn->prepare(
        "INSERT INTO quiz_results (user_id, quiz_id, score, total, percentage) VALUES (?, ?, ?, ?, ?)"
    );
    $save_stmt->bind_param("iiiid", $user_id, $quiz_id, $correct_score, $total_questions, $percentage);
    $save_stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .result-card { margin-top: 50px; border-radius: 15px; overflow: hidden; }
        .score-circle { 
            width: 150px; height: 150px; border-radius: 50%; 
            background: #17a2b8; color: white; display: flex; 
            align-items: center; justify-content: center; 
            font-size: 30px; font-weight: bold; margin: 20px auto;
        }
        @media print {
            .btn, .sidebar, .header { display: none !important; }
            .result-card { border: none; box-shadow: none; margin-top: 0; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card result-card shadow-lg">
                <div class="card-header bg-info text-white text-center">
                    <h3>Results for: <?= htmlspecialchars($quiz['quiz_name']) ?></h3>
                </div>
                <div class="card-body text-center">
                    
                    <?php if ($total_questions > 0): ?>
                        <div class="score-circle">
                            <?= $correct_score ?> / <?= $total_questions ?>
                        </div>
                        
                        <h4 class="mb-4">Your Score: <strong><?= round($percentage, 2) ?>%</strong></h4>

                        <div class="progress mb-4" style="height: 30px;">
                            <div class="progress-bar <?= ($percentage >= 50) ? 'bg-success' : 'bg-danger' ?>" 
                                 role="progressbar" 
                                 style="width: <?= $percentage ?>%" 
                                 aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                 <?= round($percentage) ?>%
                            </div>
                        </div>

                        <?php if ($percentage >= 50): ?>
                            <div class="alert alert-success">🎉 Congratulations! You passed the quiz.</div>
                        <?php else: ?>
                            <div class="alert alert-danger">❌ You didn't pass this time. Better luck next time!</div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="alert alert-warning">No answers were submitted or no questions exist in this quiz.</div>
                    <?php endif; ?>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="user_quiz.php" class="btn btn-outline-info">More Quizzes</a>
                        <a href="my_results.php" class="btn btn-info">View All My Results</a>
                        <button onclick="window.print()" class="btn btn-secondary">Print Result</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>