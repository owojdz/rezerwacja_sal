<?php
require_once '../include/settings_db.php';

/**Sprawdzenie zajętości sali w wybranym terminie
 * @returns string "ok" jeśli zajęta oraz "false" jeśli wolna 
 */
function checkOccupancy($pdo,$sala,$data,$start,$stop){
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //przygotowanie zapytania do bazy sprawdzającego czy sala jest zajęta
        $stmt = $pdo->prepare(' SELECT nazwa_sali FROM sale WHERE `nazwa_sali` NOT IN (SELECT nazwa_sali FROM rezerwacje 
                                WHERE data=:data and ((:start>=`czas_start` AND :start<`czas_stop`) OR (:stop>`czas_start` AND :stop<=`czas_stop`) 
                                OR (`czas_start`>=:start AND `czas_start`<:stop) OR (`czas_stop`>=:start AND `czas_stop`<:stop)));');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':stop', $stop, PDO::PARAM_STR);
        $stmt->execute();
        $ar1=array("false");;
        //sprawdzenie, czy wybrana sala jest na liście zajetych sal
        foreach ($stmt as $row){
            if ($row['nazwa_sali']==$sala) {
                $ar1=array("ok");
            }
        }
        $stmt->closeCursor();
        return  $ar1;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}
/**Sprawdzenie czy rezerwacja nie nakłada się z inną (poza sobą przed zmianą)
 * @returns string "ok" jeśli zajęta oraz "false" jeśli wolna 
 */
function checkOccupancy_edit($pdo,$sala,$data,$start,$stop,$id_rez){
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //przygotowanie zapytania do bazy sprawdzającego czy sala jest zajęta
//        $stmt = $pdo->prepare(' SELECT nazwa_sali FROM sale WHERE `nazwa_sali` NOT IN (SELECT nazwa_sali FROM rezerwacje WHERE data=:data and ((:start>=`czas_start` AND :start<`czas_stop`) OR (:stop>`czas_start` AND :stop<`czas_stop`)));');
        $stmt = $pdo->prepare(' SELECT id_rezerwacji FROM rezerwacje WHERE data=:data and nazwa_sali=:sala and ((:start>=`czas_start` AND :start<`czas_stop`) OR (:stop>`czas_start` AND :stop<=`czas_stop`) 
                                OR (`czas_start`>=:start AND `czas_start`<:stop) OR (`czas_stop`>=:start AND `czas_stop`<:stop));');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':stop', $stop, PDO::PARAM_STR);
        $stmt->bindValue(':sala', $sala, PDO::PARAM_STR);
        $stmt->execute();
        $ar1=array("false");;
        //sprawdzenie, czy wybrana sala jest na liście zajetych sal
        foreach ($stmt as $row){
            if ($row['id_rezerwacji']!=$id_rez) {
                $ar1=array("ok");
            }
        }
        $stmt->closeCursor();
        return  $ar1;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}
/**Listuje zajętości wszystkich sal w wybranym dniu
 * @returns - tablicę zajętości
 */
function showOccupancy($pdo,$data,$sala){
    $inner=0;
    $a;
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //przygotowanie zapytania do bazy sprawdzającego kiedy sale są zajęte
        $stmt = $pdo->prepare(' SELECT rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, pracownicy.imie, pracownicy.nazwisko, sale.id_sali
                                FROM rezerwacje JOIN pracownicy JOIN sale
                                WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika and rezerwacje.nazwa_sali=sale.nazwa_sali and rezerwacje.data=:data;');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->execute();
        $tresc="";
        $ar1=array();
        $ar2=array();
        $ar3=array();
        $ar4=array();
        $ar5=array();
        //wypełnienie tablicy rezerwacji
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
                switch ($row['id_sali']) {
                    case 1:
                        array_push($ar1, $st1);
                        array_push($ar1, $name);
                        break;
                    case 2:
                        array_push($ar2, $st1);
                        array_push($ar2, $name);
                        break;
                    case 3:
                        array_push($ar3, $st1);
                        array_push($ar3, $name);
                        break;
                    case 4:
                        array_push($ar4, $st1);
                        array_push($ar4, $name);
                        break;
                    case 5:
                        array_push($ar5, $st1);
                        array_push($ar5, $name);
                        break;
                }
                $st1+=50;
            }
        }
        $ar=array($ar1, $ar2, $ar3, $ar4, $ar5);
        
    $stmt->closeCursor();
        return  $ar;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}

try{
    $value;
    //nawiązanie połączenia z bazą 
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
    
     //wybór odpowiedniej funkcji w zależności od składni zapytania
     if(isset($_GET['start'])){
         if(isset($_GET['id_rez'])){
             echo json_encode(checkOccupancy_edit($pdo, $_GET['sala'], $_GET['data'], $_GET['start'], $_GET['stop'], $_GET['id_rez']));
             
         }else{
            echo json_encode(checkOccupancy($pdo, $_GET['sala'], $_GET['data'], $_GET['start'], $_GET['stop']));
         }
     } else {
         echo json_encode(showOccupancy($pdo, $_GET['data'], $_GET['sala']));
     }
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}

?>