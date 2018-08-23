var xmlhttp;

/**Generowanie AJAX reguest.
 * @param url
 * @param callback_function
 * @returns
 */
function handleAjaxRequest(url, callback_function) {	//Wykkorzystujemy podpinanie funkcji jako callback'a
	if (window.XMLHttpRequest) //sprawdzamy czy obiekt jest obsługiwany
	{// nowsze przeglądarki
		xmlhttp = new XMLHttpRequest(); //Tworzymy obiekt
	} else {// dla przeglądarek IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); //Tworzymy obiekt ActiveX
	}
	xmlhttp.onreadystatechange = callback_function;
	xmlhttp.open("GET", url + "&_random_ticket=" + Math.random(), true);
	xmlhttp.send();
}


/**Pobranie dostępnych sal dla formularza rezerwacji
 * @returns zwraca kod HTML wypełniajacy <select> sale
 */
function salaCheckJS() {	
	$data=document.getElementById("data").value;
	$start=document.getElementById("timestart").value;
	$stop=document.getElementById("timefinish").value;
	//sprawdzenie czy koniec rezerwacji wybrano później niż jej rozpoczęcie
	if($start>=$stop){
		document.getElementById("warning").innerHTML = "<p id='warning' style='color:red;'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz<br /></p>"; 
	}else{
		//kasowanie pola warning jeżeli zostało wcześniej wypełnione
		document.getElementById("warning").innerHTML = "<p id='warning'> </p>"; 
		//generowanie AJAX request
		handleAjaxRequest("ajax_request/show.php?&data="+$data+"&start="+$start+"&stop="+$stop,
				function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var requestOutput = JSON.parse(xmlhttp.responseText);
				document.getElementById("sale").innerHTML = requestOutput[0]; 
			}
		});
	}
}	

/**Pobranie dostępnych sal dla formularza edycji rezerwacji. Jako pierwsza na liście zawsze sala wcześniej wybrana.
 * @returns zwraca kod HTML wypełniajacy <select> sale
 */
function salaCheckEditJS() {	
	$data=document.getElementById("data").value;
	$start=document.getElementById("timestart").value;
	$stop=document.getElementById("timefinish").value;
	$sala=document.getElementById("sala").value;
	document.getElementById("wynik").value = $sala; 
	//sprawdzenie czy koniec rezerwacji wybrano później niż jej rozpoczęcie
	if($start>=$stop){
		document.getElementById("warning").innerHTML = "<p id='warning' style='color:red;'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz<br /></p>"; 
	}else{
		//kasowanie pola warning jeżeli zostało wcześniej wypełnione
		document.getElementById("warning").innerHTML = "<p id='warning'></p>"; 
		//generowanie AJAX request
		handleAjaxRequest("ajax_request/show.php?&data="+$data+"&start="+$start+"&stop="+$stop+"&sala="+$sala,
				function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var requestOutput = JSON.parse(xmlhttp.responseText);
				document.getElementById("sale").innerHTML = requestOutput[0]; 
			}
		});
	};
}

/**Pobranie wszystkich sal z bazy
 * @returns zwraca kod HTML wypełniajacy <select> sale
 */
function saleList() {	
	$data=document.getElementById("data").value;
		document.getElementById("warning").innerHTML = "<p id='warning'></p>"; 
			handleAjaxRequest("ajax_request/show.php?&&data="+$data,
					function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var requestOutput = JSON.parse(xmlhttp.responseText);
					document.getElementById("sale").innerHTML = requestOutput[0]; 
				}
			});
}	

