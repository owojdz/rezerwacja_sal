//<SCRIPT LANGUAGE="JavaScript">
function checkform ()
{
	$data = document.getElementById("data").value;
	$sala = document.getElementById("sale").value;
	$start=document.getElementById("timestart").value;
	$stop=document.getElementById("timefinish").value;
	$valuess=false;
	if($start>=$stop){
		document.getElementById("warning").innerHTML = "<p type='text' id='warning'><font color='red'>Zakończenie rezerwacji nie jest później niż jej początek. Spróbuj jeszcze raz</font><br /></p>"; 
        return false ;
	}else{
		document.getElementById("warning").innerHTML = "<p id='warning' type='hidden'></p>"; 
			handleAjaxRequest("ajax_request/show_reservations.php?&sala="+$sala+"&data="+$data+"&start="+$start+"&stop="+$stop,
					function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					result = new Array();
					result = JSON.parse(xmlhttp.responseText);
					if (result[0]=="false"){
						document.getElementById("warning").innerHTML = "<p type='text' id='warning'><font color='red'>Sala jest już zajęta w wybranym terminie. Spróbuj jeszcze raz</font><br /></p>";
						return false ;
//						$valuess=false;
					} else {
						document.getElementById("wartosc").value="true";
						document.getElementById("warning").innerHTML = "<p type='text' id='warning'><font color='red'>submit</font><br /></p>"; 
//						document.getElementById("submi").value="set";
						document.getElementById("formularz").submit();
//						form.submit();
						return true ;			
//						$valuess=true;
					}
				}
		});	
//		if (document.getElementById("wartosc").value=="true") {
//			alert( "submit");
//			document.getElementById("warning").innerHTML = "<p type='text' id='warning'><font color='red'>ok</font><br /></p>"; 
//			document.getElementById("formularz").submit();
//			return true;
//		} else {
//	        wait(5000);
//			return false;
//		}	
    }
}
function wait(ms)
{
var d = new Date();
var d2 = null;
do { d2 = new Date(); }
while(d2-d < ms);
}

function enable() {
//    document.getElementById("timefinish").disabled=false;    
}
function add_one() {
	var str=document.getElementById("timestart").value;
	var h=str.slice(0, 2);
	var res = str.slice(2, 8);
	var hint = parseInt(h);
	hint=hint+1;
	str = hint.toString();
	if (hint<10) str="0".concat(str);
	str = str.concat(res);
	document.getElementById("timefinish").value=str;
}
function edit(id) {
	var str1 = "edit.php?id=";
	var str2 = id;
	var res = str1.concat(str2);
	this.open(res,"_self");
}
function del(id) {
 	if (confirm("Czy napewno usunąć rezerwację?")  ){
 		var str1 = "delete.php?id=";
 		var str2 = id;
 		var res = str1.concat(str2);
 		this.open(res,"_self");
	}
}
/*function checkform ( form )
{
    if (form.timestart.value >= form.timefinish.value) {
        alert( "Czas końca nie jest późniejszy niż czas początku spotkania"+form.timestart.value+"  "+form.timefinish.value );
        return false ;
    }
    return true;
}*/
function show_reservations_by_color(){
	var c= document.getElementById("container"); 
	var myNodelist = c.querySelectorAll(".cal");
	//var myNodelist = c.querySelectorAll("div");
	var i;
	for (i = 0; i < myNodelist.length; i++) {
	    myNodelist[i].style.backgroundColor = "white";
	    myNodelist[i].innerText = "";
	}
		$data = document.getElementById("data").value;
		$sala = "Czarna";
		handleAjaxRequest("ajax_request/show_reservations.php?&data="+$data+"&sala="+$sala,
			function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					hours = new Array();
					hours = JSON.parse(xmlhttp.responseText);
//					document.getElementById(hours[2]).style.backgroundColor = "blue"; 
//					document.getElementById("wynik").value=hours[0][0]; 
					var str, i, j, plus;
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

function show_reservations_single(){
	var c= document.getElementById("container"); 
	var myNodelist = c.querySelectorAll(".cal");
	var i;
	for (i = 0; i < myNodelist.length; i++) {
	    myNodelist[i].style.backgroundColor = "white";
	    myNodelist[i].innerText = "";
	}
		$data = document.getElementById("data").value;
		$sala = document.getElementById("sale").value;
		handleAjaxRequest("ajax_request/show_reservations_single.php?&data="+$data+"&sala="+$sala,
			function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					hours = new Array();
					hours = JSON.parse(xmlhttp.responseText);
					document.getElementById("nazwaSali").innerText = $sala; 
					var str, i, j, plus;
						for(i=0;i<hours.length;i+=2){
	        					str=String(hours[i]);
	        					plus=0+1;
	        					str= str.concat(plus);
	        					document.getElementById(str).style.backgroundColor = "#dfe2e8"; 
	        					document.getElementById(str).style.fontSize = "small";
	        					document.getElementById(str).innerText = hours[i+1]; 
						}
				}
			});
}//show_reservations_single()

//</script>