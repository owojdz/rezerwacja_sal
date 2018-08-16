var xmlhttp;

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


function salaCheckJS(id_aktora,id_filmu,state) {	//Funkcja wywoływana przy kliknięciu na pozycję menu
	$data=document.getElementById("data").value;
	$start=document.getElementById("timestart").value;
	$stop=document.getElementById("timefinish").value;
//	document.getElementById("wynik").value = $data+";"+$start+";"+$stop; 
//	document.getElementById("wynik").value = $sala; 
	if($start>=$stop){
		document.getElementById("warning").innerHTML = "<p type='text' id='warning'><font color='red'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz</font><br /></p>"; 
	}else{
		document.getElementById("warning").innerHTML = "<p id='warning' type='hidden'></p>"; 
			handleAjaxRequest("ajax_request/show.php?&state="+state+"&data="+$data+"&start="+$start+"&stop="+$stop,
					function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var requestOutput = JSON.parse(xmlhttp.responseText);
					document.getElementById("sale").innerHTML = requestOutput[0]; 
//					document.getElementById("wynik").value = requestOutput[0]; 
//					document.getElementById("filmyWithout").innerHTML = requestOutput[0];
				}
			});
	}
}	

function salaCheckEditJS(id_aktora,id_filmu,state) {	//Funkcja wywoływana przy kliknięciu na pozycję menu
	$data=document.getElementById("data").value;
	$start=document.getElementById("timestart").value;
	$stop=document.getElementById("timefinish").value;
	$sala=document.getElementById("sala").value;
//	document.getElementById("wynik").value = $data+";"+$start+";"+$stop; 
	document.getElementById("wynik").value = $sala; 
	if($start>=$stop){
		document.getElementById("warning").innerHTML = "<p type='text'><font color='red'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz</font><br /></p>"; 
	}else{
		document.getElementById("warning").innerHTML = "<p id='warning' type='hidden'></p>"; 
			handleAjaxRequest("ajax_request/show.php?&state="+state+"&data="+$data+"&start="+$start+"&stop="+$stop+"&sala="+$sala,
					function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var requestOutput = JSON.parse(xmlhttp.responseText);
					document.getElementById("sale").innerHTML = requestOutput[0]; 
//					document.getElementById("wynik").value = requestOutput[0]; 
//					document.getElementById("filmyWithout").innerHTML = requestOutput[0];
				}
			});
	};
}

function saleList(id_aktora,id_filmu,state) {	//Funkcja wywoływana przy kliknięciu na pozycję menu
	$data=document.getElementById("data").value;
		document.getElementById("warning").innerHTML = "<p id='warning' type='hidden'></p>"; 
			handleAjaxRequest("ajax_request/show.php?&state="+state+"&data="+$data,
					function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var requestOutput = JSON.parse(xmlhttp.responseText);
					document.getElementById("sale").innerHTML = requestOutput[0]; 
//					document.getElementById("wynik").value = requestOutput[0]; 
//					document.getElementById("filmyWithout").innerHTML = requestOutput[0];
				}
			});
}	

