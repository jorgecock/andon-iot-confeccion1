<?php
	//validacion de estado actual vs pagina cargada
	include "../conexion.php";
	$query = mysqli_query($conexion,"SELECT * FROM modulos WHERE idmodulo=$mod");
	mysqli_close($conexion);
	$result = mysqli_num_rows($query);
	if($result>0){
		$data=mysqli_fetch_array($query);
		$estado=$data['estado'];
		if ($estado<>$estadopagina){
			if ($estado==1){
				header("location: programar.php");
			} elseif ($estado==2){
				header("location: validacion.php");
			} elseif ($estado==3){
				header("location: tablero.php");  //conteo.php
			} elseif ($estado==4){
				header("location: pausa.php");
			} elseif ($estado==5){
				header("location: error.php");
			} elseif ($estado==6){
				header("location: reportefinal.php");
			} 
		}
	} else {
		echo "numero de modulo invalido";
	} 
?>