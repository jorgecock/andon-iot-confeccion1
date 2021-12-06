<?php 
	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	//mostrar datos a enviar por post
	if(!empty($_POST)){
		
		//seguridad
		if(empty($_POST['idmodulo'])){
			header('location: lista_modulos.php');
		}

		$idmodulo=$_POST['idmodulo'];
		$fecha=date('y-m-d H:i:s');
		include "../conexion.php";
		$query_delete=mysqli_query($conexion,"UPDATE modulos SET status=0, deleted_at='$fecha' WHERE idmodulo=$idmodulo");
		mysqli_close($conexion);
		if($query_delete){
			header('location: lista_modulos.php');
		}else{
			echo "Error al eliminar";
		}
	}


	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id'])){
		header('location: lista_modulos.php');
	}else{
		
		$idmodulo=$_REQUEST['id'];
		include "../conexion.php";
		$query=mysqli_query($conexion,"SELECT * FROM modulos WHERE idmodulo=$idmodulo");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if ($result>0){
			while ($data=mysqli_fetch_array($query)) {
				$nombremodulo=$data['nombremodulo'];
				$descripcion=$data['descripcion'];
			}

		}else{
			header('location: lista_modulos.php'); 
		}
	}
	
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Eliminar Módulo</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<div class="data_delete">
			<h2>Está seguro de eliminar el registro:</h2>
			<p>Modulo: <span><?php echo $nombremodulo; ?></span></p>
			<p>Descripcion: <span><?php echo $descripcion; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="idmodulo" value="<?php echo $idmodulo; ?>">
				<a href="lista_modulos.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Aceptar" class="btn_ok"> 
			</form>	
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>