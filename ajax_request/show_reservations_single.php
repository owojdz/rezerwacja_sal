<?php
require_once '../include/settings_db.php';

/**Listuje zajętości wybranej sali w wybranym dniu
 * @returns - tablicę zajętości
 */
function showOccupancy($pdo,$data,$sala){
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //przygotowanie zapytania do bazy sprawdzającego kiedy sala jest zajęta
        $stmt = $pdo->prepare(' SELECT rezerwacje.data, rezerwacje.czas_start, rezerwacje.czas_stop, pracownicy.imie, pracownicy.nazwisko
                                FROM rezerwacje JOIN pracownicy
                                WHERE rezerwacje.id_pracownika=pracownicy.id_pracownika and rezerwacje.nazwa_sali=:sala and rezerwacje.data=:data;');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':sala', $sala, PDO::PARAM_STR);
        $stmt->execute();
        $tresc="";
        $ar=array();
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
    //nawiązanie połączenia z bazą
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki

    //zwrócenie wartości zapytania
    echo json_encode(showOccupancy($pdo, $_GET['data'], $_GET['sala']));
     
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}

?>