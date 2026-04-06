<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'admin') {
    $quiz_name = mysqli_real_escape_string($conn, $_POST['quiz_name']);

    $query = "INSERT INTO quizs (quiz_name) VALUES ('$quiz_name')";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_quiz.php?msg=QuizCreated");
    } else {
        header("Location: admin_quiz.php?msg=Error");
    }
}
?>