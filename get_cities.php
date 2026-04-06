<?php
require 'config.php';

if(isset($_GET['state_id'])) {
    $sid = intval($_GET['state_id']);
    $result = mysqli_query($conn, "SELECT * FROM city WHERE state_id = $sid");
    
    $cities = [];
    while($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row;
    }
    echo json_encode($cities);
}
?>