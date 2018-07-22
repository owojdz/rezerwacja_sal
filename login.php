<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
//require_once 'nibble-forms/Flash.class.php';
require_once 'nibble-forms/NibbleForm.class.php';

$LOKALIZACJA="logowanie";

//Generacja treści
$form=NibbleForm::getInstance('','Zaloguj się','post',true,'list');
$form->username = new Text('Podaj swój login', true, 20, '/[a-zA-Z0-9]+/');
$form->password = new Password('Podaj hasło', 6, true, true, 12);
$TRESC="";
$TRESC.="Przykładowy login: <b>jkowal</b>, hasło: <b>zaq12wsx</b><br/>".PHP_EOL;
$TRESC.="Przykładowy login: <b>kpucha</b>, hasło: <b>zaq12wsx</b><br/>".PHP_EOL;
$TRESC.="Przykładowy login: <b>admin</b>, hasło: <b>zaq12wsx</b><br/>".PHP_EOL;

if (isset($_POST['submit'])){
    if($form->validate()){
        try
        {
            $username=$_POST['username'];
            $password=$_POST['password'];
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
             
            $stmt = $pdo->prepare('   SELECT
                                password
                            FROM
                                pracownicy
                            WHERE
                                username = :username;');
            $stmt->bindValue(':username', $username,PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount()>1) throw new PDOException('W bazie jest więcej niż jeden uzytkownik o tym samym loginie');
            if($stmt->rowCount()==0){
                $TRESC="Nieprawidłowy login";
                $TRESC.=$form->render();
            } else{
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if ($row['password']==$password){//md5($password . $SALT)){
                    $_SESSION['username']=$username;
                    $TRESC="Witaj <b>$username</b>";
                }else{
                    $TRESC="Niepoprawne hasło.";
                    $TRESC.=$form->render();
                }
            }
            $stmt->closeCursor();
            echo '</ul>';
        }
        catch(PDOException $e)
        {
            echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
        }
        
    }else {
        $TRESC="Błędne dane";
        $TRESC.=$form->render();
    }
}else {
    $TRESC.=$form->render();
}

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
?>
