<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	$estadopagina=1; //entrandoorden
	$idempresa=$_SESSION['idempresa'];
	include "scripts.php";
	include "functions.php";
	include "definicionmodulo.php";
	include "includes/scripts.php";
	include "validacionestadoactualTablero.php"; // cambia de ventana según el estado en el que esté el modulo
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Sin programar modulo visualizado en tablero</title>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="5">
</head>
<body align='center' onload="mueveReloj()">	
	<div>
		<hr size="8px" color="black" />
		<form name="form_reloj" align="left">
			<input type="text" name="reloj" style="font-size : 14pt; text-align : left;" onfocus="window.document.form_reloj.reloj.blur()">
		</form>
		<h3 align='left'> Fecha: <?php echo date("d/m/Y"); ?></h3>
		
		<?php 
	  		//letrero nombre módulo
	  		include "letreroNombreModulo.php"; 
	  	?>
		
		<h1>En espera a ser programado.</h1>
		<br>
		<a href="index.php">Regresar a la ventana de inicio</a>
	  	

	  	<?php 
	  		//Selector de cambio de módulo
	  		include "selectCambioModulo.php"; 
	  	?>

		<script>
			function cambiodemodulo(val) {
	  		url="programarTablero.php?mod="+val;
	  		location.replace(url);
			}
		</script>
	</div>
	<br>
	<?php  include "includes/footer.php"; ?>
</body>
</html>