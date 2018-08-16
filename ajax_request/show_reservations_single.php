<?php
require_once '../include/settings_db.php';
///require_once 'include/functions.php';
/*$DBEngine = 'mysql';
$DBServer = 'localhost:3306';
$DBUser   = 'root';
$DBPass   = '';
$DBName   = 'firma';*/

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
function showAvailibility($pdo,$data,$sala){
    $inner=0;
    $a;
    try {
        //  $stmt = $pdo->prepare(' SELECT nazwa_sali,czas_start,czas_stop FROM rezerwacje WHERE data=:data and NOT ((:start BETWEEN `czas_start` AND `czas_stop`) OR :stop BETWEEN `czas_start` AND `czas_stop`);');
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        $stmt = $pdo->prepare(' SELECT rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, pracownicy.imie, pracownicy.nazwisko
                                FROM rezerwacje JOIN pracownicy
                                WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika and rezerwacje.nazwa_sali=:sala and rezerwacje.data=:data;');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':sala', $sala, PDO::PARAM_STR);
        $stmt->execute();
        $tresc="";
        $ar=array();
        foreach ($stmt as $row){
            $name = $row['imie']." ".$row['nazwisko'];
            $str1 =$row['czas_start'];
            $str2 =$row['czas_stop'];
            $str1 =str_ireplace(":30",":50",$str1);
            $str1 =str_ireplace(":","",$str1);
            $str1 =substr($str1,0,4);
            $str2 =str_ireplace(":30",":50",$str2);
            $str2 =str_ireplace(":","",$str2);
            $str2 =substr($str2,0,4);
            $st1=(int)$str1;
            $st2=(int)$str2;
            
            while ($st1 < $st2) {
                array_push($ar, $st1);
                array_push($ar, $name);
                $st1+=50;
            }
        }
    $stmt->closeCursor();
        return  $ar;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}

try{
    $value;
     $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki

    
//    $not_in_films=createFilmyWithoutActorSelect($pdo,$_GET['id_aktora'],FALSE);
//    $in_films=createFilmyWithActorSelect($pdo,$_GET['id_aktora'],FALSE);
 //   if(isset($_GET['sala'])){
 ///       $value=availibilityCheckEdit($pdo,'Czarna',$_GET['data'],$_GET['start'],$_GET['stop'],$_GET['sala']);
 //   } else {
 //       $value=availibilityCheck($pdo,'Czarna',$_GET['data'],$_GET['start'],$_GET['stop']);
 //   }
//    $value = showAvailibility($pdo, $_GET['data'], $_GET['sala']);
//    echo $value;
//    $array=array($value);
    //$array=showAvailibility($pdo, $_GET['data'], $_GET['sala']);
//     echo json_encode($array);
     echo json_encode(showAvailibility($pdo, $_GET['data'], $_GET['sala']));
     
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}

?>