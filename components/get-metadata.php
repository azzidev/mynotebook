<?php
    include('connect-db.php');
    
    $dates = $_GET['dates'];
    $dates = explode(',', $dates);
    $condition = "";
    $json = "[";

    foreach($dates AS $date){
        $condition .= "OR last_update='".$date."'";
    }

    $condition = substr($condition, 3);

    $stmt = $conn->prepare("SELECT * FROM all_notebooks WHERE $condition");
    $stmt->execute();

    
    if($stmt->rowCount() >= 1){
        $objs=$stmt->fetchAll();
        foreach($objs AS $obj){
            $name = $obj['notebook_name'];
            $date = $obj['last_update'];
            $content = $obj['notebook_content'];

            $byte = strlen($content);
            $size = (($byte * 2) / 1024);
            $size = number_format($size, 2, '.', '');

            $json .= '{"size" : "'.$size.'", "name" : "'.$name.'", "date" : "'.date('d/m/Y H:i:s', strtotime($date)).'"}';
        }
    }
    
    $json .= "]";
    echo $json;
?>