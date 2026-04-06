<?php 
session_start(); 
require '../config.php';
if($_SESSION['role'] !== 'user') header('Location: ../login.php');

$quiz_id = intval($_GET['id']);
$questions = mysqli_query($conn, "SELECT * FROM mcq_questions WHERE quiz_id = $quiz_id");
$quiz_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT quiz_name FROM quizs WHERE quiz_id = $quiz_id"));

include '../header.php'; 
?>
<!-- Note: If you don't have the global body classes from header.php here, make sure your wrapper has standard text colors -->
<div class="min-h-screen bg-slate-50 flex flex-col font-sans antialiased text-slate-900 selection:bg-indigo-100 selection:text-indigo-900">
    
    <!-- Sticky Quiz Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-8 py-3 md:py-4 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div>
            <h2 class="text-base md:text-lg font-bold text-slate-900 tracking-tight leading-tight">
                <?= htmlspecialchars($quiz_info['quiz_name']) ?>
            </h2>
            <div class="flex items-center gap-2 mt-0.5">
                <!-- Tiny pulsing indicator for "Live" status -->
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Live Assessment</p>
            </div>
        </div>
        
        <!-- Timer Badge -->
        <div class="bg-rose-50 text-rose-700 px-3 md:px-4 py-1.5 md:py-2 rounded-xl font-mono font-bold text-sm md:text-base border border-rose-200/60 flex items-center shadow-sm">
            <i class="fas fa-clock mr-2 opacity-70"></i> 
            <span id="timer">10:00</span>
        </div>
    </header>

    <main class="flex-1 max-w-3xl mx-auto w-full p-4 md:p-6 lg:py-10">
        <form action="submit_quiz.php" method="POST" id="quizForm">
            <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
            
            <?php 
            $count = 1; 
            // Suppressed error for UI rendering
            if(isset($questions)):
                while($q = @mysqli_fetch_assoc($questions)): 
            ?>
            <!-- Question Card -->
            <div class="bg-white p-6 md:p-8 rounded-2xl mb-6 md:mb-8 border border-slate-200 shadow-sm hover:shadow-soft transition-shadow duration-300">
                
                <!-- Question Header -->
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                        <?= $count ?>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold text-slate-900 leading-snug pt-0.5">
                        <?= htmlspecialchars($q['question']) ?>
                    </h3>
                </div>

                <!-- Options Grid -->
                <div class="grid grid-cols-1 gap-3">
                    <?php foreach(['a', 'b', 'c', 'd'] as $opt): ?>
                    <!-- The Option Label (acts as the clickable button) -->
                    <label class="relative flex items-center p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all group has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                        
                        <!-- Hidden Radio Input -->
                        <input type="radio" name="q<?= $q['q_id'] ?? '' ?>" value="<?= strtoupper($opt) ?>" class="peer sr-only" required>
                        
                        <!-- Custom CSS Radio Button -->
                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 mr-4 flex items-center justify-center group-hover:border-indigo-400 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition-all flex-shrink-0">
                            <!-- Inner white dot that scales up when checked -->
                            <div class="w-2 h-2 rounded-full bg-white scale-0 peer-checked:scale-100 transition-transform duration-200 ease-out"></div>
                        </div>
                        
                        <!-- Option Text -->
                        <span class="text-sm font-medium text-slate-700 peer-checked:text-indigo-900 transition-colors">
                            <?= htmlspecialchars($q['option_'.$opt] ?? '') ?>
                        </span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php 
                $count++; 
                endwhile; 
            endif;
            ?>

            <!-- Submission Confirmation Card -->
            <div class="mt-10 mb-20 bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center">
                <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4 border border-slate-100 text-xl">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-2">Ready to submit?</h4>
                <p class="text-sm text-slate-500 mb-6 max-w-sm">Make sure you have answered all questions. You cannot change your answers after submitting.</p>
                
                <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group">
                    Finish & Submit Quiz
                    <i class="fas fa-paper-plane text-xs opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all"></i>
                </button>
            </div>
        </form>
    </main>
</div>

<script>
// Simple Timer Logic
let time = 600; // 10 minutes
const timerDisplay = document.getElementById('timer');

const countdown = setInterval(() => {
    let minutes = Math.floor(time / 60);
    let seconds = time % 60;
    timerDisplay.innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    if (time <= 0) {
        clearInterval(countdown);
        document.getElementById('quizForm').submit();
    }
    time--;
}, 1000);
</script>
<style>
    /* Styling the radio check effect */
    input[type="radio"]:checked + span span { scale: 1; }
</style>
</body>
</html>