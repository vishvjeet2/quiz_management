<?php 
$cp = basename($_SERVER['PHP_SELF']); 

// Helper functions to keep HTML clean and maintain the "Smooth SaaS" look
function getNavClass($page, $cp) {
    $baseClass = "flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group ";
    return ($cp == $page) 
        ? $baseClass . "bg-indigo-50 text-indigo-700" // Active state (Soft indigo background)
        : $baseClass . "text-slate-600 hover:bg-slate-50 hover:text-slate-900"; // Inactive state
}

function getIconClass($page, $cp) {
    $baseClass = "w-5 h-5 mr-3 flex-shrink-0 transition-colors duration-200 ";
    return ($cp == $page) 
        ? $baseClass . "text-indigo-600" // Active icon
        : $baseClass . "text-slate-400 group-hover:text-slate-500"; // Inactive icon
}
?>

<aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col sticky top-0 h-screen">
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100">
        <i class="fas fa-graduation-cap text-indigo-600 text-xl mr-2"></i>
        <span class="text-slate-900 text-xl font-bold tracking-tight">Admin<span class="text-indigo-600">.</span></span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-6 px-4 space-y-1.5 overflow-y-auto">
        
        <a href="admin_users.php" class="<?= getNavClass('admin_users.php', $cp) ?>">
            <i class="fas fa-users <?= getIconClass('admin_users.php', $cp) ?>"></i>
            Users
        </a>

        <a href="admin_docs.php" class="<?= getNavClass('admin_docs.php', $cp) ?>">
            <i class="fas fa-file-invoice <?= getIconClass('admin_docs.php', $cp) ?>"></i>
            Documents
        </a>

        <a href="admin_quiz.php" class="<?= getNavClass('admin_quiz.php', $cp) ?>">
            <i class="fas fa-lightbulb <?= getIconClass('admin_quiz.php', $cp) ?>"></i>
            Quizzes
        </a>

        <a href="admin_results.php" class="<?= getNavClass('admin_results.php', $cp) ?>">
            <i class="fas fa-chart-bar <?= getIconClass('admin_results.php', $cp) ?>"></i>
            All Results
        </a>

        <a href="admin_pages.php" class="<?= getNavClass('admin_pages.php', $cp) ?>">
            <i class="fas fa-pager <?= getIconClass('admin_pages.php', $cp) ?>"></i>
            Site Pages
        </a>

        <a href="admin_contact.php" class="<?= getNavClass('admin_contact.php', $cp) ?>">
            <i class="fas fa-envelope <?= getIconClass('admin_contact.php', $cp) ?>"></i>
            Inbox
        </a>

    </nav>

    <!-- Logout Area -->
    <div class="p-4 border-t border-slate-100 mt-auto">
        <a href="logout.php" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 group">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-slate-400 group-hover:text-red-500 transition-colors duration-200"></i>
            Logout
        </a>
    </div>
</aside>