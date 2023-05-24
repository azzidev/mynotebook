<?php
    include('../partials/config.php');
    include('connect-db.php');

    $name = $_POST['notebook'];
    $now = date('Y-m-d H:i:s');
    $default = '<p>Você é livre, comece quando quiser</p>';

    $stmt = $conn->prepare("INSERT INTO all_notebooks (notebook_name, notebook_content, last_update) VALUES (:notebook, :content, :last_update)");
    $stmt->bindParam(':notebook', $name);
    $stmt->bindParam(':content', $default);
    $stmt->bindParam(':last_update', $now);
    $stmt->execute();

    echo $now;
?>