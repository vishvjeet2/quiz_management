<?php
session_start();
require 'config.php';

// Security: Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$quizzes = mysqli_query($conn, "SELECT * FROM quizs");
include 'header.php';
include 'sidebar.php';
?>

<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">

    <!-- Smooth Success Alert -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'quizDeleted'): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="font-medium text-sm">Quiz has been deleted successfully!</span>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Quizzes</h1>
            <p class="text-sm text-slate-500 mt-1">Create and manage your course assessments.</p>
        </div>
        <button onclick="openModal('quizModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i>
            Create Quiz
        </button>
    </div>

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php
        // Suppressing errors for UI preview
        if (isset($quizzes) && @mysqli_num_rows($quizzes) > 0):
            while ($q = @mysqli_fetch_assoc($quizzes)):
        ?>
                <!-- Quiz Card -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between group hover:-translate-y-1 hover:shadow-soft hover:border-indigo-200 transition-all duration-300">

                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fas fa-lightbulb text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 line-clamp-1" title="<?= htmlspecialchars($q['quiz_name']) ?>">
                                    <?= htmlspecialchars($q['quiz_name']) ?>
                                </h3>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-200/60">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-1.5">
                        <!-- Add Question -->
                        <a href="add_question.php?id=<?= $q['quiz_id'] ?>" class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors" title="Add Question">
                            <i class="fas fa-plus"></i>
                        </a>

                        <!-- View Questions -->
                        <button onclick="viewQuizQuestions(<?= $q['quiz_id'] ?>, '<?= addslashes(htmlspecialchars($q['quiz_name'])) ?>')"
                            class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="View Questions">
                            <i class="fas fa-eye"></i>
                        </button>

                        <!-- Delete Quiz -->
                        <a href="delete_quiz.php?id=<?= $q['quiz_id'] ?>"
                            onclick="return confirm('Are you sure you want to delete this quiz? This cannot be undone.')"
                            class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Delete Quiz">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            <?php
            endwhile;
        else:
            ?>
            <!-- Empty State if no quizzes exist -->
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 border-dashed p-12 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                    <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">No quizzes yet</h3>
                <p class="text-sm text-slate-500 max-w-sm mb-6">Create your first quiz to start evaluating your students.</p>
                <button onclick="openModal('quizModal')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-lg transition-colors">
                    Create Quiz Now
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Create Quiz Modal -->
    <div id="quizModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-soft border border-slate-200 overflow-hidden text-left relative transform transition-all sm:my-8">

                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-bold text-slate-900">Create New Quiz</h3>
                    <button onclick="closeModal('quizModal')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="save_quiz.php" method="POST" class="p-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Quiz Title</label>
                        <input type="text" name="quiz_name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900" placeholder="e.g. JavaScript Fundamentals" required>
                    </div>

                    <div class="mt-8 pt-5 border-t border-slate-100 flex gap-3 justify-end">
                        <button type="button" onclick="closeModal('quizModal')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                            Create Quiz
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- View Questions Modal -->
    <div id="viewQuizModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-soft border border-slate-200 overflow-hidden text-left relative transform transition-all sm:my-8">

                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fas fa-list-ul text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900" id="viewQuizTitle">Quiz Questions</h3>
                    </div>
                    <button onclick="closeModal('viewQuizModal')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 max-h-[65vh] overflow-y-auto bg-slate-50/50" id="questionsContainer">
                    <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                        <i class="fas fa-circle-notch fa-spin text-2xl mb-3 text-indigo-400"></i>
                        <span class="text-sm font-medium">Loading questions...</span>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-end">
                    <button onclick="closeModal('viewQuizModal')" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 shadow-sm transition-all">
                        Close Window
                    </button>
                </div>

            </div>
        </div>
    </div>


</main>
<script>
    // Combined Modal Logic
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
    }

    function viewQuizQuestions(quizId, quizName) {
        document.getElementById('viewQuizTitle').innerText = "Questions for: " + quizName;
        document.getElementById('viewQuizModal').classList.remove('hidden');
        document.getElementById('questionsContainer').innerHTML = '<div class="text-center py-10 text-slate-400">Loading questions...</div>';

        // Fetch questions using AJAX
        fetch('fetch_questions.php?id=' + quizId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('questionsContainer').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('questionsContainer').innerHTML = '<p class="text-red-500 text-center">Error loading questions.</p>';
            });
    }
</script>
</body>

</html>