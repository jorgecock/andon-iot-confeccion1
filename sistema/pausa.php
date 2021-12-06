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
	$prodhechosdespausaini=$data['prodhechosdespausaini'];
	$tiempopausadoanterior=$data['tiempopausado'];
	$momentodepausa=$data['momentodepausa'];
	$tiempoactual=strtotime("now");
	$tiempopasadodesdeultimapausa=($tiempoactual-$momentodepausa);
	$nuevotiempoacumpausa=$tiempopasadodesdeultimapausa+$tiempopausadoanterior;
  $pausashechas=$data['pausashechas'];
  $tiempoacumulado=$data['tiempoacumulado'];
  $eficienciaacumulada=$productoshechos*$tiempocicloesperado*6000/$tiempoacumulado;

	//Definicion de estado siguiente
	if (isset($_POST)){
		//Selecciona a la pagina del siguiente estado con la funcion de salida para iniciar el estado siguiente
		$prodhechosdespausaini=0; //productos hechos luego de pausa
		if (isset($_POST['reanudar'])){
				
			$siguienteestado=3; //estado continuar conteo
			$momentoinidespausa=strtotime("now");

			include "../conexion.php";
			$query1 = mysqli_query($conexion,"
				UPDATE modulos 
				SET estado=$siguienteestado, prodhechosdespausaini=$prodhechosdespausaini, momentoinidespausa= $momentoinidespausa, tiempopausado=$nuevotiempoacumpausa
				WHERE idmodulo=$mod");
			mysqli_close($conexion);
			header("location: conteo.php");
		} 

		if (isset($_POST['terminar'])){
			
			$siguienteestado=6; //estado terminado
			
			include "../conexion.php";
			$query1 = mysqli_query($conexion,"
				UPDATE modulos 
				SET estado=$siguienteestado,prodhechosdespausaini=$prodhechosdespausaini
				WHERE idmodulo=$mod");
			mysqli_close($conexion);
			header("location: reportefinal.php");
		}
	}

	include "validacionestadoactual.php";
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Estado 4 Pausa</title>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="5">
</head>
<body onload="mueveReloj()">
	<div>	
		<?php include "includes/header.php"; ?>	
		<br><br><br><br>
	</div>
	<div>
		<hr size="8px" color="black" />
		<form name="form_reloj">
			<input type="text" name="reloj" style="font-size : 14pt; text-align : left;" onfocus="window.document.form_reloj.reloj.blur()">
		</form>
		
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
			if ($prodhechosdespausaini > 1){
				//primer productdo
				echo round($ultimotiempodeproduccion/60,2)." minutos, ".round($ultimotiempodeproduccion,2)." segundos"; 
				$eficienciaultimociclo=round($tiempocicloesperado*6000/$ultimotiempodeproduccion,2)." %";
			}else{
				//segundo producto en adelante.
				echo ("No aplica para la primera unidad hecha despues del inicio de producción o luego de renudar por algún tipo de pausa.");
				$eficienciaultimociclo=" No aplica para la primera unidad hecha despues del inicio de producción o luego de renudar por algún tipo de pausa.";
			}
		?>
		<br>
		Tiempo de ciclo esperado: <?php echo $tiempocicloesperado; ?> minutos, <?php echo $tiempocicloesperado*60; ?> segundos.<br>
		Eficiencia del ultimo ciclo: <?php echo $eficienciaultimociclo; ?><br>
		</h3>
		<h3>Eficiencia Acumulada: <?php echo round($eficienciaacumulada,2); ?></h3>
		<h3>Tiempo Acumulado en pausa: <?php echo round($nuevotiempoacumpausa/60,2); ?>, en segundos: <?php echo round($nuevotiempoacumpausa,2); ?></h3>
		
		<h3>Tiempo transcurrido en la ultima pausa en minutos: <?php echo round($tiempopasadodesdeultimapausa/60,2); ?>, en segundos: <?php echo round($tiempopasadodesdeultimapausa,2); ?></h3>

		<h3>Pausas hechas: <?php echo ($pausashechas); ?></h3>
		<h3>Tiempo acumulado en trabajo hecho en minutos: <?php echo round($tiempoacumulado/60,2); ?>, en segundos: <?php echo ($tiempoacumulado); ?></h3>
		<hr size="3px" color="black" />

		<form method="post" action="">
			
			<input type="submit" name="reanudar" value="Reanudar Conteo"><br> 
			<input type="submit" name="terminar" value="terminar">
			<!-- <a href="index.php">Regresar a la ventana de inicio</a> -->
		</form>	
		
		<?php 
	  		//Selector de cambio de módulo
	  		include "selectCambioModulo.php"; 
	  	?>

		<script>
			function cambiodemodulo(val) {
	  		url="pausa.php?mod="+val;
	  		location.replace(url);
			}
		</script>
	</div>	
	<?php  include "includes/footer.php"; ?>
</body>
</html>