<?php
require 'config.php';

if (isset($_GET['id'])) {
    $quiz_id = intval($_GET['id']);
    $query = "SELECT * FROM mcq_questions WHERE quiz_id = $quiz_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $count = 1;
        while ($q = mysqli_fetch_assoc($result)) {
            echo "
            <div class='mb-6 p-4 border border-slate-100 rounded-2xl bg-slate-50'>
                <p class='font-bold text-slate-800 mb-2'>Q{$count}. " . htmlspecialchars($q['question']) . "</p>
                <div class='grid grid-cols-2 gap-2 text-sm'>
                    <div class='p-2 bg-white rounded border'>A: " . htmlspecialchars($q['option_a']) . "</div>
                    <div class='p-2 bg-white rounded border'>B: " . htmlspecialchars($q['option_b']) . "</div>
                    <div class='p-2 bg-white rounded border'>C: " . htmlspecialchars($q['option_c']) . "</div>
                    <div class='p-2 bg-white rounded border'>D: " . htmlspecialchars($q['option_d']) . "</div>
                </div>
                <p class='mt-2 text-emerald-600 font-bold text-xs uppercase'>Correct Answer: " . $q['correct_answer'] . "</p>
            </div>";
            $count++;
        }
    } else {
        echo "<p class='text-center py-10 text-slate-400'>No questions added to this quiz yet.</p>";
    }
}
?>