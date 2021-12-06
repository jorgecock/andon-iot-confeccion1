<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	$estadopagina=3; //contando

	include "scripts.php";
	include "functions.php";
	include "definicionmodulo.php";
	include "includes/scripts.php";

	include "validacionestadoactualTablero.php";

	include "../conexion.php";
	$query2 =mysqli_query($conexion,"
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
	$idordenproduccion=$data['ordendeprod'];
	$ordendeprod=$data['numeroordenproduccion'];
	$idproducto=$data['itemaproducir'];
	$itemaproducir=$data['nombre'];
	$ultimotiempodeproduccion=$data['ultimotiempodeproduccion'];
	$tiempocicloesperado=$data['tiempocicloesperado'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Estado 3 Contando, tablero</title>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="5">
</head>
<body onload="mueveReloj()">
	<div style="padding: 10px; float: left; width: 50%; text-align: justify;">
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
		<h1 style='background-color:#F7F561;'>Conteo de producción</h1>
		<hr size="3px" color="black" />
		<h3>Orden de producción: <?php echo $ordendeprod; ?><br>Item a producir: <?php echo $itemaproducir; ?></h3>
		<hr size="3px" color="black" />
		<h3>Unidades terminadas actualmente: <?php echo $productoshechos; ?><br>
		Unidades programadas: <?php echo $unidadesesperadas; ?><br>
		Porcentaje completado: <?php echo round($porcentajecompletado,2); ?> %</h3>
		<hr size="3px" color="black" />
		<h3>Ultimo tiempo de ciclo realizado: 

			<?php 
				if ($productoshechos > 1){
					//primer productdo
					echo round($ultimotiempodeproduccion/60,2)." minutos, ".round($ultimotiempodeproduccion,2)." segundos"; 
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
		<hr size="8px" color="black" />
		
	</div>	
	


	<div style="padding: 10px; float: right; width: 50%; text-align: justify;"	>
		
		<?php
			
			$idmodulo=$mod;
			$fecha=date('y-m-d');

			include "../conexion.php";
			$query1 = mysqli_query($conexion,"
				SELECT *
				FROM registroeficiencias
				WHERE (ordendeprod='$idordenproduccion' AND itemaproducir='$idproducto' AND modulo=$idmodulo AND (fechahora LIKE '%$fecha%'))" );
	
			$result = mysqli_num_rows($query1);
			if($result>0){
				while ($data=mysqli_fetch_array($query1)) {
					$eficiencias[]=$data;		
				}

				$query_tipo = mysqli_query($conexion,"
								SELECT nombremodulo FROM  modulos    
								WHERE (status=1 AND idmodulo=$idmodulo)");
				$tipoa= mysqli_fetch_array($query_tipo);
				$nombremodulo=$tipoa['nombremodulo'];
				
				$query_tipo = mysqli_query($conexion,"
								SELECT numeroordenproduccion FROM  ordenesproduccion    
								WHERE (status=1 AND idordenproduccion=$idordenproduccion)");
				$tipoa= mysqli_fetch_array($query_tipo);
				$numeroordenproduccion=$tipoa['numeroordenproduccion'];

				$query_tipo = mysqli_query($conexion,"
								SELECT nombre FROM  producto    
								WHERE (status=1 AND idproducto=$idproducto)");
				$tipoa= mysqli_fetch_array($query_tipo);
				$nombre=$tipoa['nombre'];
			}else{
				$eficiencias=[];
			}
			mysqli_close($conexion);
		?>
		<hr size="3px" color="black" />
		<table id="" class="table table-striped table-bordered">
			<tr>
				<th>Hora</th>
				<th>Cantidad Esperada</th>
				<th>Cantidad Hecha</th>
				<th>Eficiencia Acumulada</th>
			</tr>
			
			<tbody>
				<?php foreach($eficiencias as $eficiencia) { ?>
					<tr>
						<td><?php echo substr($eficiencia['fechahora'], 11) ; ?></td>
						<td><?php echo $eficiencia['cantidadesperada']; ?></td>
						<td><?php echo $eficiencia['cantidadhecha']; ?></td>
						<td><?php echo $eficiencia['eficiencia']; ?></td>
					</tr>
				<?php } ?>
			</tbody>	
		</table>
		
		<?php 
	  		//Selector de cambio de módulo
	  		include "selectCambioModulo.php"; 
	  	?>

		<script>
			function cambiodemodulo(val) {
	  		url="conteoTablero.php?mod="+val;
	  		location.replace(url);
			}
		</script>

		<a href="index.php">Regresar a la ventana de inicio</a>
		<hr size="8px" color="black" />
	</div>
</body>
</html>