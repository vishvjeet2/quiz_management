<?php
session_start();
require '../config.php'; // Ek folder piche jaakar config uthao

// 1. Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['doc'])) {
    
    // 2. AUTO-ASSIGN: Session se ID nikaalo
    $user_id = $_SESSION['user_id']; 
    
    $filename = $_FILES['doc']['name'];
    $tempname = $_FILES['doc']['tmp_name'];
    
    // File name ko unique banayein (Current Time + Original Name)
    $new_filename = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $filename);
    
    // Path: admin/uploads/ folder mein save hogi
    $folder = "../uploads/" . $new_filename;

    // 3. Upload process
    if (move_uploaded_file($tempname, $folder)) {
        // Database mein entry (user_id session se aa rahi hai, auto-assigned)
        $query = "INSERT INTO document (user_id, doc) VALUES ($user_id, '$new_filename')";
        
        if(mysqli_query($conn, $query)) {
            header("Location: student_docs.php?msg=UploadSuccess");
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        header("Location: student_docs.php?msg=UploadFailed");
    }
}
?>