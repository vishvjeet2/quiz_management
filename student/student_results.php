<?php 
session_start(); 
require '../config.php';

// Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Optimized Query using JOIN to get Quiz Names
$query = "SELECT r.*, q.quiz_name 
          FROM quiz_results r 
          JOIN quizs q ON r.quiz_id = q.quiz_id 
          WHERE r.user_id = $user_id 
          ORDER BY r.created_at DESC";

$results = mysqli_query($conn, $query);

include '../header.php'; 
include 'student_sidebar.php'; 
?>

<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-5xl mx-auto w-full">
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Performance Analytics</h1>
        <p class="text-sm text-slate-500 mt-1">Review your exam history, scores, and progress.</p>
    </div>

    <!-- Results List -->
    <div class="space-y-4 md:space-y-5">
        <?php 
        // Suppressing error for UI preview
        if(isset($results) && @mysqli_num_rows($results) > 0): 
            while($row = @mysqli_fetch_assoc($results)): 
                
                // SaaS Status Logic
                $is_pass = ($row['percentage'] >= 50);
                
                // Pass Colors (Soft Emerald)
                if ($is_pass) {
                    $circle_bg = 'bg-emerald-50 border-emerald-100 text-emerald-600';
                    $badge_class = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                    $icon = '<i class="fas fa-check-circle mr-1.5 opacity-70"></i>';
                    $status_text = 'Passed';
                } 
                // Fail Colors (Soft Rose)
                else {
                    $circle_bg = 'bg-rose-50 border-rose-100 text-rose-600';
                    $badge_class = 'bg-rose-50 text-rose-700 border-rose-200/60';
                    $icon = '<i class="fas fa-times-circle mr-1.5 opacity-70"></i>';
                    $status_text = 'Failed';
                }
        ?>
            <!-- Result Card -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-soft hover:-translate-y-0.5 hover:border-indigo-200 transition-all duration-300 group flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-6">
                
                <!-- Left Side: Score Circle & Quiz Info -->
                <div class="flex items-center gap-4 md:gap-5">
                    <!-- Elegant Percentage Circle -->
                    <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-sm border flex-shrink-0 <?= $circle_bg ?>">
                        <?= number_format($row['percentage'], 0) ?><span class="text-[10px] opacity-70">%</span>
                    </div>
                    
                    <div>
                        <h3 class="text-base md:text-lg font-bold text-slate-900 leading-tight">
                            <?= htmlspecialchars($row['quiz_name']) ?>
                        </h3>
                        <div class="flex items-center text-slate-500 text-xs mt-1.5 font-medium">
                            <i class="far fa-calendar-alt mr-1.5 opacity-70"></i>
                            <?= date('d M Y • h:i A', strtotime($row['created_at'])) ?>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Raw Score & Status Badge -->
                <div class="flex items-center justify-between sm:justify-end gap-6 sm:gap-8 w-full sm:w-auto pt-4 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                    
                    <!-- Raw Score Details -->
                    <div class="text-left sm:text-right">
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Raw Score</p>
                        <p class="text-base font-bold text-slate-900">
                            <?= $row['score'] ?> <span class="text-slate-400 text-sm font-medium">/ <?= $row['total'] ?></span>
                        </p>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold border uppercase tracking-wider <?= $badge_class ?>">
                        <?= $icon ?>
                        <?= $status_text ?>
                    </div>
                    
                </div>
            </div>
            <?php endwhile; ?>
            
        <?php else: ?>
            <!-- Beautiful Empty State -->
            <div class="bg-white rounded-2xl border border-slate-200 border-dashed p-10 md:p-16 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-5 border border-slate-100 shadow-sm">
                    <i class="fas fa-medal text-3xl text-indigo-300"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">No Exams Completed Yet</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto mb-8">You haven't attempted any quizzes. Browse the available assessments to get started and track your progress here!</p>
                <a href="student_quiz.php" class="bg-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 group">
                    Browse Available Quizzes
                    <i class="fas fa-arrow-right text-xs opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-transform"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Micro Footer -->
    <?php if(isset($results) && @mysqli_num_rows($results) > 0): ?>
    <div class="mt-8 text-center">
        <p class="text-xs font-medium text-slate-400 flex items-center justify-center gap-1.5">
            <i class="fas fa-shield-alt opacity-70"></i>
            These records are permanent and cannot be modified.
        </p>
    </div>
    <?php endif; ?>

</main>

</body>
</html>