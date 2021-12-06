<?php 
	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	
	//mostrar datos a enviar por post
	if(!empty($_POST)){
		
		//seguridad
		if(empty($_POST['idordenproduccion'])){
			header('location: lista_ordenes_produccion.php');
		}

		$idordenproduccion=$_POST['idordenproduccion'];
		
		//$query_delete=mysqli_query($conexion,"DELETE FROM usuario WHERE idusuario=$idusuario");
		include "../conexion.php";
		$query_delete=mysqli_query($conexion,"UPDATE ordenesproduccion SET status=0 WHERE idordenproduccion=$idordenproduccion");
		mysqli_close($conexion);

		if($query_delete){
			header('location: lista_ordenes_produccion.php');
		}else{
			echo "Error al eliminar";
		}
	}



	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id'])){
		header('location: lista_ordenes_produccion.php');
	}else{
		
		$idordenproduccion=$_REQUEST['id'];
		include "../conexion.php";
		$query=mysqli_query($conexion,"SELECT * FROM ordenesproduccion WHERE idordenproduccion=$idordenproduccion");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if ($result>0){
			while ($data=mysqli_fetch_array($query)) {
				$numeroordenproduccion=$data['numeroordenproduccion'];
				$descripcion=$data['descripcion'];
			}
		}else{
			header('location: lista_ordenes_produccion.php'); 
		}
	}
	
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Eliminar Orden de Producción</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<div class="data_delete">
			<h2>Está seguro de eliminar la orden de producción</h2>
			<p>Orden número: <span><?php echo $numeroordenproduccion; ?></span></p>
			<p>Descripción: <span><?php echo $descripcion; ?></span></p>
			
			<form method="post" action="">
				<input type="hidden" name="idordenproduccion" value="<?php echo $idordenproduccion; ?>">
				<a href="lista_ordenes_produccion.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Aceptar" class="btn_ok"> 
			</form>	
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>