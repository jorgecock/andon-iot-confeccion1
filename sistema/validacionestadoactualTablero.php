<?php
	//validacion de estado actual vs pagina cargada
	include "../conexion.php";
	$query = mysqli_query($conexion,"SELECT * FROM modulos WHERE idmodulo=$mod");
	mysqli_close($conexion);
	$result = mysqli_num_rows($query);
	if($result>0){
		$data=mysqli_fetch_array($query);
		$estado=$data['estado'];
		
		if (($estado==1 OR $estado==2) AND $estadopagina<>1){
			header("location: programarTablero.php");
		} elseif ($estado==3 AND $estadopagina<>3){
			header("location: conteoTablero.php");
		} elseif ($estado==4 AND $estadopagina<>4){
			header("location: pausaTablero.php");
		} elseif ($estado==5 AND $estadopagina<>5){
				header("location: errorTablero.php");
		} elseif ($estado==6 AND $estadopagina<>6){
				header("location: reportefinalTablero.php");
		} 		
	} else {
		echo "Número de modulo invalido";
	} 
?>