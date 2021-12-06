<!-- Nombre modulo  -->
<?php
	include "../conexion.php";
	$query_tipo = mysqli_query($conexion,"
		SELECT nombremodulo 
		FROM modulos 
		WHERE idmodulo=$mod");
	mysqli_close($conexion);
	$result_tipo = mysqli_num_rows($query_tipo);
	if($result_tipo>0){
		$data= mysqli_fetch_array($query_tipo);
		$nombremodulo = $data['nombremodulo'];
	}else {
		echo ('error nombre modulo');					
	}
?>
<h1 align='center'>MODULO: <?php echo $nombremodulo; ?></h1>