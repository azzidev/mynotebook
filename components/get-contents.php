<?php
    include('connect-db.php');
    
    $date = $_GET['date'];
    $day_calendar = explode(' ', $date);

    $stmt = $conn->prepare("SELECT * FROM all_notebooks WHERE last_update='$date'");
    $stmt->execute();

    
    if($stmt->rowCount() >= 1){
        if($obj=$stmt->fetch()){
            echo $obj['notebook_content'];
        }
    }
?>