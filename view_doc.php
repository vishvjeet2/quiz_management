<?php
require "config.php";

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = intval($_GET['id']);

/* Fetch file from database */
$stmt = $conn->prepare(
    "SELECT user_doc FROM document WHERE user_id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Document not found");
}

$row = $result->fetch_assoc();
$file = $row['user_doc'];

/* File location */
$filePath = "uploads/" . basename($file);

/* Check if file exists */
if (!file_exists($filePath)) {
    die("File missing on server");
}

/* Detect file type */
$mime = mime_content_type($filePath);

/* Open file in browser */
header("Content-Type: $mime");
header("Content-Disposition: inline; filename=\"$file\"");
header("Content-Length: " . filesize($filePath));

readfile($filePath);
exit;
?>