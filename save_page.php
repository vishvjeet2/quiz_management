<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'admin') {
    $title   = mysqli_real_escape_string($conn, $_POST['title']);
    $slug    = mysqli_real_escape_string($conn, $_POST['slug']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $status  = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "INSERT INTO pages (title, slug, content, status) 
              VALUES ('$title', '$slug', '$content', '$status')";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_pages.php?msg=PageCreated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_pages.php");
}
?>