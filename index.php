<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Modern Learning Management System</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    boxShadow: { 
                        'soft': '0 4px 20px -2px rgba(15, 23, 42, 0.05)',
                        'glow': '0 0 40px -10px rgba(79, 70, 229, 0.4)'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased selection:bg-indigo-100 selection:text-indigo-900 overflow-x-hidden">

    <!-- Subtle Background Elements -->
    <div class="absolute top-0 inset-x-0 h-screen overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-200/40 rounded-full blur-3xl"></div>
        <div class="absolute top-40 -left-20 w-72 h-72 bg-emerald-200/30 rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 md:px-6 h-16 md:h-20 flex items-center justify-between">
            
            <!-- Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-600 rounded-lg md:rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="fas fa-graduation-cap text-sm md:text-base"></i>
                </div>
                <span class="text-lg md:text-xl font-bold tracking-tight text-slate-900">
                    Academy<span class="text-indigo-600">.</span>
                </span>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-2 md:gap-4">
                <a href="login.php" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors px-3 py-2 hidden sm:block">
                    Log in
                </a>
                <a href="Register.php" class="bg-indigo-600 text-white px-4 md:px-5 py-2 md:py-2.5 rounded-lg md:rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 group">
                    Get Started
                    <i class="fas fa-arrow-right text-xs opacity-80 group-hover:translate-x-1 group-hover:opacity-100 transition-transform hidden sm:inline-block"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-16 md:pt-40 md:pb-24 px-4 md:px-6 relative z-10">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            
            <!-- Hero Copy -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-semibold mb-6 mx-auto lg:mx-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    Next-Gen Learning Portal v2.0
                </div>
                
                <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-[1.15] tracking-tight mb-6">
                    Master your skills with <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Smart Assessments.</span>
                </h1>
                
                <p class="text-base md:text-lg text-slate-500 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                    A complete Learning Management System built for modern education. Track progress, take dynamic quizzes, and manage documents in a beautifully clean environment.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="Register.php" class="w-full sm:w-auto px-8 py-3.5 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                        Start Learning Free
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 hover:text-slate-900 transition-all text-center">
                        Explore Features
                    </a>
                </div>
            </div>

            <!-- Hero Abstract UI Mockup (Hidden on mobile for better flow) -->
            <div class="relative hidden lg:block h-[500px] w-full perspective-1000">
                
                <!-- Abstract Dashboard Card (Back) -->
                <div class="absolute top-10 right-0 w-80 bg-white rounded-2xl border border-slate-200 shadow-xl p-6 transform rotate-3 translate-x-4 opacity-90 transition-transform duration-700 hover:rotate-0 hover:translate-x-0">
                    <div class="flex justify-between items-center mb-6">
                        <div class="h-4 w-24 bg-slate-100 rounded-md"></div>
                        <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center">
                            <i class="fas fa-chart-line text-indigo-400 text-xs"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                            <div class="h-3 w-full bg-slate-100 rounded"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <div class="h-3 w-4/5 bg-slate-100 rounded"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-rose-400"></div>
                            <div class="h-3 w-3/5 bg-slate-100 rounded"></div>
                        </div>
                    </div>
                </div>

                <!-- Abstract Quiz Card (Front) -->
                <div class="absolute bottom-10 left-10 w-96 bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 transform -rotate-2 -translate-x-4 transition-transform duration-700 hover:rotate-0 hover:translate-x-0 z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md">
                            <span class="font-bold text-sm">Q1</span>
                        </div>
                        <div class="h-5 w-48 bg-slate-100 rounded-md"></div>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="h-12 w-full rounded-xl border-2 border-indigo-500 bg-indigo-50/50 flex items-center px-4 gap-3">
                            <div class="w-4 h-4 rounded-full bg-indigo-600 flex items-center justify-center border-2 border-indigo-200"></div>
                            <div class="h-3 w-32 bg-indigo-200/70 rounded"></div>
                        </div>
                        <div class="h-12 w-full rounded-xl border border-slate-200 flex items-center px-4 gap-3">
                            <div class="w-4 h-4 rounded-full border-2 border-slate-300"></div>
                            <div class="h-3 w-40 bg-slate-100 rounded"></div>
                        </div>
                    </div>
                    <div class="h-10 w-full bg-slate-900 rounded-lg flex items-center justify-center">
                        <div class="h-3 w-20 bg-slate-700 rounded"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 md:py-24 px-4 md:px-6 bg-white relative z-10">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 tracking-tight">Everything you need in one place.</h2>
                <p class="text-slate-500 text-base max-w-2xl mx-auto">Our platform simplifies learning with these modern core features, designed for both students and instructors.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-white border border-slate-200 hover:border-indigo-200 hover:shadow-soft hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-lg text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Live Quizzes</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">Take timed multiple-choice exams with instant results and automated grading systems.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-white border border-slate-200 hover:border-emerald-200 hover:shadow-soft hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-lg text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Analytics Dashboard</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">Track your performance over time with a clean, visual history of all your taken exams.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-white border border-slate-200 hover:border-amber-200 hover:shadow-soft hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-lg text-amber-500 mb-6 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Doc Management</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">Securely upload and manage your student documentation for verification and records.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Simple Footer -->
    <footer class="py-10 px-6 border-t border-slate-200 bg-slate-50">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-graduation-cap text-indigo-600"></i>
                <span class="font-bold text-slate-900">Academy.</span>
            </div>
            
            <p class="text-slate-500 text-sm">
                &copy; <?= date('Y') ?> LMS Portal. All rights reserved.
            </p>
            
            <div class="flex gap-4">
                <a href="#" class="text-slate-400 hover:text-indigo-600 transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-slate-400 hover:text-indigo-600 transition-colors"><i class="fab fa-github"></i></a>
                <a href="#" class="text-slate-400 hover:text-indigo-600 transition-colors"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>