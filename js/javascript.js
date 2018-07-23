//<SCRIPT LANGUAGE="JavaScript">
function checkform ( form )
{
    if (form.timestart.value >= form.timefinish.value) {
//        alert( "Czas końca nie jest późniejszy niż czas początku spotkania"+form.timestart.value+"  "+form.timefinish.value );
        return false ;
    }
    return true;
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
function checkform ( form )
{
    if (form.timestart.value >= form.timefinish.value) {
        alert( "Czas końca nie jest późniejszy niż czas początku spotkania"+form.timestart.value+"  "+form.timefinish.value );
        return false ;
    }

    
    return true;
}
//</script>