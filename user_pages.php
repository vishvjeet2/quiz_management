<?php
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

    $query = "SELECT id, title, status FROM pages";

    $result = mysqli_query($conn, $query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="logo">Users</h2>
        <ul>
            <li >
                <a href="user_document.php">Users document</a>
            </li>
            <li >
                <a href="user_quiz.php">Quiz</a>
            </li>
            <li class="active">
                <a href="">pages</a>
            </li>
            <li >
                <a href="contact.php">Contact US</a>
            </li>
            <li class="logout">
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </aside>


    <!-- Main Content -->
    <main class="content">

        <!-- Header -->
        <div class="header">
            <h2>Admin Management</h2>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (mysqli_num_rows($result) == 0) {
                            echo "<tr> <td colspan='4'> No Quizes Found </td></tr>";
                        }
                        else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "
                                <tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['title']}</td>
                                    <td>{$row['status']}</td>
                                    
                                    <td>
                                        <a href='view_page.php?id={$row['id']}' class='btn edit' target='_blank'> View </a>
                                    </td>
                                </tr>";
                            }
                        }
                        ?>                        
                </tbody>
            </table>
        </div>

        

    </main>

</div>

</body>
</html>
