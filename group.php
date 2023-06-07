<?php
    include('components/connect-db.php');
    include('partials/config.php');
?>
<?php
    $uri_group = $_GET['uri'];

    $stmt = $conn->prepare("SELECT * FROM group_notebooks WHERE group_uri='$uri_group'");
    $stmt->execute();
    $name_group = "";

    if($stmt->rowCount() >= 1){
        if($obj=$stmt->fetch()){
            $name_group = $obj['group_name'];
            $uris_group = $obj['notebooks_uri'];
            $date = $obj['day_create'];
        }
    }

    $tempDate = date('Y-m-d', strtotime($date));
    $monthDate = date("F", strtotime($date));
    $tempDate = explode('-', $tempDate);
    $monthEN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $monthPT = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

    if($key=array_search($monthDate, $monthEN)){
        $monthDate = $monthPT[$key];
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
        <link rel="stylesheet" href="assets/css/all.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap" rel="stylesheet">
        <link rel="manifest" href="manifest.webmanifest">
        <title><?=$name_group.' | '.$date_group?> | MyNotebook</title>
    </head>
    <body onmousemove="getCursorPosition(event)">
        <?php
            include('partials/loading.php');
        ?>
        <header class="date">
            <?=$name_group.' | '.$date_group?>
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
                        if(isset($uris_group) AND $uris_group != ''){
                            $stmt = $conn->prepare("SELECT * FROM all_notebooks WHERE notebook_uri IN ($uris_group)");
                            $stmt->execute();

                            if($stmt->rowCount() >= 1){
                                $objs=$stmt->fetchAll();
                                foreach($objs AS $obj){
                                    preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $obj['notebook_content'], $image);
                                    $obj['notebook_content'] = substr($obj['notebook_content'],0, strpos($obj['notebook_content'], "</p>")+4);
                                    if(isset($image['src'])){
                                        echo '
                                            <div class=" col-md-3 notebook">
                                                <div class="overflow">  
                                                    <icon class="fa-eye" onclick="viewNotebook(`'.$obj['last_update'].'`)"></icon>
                                                    <icon class="fa-pen" onclick="openNotebook(`'.$obj['last_update'].'`)"></icon>
                                                    <img src="'.$image['src'].'"> 
                                                    <h5>'.$obj['notebook_name'].'</h5>
                                                    <hr>
                                                    '.str_replace($image, '', $obj['notebook_content']).'
                                                </div>
                                            </div>
                                        ';
                                    }else{
                                        echo '
                                            <div class=" col-md-3 notebook">
                                                <div class="overflow">  
                                                    <icon class="fa-eye" onclick="viewNotebook(`'.$obj['last_update'].'` )"></icon>    
                                                    <icon class="fa-pen" onclick="openNotebook(`'.$obj['last_update'].'`)"></icon>
                                                    <h5>'.$obj['notebook_name'].'</h5>
                                                    <hr>
                                                    '.$obj['notebook_content'].'
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                            }else{
                                echo '
                                    <div class="default">
                                        <i class="fas fa-arrow-left mr-5"></i>Use a barra lateral para criar uma nova folha
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

        <div class="modal blur" id="modal-new-notebook">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow">
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