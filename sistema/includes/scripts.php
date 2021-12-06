	<link rel="stylesheet" type="text/css" href="css/style1.css">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/icons.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<?php 
		date_default_timezone_set('America/Bogota');
	
		function fechaC(){
			$mes = array("","Enero", 
						  "Febrero", 
						  "Marzo", 
						  "Abril", 
						  "Mayo", 
						  "Junio", 
						  "Julio", 
						  "Agosto", 
						  "Septiembre", 
						  "Octubre", 
						  "Noviembre", 
						  "Diciembre");
			return date('d')." de ". $mes[date('n')] . " de " . date('Y');
		}

	?>
	
