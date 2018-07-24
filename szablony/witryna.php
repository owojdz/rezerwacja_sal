<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php $NAZWA_STRONY ?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<!--  charset=windows-1250"> -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">-->
<!-- <link rel="Stylesheet" type="text/css" href="styles/style.css" />	-->
<!-- <link rel="Stylesheet" type="text/css" href="styles/menu.css" />	 -->
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
</head>
<body>
	<div class="container">
	<?php require_once 'szablony/menu.php';?>
 	<div class="row">
			<div class="col-lg-3 col-sm-6" id="TRESC"><?php echo $TRESC;?></div>
			<div class="col-lg-9 col-sm-12" id="TRESC1"><?php echo $TRESC1;?></div>
		</div>
		<!-- <div id="STOPKA"><?php require_once 'szablony/stopka.php';?></div>  -->
	</div>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/javascript.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
		integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
		crossorigin="anonymous"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
		integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
		crossorigin="anonymous"></script>
	<script
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"
		integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
		crossorigin="anonymous"></script>
</body>
</html>


