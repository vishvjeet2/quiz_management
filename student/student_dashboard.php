<?php 
session_start(); 
require '../config.php';
if($_SESSION['role'] !== 'user') header('Location: login.php');

$user_id = $_SESSION['user_id'];
// Stats fetch karein
$quiz_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM quiz_results WHERE user_id = $user_id"));
$doc_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM document WHERE user_id = $user_id"));

include '../header.php'; 
include 'student_sidebar.php'; 
?>
<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">
            Welcome back, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Student') ?>!
        </h1>
        <p class="text-sm text-slate-500 mt-1">Track your learning progress and upcoming exams.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8 md:mb-10">
        
        <!-- Stat Card 1: Quizzes -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-soft hover:border-indigo-200 transition-all duration-300 ease-in-out">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg flex-shrink-0 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                <i class="fas fa-spell-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Quizzes Taken</p>
                <h2 class="text-2xl font-bold text-slate-900 leading-none"><?= $quiz_count ?? 0 ?></h2>
            </div>
        </div>

        <!-- Stat Card 2: Documents -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-soft hover:border-emerald-200 transition-all duration-300 ease-in-out">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg flex-shrink-0 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                <i class="fas fa-file-upload"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Docs Uploaded</p>
                <h2 class="text-2xl font-bold text-slate-900 leading-none"><?= $doc_count ?? 0 ?></h2>
            </div>
        </div>

        <!-- Stat Card 3: Account Status -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-soft hover:border-amber-200 transition-all duration-300 ease-in-out">
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-lg flex-shrink-0 transition-colors group-hover:bg-amber-500 group-hover:text-white">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Account Status</p>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold">
                    <i class="fas fa-check-circle text-[10px]"></i> Verified
                </div>
            </div>
        </div>

    </div>

    <!-- Premium Call-to-Action Banner -->
    <!-- We use Slate-900 (Dark theme) to create a striking, premium contrast on the dashboard -->
    <div class="bg-slate-900 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-lg border border-slate-800 relative overflow-hidden group">
        
        <!-- Decorative Background Glow (Pure CSS effect) -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>

        <!-- Text Content -->
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-[10px] font-bold uppercase tracking-wider mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                New Assessments
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-white mb-1.5">Ready for a new challenge?</h2>
            <p class="text-sm text-slate-400 max-w-md">Check out the latest quizzes and course materials added by your instructor to continue your learning journey.</p>
        </div>
        
        <!-- Action Button -->
        <div class="relative z-10 w-full md:w-auto">
            <a href="student_quiz.php" class="w-full md:w-auto bg-indigo-500 text-white px-6 py-3 md:py-3.5 rounded-xl text-sm font-bold hover:bg-indigo-400 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                Browse Quizzes
                <i class="fas fa-arrow-right text-xs opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all"></i>
            </a>
        </div>

    </div>
</main>
</body>
</html>