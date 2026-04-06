<?php
require "config.php";
session_start();

// 1. Security Check: Only Admins can view this
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. Validate Quiz ID
if (!isset($_GET['id'])) {
    header("Location: admin_quiz.php");
    exit;
}

$quiz_id = intval($_GET['id']);

/* Fetch Quiz Metadata */
$stmt = $conn->prepare("SELECT quiz_name FROM quizs WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();

if (!$quiz) {
    die("Quiz not found in database.");
}

/* Fetch All Questions for this Quiz */
$stmt = $conn->prepare(
    "SELECT id, question, option_a, option_b, option_c, option_d, correct_answer
     FROM mcq_questions
     WHERE quiz_id = ? ORDER BY id ASC"
);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$questions = $stmt->get_result();

include 'header.php'; 
include 'sidebar.php'; 
?>

<main class="flex-1 p-4 md:p-6 lg:p-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        
        <!-- Refined Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-5 border-b border-slate-200 pb-6 print:hidden">
            <div class="flex-1">
                <!-- Smooth Back Link -->
                <a href="admin_quiz.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-3 group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i> 
                    Back to Quizzes
                </a>
                
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">
                    <?= htmlspecialchars($quiz['quiz_name']) ?>
                </h1>
                <p class="text-sm text-slate-500 mt-1">Manage and review questions for this assessment.</p>
            </div>

            <!-- Fixed Button Hierarchy -->
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-print text-slate-400"></i> Print
                </button>
                
                <a href="add_question.php?id=<?= $quiz_id ?>" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-plus text-xs"></i> Add Question
                </a>
            </div>
        </div>

        <!-- Questions List -->
        <div class="space-y-6 print:space-y-4">
            <?php 
            $i = 1;
            // Suppressing errors for UI preview purposes
            if (isset($questions) && @$questions->num_rows > 0):
                while ($q = @$questions->fetch_assoc()): 
            ?>
                <!-- Question Card -->
                <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-sm hover:shadow-soft transition-all group print:border-slate-300 print:shadow-none print:break-inside-avoid">
                    
                    <!-- Card Header (Question Number & Actions) -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Question <?= $i++ ?>
                        </div>
                        
                        <!-- Delete Action (Hidden when printing) -->
                        <div class="print:hidden">
                            <a href="delete_question.php?id=<?= $q['id'] ?>&quiz_id=<?= $quiz_id ?>" 
                               onclick="return confirm('Do you really want to remove this question?')"
                               class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors tooltip" title="Delete Question">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Question Text -->
                    <div class="mt-2">
                        <h3 class="text-lg font-bold text-slate-900 leading-relaxed mb-6">
                            <?= htmlspecialchars($q['question']) ?>
                        </h3>

                        <!-- Options Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-2">
                            <?php 
                            // This PHP array logic is excellent. Kept it exactly the same!
                            $options = [
                                'A' => $q['option_a'],
                                'B' => $q['option_b'],
                                'C' => $q['option_c'],
                                'D' => $q['option_d']
                            ];
                            foreach ($options as $key => $val):
                                $is_correct = ($key === $q['correct_answer']);
                                
                                // SaaS dynamic classes based on correct/incorrect state
                                $boxClass = $is_correct ? 'border-indigo-200 bg-indigo-50/40 ring-1 ring-indigo-500/10' : 'border-slate-200 bg-white';
                                $letterClass = $is_correct ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500 border border-slate-200';
                                $textClass = $is_correct ? 'text-indigo-900 font-semibold' : 'text-slate-600 font-medium';
                            ?>
                                <div class="flex items-center p-3 rounded-xl border <?= $boxClass ?> transition-all relative">
                                    
                                    <!-- Option Letter Badge -->
                                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-[11px] font-bold mr-3 flex-shrink-0 transition-colors <?= $letterClass ?>">
                                        <?= $key ?>
                                    </span>
                                    
                                    <!-- Option Text -->
                                    <span class="text-sm <?= $textClass ?> pr-6">
                                        <?= htmlspecialchars($val) ?>
                                    </span>

                                    <!-- Correct Answer Icon -->
                                    <?php if($is_correct): ?>
                                        <i class="fas fa-check-circle absolute right-4 text-indigo-600 text-lg"></i>
                                    <?php endif; ?>
                                    
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Micro-footer for ID -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center print:hidden">
                            <span class="text-[11px] font-mono text-slate-400">ID: #<?= str_pad($q['id'], 4, '0', STR_PAD_LEFT) ?></span>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile; 
            else: 
            ?>
                <!-- Empty State -->
                <div class="bg-white rounded-2xl border border-slate-200 border-dashed p-12 text-center print:hidden">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 text-slate-300 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">This quiz is empty.</h3>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">You haven't added any questions yet. Add some to make this quiz live!</p>
                    <a href="add_question.php?id=<?= $quiz_id ?>" class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-100 hover:text-indigo-700 transition-all">
                        <i class="fas fa-plus text-xs"></i>
                        Create First Question
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
    @media print {
        body { background: white !important; }
        .sidebar, header, .print\:hidden { display: none !important; }
        main { padding: 0 !important; }
        .rounded-\[2rem\] { border-radius: 0 !important; border: 1px solid #eee !important; box-shadow: none !important; }
    }
</style>

</body>
</html>