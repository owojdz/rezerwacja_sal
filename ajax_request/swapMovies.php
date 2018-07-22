<?php
//require_once 'include/settings.php';
//require_once 'include/functions.php';
$DBEngine = 'mysql';
$DBServer = 'localhost:3306';
$DBUser   = 'root';
$DBPass   = '';
$DBName   = 'firma';

function availibilityCheck($pdo,$nazwa_sali,$data,$start,$stop){
    $inner=0;
    try {
      //  $stmt = $pdo->prepare(' SELECT nazwa_sali,czas_start,czas_stop FROM rezerwacje WHERE data=:data and NOT ((:start BETWEEN `czas_start` AND `czas_stop`) OR :stop BETWEEN `czas_start` AND `czas_stop`);');
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        $stmt = $pdo->prepare(' SELECT nazwa_sali FROM sale WHERE `nazwa_sali` NOT IN (SELECT nazwa_sali FROM rezerwacje WHERE data=:data and ((:start BETWEEN `czas_start` AND `czas_stop`) OR :stop BETWEEN `czas_start` AND `czas_stop`));');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':stop', $stop, PDO::PARAM_STR);
        $stmt->execute();
        $tresc="";
        if($inner){
            $tresc.='<select id="sale_ajax" name="sale_ajax">'.PHP_EOL;
        }
        foreach ($stmt as $row){
            //            $tresc.="<option value='".$row['nazwa_sali']."' >".$row['czas_start']." ".$row['czas_stop']."</option>".PHP_EOL;
            $tresc.="<option value='".$row['nazwa_sali']."' >".$row['nazwa_sali']."</option>".PHP_EOL;
        }
        if($inner){
            $tresc.='</select><br/>'.PHP_EOL;
        }
        $stmt->closeCursor();
        return  $tresc;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}
function availibilityCheckEdit($pdo,$nazwa_sali,$data,$start,$stop,$sala){
    $inner=0;
    try {
        //  $stmt = $pdo->prepare(' SELECT nazwa_sali,czas_start,czas_stop FROM rezerwacje WHERE data=:data and NOT ((:start BETWEEN `czas_start` AND `czas_stop`) OR :stop BETWEEN `czas_start` AND `czas_stop`);');
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        $stmt = $pdo->prepare(' SELECT nazwa_sali FROM sale WHERE `nazwa_sali` NOT IN (SELECT nazwa_sali FROM rezerwacje WHERE data=:data and ((:start BETWEEN `czas_start` AND `czas_stop`) OR :stop BETWEEN `czas_start` AND `czas_stop`));');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':stop', $stop, PDO::PARAM_STR);
        $stmt->execute();
        $tresc="";
        if($inner){
            $tresc.='<select id="sale_ajax" name="sale_ajax">'.PHP_EOL;
        }
        $tresc.="<option value='".$sala."' >".$sala."</option>".PHP_EOL;
        foreach ($stmt as $row){
            //            $tresc.="<option value='".$row['nazwa_sali']."' >".$row['czas_start']." ".$row['czas_stop']."</option>".PHP_EOL;
            if($row['nazwa_sali']!=$sala){
                $tresc.="<option value='".$row['nazwa_sali']."' >".$row['nazwa_sali']."</option>".PHP_EOL;
            }
        }
        if($inner){
            $tresc.='</select><br/>'.PHP_EOL;
        }
        $stmt->closeCursor();
        return  $tresc;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}

try{
    $value;
     $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki

    if($_GET['state']==1){
//        addMovieToActor($pdo,$_GET['id_aktora'],$_GET['id_filmu']);
        $value="true";
    }
    
    if($_GET['state']==0){
//        removeActorFromMovie($pdo,$_GET['id_aktora'],$_GET['id_filmu']);
        $value="false";
    }
    
//    $not_in_films=createFilmyWithoutActorSelect($pdo,$_GET['id_aktora'],FALSE);
//    $in_films=createFilmyWithActorSelect($pdo,$_GET['id_aktora'],FALSE);
    if(isset($_GET['sala'])){
        $value=availibilityCheckEdit($pdo,'Czarna',$_GET['data'],$_GET['start'],$_GET['stop'],$_GET['sala']);
    } else {
        $value=availibilityCheck($pdo,'Czarna',$_GET['data'],$_GET['start'],$_GET['stop']);
    }
//    echo $value;
    $array=array($value);
    echo json_encode($array);
    
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}

?>