<?php
    include('components/connect-db.php');
    include('partials/config.php');
?>
<?php
    $month = date('m');
    $year = date('Y');
    
    $start_date = "01-".$month."-".$year;
    $start_time = strtotime($start_date);
    
    $end_time = strtotime("+1 month", $start_time);
    
    for($i=$start_time; $i<$end_time; $i+=86400){
       $list[] = date('Y-m-d-D', $i);
    }

    $default_cell = '
        <div class="cell">
            <div class="day">

            </div>
            <div class="activity">

            </div>
        </div>
    ';

    $days_numbers = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');

    $month_actual = array(
        array(0, 0, $default_cell),
        array(0, 1, $default_cell),
        array(0, 2, $default_cell),
        array(0, 3, $default_cell),
        array(0, 4, $default_cell),
        array(0, 5, $default_cell),
        array(0, 6, $default_cell),
        array(1, 0, $default_cell),
        array(1, 1, $default_cell),
        array(1, 2, $default_cell),
        array(1, 3, $default_cell),
        array(1, 4, $default_cell),
        array(1, 5, $default_cell),
        array(1, 6, $default_cell),
        array(2, 0, $default_cell),
        array(2, 1, $default_cell),
        array(2, 2, $default_cell),
        array(2, 3, $default_cell),
        array(2, 4, $default_cell),
        array(2, 5, $default_cell),
        array(2, 6, $default_cell),
        array(3, 0, $default_cell),
        array(3, 1, $default_cell),
        array(3, 2, $default_cell),
        array(3, 3, $default_cell),
        array(3, 4, $default_cell),
        array(3, 5, $default_cell),
        array(3, 6, $default_cell),
        array(4, 0, $default_cell),
        array(4, 1, $default_cell),
        array(4, 2, $default_cell),
        array(4, 3, $default_cell),
        array(4, 4, $default_cell),
        array(4, 5, $default_cell),
        array(4, 6, $default_cell),
        array(5, 0, $default_cell),
        array(5, 1, $default_cell),
        array(5, 2, $default_cell),
        array(5, 3, $default_cell),
        array(5, 4, $default_cell),
        array(5, 5, $default_cell),
        array(5, 6, $default_cell),
    );

    $iterator = 0;
    $init = false;
    foreach($list AS $day){
        foreach($days_numbers AS $dayweek){
            if(strrpos($day, $dayweek) != false){
                $temp_day = explode(' ', $day);
                $temp_day = explode('-', $temp_day[0]);
                $temp_day = $temp_day[2];
                $formated_date = $year."-".$month."-".$temp_day;
                $default = "";

                $stmt = $conn->prepare("SELECT * FROM days_calendar WHERE day_calendar='$formated_date'");
                $stmt->execute();
                
                if($stmt->rowCount() >= 1){
                    if($obj=$stmt->fetch()){
                        $tempAmount = explode(',', $obj['notebook_uri']);
                        if(count($tempAmount) > 1){
                            $default = '<i class="fas fa-book-open mr-2"></i> '.count($tempAmount).' folhas registradas';
                        }else{
                            $default = '<i class="fas fa-book-open mr-2"></i> '.count($tempAmount).' folha registrada';
                        }
                    }
                }
    
                $this_cell = '
                    <div class="cell" onclick="openNotebooksDay(`'.$formated_date.'`)">
                        <div class="day">
                            '.$temp_day.'
                        </div>
                        <div class="activity">
                            <div class="line">
                                '.$default.'
                            </div>
                        </div>
                    </div>
                ';
                $month_actual[$iterator][2] = $this_cell;
                
                $init = true;
                if($init == true){
                    $iterator++;
                }
            }
            if($init == false){
                $iterator++;
            }
        }

    }
?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"></link>
        <link rel="stylesheet" href="assets/css/fontawesome-all.min.css"></link>
        <link rel="stylesheet" href="assets/css/index.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap" rel="stylesheet">
        <title>Calendário | MyNotebook</title>
    </head>
    <body onmousemove="getCursorPosition(event)">
        <div class="navbar">
            <div class="cell" onclick="openModalSelectYear()">
                2023
            </div>
            <div class="cell">
                Jan.
            </div>
            <div class="cell">
                Fev.
            </div>
            <div class="cell">
                Mar.
            </div>
            <div class="cell">
                Abr.
            </div>
            <div class="cell active">
                Mai.
            </div>
            <div class="cell">
                Jun.
            </div>
            <div class="cell">
                Jul.
            </div>
            <div class="cell">
                Ago.
            </div>
            <div class="cell">
                Set.
            </div>
            <div class="cell">
                Out.
            </div>
            <div class="cell">
                Nov.
            </div>
            <div class="cell">
                Dez.
            </div>
        </div>
        <main class="calendar">
            <div class="week-header">
                <div class="cell">
                    <div class="day-header">
                        Domingo
                    </div>
                </div>
                <div class="cell">
                    <div class="day-header">
                        Segunda
                    </div>
                </div>
                <div class="cell">
                    <div class="day-header">
                        Terça
                    </div>
                </div>
                <div class="cell">
                    <div class="day-header">
                        Quarta
                    </div>
                </div>
                <div class="cell">
                    <div class="day-header">
                        Quinta
                    </div>
                </div>
                <div class="cell">
                    <div class="day-header">
                        Sexta
                    </div>
                </div>
                <div class="cell">
                    <div class="day-header">
                        Sábado
                    </div>
                </div>
            </div>
            <?php
                $iterator = 0;
                foreach($month_actual AS $day){
                    if($iterator == 0){
                        echo '<div class="week">';
                    }

                    echo $day[2];

                    $iterator++;
                    if($iterator == 7){
                        $iterator = 0;
                        echo '</div>';
                    }
                }
            ?>
        </main>
    </body>

    <div class="modal blur d-block" id="modal-select-year">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 years">
                            2020
                        </div>
                        <div class="col-md-2 years">
                            2021
                        </div>
                        <div class="col-md-2 years">
                            2022
                        </div>
                        <div class="col-md-2 years active">
                            2023
                        </div>
                        <div class="col-md-2 years">
                            2024
                        </div>
                        <div class="col-md-2 years">
                            2025
                        </div>
                        <div class="col-md-2 years">
                            2026
                        </div>
                        <div class="col-md-2 years">
                            2027
                        </div>
                        <div class="col-md-2 years">
                            2028
                        </div>
                        <div class="col-md-2 years">
                            2029
                        </div>
                        <div class="col-md-2 years">
                            2030
                        </div>
                        <div class="col-md-2 years">
                            2031
                        </div>
                    </div>

                    
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">Passado</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Futuro</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$(this)[0].parentNode.parentNode.parentNode.parentNode.classList.remove('d-block')">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/main.js"></script>
</html>