<?php
session_start();
// Path fix: config.php ek folder bahar hai
require '../config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quiz_id = intval($_POST['quiz_id']);
    $user_id = $_SESSION['user_id'];
    
    // Yahan 'q_id' ko badal kar jo aapka sahi column name hai wo likhein (likely 'id')
    $questions = mysqli_query($conn, "SELECT id, correct_answer FROM mcq_questions WHERE quiz_id = $quiz_id");
    
    $score = 0;
    $total = mysqli_num_rows($questions);
    
    if ($total > 0) {
        while($q = mysqli_fetch_assoc($questions)) {
            // Input field ka naam 'take_quiz.php' mein 'q' + ID tha
            $ans_key = 'q' . $q['id']; 
            
            if(isset($_POST[$ans_key]) && $_POST[$ans_key] == $q['correct_answer']) {
                $score++;
            }
        }
        
        $percentage = ($score / $total) * 100;
        
        // Results save karein
        $stmt = $conn->prepare("INSERT INTO quiz_results (user_id, quiz_id, score, total, percentage) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiid", $user_id, $quiz_id, $score, $total, $percentage);
        
        if($stmt->execute()) {
            header("Location: student_results.php?msg=ExamSubmitted");
            exit;
        } else {
            echo "Error saving results: " . $conn->error;
        }
    } else {
        echo "No questions found for this quiz.";
    }
}
?>