<?php 
	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	//mostrar datos a enviar por post
	if(!empty($_POST)){
		
		//seguridad
		if(empty($_POST['id'])){
			header('location: lista_productos.php');
		}
		
		$id=$_POST['id'];
		
		//Query para borrar cambiando el estatus a 0
		include "../conexion.php";
		$fecha=date('y-m-d H:i:s');
		$query_delete=mysqli_query($conexion,"UPDATE producto SET status=0, deleted_at ='$fecha' WHERE idproducto='$id'");
		mysqli_close($conexion);

		if($query_delete){
			header('location: lista_productos.php');
		}else{
			echo "Error al eliminar";
		}
	}



	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id'])){
		header('location: lista_productos.php');
	}else{
		$id=$_REQUEST['id'];
		include "../conexion.php";
		$query=mysqli_query($conexion,"SELECT nombre, referencia FROM producto WHERE idproducto=$id");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if ($result>0){
			while ($data=mysqli_fetch_array($query)) {
				$nombre=$data['nombre'];
				$referencia=$data['referencia'];
			}
		}else{
			header('location: lista_productos.php'); 
		}
	}
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Eliminar Producto</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<div class="data_delete">
			<h2>Está seguro de eliminar el registro:</h2>
			<p>Nombre: <span><?php echo $nombre; ?></span></p>
			<p>Referencia: <span><?php echo $referencia; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<a href="lista_productos.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Aceptar" class="btn_ok"> 
			</form>	
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>