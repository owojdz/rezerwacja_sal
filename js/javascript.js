//<SCRIPT LANGUAGE="JavaScript">
/**Walidacja formularza rezerwacji - w przypadku poprawności danych realizuje submit()
 * @returns - submit()
 */
function checkform ()
{
	$data = document.getElementById("data").value;
	$sala = document.getElementById("sale").value;
	$start=document.getElementById("timestart").value;
	$stop=document.getElementById("timefinish").value;
	//sprawdzenie czy koniec rezerwacji wybrano później niż jej rozpoczęcie
	if($start>=$stop){
		document.getElementById("warning").innerHTML = "<p id='warning' style='color:red;'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz<br /></p>"; 
        return false ;
	}else{
	//sprawdzenie czy użytkownik nie obszedł wpudowanego mechanizmu wyświetlania dostępnych sal i nie wybrał sali już zarezerwowanej	
		document.getElementById("warning").innerHTML = "<p id='warning' type='hidden'></p>"; 
			handleAjaxRequest("ajax_request/show_reservations.php?&sala="+$sala+"&data="+$data+"&start="+$start+"&stop="+$stop,
					function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					result = new Array();
					result = JSON.parse(xmlhttp.responseText);
					if (result[0]=="false"){
						document.getElementById("warning").innerHTML = "<p id='warning' id='warning' >Sala jest już zajęta w wybranym terminie. Spróbuj jeszcze raz<br /></p>";
						return false ;
					} else {
						//wartosc jest polem pokazującym, że formularz został już wypełniony i można dodawać rezerwację do bazy 
						document.getElementById("warning").innerHTML = "<p id='warning' style='color:red;'> <br /></p>";
						document.getElementById("wartosc").value="true";
						document.getElementById("formularz").submit();
						return true ;			
					}
				}
		});	
    }
}
function checkform_edit ()
{
	$data = document.getElementById("data").value;
	$sala = document.getElementById("sale").value;
	$start=document.getElementById("timestart").value;
	$stop=document.getElementById("timefinish").value;
	$id_rez=document.getElementById("id_rez").value;
	//sprawdzenie czy koniec rezerwacji wybrano później niż jej rozpoczęcie
	if($start>=$stop){
		document.getElementById("warning").innerHTML = "<p type='text' id='warning'><font color='red'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz</font><br /></p>"; 
        return false ;
	}else{
	//sprawdzenie czy użytkownik nie obszedł wpudowanego mechanizmu wyświetlania dostępnych sal i nie wybrał sali już zarezerwowanej	
		document.getElementById("warning").innerHTML = "<p id='warning' type='hidden'></p>"; 
			handleAjaxRequest("ajax_request/show_reservations.php?&sala="+$sala+"&data="+$data+"&start="+$start+"&stop="+$stop+"&id_rez="+$id_rez,
					function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					result = new Array();
					result = JSON.parse(xmlhttp.responseText);
					if (result[0]=="ok"){
						document.getElementById("warning").innerHTML = "<p type='text' id='warning'><font color='red'>Sala jest już zajęta w wybranym terminie. Spróbuj jeszcze raz</font><br /></p>";
						return false ;
					} else {
						//wartosc jest polem pokazującym, że formularz został już wypełniony i można dodawać rezerwację do bazy 
						document.getElementById("wartosc").value="true";
						document.getElementById("formularz").submit();
						return true ;			
					}
				}
		});	
    }
}
/**Zaokrąglenie czasu rozpoczęcia rezerwacji do 30 minut
 */
function	my_round_start(){
	document.getElementById("timestart").value=getNearestHalfHourTimeString(document.getElementById("timestart").value);
}
/**Zaokrąglenie czasu zakończenia rezerwacji do 30 minut
 */
function	my_round_stop(){
	document.getElementById("timefinish").value=getNearestHalfHourTimeString(document.getElementById("timefinish").value);
}
/**Realizacja zaokrąglenia czasu do 30 minut
 * @param time - czas w formacie HH:mm
 * @returns czas w formacie HH:mm zaokrąglony do 30 minut
 */
function getNearestHalfHourTimeString(time) {
	var changed=false;
	var hour = time.substring(0,2);
    var minutes = time.substring(3,5);
    if (minutes < 15) {
        minutes = "00";
    } else if (minutes < 45){
        minutes = "30";
    } else {
        minutes = "00";
        ++hour;
        changed=true;
    }
    if (hour<10 && changed){
		return("0"+hour + ":" + minutes);
    } else{
    		return(hour + ":" + minutes);
    }
}
/**Ustawianie godziny zakończenia rezerwacji na wartość o godzinę większą niż godzina rozpoczęcia, w sytuacji gdy zmieniana jest godzina rozpoczęcia rezerwacji
 * @returns ustawia pole godziny zakończenia rezerwacji
 */
function add_one() {
	var str=document.getElementById("timestart").value;
	var h=str.slice(0, 2);
	var res = str.slice(2, 8);
	var hint = parseInt(h);
	hint=hint+1;
	str = hint.toString();
	if (hint<10) str="0".concat(str);
	str = str.concat(res);
	str=getNearestHalfHourTimeString(str);
	document.getElementById("timefinish").value=str;
}
/**Wywołanie strony edycji rezerwacji
 * @param id - id rezerwacji
 */
function edit(id) {
	var str1 = "edit.php?id=";
	var str2 = id;
	var res = str1.concat(str2);
	this.open(res,"_self");
}
/**Wywołanie strony/akcji usunięcia rezerwacji
 * @param id - id rezerwacji
 */
function del(id) {
 	if (confirm("Czy napewno usunąć rezerwację?")  ){
 		var str1 = "delete.php?id=";
 		var str2 = id;
 		var res = str1.concat(str2);
 		this.open(res,"_self");
	}
}
/**Wyświetlenie rezerwacji wszystkich sal w formie strony z terminarza
 */
function show_reservations_by_color(){
	//kasowanie zawartości pól terminarza
	var c= document.getElementById("container"); 
	var myNodelist = c.querySelectorAll(".cal");
	var i;
	for (i = 0; i < myNodelist.length; i++) {
	    myNodelist[i].style.backgroundColor = "white";
	    myNodelist[i].innerText = "";
	}
	//pobranie rezerwacji z bazy danych
	$data = document.getElementById("data").value;
	$sala = "Czarna";
	handleAjaxRequest("ajax_request/show_reservations.php?&data="+$data+"&sala="+$sala,
		function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				hours = new Array();
				hours = JSON.parse(xmlhttp.responseText);
				var str, i, j, plus;
				//wyświetlenie rezerwacji w odpowiednich polach kontenera
				for(j=0;j<hours.length;j++){
					for(i=0;i<hours[j].length;i+=2){
        					str=String(hours[j][i]);
        					plus=j+1;
        					str= str.concat(plus);
        					document.getElementById(str).style.backgroundColor = "#dfe2e8"; 
        					document.getElementById(str).style.fontSize = "small";
        					document.getElementById(str).innerText = hours[j][i+1]; 

					}
				}					
			}
		});
}//show_reservations_by_color()

/**Wyświetlenie zajętości jednej sali w formie strony z terminarza
 */
function show_reservations_single(){
	//kasowanie zawartości pól terminarza
	var c= document.getElementById("container"); 
	var myNodelist = c.querySelectorAll(".cal");
	var i;
	for (i = 0; i < myNodelist.length; i++) {
	    myNodelist[i].style.backgroundColor = "white";
	    myNodelist[i].innerText = "";
	}
	//pobranie rezerwacji z bazy danych
	$data = document.getElementById("data").value;
	$sala = document.getElementById("sale").value;
	handleAjaxRequest("ajax_request/show_reservations_single.php?&data="+$data+"&sala="+$sala,
		function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				hours = new Array();
				hours = JSON.parse(xmlhttp.responseText);
				document.getElementById("nazwaSali").innerText = $sala; 
				var str, i, j, plus;
				//wyświetlenie rezerwacji w odpowiednich polach kontenera
				for(i=0;i<hours.length;i+=2){
    					str=String(hours[i]);
    					str= str.concat("1");
    					document.getElementById(str).style.backgroundColor = "#dfe2e8"; 
    					document.getElementById(str).style.fontSize = "small";
    					document.getElementById(str).innerText = hours[i+1]; 
				}
			}
		});
}//show_reservations_single()

var clickcount = 0; //do wybrania, czy ustawiana jest godzina początku czy końca rezerwacji get_select(pole)
function setcount(value){
	clickcount=value;
}
/**Wstawia do formularza wybrane pole początku i końca rezerwacji
 * @param pole - godzina rezrewacji
 * @returns
 */
function get_select(pole){
	//ustalenie początku i końca dla wybranego pola
	var czas_s=parseInt(pole);
	var czas_f=czas_s+50;
	//clickcount==1 oznacza że ustawiana jest godzina rozpoczęcia rezerwacji
	if (clickcount==1){
		czas=czas_s.toString();
		if (czas.length<4){
			czas="0"+czas;
		} 
		//ustawienie formatu czasu
		czas=czas.substring(0,2)+":"+czas.substring(2,4);
		//zamiana półówek na minuty
		czas = czas.replace(":50", ":30");
		document.getElementById("timestart").value = czas; 
		document.getElementById("warning1").innerHTML = "<p type='text' id='warning1'><font color='blue'></font><br /></p>"; 
	} 
	//clickcount==2 oznacza że ustawiana jest godzina zakończenia rezerwacji
	else if (clickcount==2){
		czas=czas_f.toString();
		if (czas.length<4){
			czas="0"+czas;
		} 
		//ustawienie formatu czasu
		czas=czas.substring(0,2)+":"+czas.substring(2,4);
		//zamiana półówek na minuty
		czas = czas.replace(":50", ":30");
		document.getElementById("timefinish").value = czas; 
		document.getElementById("warning1").innerHTML = "<p type='text' id='warning1'><font color='blue'></font><br /></p>"; 
	} else {
		document.getElementById("warning1").innerHTML = "<p type='text' id='warning1'><font color='blue'>Zaznacz pole, które chcesz ustawić</font><br /></p>"; 
	}
}

//</script>