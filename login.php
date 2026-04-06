<?php
session_start();
require "./config.php";

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {
        $error = "Email and password are required";
    } else {
        $stmt = $conn->prepare("SELECT user_id, full_name, password, role FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name']    = $user['full_name'];
                $_SESSION['role']    = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: student/student_dashboard.php");
                }
                exit;
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LMS Portal</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    boxShadow: { 'soft': '0 4px 20px -2px rgba(15, 23, 42, 0.05)' }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased selection:bg-indigo-100 selection:text-indigo-900 min-h-screen flex flex-col items-center justify-center p-4 md:p-8">

    <!-- Brand / Logo Area -->
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 mb-4">
            <i class="fas fa-graduation-cap text-xl"></i>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Welcome back</h1>
        <p class="text-sm text-slate-500 mt-2">Enter your credentials to access your account.</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white w-full max-w-md rounded-2xl shadow-soft border border-slate-200 overflow-hidden">
        <div class="p-6 md:p-8">

            <!-- Alerts (Errors) -->
            <?php 
            // Suppressing error for UI preview purposes
            if (isset($error) && $error != ""): 
            ?>
                <div class="bg-rose-50 border border-rose-100 text-rose-800 px-4 py-3 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                    <i class="fas fa-exclamation-circle text-rose-500 mt-0.5"></i>
                    <span class="text-sm font-medium leading-relaxed"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                
                <!-- Email Field -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900" placeholder="name@example.com" required>
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <!-- Label & Forgot Password Flexbox -->
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="forget.php" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors tabindex="-1"">Forgot password?</a>
                    </div>
                    
                    <!-- Input with Floating Eye Toggle -->
                    <div class="relative flex items-center">
                        <input type="password" name="password" id="password" class="w-full pr-12 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900" placeholder="••••••••" required>
                        
                        <!-- Toggle Button (Absolute Positioned) -->
                        <button type="button" onclick="togglePassword()" class="absolute right-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 w-8 h-8 rounded-lg flex items-center justify-center transition-colors focus:outline-none" title="Toggle Password Visibility">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="login" class="w-full bg-indigo-600 text-white py-3.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group">
                    Sign In
                    <i class="fas fa-arrow-right text-xs opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-all"></i>
                </button>

            </form>
        </div>
        
        <!-- Footer Register Link -->
        <div class="px-6 py-5 bg-slate-50 border-t border-slate-100 text-center">
            <p class="text-sm font-medium text-slate-600">
                Don't have an account? 
                <a href="Register.php" class="text-indigo-600 hover:text-indigo-700 font-bold hover:underline transition-colors ml-1">Register now</a>
            </p>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
    function togglePassword() {
        const pwd = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");
        
        if (pwd.type === "password") {
            pwd.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
            icon.parentElement.classList.add("text-indigo-600"); // Highlight icon when active
        } else {
            pwd.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
            icon.parentElement.classList.remove("text-indigo-600");
        }
    }
    </script>

</body>
</html>