<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';
require_once 'include/settings_db.php';

/**Generowanie formularza logowania
 * @param $action - akcja do wykonania po submit
 * @param $warning - informacja o błędach walidacji
 * @return string - zwraca kod HTML formularza
 */
function formGenerate($action,$warning){
    $form="<form  class='form-group' name='formularz' id='formularz' action='$action' method='post' >" ;
    $form.="<fieldset><legend>Zaloguj się</legend>";
    $form.="<a id='warning' style='color:red;' class='warning'>$warning</a><br/>";
    $form.="<label class='col-form-label' for='username'>Podaj swój login</label>";
    $form.="<input type='text' name='username' id='username' required class='form-control'>";
    $form.="<label class='col-form-label' for='password'>Podaj hasło</label>";
    $form.="<input type='password' name='password' id='password' required class='form-control'><br/>";
    $form.="<input type='submit' value='Zaloguj' name='submit' id='submit' class='btn btn-primary btn-lg btn-block'><br/>";
    $form.="</fieldset></form>";
    return $form;
}


$TRESC="";
$TRESC1="";
$TRESC1.="Przykładowy login: <b>jkowal</b>, hasło: <b>zaq12wsx</b><br/>".PHP_EOL;
$TRESC1.="Przykładowy login: <b>kpucha</b>, hasło: <b>zaq12wsx</b><br/>".PHP_EOL;
$TRESC1.="Przykładowy login: <b>admin</b>, hasło: <b>zaq12wsx</b><br/>".PHP_EOL;
$warning="";

//kontrola, czy formularz był już wypełniany
if (isset($_POST['submit'])){
    try
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        
        //połączenie z bazą danych
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
         
        //weryfikacja hasła
        $stmt = $pdo->prepare('   SELECT
                            password
                        FROM
                            pracownicy
                        WHERE
                            username = :username;');
        $stmt->bindValue(':username', $username,PDO::PARAM_STR);
        $stmt->execute();
        //gdy w bazie jest więcej niż jeden użytkownik z podanym loginem
        if($stmt->rowCount()>1) throw new PDOException('W bazie jest więcej niż jeden uzytkownik o tym samym loginie');
        //gdy w bazie nie ma użytkownika
        if($stmt->rowCount()==0){
            $warning="Nieprawidłowy login";
            $TRESC=formGenerate(basename(__FILE__),$warning);
        } else{
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['password']==$password){
                $_SESSION['username']=$username;
                $TRESC="Witaj <b>$username</b>";
                $warning="";
            }else{
                $warning="Niepoprawne hasło";
                $TRESC=formGenerate(basename(__FILE__),$warning);
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
//    $TRESC.=$form->render();
    $TRESC.=formGenerate(basename(__FILE__),$warning);
}

//Przetworzenie szablonów
require_once 'szablony/witryna.php';
?>
