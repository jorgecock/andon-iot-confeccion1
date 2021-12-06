
<?php
	header("Content-Type: application/xls; charset=utf-8");
	header("Content-Disposition: attachment; filename= reporte.xls");	



	$idordenproduccion=($_GET['idordenproduccion']);
	$idproducto=($_GET['itemaproducir']);
	$idmodulo=$_GET['idmodulo'];
	$fecha=$_GET['fecha'];



	include "../conexion.php";
	$query1 = mysqli_query($conexion,"
		SELECT *
		FROM registroeficiencias
		WHERE (ordendeprod='$idordenproduccion' AND itemaproducir='$idproducto' AND modulo=$idmodulo AND (fechahora LIKE '%$fecha%'))" );
		include "conexion.php";
	
	$result = mysqli_num_rows($query1);
	if($result>0){
		while ($data=mysqli_fetch_array($query1)) {
			$eficiencias[]=$data;	
		}
		$mod=$eficiencias[0]['modulo'];
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

	mysqli_close($conexion);
	

?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Grabar</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<h2>Modulo: <?php echo $nombremodulo; ?></h2>	
		<h2>Orden de Producción: <?php echo $numeroordenproduccion; ?></h2>
		<h2>Item a producir: <?php echo $nombre; ?></h2>
	 
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
						<td><?php echo str_replace(".",",",$eficiencia ['eficiencia']) ; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</body>
</html>