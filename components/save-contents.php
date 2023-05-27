<?php
    include('../partials/config.php');
    include('connect-db.php');
    
    $date = $_POST['date'];
    $now = date('Y-m-d H:i:s');
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE all_notebooks SET notebook_content=:content, last_update=:now WHERE last_update=:date");
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':now', $now);
    $stmt->bindParam(':date', $date);

    if($stmt->execute()){
        echo '{"status": 200, "date": "'.$now.'"}';
    }
?>