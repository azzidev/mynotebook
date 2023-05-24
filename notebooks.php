<?php
    include('components/connect-db.php');
    include('partials/config.php');
?>
<?php
    $date = $_GET['date'];
    $tempDate = date('Y-m-d', strtotime($date));
    $monthDate = date("F", strtotime($date));
    $tempDate = explode('-', $tempDate);

    $stmt = $conn->prepare("SELECT * FROM days_calendar WHERE day_calendar='$date'");
    $stmt->execute();

    if($stmt->rowCount() >= 1){
        if($obj=$stmt->fetch()){
            $uris_notebooks = $obj['notebook_uri'];
        }
    }

    $date_group = $tempDate[2] .' de '.$monthDate. ' de '. $tempDate[0];
?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/rte_theme_default.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"></link>
        <link rel="stylesheet" href="assets/css/fontawesome-all.min.css"></link>
        <link rel="stylesheet" href="assets/css/notebooks.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap" rel="stylesheet">
    </head>
    <body onmousemove="getCursorPosition(event)">
        <header class="date">
            <?=$date_group?>
        </header>

        <div class="row">
            <div class="navbar">
                <div class="cell" data-toggle="tooltip" data-placement="right" title="Nova folha" onclick="openModalNewNotebook()">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="cell" data-toggle="tooltip" data-placement="right" title="Selecionar">
                    <i class="fas fa-pen-square"></i>
                </div>
                <div class="cell" data-toggle="tooltip" data-placement="right" title="Remover">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="cell" data-toggle="tooltip" data-placement="right" title="Voltar ao calendário" onclick="window.location.href='index'">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
            <div class="col-md-12 p-0">
                <div class="row notebooks">
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM all_notebooks WHERE notebook_uri IN ($uris_notebooks)");
                        $stmt->execute();

                        if($stmt->rowCount() >= 1){
                            $objs=$stmt->fetchAll();
                            foreach($objs AS $obj){
                                echo '
                                    <div class="col-md-2 notebook" onclick="openNotebook(`'.$obj['last_update'].'`)">
                                        <h5>'.$obj['notebook_name'].'</h5>
                                        <hr>
                                        <p>'.$obj['notebook_content'].'</p>
                                    </div>
                                ';
                            }
                        }else{
                            echo '
                                <div class="default">
                                    <i class="fas fa-arrow-left mr-5"></i>Use a barra lateral para criar uma nova folha
                                </div>
                            ';
                        }
                    ?>
                </div>
            </div>
        </div>

        <div class="modal" id="modal-new-notebook">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name-notebook">Nome da folha</label>
                                    <input type="text" class="form-control" name="name-notebook" id="name-notebook">
                                    <p class="small mt-2 pb-0">Não é necessário inserir um nome a folha, entretanto, fornecer está informação auxilia nosso buscador.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$(this)[0].parentNode.parentNode.parentNode.parentNode.classList.remove('d-block')">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="createNewNotebook()">Criar folha</button>
                    </div>
                </div>
            </div>
         </div>

         <div class="notebook-view">
            
         </div>

        <script src="assets/js/rte.js"></script>
        <script src="assets/plugins/all_plugins.js"></script>
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>