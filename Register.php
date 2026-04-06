<?php
require "config.php";

$success = false;
$errors = [];

// ----------------------- Load city dropdown (AJAX) ---------------------
if (isset($_POST['loadCity'])) {
    $state_id = mysqli_real_escape_string($conn, $_POST['state_id']);
    $q = mysqli_query($conn, "SELECT c_id, city_name FROM city WHERE state_id = '$state_id'");
    echo "<option value=''>--Select City--</option>";
    if (mysqli_num_rows($q) > 0) {
        while ($row = mysqli_fetch_assoc($q)) {
            echo "<option value='".$row['c_id']."'>".$row['city_name']."</option>";
        }
    } else {
        echo "<option value=''>No cities found</option>";
    }
    exit; // Stop further execution for AJAX request
}

if (isset($_POST['submit'])) {
    // Trim inputs
    $first_name = trim($_POST['fname']);
    $last_name  = trim($_POST['lname']);
    $email      = trim($_POST['email']);
    $gender     = $_POST['gender'] ?? '';
    $state      = $_POST['state'] ?? '';
    $city       = $_POST['city'] ?? '';
    $password   = $_POST['password'];

    // 1. Email Exists Check
    $checkEmail = $conn->prepare("SELECT user_id FROM students WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    
    if ($checkEmail->num_rows > 0) {
        $errors[] = "Email already exists. Please login.";
    }

    // 2. Simple Validations
    if ($first_name == '') $errors[] = "First name is required";
    if ($email == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if ($gender == '') $errors[] = "Please select gender";
    if ($state == '' || $city == '') $errors[] = "State and City are required";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";

    // 3. Insert Data
    if (empty($errors)) {
        $full_name = $first_name . " " . $last_name;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Note: Adding 'user' as default role
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, gender, state_id, city_id, password, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");
        $stmt->bind_param("sssiss", $full_name, $email, $gender, $state, $city, $hashed_password);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Registration failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | LMS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<body class="bg-slate-50 font-sans text-slate-900 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white shadow-lg mb-4">
            <i class="fas fa-graduation-cap text-xl"></i>
        </div>
        <h1 class="text-2xl font-bold tracking-tight">Create your account</h1>
    </div>

    <div class="bg-white w-full max-w-xl rounded-2xl shadow-soft border border-slate-200 overflow-hidden">
        <div class="p-8">
            <?php if (!empty($errors)): ?>
                <div class="bg-rose-50 border border-rose-100 text-rose-800 px-4 py-3 rounded-xl mb-6">
                    <ul class="text-sm font-medium list-disc list-inside">
                        <?php foreach ($errors as $error) echo "<li>$error</li>"; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span class="text-sm font-medium">Registration successful! <a href="login.php" class="underline font-bold">Log in now</a></span>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">First Name</label>
                        <input type="text" name="fname" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white outline-none focus:border-indigo-500 transition-all text-sm" placeholder="Jane" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Last Name</label>
                        <input type="text" name="lname" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white outline-none focus:border-indigo-500 transition-all text-sm" placeholder="Doe">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                        <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white outline-none focus:border-indigo-500 transition-all text-sm" placeholder="jane@example.com" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gender</label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer"><input type="radio" name="gender" value="male" class="peer sr-only" checked><div class="px-4 py-2.5 rounded-xl border border-slate-200 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-all text-sm font-medium">Male</div></label>
                            <label class="flex-1 cursor-pointer"><input type="radio" name="gender" value="female" class="peer sr-only"><div class="px-4 py-2.5 rounded-xl border border-slate-200 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-all text-sm font-medium">Female</div></label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">State</label>
                        <select id="state" name="state" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white outline-none focus:border-indigo-500 transition-all text-sm text-slate-700" required>
                            <option value="">Select State...</option>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM state");
                            while ($row = mysqli_fetch_assoc($res)) echo "<option value='".$row['s_id']."'>".$row['s_name']."</option>";
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">City</label>
                        <select id="city" name="city" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white outline-none focus:border-indigo-500 transition-all text-sm text-slate-700" required>
                            <option value="">Select City...</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Create Password</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white outline-none focus:border-indigo-500 transition-all text-sm" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" name="submit" class="w-full bg-indigo-600 text-white py-3.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-md">Create Account</button>
            </form>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-center">
            <p class="text-sm font-medium text-slate-600">Already have an account? <a href="login.php" class="text-indigo-600 font-bold hover:underline">Log in here</a></p>
        </div>
    </div>

    <script>
    $(document).ready(function(){
        $("#state").change(function () {
            let state_id = $(this).val();
            if (state_id !== "") {
                $.ajax({
                    url: "Register.php", 
                    type: "POST",
                    data: { loadCity: true, state_id: state_id },
                    success: function (data) { $("#city").html(data); }
                });
            } else {
                $("#city").html("<option value=''>--Select City--</option>");
            }
        });
    });
    </script>
</body>
</html>