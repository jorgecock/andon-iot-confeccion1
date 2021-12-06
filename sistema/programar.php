<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	$idempresa=$_SESSION['idempresa'];
	$estadopagina=1; //entrandoorden

	include "scripts.php";
	include "functions.php";
	include "definicionmodulo.php";
	include "includes/scripts.php"; 
	include "validacionestadoactual.php"; // cambia de ventana según el estado en el que esté el modulo

	//Definicion de estado siguiente
	$alert="";
	if (!empty($_POST)){
		if (empty($_POST['unidadesesperadas']) AND empty($_POST['ordendeprod']) AND empty($_POST['itemaproducir']) AND empty($_POST['personasasignadas'])){
			$alert="Se deben llenar todos los campos";
		} else {
			if ($_POST['unidadesesperadas']==0 || $_POST['personasasignadas']==0){
				$alert="Lo campos no pueden estar en cero";
			} else {
				$ordendeprod=$_POST['ordendeprod'];
				$nper=$_POST['personasasignadas'];
				$siguienteestado=3; //estado conteo
				$unidadesesperadas=$_POST['unidadesesperadas'];
				$itemaproducir=$_POST['itemaproducir'];
				$momentodeinicio=strtotime("now");
				$momentoinidespausa=strtotime("now");

				include "../conexion.php";
				$query1 = mysqli_query($conexion,"
					UPDATE modulos 
					SET estado=$siguienteestado, unidadesesperadas=$unidadesesperadas, ordendeprod='$ordendeprod', itemaproducir='$itemaproducir', productoshechos=0, momentodeinicio=$momentodeinicio, tiempopausado=0, tiempoacumulado=0, tiemporegistro=0, tiemporegistroanterior=0, ultimotiempodeproduccion=0,  prodhechosdespausaini=0, momentoinidespausa= $momentoinidespausa, pausashechas=0, numper=$nper
					WHERE idmodulo=$mod");
				mysqli_close($conexion);
				
				header("location: tablero.php");
			}
		}		
	} 

	//datos para los select de ordenes de produccion y producto
	include "../conexion.php";
	$query_ordenesproduccion = mysqli_query($conexion,"SELECT * FROM ordenesproduccion WHERE status=1 AND idempresa=$idempresa");
	$query_producto = mysqli_query($conexion,"SELECT * FROM producto WHERE status=1 AND idempresa=$idempresa");
	mysqli_close($conexion);
	$result_ordenesproduccion = mysqli_num_rows($query_ordenesproduccion);
	$result_producto = mysqli_num_rows($query_producto);
	if($result_ordenesproduccion==0 or $result_producto==0){
		header("location: sinmodulosproductosordenes.php");
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Estado 1 Ingreso de Datos de orden de producción</title>
</head>
<body onload="mueveReloj()">
	<div>	
		<?php include "includes/header.php"; ?>	
		<br><br><br><br>
	</div>	
	<div>	
		<!-- Reloj -->
		<hr size="8px" color="black" />
		<form name="form_reloj">
			<input type="text" name="reloj" style="font-size : 14pt; text-align : left;" onfocus="window.document.form_reloj.reloj.blur()">
		</form>
		
		<?php 
	  		//letrero nombre módulo
	  		include "letreroNombreModulo.php"; 
	  	?>

		<hr size="3px" color="black" />
		<h1>Datos de orden de producción para el día:</h1>
	
		<h2>Inserte los datos de la orden de producción a programar en el módulo.</h2>
		<hr size="3px" color="black" />
		
		<form align='left' method="post" action="">
			
			<!-- Orden de produccion -->
			<label for="ordendeprod">Orden de producción:</label>
			<select name="ordendeprod" id="ordendeprod">
				<?php 
					while ($tipoa= mysqli_fetch_array($query_ordenesproduccion)) { ?>
						<option value="<?php echo $tipoa["idordenproduccion"]; ?>">	
							<?php echo $tipoa["numeroordenproduccion"]; ?>
						</option>
					<?php }
				?>
			</select>

			<!-- Item a producir de produccion -->
			<label for="itemaproducir">Item a producir:</label>
			<select name="itemaproducir" id="itemaproducir">
				<?php 
					while ($tipoa= mysqli_fetch_array($query_producto)) { ?>
						<option value="<?php echo $tipoa["idproducto"]; ?>">	
							<?php echo $tipoa["nombre"]; ?>
						</option>
					<?php }
				?>	
			</select>
			
			<label for="unidadesesperadas">Unidades requeridas a programar:  </label>
			<input type="number" name="unidadesesperadas">

			<label for="personasasignadas">Numero de personas asignadas al módulo:  </label>
			<input type="number" name="personasasignadas">
			
			<br>
			<input type="submit" name="ProgProd" value="Programar Producción">
			<!-- <a href="index.php">Regresar a la ventana de inicio</a> -->
			<h4 style="color:red"><?php echo $alert; ?></h4>
		</form>

	  	
	  	<?php 
	  		//Selector de cambio de módulo
	  		include "selectCambioModulo.php"; 
	  	?>

		<script>
			function cambiodemodulo(val) {
	  		url="programar.php?mod="+val;
	  		location.replace(url);
			}
		</script>
	</div>
	<br>
	<?php  include "includes/footer.php"; ?>
</body>
</html>