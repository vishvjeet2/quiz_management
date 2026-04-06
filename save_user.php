<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'admin') {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'];
    $state = intval($_POST['state_id']);
    $city = intval($_POST['city_id']);

    $query = "INSERT INTO students (full_name, email, password, gender, state_id, city_id, role) 
              VALUES ('$name', '$email', '$pass', '$gender', $state, $city, 'user')";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_users.php?msg=UserAdded");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>