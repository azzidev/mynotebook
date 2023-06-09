<?php
    include('../partials/config.php');
    include('connect-db.php');

    $name = $_POST['notebook'];
    $notebookDate = $_POST['date'];
    $group = $_POST['group'];
    $now = date('Y-m-d H:i:s');
    $default = '<p>Você é livre, comece quando quiser</p>';

    $stmt = $conn->prepare("INSERT INTO all_notebooks (notebook_name, notebook_content, last_update) VALUES (:notebook, :content, :last_update)");
    $stmt->bindParam(':notebook', $name);
    $stmt->bindParam(':content', $default);
    $stmt->bindParam(':last_update', $now);
    $stmt->execute();
    $id = ','.$conn->lastInsertId();


    $stmt = $conn->prepare("SELECT * FROM days_calendar WHERE day_calendar=:dateNote");
    $stmt->bindParam(':dateNote', $notebookDate);
    $stmt->execute();

    if($stmt->rowCount == 1){
        $stmt = $conn->prepare("UPDATE days_calendar SET notebook_uri=CONCAT(notebook_uri, :id) WHERE day_calendar=:dateNote");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':dateNote', $notebookDate);
        $stmt->execute();
    }else{
        $stmt = $conn->prepare("INSERT INTO days_calendar (days_calendar, notebook_uri, last_update) VALUES (:day_add, :id, :last_update)");
        $stmt->bindParam(':day_add', $notebookDate);
        $stmt->bindParam(':id', substr($id, 1));
        $stmt->bindParam(':last_update', $now);
        $stmt->execute();
    }

    if($group != false){
        $stmt = $conn->prepare("UPDATE group_notebooks SET notebooks_uri=CONCAT(notebooks_uri, :id) WHERE group_uri=:uri");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':uri', $_POST['group']);
        $stmt->execute();
    }

    echo $now;
?>