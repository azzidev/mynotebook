<?php
    include('components/connect-db.php');
    include('partials/config.php');
?>
<?php
    $date = $_GET['q'];
    $day_calendar = explode(' ', $date);

    $stmt = $conn->prepare("SELECT * FROM all_notebooks WHERE last_update='$date'");
    $stmt->execute();

    
    if($stmt->rowCount() >= 1){
        if($obj=$stmt->fetch()){
            $title_page = $obj['notebook_name'];
            $content_page = $obj['notebook_content'];
        }
    }
?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/rte_theme_default.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"></link>
        <link rel="stylesheet" href="assets/css/fontawesome-all.min.css"></link>
        <link rel="stylesheet" href="assets/css/notebook.css">
        <link rel="stylesheet" href="assets/css/errors.css">
        <link rel="stylesheet" href="assets/css/all.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap" rel="stylesheet">
        <title><?=$title_page?> | MyNotebook</title>
    </head>
    <body onmousemove="getCursorPosition(event)">
        <?php
            include('partials/loading.php');
        ?>
        <header class="date">
            <?=$title_page?>
            <div class="sync-content">
                <i class="fas fa-arrow-up fade"></i>
                <i class="fas fa-pencil-alt fade"></i>
                <i class="fas fa-check fade show"></i>
                <i class="fas fa-cloud"></i>
            </div>
        </header>

        <div class="row">
            <div class="navbar">
                <div class="cell" data-toggle="tooltip" data-placement="right" title="Voltar ao calendário" onclick="window.location.href='index'">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="cell" data-toggle="tooltip" data-placement="right" title="Voltar ao caderno" onclick="window.location.href='notebooks?date=<?=$day_calendar[0]?>'">
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
            <div class="col-md-12 p-0">
                <div class="row notebook">
                    <?=$content_page?>
                </div>
            </div>
        </div>

        <script src="assets/js/rte.js"></script>
        <script src="assets/plugins/all_plugins.js"></script>
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>