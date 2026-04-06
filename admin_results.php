<?php 
session_start(); require 'config.php';
if($_SESSION['role'] !== 'admin') header('Location: login.php');

$query = "SELECT r.*, u.full_name, q.quiz_name FROM quiz_results r 
          JOIN students u ON r.user_id = u.user_id 
          JOIN quizs q ON r.quiz_id = q.quiz_id ORDER BY r.created_at DESC";
$results = mysqli_query($conn, $query);

include 'header.php'; include 'sidebar.php'; 
?>
<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full overflow-y-auto">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Exam Analytics</h1>
            <p class="text-sm text-slate-500 mt-1">Track student performance across all quizzes.</p>
        </div>
        <!-- Added a mock Export button - very common in SaaS analytics -->
        <button class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-slate-900 px-4 py-2.5 rounded-xl font-medium transition-all shadow-sm flex items-center gap-2 text-sm">
            <i class="fas fa-download text-slate-400"></i>
            Export CSV
        </button>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Quiz Name</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Completion Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    // Suppressing error for UI preview
                    if(isset($results) && @mysqli_num_rows($results) > 0):
                        while($row = @mysqli_fetch_assoc($results)): 
                            // Refined SaaS colors for Pass/Fail badges
                            if($row['percentage'] >= 50) {
                                $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                                $icon = '<i class="fas fa-check-circle mr-1.5 opacity-70"></i>';
                            } else {
                                $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200/60';
                                $icon = '<i class="fas fa-times-circle mr-1.5 opacity-70"></i>';
                            }
                    ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        
                        <!-- Student Column with Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shadow-sm">
                                    <?= strtoupper(substr($row['full_name'], 0, 1)) ?>
                                </div>
                                <span class="font-semibold text-slate-900 text-sm"><?= htmlspecialchars($row['full_name']) ?></span>
                            </div>
                        </td>

                        <!-- Quiz Name Column -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-700"><?= htmlspecialchars($row['quiz_name']) ?></div>
                        </td>

                        <!-- Score Column (Enhanced Badge) -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border <?= $badgeClass ?>">
                                <?= $icon ?>
                                <?= $row['score'] ?>/<?= $row['total'] ?> 
                                <span class="ml-1 opacity-80 font-medium">(<?= round($row['percentage']) ?>%)</span>
                            </span>
                        </td>

                        <!-- Date Column -->
                        <td class="px-6 py-4 text-right">
                            <span class="text-slate-500 text-sm font-medium">
                                <?= date('d M, Y', strtotime($row['created_at'])) ?>
                            </span>
                            <div class="text-xs text-slate-400 mt-0.5">
                                <?= date('h:i A', strtotime($row['created_at'])) ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <!-- Beautiful "Empty State" -->
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                                    <i class="fas fa-inbox text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 mb-1">No exam records found</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto">When students complete their quizzes, their performance analytics will appear here automatically.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Footer / Pagination Area -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-sm text-slate-500">
            <span>Analytics sync automatically.</span>
        </div>
    </div>

</main>

<!-- Closing tags from your original file -->
</body>
</html>