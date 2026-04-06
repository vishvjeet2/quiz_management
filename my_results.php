<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Join with quizs table to get the name of the quiz
$query = "SELECT r.*, q.quiz_name 
          FROM quiz_results r 
          JOIN quizs q ON r.quiz_id = q.quiz_id 
          WHERE r.user_id = $user_id 
          ORDER BY r.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Quiz Results</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <h2 class="logo">Users</h2>
            <ul>
                <li><a href="user_document.php">Documents</a></li>
                <li><a href="user_quiz.php">Quizzes</a></li>
                <li class="active"><a href="my_results.php">Quiz Results</a></li>
                <li><a href="contact.php">Contact US</a></li>
                <li class="logout"><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="content">
            <div class="header"><h2>My Quiz History</h2></div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Quiz Name</th>
                            <th>Score</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                <td><?= htmlspecialchars($row['quiz_name']) ?></td>
                                <td><?= $row['score'] ?> / <?= $row['total'] ?></td>
                                <td><strong><?= $row['percentage'] ?>%</strong></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4">No results found. Start a quiz to see your score!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>