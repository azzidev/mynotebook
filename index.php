<?php
    include('components/connect-db.php');
    include('partials/config.php');
?>
<?php
    $month = date('m');
    $year = date('Y');

    if(isset($_GET['year'])){
        $year = $_GET['year'];
        if(!isset($_GET['month'])){
            $month = "01";
        }else{
            $month = $_GET['month'];
        }
    }
    
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
                <?=$year?>
            </div>
            <div class="cell" onclick="getThisMonth(`01`)">
                Jan.
            </div>
            <div class="cell" onclick="getThisMonth(`02`)">
                Fev.
            </div>
            <div class="cell" onclick="getThisMonth(`03`)">
                Mar.
            </div>
            <div class="cell" onclick="getThisMonth(`04`)">
                Abr.
            </div>
            <div class="cell" onclick="getThisMonth(`05`)">
                Mai.
            </div>
            <div class="cell" onclick="getThisMonth(`06`)">
                Jun.
            </div>
            <div class="cell" onclick="getThisMonth(`07`)">
                Jul.
            </div>
            <div class="cell" onclick="getThisMonth(`08`)">
                Ago.
            </div>
            <div class="cell" onclick="getThisMonth(`09`)">
                Set.
            </div>
            <div class="cell" onclick="getThisMonth(`10`)">
                Out.
            </div>
            <div class="cell" onclick="getThisMonth(`11`)">
                Nov.
            </div>
            <div class="cell" onclick="getThisMonth(`12`)">
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

    <div class="modal blur" id="modal-select-year">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="specific-year">Exibimos uma pequena parcela de tempo nesta janela, entretanto, você pode especificar o ano que desejar no campo abaixo:</p>
                            <input type="number" class="form-control mb-3" oninput="getThisYear(this.value)">
                        </div>
                    </div>

                    <div class="row">
                        <?php
                            $sixLastYears = "";
                            $sixAfterYears = "";
                            for($iterator = 6; $iterator >= 1; $iterator--){
                                if(!isset($_GET['year'])){
                                    $sixLastYears .= date('Y', strtotime('-'.$iterator.' years')).',';
                                    $sixAfterYears .= date('Y', strtotime('+'.($iterator-1).' years')).',';
                                }else{
                                    $sixLastYears .= date('Y', strtotime('-'.$iterator.' years', strtotime($year.'-01-01'))).',';
                                    $sixAfterYears .= date('Y', strtotime('+'.($iterator-1).' years', strtotime($year.'-01-01'))).',';
                                }
                            }

                            $sixAfterYears = explode(',', $sixAfterYears);
                            $sixAfterYears = array_reverse($sixAfterYears);
                            $sixLastYears = explode(',', $sixLastYears);

                            foreach($sixLastYears AS $yearEach){
                                $active_class = "";
                                if($yearEach == "".date('Y')."" AND !isset($year)){
                                    $active_class = "active";
                                }elseif(isset($_GET['year'])){
                                    if($yearEach == $_GET['year']){
                                        $active_class = "active";
                                    }
                                }

                                if($yearEach != ''){
                                    echo '
                                        <div class="col-md-2 years '.$active_class.'" onclick="getThisYear(`'.$yearEach.'`)">
                                            '.$yearEach.'
                                        </div>
                                    ';
                                }
                            }
                            
                            foreach($sixAfterYears AS $yearEach){
                                $active_class = "";
                                if($yearEach == "".date('Y')."" AND !isset($year)){
                                    $active_class = "active";
                                }elseif(isset($_GET['year'])){
                                    if($yearEach == $_GET['year']){
                                        $active_class = "active";
                                    }
                                }

                                if($yearEach != ''){
                                    echo '
                                        <div class="col-md-2 years '.$active_class.'" onclick="getThisYear(`'.$yearEach.'`)">
                                            '.$yearEach.'
                                        </div>
                                    ';
                                }
                            }
                        ?>
                    </div>
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