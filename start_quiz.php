<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Error: No quiz selected.");
}

$quiz_id = intval($_GET['id']);

$quiz_stmt = $conn->prepare("SELECT quiz_name FROM quizs WHERE quiz_id = ?");
$quiz_stmt->bind_param("i", $quiz_id);
$quiz_stmt->execute();
$quiz = $quiz_stmt->get_result()->fetch_assoc();

if (!$quiz) {
    die("Error: Quiz not found.");
}

if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time();
}

// 5. Fetch Questions for this Quiz
$q_query = "SELECT * FROM mcq_questions WHERE quiz_id = $quiz_id ORDER BY RAND()"; 
$questions = mysqli_query($conn, $q_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attempt Quiz: <?= htmlspecialchars($quiz['quiz_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .quiz-header { background: #17a2b8; color: white; padding: 30px 0; }
        .sticky-timer { position: sticky; top: 0; z-index: 1020; background: white; border-bottom: 2px solid #17a2b8; padding: 10px 0; }
        .question-card { margin-top: 25px; margin-bottom: 25px; border-left: 5px solid #17a2b8; }
        .option-label { cursor: pointer; display: block; padding: 10px; border: 1px solid #ddd; border-radius: 5px; transition: 0.3s; }
        .option-label:hover { background: #e9ecef; }
        input[type="radio"]:checked + .option-label { background: #17a2b8; color: white; border-color: #17a2b8; }
        #timer { font-size: 1.5rem; font-weight: bold; }
    </style>
</head>
<body>

<div class="quiz-header text-center">
    <h1><?= htmlspecialchars($quiz['quiz_name']) ?></h1>
    <p class="mb-0">Please answer all questions and click "Submit Quiz" at the bottom.</p>
</div>

<div class="sticky-timer shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="text-muted">Don't refresh the page!</span>
        <div class="d-flex align-items-center">
            <span class="mr-2 text-secondary">Time Left:</span>
            <span id="timer" class="text-danger">10:00</span>
        </div>
    </div>
</div>

<div class="container">
    <form id="quizForm" action="submit_quiz.php" method="post">
        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

        <?php 
        $count = 1;
        if (mysqli_num_rows($questions) > 0) {
            while ($q = mysqli_fetch_assoc($questions)) { 
        ?>
            <div class="card question-card shadow-sm">
                <div class="card-body">
                    <h5>Q<?= $count++ ?>: <?= htmlspecialchars($q['question']) ?></h5>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <input type="radio" name="answer[<?= $q['id'] ?>]" value="A" id="q<?= $q['id'] ?>a" class="d-none" required>
                            <label class="option-label" for="q<?= $q['id'] ?>a">A) <?= htmlspecialchars($q['option_a']) ?></label>
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <input type="radio" name="answer[<?= $q['id'] ?>]" value="B" id="q<?= $q['id'] ?>b" class="d-none" required>
                            <label class="option-label" for="q<?= $q['id'] ?>b">B) <?= htmlspecialchars($q['option_b']) ?></label>
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <input type="radio" name="answer[<?= $q['id'] ?>]" value="C" id="q<?= $q['id'] ?>c" class="d-none" required>
                            <label class="option-label" for="q<?= $q['id'] ?>c">C) <?= htmlspecialchars($q['option_c']) ?></label>
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <input type="radio" name="answer[<?= $q['id'] ?>]" value="D" id="q<?= $q['id'] ?>d" class="d-none" required>
                            <label class="option-label" for="q<?= $q['id'] ?>d">D) <?= htmlspecialchars($q['option_d']) ?></label>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            } 
        } else {
            echo "<div class='alert alert-warning mt-4'>No questions found for this quiz.</div>";
        }
        ?>

        <div class="text-center pb-5">
            <button type="submit" name="submit_quiz" class="btn btn-lg btn-success px-5 shadow">Submit Quiz</button>
        </div>
    </form>
</div>

<script>
    let timeInSeconds = 10 * 60; 
    const timerDisplay = document.getElementById('timer');
    const form = document.getElementById('quizForm');

    const countdown = setInterval(function() {
        let minutes = Math.floor(timeInSeconds / 60);
        let seconds = timeInSeconds % 60;

        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        timerDisplay.innerHTML = `${minutes}:${seconds}`;

        if (timeInSeconds <= 0) {
            clearInterval(countdown);
            alert("Time is up! Your quiz will be submitted automatically.");
            
            const inputs = form.querySelectorAll('input[type="radio"]');
            inputs.forEach(input => input.removeAttribute('required'));
            
            form.submit();
        }

        if (timeInSeconds <= 60) {
            timerDisplay.classList.add('blink');
        }

        timeInSeconds--;
    }, 1000);

    window.onbeforeunload = function() {
        return "Your progress will be lost if you leave this page.";
    };
    
    form.onsubmit = function() {
        window.onbeforeunload = null;
    };
</script>

<style>
    .blink {
        animation: blinker 1s linear infinite;
    }
    @keyframes blinker {
        50% { opacity: 0; }
    }
</style>

</body>
</html>