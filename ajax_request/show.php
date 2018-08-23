<?php
require_once '../include/settings_db.php';
/**Pobranie dostępnych sal dla formularza rezerwacji
 * @returns zwraca kod HTML wypełniajacy <select> sale
 */
function availibilityCheck($pdo,$data,$start,$stop){
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //przygotowanie zapytania do bazy sprawdzającego dostępne sale w wybrany dzień i w przedziale czasowym. Między rezerwacjami musi być pół godziny przerwy na wietrzenie sali.
        $stmt = $pdo->prepare(' SELECT nazwa_sali FROM sale WHERE `nazwa_sali` NOT IN (SELECT nazwa_sali FROM rezerwacje 
                                WHERE data=:data and ((:start>=`czas_start` AND :start<`czas_stop`) OR (:stop>`czas_start` AND :stop<=`czas_stop`) 
                                OR (`czas_start`>=:start AND `czas_start`<:stop) OR (`czas_stop`>=:start AND `czas_stop`<:stop)));');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':stop', $stop, PDO::PARAM_STR);
        $stmt->execute();
        $tresc="";
        foreach ($stmt as $row){
            $tresc.="<option value='".$row['nazwa_sali']."' >".$row['nazwa_sali']."</option>".PHP_EOL;
        }
        $stmt->closeCursor();
        return  $tresc;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}
/**Pobranie dostępnych sal dla formularza edycji rezerwacji
 * @returns zwraca kod HTML wypełniajacy <select> sale
 */
function availibilityCheckEdit($pdo,$data,$start,$stop,$sala){
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //przygotowanie zapytania do bazy sprawdzającego dostępne sale w wybrany dzień i w przedziale czasowym. Między rezerwacjami musi być pół godziny przerwy na wietrzenie sali.
        $stmt = $pdo->prepare(' SELECT nazwa_sali FROM sale WHERE `nazwa_sali` NOT IN (SELECT nazwa_sali FROM rezerwacje 
                                WHERE data=:data and ((:start>=`czas_start` AND :start<`czas_stop`) OR (:stop>`czas_start` AND :stop<=`czas_stop`) 
                                OR (`czas_start`>=:start AND `czas_start`<:stop) OR (`czas_stop`>=:start AND `czas_stop`<:stop)));');
        
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':stop', $stop, PDO::PARAM_STR);
        $stmt->execute();
        $tresc="";
        $tresc.="<option value='".$sala."' >".$sala."</option>".PHP_EOL;
        foreach ($stmt as $row){
            if($row['nazwa_sali']!=$sala){
                $tresc.="<option value='".$row['nazwa_sali']."' >".$row['nazwa_sali']."</option>".PHP_EOL;
            }
        }
        $stmt->closeCursor();
        return  $tresc;
    } catch (PDOException $e){
        echo 'Błąd odczytu z bazy: ' . $e->getMessage();
    }
}

/**Pobranie wszystkich sal z bazy
 * @returns zwraca kod HTML wypełniajacy <select> sale
 */
function saleList($pdo,$data){
    try {
        //zapewnienie poprawngo kodowania polskich zanków
        $stmt = $pdo->query("SET CHARSET utf8");
        $stmt = $pdo->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
        
        //przygotowanie zapytania do bazy listującego wszystkie sale z bazy
        $stmt = $pdo->prepare(' SELECT nazwa_sali FROM sale;');
        
        $stmt->execute();
        $tresc="";
        foreach ($stmt as $row){
                $tresc.="<option value='".$row['nazwa_sali']."' >".$row['nazwa_sali']."</option>".PHP_EOL;
        }
        $stmt->closeCursor();
        return  $tresc;
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
        if(isset($_GET['sala'])){
            $value=availibilityCheckEdit($pdo,$_GET['data'],$_GET['start'],$_GET['stop'],$_GET['sala']);
        } else {
            $value=availibilityCheck($pdo,$_GET['data'],$_GET['start'],$_GET['stop']);
        }
    } else {
        $value=saleList($pdo,$_GET['data']);
    }
    $array=array($value);
    echo json_encode($array);
    
} catch (PDOException $e){
    echo "Nie można się połączyć do bazy".$e->getMessage();
    die();
}

?>