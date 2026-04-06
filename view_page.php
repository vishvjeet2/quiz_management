<?php
require "config.php";

if (!isset($_GET['id'])) {
    die("Invalid Page ID");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT title, content, status FROM pages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Page not found.");
}

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .page-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .content-area {
            line-height: 1.6;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="page-container">
        <header class="mb-4 border-bottom pb-3">
            <h1 class="display-4"><?= htmlspecialchars($row['title']) ?></h1>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">← Back</a>
        </header>

        <article class="content-area">
            <?= nl2br(htmlspecialchars($row['content'])) ?>
        </article>
    </div>
</div>

</body>
</html>