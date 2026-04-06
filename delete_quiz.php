<?php
session_start();
require 'config.php';

// 1. Security Check: Only Admin can delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. Validate ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 3. Execution: Delete Query
    $query = "DELETE FROM quizs WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        // Redirect back with success message
        header("Location: admin_quiz.php?msg=quizDeleted");
        exit;
    } else {
        // Handle database errors
        die("Error deleting record: " . mysqli_error($conn));
    }
} else {
    // If no ID is provided, just go back
    header("Location: admin_quiz.php");
    exit;
}
?>