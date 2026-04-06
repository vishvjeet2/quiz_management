<?php
require 'config.php';

echo "<h2>Database Seeding Started...</h2>";

// 1. Disable Foreign Key Checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

/* ---------------- 2. CREATE TABLES IF THEY DON'T EXIST ---------------- */

// State Table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `state` (
    `s_id` INT PRIMARY KEY AUTO_INCREMENT,
    `s_name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// City Table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `city` (
    `c_id` INT PRIMARY KEY AUTO_INCREMENT,
    `city_name` VARCHAR(100) NOT NULL,
    `state_id` INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Students Table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `students` (
    `user_id` INT PRIMARY KEY AUTO_INCREMENT,
    `full_name` VARCHAR(255),
    `email` VARCHAR(255) UNIQUE,
    `password` VARCHAR(255),
    `gender` ENUM('male', 'female'),
    `state_id` INT,
    `city_id` INT,
    `role` ENUM('admin', 'user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Quizs Table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `quizs` (
    `quiz_id` INT PRIMARY KEY AUTO_INCREMENT,
    `quiz_name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// MCQ Questions Table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `mcq_questions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `quiz_id` INT,
    `question` TEXT,
    `option_a` VARCHAR(255),
    `option_b` VARCHAR(255),
    `option_c` VARCHAR(255),
    `option_d` VARCHAR(255),
    `correct_answer` ENUM('A','B','C','D')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Quiz Results Table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `quiz_results` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT,
    `quiz_id` INT,
    `score` INT,
    `total` INT,
    `percentage` DECIMAL(5,2),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo "✓ All table structures verified/created.<br>";

/* ---------------- 3. CLEAR OLD DATA ---------------- */
$tables = ['state', 'city', 'students', 'quizs', 'mcq_questions', 'quiz_results'];
foreach ($tables as $table) {
    mysqli_query($conn, "TRUNCATE TABLE $table");
}
echo "✓ Old data cleared.<br>";

mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

/* ---------------- 4. INSERT FRESH DATA ---------------- */

// Insert States
mysqli_query($conn, "INSERT INTO state (s_id, s_name) VALUES (1, 'Maharashtra'), (2, 'Gujarat'), (3, 'Rajasthan')");

// Insert Cities
mysqli_query($conn, "INSERT INTO city (c_id, city_name, state_id) VALUES (1, 'Mumbai', 1), (2, 'Ahmedabad', 2), (3, 'Jaipur', 3)");

// Insert Users
$pass = password_hash('password123', PASSWORD_BCRYPT);
mysqli_query($conn, "INSERT INTO students (full_name, email, password, gender, state_id, city_id, role) 
    VALUES ('System Admin', 'admin@gmail.com', '$pass', 'male', 1, 1, 'admin')");

for($i=1; $i<=5; $i++) {
    mysqli_query($conn, "INSERT INTO students (full_name, email, password, gender, state_id, city_id, role) 
        VALUES ('Student $i', 'student$i@gmail.com', '$pass', 'male', 1, 1, 'user')");
}

// Insert Quiz & Sample Question
mysqli_query($conn, "INSERT INTO quizs (quiz_id, quiz_name) VALUES (1, 'General Knowledge')");
mysqli_query($conn, "INSERT INTO mcq_questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer) 
    VALUES (1, 'What is the capital of India?', 'Mumbai', 'New Delhi', 'Kolkata', 'Chennai', 'B')");

echo "<h3>✓ Seeding Successful!</h3>";
echo "<b>Admin:</b> admin@gmail.com | <b>Pass:</b> password123";
?>