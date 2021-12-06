<?php
	session_start();
	include "includes/scripts.php";
	
	$idordenproduccion=1;

	$mensaje ="";
	$verdatosyexportar=false; // si se activa es porque ya se encontraron datos para descargar
	$inicio=true;
	$mod="";

	if(!isset($_POST["cargar_data"])) {
		$idproducto=""; //se cambiara cuando sea un select para item
	} else {
		if (empty($_POST['idordenproduccion']) AND empty($_POST['itemaproducir'])){
				echo "Debe seleccionar una orden de produccion y un tipo de prenda producida";
		} else {
			$inicio=false;
			$idordenproduccion=$_POST['idordenproduccion'];
			$idproducto=$_POST['itemaproducir'];
			$idmodulo=$_POST['idmodulo'];
			$fecha=$_POST['fecha'];

			include "../conexion.php";
			$query1 = mysqli_query($conexion,"
				SELECT * FROM registroeficiencias
				WHERE (ordendeprod=$idordenproduccion AND itemaproducir='$idproducto' AND modulo=$idmodulo AND (fechahora LIKE '%$fecha%'))");
			mysqli_close($conexion);
			$result = mysqli_num_rows($query1);
			if($result>0){
				while ($data=mysqli_fetch_array($query1)) {
					$eficiencias[]=$data;	
				}
				$verdatosyexportar=true;
				$mod=$eficiencias[0]['modulo'];	
			} else {
				$mensaje = "Datos no encontrados" ;
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Eficiencias a Excel</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php include "includes/header.php"; ?>
		<br><br><br><br><br>
		<div class="container">
			<h2>Eficiencias acumuladas al ejecutar una orden de producción de un producto específico</h2>
			<div class="well-sm col-sm-12">
				<div class="btn-group pull-right">
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post">
						
						
						<!-- Orden de produccion -->
						<label for="idordenproduccion">Orden de producción:</label>
						<?php
							include "../conexion.php";
							$query_tipo = mysqli_query($conexion,"SELECT * FROM ordenesproduccion WHERE status=1");
							mysqli_close($conexion);
							$result_tipo = mysqli_num_rows($query_tipo);
						?>
						<select name="idordenproduccion" id="idordenproduccion" onchange="refrescar()">
							<?php 
								if($result_tipo>0){
								while ($tipoa= mysqli_fetch_array($query_tipo)) { 
									?>
									<option value="<?php echo $tipoa['idordenproduccion']; ?>" 
										<?php 
											if ($inicio==false AND $tipoa['idordenproduccion']==$idordenproduccion){
												echo (" selected");
											}
										?>
									>
										<?php echo $tipoa["numeroordenproduccion"]; ?>
									</option>
								<?php }
								}
							?>
						</select>
						<br>
						

						<!-- Item producido -->
						<label for="itemaproducir">Item a producir: </label>
						<?php
							include "../conexion.php";
							$query_tipo = mysqli_query($conexion,"SELECT * FROM producto WHERE status=1");
							mysqli_close($conexion);
							$result_tipo = mysqli_num_rows($query_tipo);
						?>
						<select name="itemaproducir" id="itemaproducir" onchange="refrescar()">
							<?php 
								if($result_tipo>0){
								while ($tipoa= mysqli_fetch_array($query_tipo)) { 
									?>
									<option value="<?php echo $tipoa['idproducto']; ?>" 
										<?php 
											if ($inicio==false AND $tipoa['idproducto']==$idproducto){
												echo (" selected");
											}
										?>
									>
										<?php echo $tipoa["nombre"]; ?>
									</option>
								<?php }
								}
							?>
						</select>
						<br>


						<!-- modulos en los que se ha ejecutado la orden de produccion -->
						<label for="idmodulo">Modulo:</label>
						<?php
							include "../conexion.php";
							$query_tipo = mysqli_query($conexion,"
								SELECT * FROM  modulos    
								WHERE (status=1)");
							mysqli_close($conexion);
							$result_tipo = mysqli_num_rows($query_tipo);
						?>
						<select name="idmodulo" id="idmodulo" onchange="refrescar()">
							<?php 
								if($result_tipo>0){
								while ($tipoa= mysqli_fetch_array($query_tipo)) { 
									?>
									<option value="<?php echo $tipoa['idmodulo']; ?>" 
										<?php 
											if ($inicio==false AND $tipoa['idmodulo']==$idmodulo){
												echo (" selected");
											}
										?>
									>
										<?php echo $tipoa["nombremodulo"]; ?>
									</option>
								<?php }
								}
							?>
						</select>
						<br>



						<!-- Fecha en que se produjo el produccto -->
						<label for="fecha">Fecha: </label>
						<input type="date" id="fecha" name="fecha" value="<?php 
							if ($inicio==true){
								echo ""; 
							}else{
								echo $fecha;
							}
						?>"
						 onchange="refrescar()">
						<br>

						<!-- Boton enviar a excel -->
						<?php if ($verdatosyexportar==true){ ?>
							<a href="excel.php?idordenproduccion=<?php echo $idordenproduccion ?>&itemaproducir=<?php echo $idproducto ?>&idmodulo=<?php echo $idmodulo ?>&fecha=<?php echo $fecha ?>">Exportar a Excel</a>
						<?php } ?>

						<!-- Boton Buscar datos -->
						<button type="submit" id="cargar_data" name='cargar_data' value="Buscar Eficiencias" class="btn btn-info">Buscar Eficiencias</button>
						<br><br>
						
						<!-- vinculo para regresar, inhabilitado por estar ya en plataforma
							<a href="index.php">Regresar a la ventana de inicio</a> 
						-->
						<?php echo $mensaje; ?>
					</form>
				</div>
			</div>
		
			<script>
				function refrescar() {
					var idordenproduccion = document.getElementById('idordenproduccion').value;
					var idproducto = document.getElementById('itemaproducir').value;
					var idmodulo = document.getElementById('idmodulo').value;
					var fecha = document.getElementById('fecha').value;
	  			//window.alert(idordenproduccion+" "+idproducto+" "+idmodulo+" "+fecha);	
	  			url="eficienciasaexcel.php?op="+idordenproduccion+"&it="+idproducto+"&mod="+idmodulo+"&fe="+fecha;
	  			//location.replace(url);
				}
			</script>



			<?php if ($verdatosyexportar==true){ 
				include "../conexion.php";
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
				
				mysqli_close($conexion);

			?>


				<h2>Modulo: <?php echo $nombremodulo; ?></h2>
				<h2>Orden de Producción: <?php echo $numeroordenproduccion; ?></h2>
				<h2>Item a producir: <?php echo $nombre	; ?></h2>
 
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
								<td><?php echo $eficiencia ['fechahora']; ?></td>
								<td><?php echo $eficiencia ['cantidadesperada']; ?></td>
								<td><?php echo $eficiencia ['cantidadhecha']; ?></td>
								<td><?php echo $eficiencia ['eficiencia']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		</div>
		<?php  include "includes/footer.php"; ?>
	</body>
</html>



