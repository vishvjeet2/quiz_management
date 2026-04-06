<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']);
    $filename = $_FILES['doc']['name'];
    $tempname = $_FILES['doc']['tmp_name'];
    
    $new_filename = time() . "_" . $filename;
    $folder = "uploads/" . $new_filename;

    if (move_uploaded_file($tempname, $folder)) {
        // Now inserting user_id instead of just a name string
        $query = "INSERT INTO document (user_id, doc) VALUES ($user_id, '$new_filename')";
        mysqli_query($conn, $query);
        header("Location: admin_docs.php?msg=success");
    }
}
?>