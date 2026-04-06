<?php
require "config.php";
session_start();

// 1. Security Check: Only Admins can add questions
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. Validate Quiz ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid quiz ID.");
}

$quiz_id = intval($_GET['id']);
$error = "";
$success = "";

// 3. Fetch Quiz Details to verify it exists
$stmt = $conn->prepare("SELECT quiz_name FROM quizs WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();

if (!$quiz) {
    die("Quiz not found in database.");
}

// 4. Handle Form Submission
if (isset($_POST['submit'])) {
    // Collect and trim data
    $question = trim($_POST['question']);
    $a = trim($_POST['option_a']);
    $b = trim($_POST['option_b']);
    $c = trim($_POST['option_c']);
    $d = trim($_POST['option_d']);
    $answer = $_POST['correct_answer'] ?? "";

    // Validation
    if ($question == "" || $a == "" || $b == "" || $c == "" || $d == "") {
        $error = "All fields are required.";
    } elseif (!in_array($answer, ['A', 'B', 'C', 'D'])) {
        $error = "Please select a valid correct answer (A, B, C, or D).";
    } else {
        // Insert Question
        $stmt = $conn->prepare(
            "INSERT INTO mcq_questions 
            (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("issssss", $quiz_id, $question, $a, $b, $c, $d, $answer);

        if ($stmt->execute()) {
            // Success! We redirect to the same page with a success flag 
            // to prevent "Confirm Form Resubmission" on refresh.
            header("Location: add_question.php?id=$quiz_id&status=success");
            exit;
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}

// 5. Check for success message from redirect
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $success = "Question added successfully!";
}
include 'header.php'; 
include 'sidebar.php';
?>



<!-- <body class="bg-slate-50 font-sans text-slate-900 antialiased selection:bg-indigo-100 selection:text-indigo-900 min-h-screen flex flex-col"> -->

    <main class="flex-1 p-4 md:p-6 lg:p-8 w-full flex justify-center items-start pt-10 md:pt-16">
        
        <!-- Max-width container to keep the form readable -->
        <div class="w-full max-w-3xl">
            
            <!-- Breadcrumb / Back Link -->
            <a href="admin_quiz.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-6 group">
                <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i> 
                Back to Quizzes
            </a>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <!-- Card Header -->
                <div class="px-6 md:px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-question-circle text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Add New Question</h2>
                            <p class="text-sm text-slate-500 mt-0.5">Quiz: <span class="font-semibold text-slate-700"><?= htmlspecialchars($quiz['quiz_name'] ?? 'Unknown Quiz') ?></span></p>
                        </div>
                    </div>
                    <a href="view_quiz.php?id=<?= $quiz_id ?>" class="text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition-colors border border-indigo-100/50">
                        View Quiz Questions
                    </a>
                </div>

                <!-- Smooth Alerts (Only show if set) -->
                <?php if (!empty($error)): ?>
                    <div class="mx-6 md:mx-8 mt-6 bg-rose-50 border border-rose-100 text-rose-800 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm">
                        <i class="fas fa-exclamation-circle text-rose-500 mt-0.5"></i>
                        <span class="text-sm font-medium leading-relaxed"><?= $error ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="mx-6 md:mx-8 mt-6 bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span class="text-sm font-medium"><?= $success ?></span>
                    </div>
                <?php endif; ?>

                <!-- Form Body -->
                <form method="post" class="p-6 md:p-8">
                    
                    <!-- Main Question -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-800 mb-2">Question Text</label>
                        <textarea name="question" rows="3" placeholder="What is the output of..." class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900 resize-y" required></textarea>
                    </div>

                    <!-- Options Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        
                        <!-- Option A -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-black border border-slate-200">A</span>
                                Option A
                            </label>
                            <input type="text" name="option_a" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-900" required>
                        </div>

                        <!-- Option B -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-black border border-slate-200">B</span>
                                Option B
                            </label>
                            <input type="text" name="option_b" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-900" required>
                        </div>

                        <!-- Option C -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-black border border-slate-200">C</span>
                                Option C
                            </label>
                            <input type="text" name="option_c" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-900" required>
                        </div>

                        <!-- Option D -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-black border border-slate-200">D</span>
                                Option D
                            </label>
                            <input type="text" name="option_d" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-900" required>
                        </div>

                    </div>

                    <hr class="border-slate-100 mb-8">

                    <!-- Correct Answer Selector -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-800 mb-2">Correct Answer</label>
                        <select name="correct_answer" class="w-full md:w-1/2 px-4 py-3 rounded-xl border border-emerald-200 bg-emerald-50/30 focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all text-sm font-medium text-slate-700 cursor-pointer" required>
                            <option value="">-- Select the correct option --</option>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">Select the option that will be marked as correct during the quiz.</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" name="submit" class="w-full bg-indigo-600 text-white py-3.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group">
                        <i class="fas fa-plus-circle opacity-80 group-hover:opacity-100 transition-opacity"></i>
                        Save Question to Quiz
                    </button>

                </form>

            </div>
            
            <!-- Micro-footer -->
            <div class="text-center mt-8">
                <p class="text-xs font-medium text-slate-400">Questions are updated immediately upon saving.</p>
            </div>

        </div>
    </main>


