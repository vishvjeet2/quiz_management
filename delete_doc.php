<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, "DELETE FROM document where id = ?");
    
    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    header("location: users_document.php");
    exit;

}
?>

<?php
session_start();
require 'config.php';

// 1. Security Check: Only Admin can delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. Validate ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 3. Execution: Delete Query
    $query = "DELETE FROM document WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        // Redirect back with success message
        header("Location: admin_pages.php?msg=documentDeleted");
        exit;
    } else {
        // Handle database errors
        die("Error deleting record: " . mysqli_error($conn));
    }
} else {
    // If no ID is provided, just go back
    header("Location: admin_docs.php");
    exit;
}
?>