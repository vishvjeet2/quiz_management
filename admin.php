<?php 
session_start(); 
require 'config.php';

// Security Check: Only Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Stats fetch karein (Real-time numbers)
$total_students = mysqli_num_rows(mysqli_query($conn, "SELECT user_id FROM students WHERE role='user'"));
$total_quizzes  = mysqli_num_rows(mysqli_query($conn, "SELECT quiz_id FROM quizs"));
$total_docs     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM document"));
$total_pages    = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pages"));

include 'header.php'; 
include 'sidebar.php'; 
?>

<main class="flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
    <!-- Header Section -->
    <div class="mb-8 max-w-7xl mx-auto">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">System Overview</h1>
        <p class="text-sm md:text-base text-slate-500 mt-1">Hello Admin, here's what's happening in your academy today.</p>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8 max-w-7xl mx-auto">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-soft hover:border-indigo-200 transition-all duration-300 ease-in-out">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Total Students</p>
                <h2 class="text-2xl font-bold text-slate-900"><?= $total_students ?></h2>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-soft hover:border-amber-200 transition-all duration-300 ease-in-out">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Live Quizzes</p>
                <h2 class="text-2xl font-bold text-slate-900"><?= $total_quizzes ?></h2>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-soft hover:border-teal-200 transition-all duration-300 ease-in-out">
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Submissions</p>
                <h2 class="text-2xl font-bold text-slate-900"><?= $total_docs ?></h2>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-soft hover:border-rose-200 transition-all duration-300 ease-in-out">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Site Pages</p>
                <h2 class="text-2xl font-bold text-slate-900"><?= $total_pages ?></h2>
            </div>
        </div>
    </div>

    <!-- Bottom Section Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 max-w-7xl mx-auto">
        
        <!-- Recent Activity List -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">Recent Student Activity</h3>
                <a href="admin_results.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">View All &rarr;</a>
            </div>
            
            <div class="space-y-4">
                <?php 
                // Using an '@' to suppress errors if the DB isn't connected while you view this, but assuming your original logic works!
                $recent = @mysqli_query($conn, "SELECT r.*, s.full_name, q.quiz_name FROM quiz_results r JOIN students s ON r.user_id = s.user_id JOIN quizs q ON r.quiz_id = q.quiz_id ORDER BY r.created_at DESC LIMIT 5");
                if($recent && mysqli_num_rows($recent) > 0):
                    while($res = mysqli_fetch_assoc($recent)):
                ?>
                <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-transparent hover:border-slate-100 group">
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                            <?= strtoupper(substr($res['full_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900"><?= $res['full_name'] ?></p>
                            <p class="text-xs text-slate-500 mt-0.5">Completed <span class="text-slate-700 font-medium"><?= $res['quiz_name'] ?></span></p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-md border border-indigo-100">
                            <?= round($res['percentage']) ?>%
                        </span>
                        <p class="text-[11px] text-slate-400 font-medium"><?= date('h:i A', strtotime($res['created_at'])) ?></p>
                    </div>
                </div>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <div class="text-center py-6 text-slate-500 text-sm">No recent activity found.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Management (Dark Card) -->
        <!-- Note: Kept the dark theme here because it provides excellent contrast and hierarchy -->
        <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6 flex flex-col h-full">
            <h3 class="text-lg font-bold text-white mb-6">Quick Actions</h3>
            
            <div class="space-y-3 flex-1">
                <a href="admin_users.php" class="flex items-center justify-between p-4 rounded-xl bg-slate-800/80 border border-slate-700/50 hover:bg-indigo-600 hover:border-indigo-500 text-slate-300 hover:text-white transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-plus w-5 text-center text-slate-400 group-hover:text-indigo-200 transition-colors"></i>
                        <span class="text-sm font-medium">Add New Student</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200"></i>
                </a>

                <a href="admin_quiz.php" class="flex items-center justify-between p-4 rounded-xl bg-slate-800/80 border border-slate-700/50 hover:bg-indigo-600 hover:border-indigo-500 text-slate-300 hover:text-white transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-folder-plus w-5 text-center text-slate-400 group-hover:text-indigo-200 transition-colors"></i>
                        <span class="text-sm font-medium">Create New Quiz</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200"></i>
                </a>

                <a href="admin_pages.php" class="flex items-center justify-between p-4 rounded-xl bg-slate-800/80 border border-slate-700/50 hover:bg-indigo-600 hover:border-indigo-500 text-slate-300 hover:text-white transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-sliders-h w-5 text-center text-slate-400 group-hover:text-indigo-200 transition-colors"></i>
                        <span class="text-sm font-medium">Modify Website</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200"></i>
                </a>
            </div>
        </div>

    </div>
</main>

</body>
</html>