<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	$estadopagina=4; //pausa

	include "scripts.php";
	include "functions.php";
	include "definicionmodulo.php";
	include "includes/scripts.php";

	include "validacionestadoactualTablero.php";

	include "../conexion.php";
	$query2 = mysqli_query($conexion,"
				SELECT u.*, r.numeroordenproduccion , s.nombre 
				FROM modulos u 
				INNER JOIN ordenesproduccion r ON u.ordendeprod=r.idordenproduccion
				INNER JOIN producto s ON  u.itemaproducir=s.idproducto
				WHERE u.idmodulo=$mod");
	mysqli_close($conexion);
	$data=mysqli_fetch_array($query2);
	$productoshechos=$data['productoshechos'];
	$unidadesesperadas=$data['unidadesesperadas'];
	$porcentajecompletado=$productoshechos*100/$unidadesesperadas;
	$ordendeprod=$data['numeroordenproduccion'];
	$itemaproducir=$data['nombre'];
	$ultimotiempodeproduccion=$data['ultimotiempodeproduccion'];
	$tiempocicloesperado=$data['tiempocicloesperado'];
	$pausashechas=$data['pausashechas'];
	$tiempopausado=$data['tiempopausado'];
	$tiempoacumulado=$data['tiempoacumulado'];
	$eficienciaacumulada=$productoshechos*$tiempocicloesperado*6000/$tiempoacumulado;
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Estado 4 Pausa en tablero</title>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="5">
</head>
<body onload="mueveReloj()">
	<div>
		<hr size="8px" color="black" />
		<form name="form_reloj">
			<input type="text" name="reloj" style="font-size : 14pt; text-align : left;" onfocus="window.document.form_reloj.reloj.blur()">
		</form>
		<h3 align='left'> Fecha: <?php echo date("d/m/Y"); ?></h3>
		

		<?php 
	  		//letrero nombre módulo
	  		include "letreroNombreModulo.php"; 
	  	?>
		

		<hr size="3px" color="black" />
		<h1 style='background-color:#F05B64';>Producción pausada por el supervisor.</h1>
		<hr size="3px" color="black" />
		
		<h3>Orden de producción: <?php echo $ordendeprod; ?><br>Item a producir: <?php echo $itemaproducir; ?></h3>
		<hr size="3px" color="black" />
		<h3>Unidades terminadas actualmente: <?php echo $productoshechos; ?><br>
		Unidades programadas: <?php echo $unidadesesperadas; ?><br>
		Porcentaje completado: <?php echo $porcentajecompletado; ?> %</h3>
		<hr size="3px" color="black" />
		<h3>Ultimo tiempo de ciclo realizado: 

			<?php 
				if ($productoshechos > 1){
					//primer productdo
					echo round($ultimotiempodeproduccion/60,2)." minutos, ".round($ultimotiempodeproduccion,2)." segundos."; 
					$eficienciaultimociclo=round($tiempocicloesperado*6000/$ultimotiempodeproduccion,2)." %";
				}else{
					//segundo producto en adelante.
					echo ("No aplica para la primera unidad hecha.");
					$eficienciaultimociclo=" No aplica para la primera unidad hecha.";
				}
			?>

			<br>
			Tiempo de ciclo esperado: <?php echo $tiempocicloesperado; ?> minutos, <?php echo $tiempocicloesperado*60; ?> segundos.<br>
			Eficiencia del ultimo ciclo: <?php echo $eficienciaultimociclo; ?><br>
		</h3>

		<h3>Eficiencia Acumulada: <?php echo round($eficienciaacumulada,2); ?></h3>
		<h3>Pausas hechas: <?php echo ($pausashechas); ?></h3> 
		
		<h3>Tiempo acumulado en pausas en minutos: <?php echo round($tiempopausado/60,2); ?>, en segundos: <?php echo ($tiempopausado); ?></h3>
		<h3>Tiempo acumulado en trabajo hecho en minutos: <?php echo round($tiempoacumulado/60,2); ?>, en segundos: <?php echo ($tiempoacumulado); ?></h3>



		<a href="index.php">Regresar a la ventana de inicio</a>
		

		<?php 
	  		//Selector de cambio de módulo
	  		include "selectCambioModulo.php"; 
	  	?>

		<script>
			function cambiodemodulo(val) {
	  		url="pausaTablero.php?mod="+val;
	  		location.replace(url);
			}
		</script>
	</div>	
</body>
</html>