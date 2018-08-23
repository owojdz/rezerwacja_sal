<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';

$TRESC='';
$TRESC1='';

//weryfikacja zalogowania, jeśli nie to odesłanie do strony logowania
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}
//kontrola, czy znane jest id rezerwacji
if(isset($_GET['id'])){
    try{
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
 
            //usuwanie rezerwacji z bazy
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
}
//Przetworzenie szablonów
require_once 'rezerwacja.php';

?>