<?php
    include('../partials/config.php');
    include('connect-db.php');

    $name = $_POST['group'];
    $groupDate = $_POST['date'];
    $now = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO group_notebooks (group_name, day_create, last_update) VALUES (:group, :dateFull, :last_update)");
    $stmt->bindParam(':group', $name);
    $stmt->bindParam(':dateFull', $groupDate);
    $stmt->bindParam(':last_update', $now);
    $stmt->execute();
    $id = $conn->lastInsertId();

    echo $id;
?>