<?php $cp = basename($_SERVER['PHP_SELF']); ?>
<?php 
// Ensure $cp is defined if not already included
$cp = basename($_SERVER['PHP_SELF']); 

// Clean PHP Helper Functions for SaaS UI
function getStudentNavClass($page, $cp) {
    $baseClass = "flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group ";
    return ($cp == $page) 
        ? $baseClass . "bg-indigo-50 text-indigo-700" // Active: Soft Indigo
        : $baseClass . "text-slate-600 hover:bg-slate-50 hover:text-slate-900"; // Inactive: Slate to Dark Slate
}

function getStudentIconClass($page, $cp) {
    $baseClass = "w-5 h-5 mr-3 flex-shrink-0 transition-colors duration-200 ";
    return ($cp == $page) 
        ? $baseClass . "text-indigo-600" // Active Icon
        : $baseClass . "text-slate-400 group-hover:text-slate-500"; // Inactive Icon
}
?>

<!-- Clean, White SaaS Sidebar -->
<aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col sticky top-0 h-screen">
    
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100">
        <i class="fas fa-user-graduate text-indigo-600 text-xl mr-2.5"></i>
        <span class="text-slate-900 text-xl font-bold tracking-tight">Student<span class="text-indigo-600">.</span></span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-6 px-4 space-y-1.5 overflow-y-auto">
        
        <a href="student_dashboard.php" class="<?= getStudentNavClass('student_dashboard.php', $cp) ?>">
            <i class="fas fa-th-large <?= getStudentIconClass('student_dashboard.php', $cp) ?>"></i>
            Dashboard
        </a>

        <a href="student_quiz.php" class="<?= getStudentNavClass('student_quiz.php', $cp) ?>">
            <i class="fas fa-lightbulb <?= getStudentIconClass('student_quiz.php', $cp) ?>"></i>
            Available Quizzes
        </a>

        <a href="student_results.php" class="<?= getStudentNavClass('student_results.php', $cp) ?>">
            <i class="fas fa-poll <?= getStudentIconClass('student_results.php', $cp) ?>"></i>
            My Results
        </a>

        <a href="student_docs.php" class="<?= getStudentNavClass('student_docs.php', $cp) ?>">
            <i class="fas fa-file-upload <?= getStudentIconClass('student_docs.php', $cp) ?>"></i>
            My Documents
        </a>

    </nav>

    <!-- Support Link (Optional but highly recommended for SaaS Student portals) -->
    <div class="px-4 pb-2">
        <a href="student_support.php" class="<?= getStudentNavClass('student_support.php', $cp) ?>">
            <i class="fas fa-life-ring <?= getStudentIconClass('student_support.php', $cp) ?>"></i>
            Help & Support
        </a>
    </div>

    <!-- Logout Area -->
    <div class="p-4 border-t border-slate-100 mt-auto">
        <a href="../logout.php" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-rose-50 hover:text-rose-600 transition-all duration-200 group">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-slate-400 group-hover:text-rose-500 transition-colors duration-200"></i>
            Logout
        </a>
    </div>
    
</aside>