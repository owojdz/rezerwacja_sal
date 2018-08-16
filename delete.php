<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';

$LOKALIZACJA="aktorzy";
$TRESC='';
$TRESC1='';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}
if(isset($_GET['id'])){
    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
 
            $stmt = $pdo->prepare(' DELETE FROM 
                                    rezerwacje 
                                WHERE 
                                    id_rezerwacji=:id;');
            $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            $TRESC.="Rezerwacja została usunięta";
        
    } catch (PDOException $e){
        echo "Nie można się połączyć do bazy".$e->getMessage();
        die();
    }
    //    header('Location: aktorzy.php');
}
//Przetworzenie szablonów
//require_once 'szablony/witryna.php';
require_once 'rezerwacja.php';

?>