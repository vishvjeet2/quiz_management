<?php 
session_start(); 
require '../config.php';
if($_SESSION['role'] !== 'user') header('Location: ../login.php');

$user_id = $_SESSION['user_id'];

// Get quizzes NOT yet taken by this student
$query = "SELECT * FROM quizs WHERE quiz_id NOT IN 
          (SELECT quiz_id FROM quiz_results WHERE user_id = $user_id)";
$quizzes = mysqli_query($conn, $query);

include '../header.php'; include 'student_sidebar.php'; 
?>
<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">
    
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Available Exams</h1>
            <p class="text-sm text-slate-500 mt-1">Select an active assessment to begin.</p>
        </div>
        <!-- Little SaaS status indicator -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-semibold">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            System Live
        </div>
    </div>

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php 
        // Suppressing errors for UI preview
        if(isset($quizzes) && @mysqli_num_rows($quizzes) > 0): 
            while($q = @mysqli_fetch_assoc($quizzes)): 
        ?>
            <!-- Quiz Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-soft hover:-translate-y-1 hover:border-indigo-200 transition-all duration-300 flex flex-col justify-between group">
                
                <div>
                    <!-- Card Header -->
                    <div class="flex items-start justify-between mb-5">
                        <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xl flex-shrink-0 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <!-- Tiny format badge -->
                        <span class="px-2.5 py-1 bg-slate-50 border border-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded-md">
                            Standard
                        </span>
                    </div>
                    
                    <!-- Title & Description -->
                    <h3 class="text-lg font-bold text-slate-900 leading-tight mb-2 line-clamp-2">
                        <?= htmlspecialchars($q['quiz_name']) ?>
                    </h3>
                    <p class="text-sm text-slate-500 font-medium mb-6">
                        Multiple choice format. Make sure you have a stable connection before starting.
                    </p>
                </div>
                
                <!-- Card Footer / Action -->
                <div class="pt-5 border-t border-slate-100 mt-auto">
                    <a href="take_quiz.php?id=<?= $q['quiz_id'] ?>" class="w-full bg-indigo-50 text-indigo-700 border border-indigo-100 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all flex items-center justify-center gap-2 group/btn">
                        Start Assessment 
                        <i class="fas fa-arrow-right text-xs opacity-70 group-hover/btn:translate-x-1 group-hover/btn:opacity-100 transition-all"></i>
                    </a>
                </div>

            </div>
            <?php endwhile; ?>
            
        <?php else: ?>
            <!-- Beautiful Empty State -->
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 border-dashed p-10 md:p-16 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                    <i class="fas fa-check-double text-3xl text-emerald-500"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">You're all caught up!</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">There are no pending quizzes or active assessments assigned to you at the moment. Relax and check back later.</p>
                
                <a href="student_dashboard.php" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-5 py-2.5 rounded-xl transition-colors">
                    Return to Dashboard
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>