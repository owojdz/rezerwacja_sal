<?php
require_once 'include/obslugaSesji.php';
require_once 'include/settings.php';

$LOKALIZACJA="Kontakt";

//Generacja treści
//Wykorzystujemy mechanizm postback - czyli przesyłania danych do tego samego skryptu
$action=basename(__FILE__); 
if (!isset($_POST["submit"]))
{
$tresc=<<<TRESC
	<form action="$action" method="post">
	Przykład obsługi formularzy<br/>
	Formularz zgłoszeniowy klienta wypożyczalni<br/>
	  <div>
	  Imię:<br />
	  <input name="imie" value="" /><br />
	  Nazwisko:<br />
	  <input name="nazwisko" value="" /><br />
	  Adres e-mail:<br />
	  <input name="email" value="" /><br />
	  <input type="checkbox" value="checked" name="aktualnosci" />Chcę otrzymywać informacje o aktualnościach<br />
      <select name="godzina">
		<option>8</option>
		<option>9</option>
		<option>10</option>
		<option>11</option>
		<option>12</option>
		<option>13</option>
		<option>14</option>
		<option>15</option>
	   </select>  :  
      <select name="minuta">
		<option>0</option>
		<option>30</option>
	   </select><br />
<input type="date" name="data" min="2010-01-01" max="2020-12-31" /><br />
<input type="time" name="czas" min="08:00" step="1800"/> <br />
	  <input type="submit" value="Wyślij" name="submit" />
	  </div>	
	</form>
TRESC;
}
else
{ 
    if(isset($_POST["aktualnosci"])){
        $aktualnosci = "TAK";
    } else {
        $aktualnosci = "NIE";
    }
$tresc=<<<TRESC
	Przykład obsługi formularzy<br/>
	Formularz zgłoszeniowy klienta wypożyczalni<br/>
	Przesłane przez Ciebie dane to:<br/>	
	<div>
	Imię:{$_POST["imie"]}<br />
	Nazwisko:{$_POST["nazwisko"]}<br />
	Adres e-mail:{$_POST["email"]}<br />
	Data:{$_POST["data"]}<br />
	Czas:{$_POST["godzina"]}:{$_POST["minuta"]}<br />
	Zgoda na wysyłanie maili z aktualnościami: $aktualnosci<br />		
	 </div>
TRESC;
}		  
$TRESC = $tresc;
require_once 'szablony/witryna.php';
?>
