<?php
    include('components/connect-db.php');
    include('partials/config.php');
?>
<?php
    $date = $_GET['date'];
    $tempDate = date('Y-m-d', strtotime($date));
    $monthDate = date("F", strtotime($date));
    $tempDate = explode('-', $tempDate);
    $monthEN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $monthPT = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

    if($key=array_search($monthDate, $monthEN)){
        $monthDate = $monthPT[$key];
    }

    $stmt = $conn->prepare("SELECT * FROM days_calendar WHERE day_calendar='$date'");
    $stmt->execute();

    if($stmt->rowCount() >= 1){
        if($obj=$stmt->fetch()){
            $uris_notebooks = $obj['notebook_uri'];
        }
    }

    $stmtCheck = $conn->prepare("SELECT * FROM group_notebooks WHERE day_create LIKE :date_get");
    $stmtCheck->bindParam(':date_get', $date);
    $stmtCheck->execute();

    $iterator = 0;
    if($stmtCheck->rowCount() >= 1){
        $objs=$stmtCheck->fetchAll();
        foreach($objs AS $obj){
            if($iterator == 0){
                $group_uris = $obj['notebooks_uri'];
            }else{
                $group_uris .= ','.$obj['notebooks_uri'];
            }
            $iterator++;
        }

        $group_uris = explode(',', $group_uris);
    
        foreach($group_uris AS $uri){
            $uris_notebooks = str_replace($uri.',', '', $uris_notebooks);
            $uris_notebooks = str_replace($uri, '', $uris_notebooks);
        }
    }

    if(isset($uris_notebooks)){
        if($uris_notebooks == ""){
            $uris_notebooks = "0";
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
        <link rel="stylesheet" href="assets/css/errors.css">
        <link rel="stylesheet" href="assets/css/all.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap" rel="stylesheet">
        <link rel="manifest" href="manifest.webmanifest">
        <title><?=$date_group?> | MyNotebook</title>
    </head>
    <body onmousemove="getCursorPosition(event)">
        <?php
            include('partials/loading.php');
        ?>

        <main>
            <header class="date">
                <?=$date_group?>
            </header>

            <div class="row">
                <div class="navbar">
                    <div class="cell" data-toggle="tooltip" data-placement="right" title="Nova folha" onclick="openModalNewNotebook()">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="cell" data-toggle="tooltip" data-placement="right" title="Novo grupo" onclick="openModalNewGroup()">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <div class="cell" data-toggle="tooltip" data-placement="right" title="Selecionar" onclick="activeSelectMode()">
                        <i class="fas fa-pen-square"></i>
                    </div>
                    <div class="cell" data-toggle="tooltip" data-placement="right" title="Mover para grupo" onclick="moveToGroup()">
                        <i class="fa fa-folder-open"></i>
                    </div>
                    <div class="cell" data-toggle="tooltip" data-placement="right" title="Mover para outro dia" onclick="moveToDay()">
                        <i class="fa fa-calendar-plus"></i>
                    </div>
                    <div class="cell" data-toggle="tooltip" data-placement="right" title="Remover" onclick="openModalDeleteNotebook()">
                        <i class="fas fa-trash"></i>
                    </div>
                    <div class="cell" data-toggle="tooltip" data-placement="right" title="Voltar ao calendário" onclick="window.location.href='index'">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
                <div class="col-md-12 p-0">
                    <div class="tool-select">
                        <div class="alert alert-info d-flex align-items-center justify-contente-center" role="alert">
                            <i class="fa fa-info-circle mr-3"></i>
                            <h2  data-toggle="tooltip" data-placement="right" title="Quando a ferramenta de seleção está ativada, não é possível clicar nos botões dos notebooks">A ferramenta de seleção está ativada</h2>
                        </div>
                    </div>
                    <div class="row notebooks">
                        <?php
                            $launchNotebook = false;
                            $launchGroup = false;
                            if(isset($uris_notebooks)){
                                $arrayGroup = array();
                                $stmt = $conn->prepare("SELECT * FROM all_notebooks WHERE notebook_uri IN ($uris_notebooks)");
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
                                }
                            }

                            $stmtCheck = $conn->prepare("SELECT * FROM group_notebooks WHERE day_create LIKE :date_get");
                            $stmtCheck->bindParam(':date_get', $date);
                            $stmtCheck->execute();

                            if($stmtCheck->rowCount() >= 1){
                                $objs=$stmtCheck->fetchAll();
                                foreach($objs AS $obj){
                                    echo '
                                        <div class="col-md-3 group-notebooks" onclick="openGroup(`'.$obj['group_uri'].'`, `'.$obj['day_create'].'`)">
                                            <h1 class="px-3">'.$obj['group_name'].'</h1>
                                            <icon class="fa-trash" onclick="deleteGroup(`'.$obj['group_uri'].'`)"></icon>
                                        </div>
                                    ';
                                }
                            }

                            if($launchNotebook AND $lauchGroup == false){
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
        </main>

        <div class="modal blur" id="modal-new-notebook">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mt-3">
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

        <div class="modal blur" id="modal-new-group">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <label for="name-group">Nome do grupo</label>
                                    <input type="text" class="form-control" name="name-group" id="name-group">
                                    <p class="small mt-2 pb-0">Os cadernos armazenados em um grupo não os afetam, ou seja, caso necessite deletar o grupo, seus notebooks permanecerão no dia criado.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$(this)[0].parentNode.parentNode.parentNode.parentNode.classList.remove('d-block')">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="createNewGroup()">Criar grupo</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal blur" id="modal-delete-notebook">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content shadow">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4>Foi bom o quanto durou...</h4>
                                    <div class="dynamic-content">
                                        <table class="table table-dark">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nome</th>
                                                    <th scope="col">Tamanho</th>
                                                    <th scope="col">Editado em</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="small mt-2 pb-0">Você terá 7 dias para recuperar qualquer notebook deletado. Após este período, o notebook será deletado permanentemente.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$(this)[0].parentNode.parentNode.parentNode.parentNode.classList.remove('d-block')">Cancelar</button>
                        <button type="button" class="btn btn-danger" onclick="deleteNotebooks()">Deletar notebooks</button>
                    </div>
                </div>
            </div>
        </div>

         <div class="notebook-view">
            <div class="notebook-content">

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