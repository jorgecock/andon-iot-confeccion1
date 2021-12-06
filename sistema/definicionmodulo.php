<?php
	//Definicion de modulo
	if (isset($_GET['mod'])){
		$_SESSION['mod']=$_GET['mod'];
	}
	if (!isset($_SESSION['mod'])){
		include "../conexion.php";
		$query=mysqli_query($conexion,"
			SELECT * FROM modulos WHERE status=1 ORDER BY idmodulo ASC ");
		mysqli_close($conexion);
		$result_tipo = mysqli_num_rows($query);
		if($result_tipo>0){
			$dato= mysqli_fetch_array($query);
			$mod=$dato['idmodulo']; 
			$_SESSION['mod']=$mod;
			echo ($mod);
		}else {
			header("location: sinmodulosproductosordenes.php");
		}
	} else {
		$mod=$_SESSION['mod'];
	}
?>