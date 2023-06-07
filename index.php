<?php
    $mobile_device = false;
    include('components/connect-db.php');
    include('partials/config.php');
?>
<?php
    $month = date('m');
    $year = date('Y');

    if(isset($_GET['year']) AND isset($_GET['year']) != 'null'){
        $year = $_GET['year'];
    }else{
        $year = date('Y');
    }

    if(!isset($_GET['month'])){
        if(isset($_GET['year']) AND isset($_GET['year']) != 'null'){
            $month = "01";
        }
    }else{
        $month = $_GET['month'];
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
                $active_day = "";

                if($month == date('m') AND $temp_day == date('d')){
                    $active_day = "active";
                }

                $useragent=$_SERVER['HTTP_USER_AGENT'];

                if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
                    $mobile_device = true;
                }

                if($stmt->rowCount() >= 1){
                    if($obj=$stmt->fetch()){
                        $tempAmount = explode(',', $obj['notebook_uri']);
                        if(count($tempAmount) > 1){
                            if($mobile_device == true){
                                $default = '<i class="fas fa-book-open mr-2"></i> '.count($tempAmount);
                            }else{
                                $default = '<i class="fas fa-book-open mr-2"></i> '.count($tempAmount).' folhas registradas';
                            }
                        }else{
                            if($mobile_device == true){   
                                $default = '<i class="fas fa-book-open mr-2"></i> '.count($tempAmount);                        
                            }else{
                                $default = '<i class="fas fa-book-open mr-2"></i> '.count($tempAmount).' folha registrada';
                            }
                        }
                    }
                }
    
                $this_cell = '
                    <div class="cell '.$active_day.'" onclick="openNotebooksDay(`'.$formated_date.'`)">
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
        <link rel="stylesheet" href="assets/css/all.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;800;900&display=swap" rel="stylesheet">
        <link rel="manifest" href="manifest.webmanifest">
        <title>Calendário | MyNotebook</title>
    </head>
    <body onmousemove="getCursorPosition(event)">
        <?php
            include('partials/loading.php');
        ?>
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
                                if($yearEach != ''){
                                    echo '
                                        <div class="col-md-2 years" onclick="getThisYear(`'.$yearEach.'`)">
                                            '.$yearEach.'
                                        </div>
                                    ';
                                }
                            }
                            
                            foreach($sixAfterYears AS $yearEach){
                                $active_class = "";
                                if($yearEach == "".date('Y')."" AND !isset($_GET['year'])){
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