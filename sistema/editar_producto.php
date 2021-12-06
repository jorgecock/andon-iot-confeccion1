<?php
	//Registro producto

	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	//validar desde post luego de dar submit
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nombre']) || empty($_POST['referencia']) || empty($_POST['id']) ) 
		{
			$alert='<p class="msg_error">Los campos, Nombre y Referencia, son obligatorios</p>';
		}else{

			$idproducto=$_POST['id'];
			$nombre=$_POST['nombre'];
			$referencia=$_POST['referencia'];
			$descripcion=$_POST['descripcion'];
			$usuario_id=$_SESSION['idUser'];
			$fecha=date('y-m-d H:i:s');

			include "../conexion.php";
			$query_update= mysqli_query($conexion,"UPDATE producto 
								SET nombre='$nombre', referencia='$referencia', descripcion='$descripcion', updated_at='$fecha'
								WHERE idproducto=$idproducto");
			mysqli_close($conexion);
			if($query_update){
				header('location: lista_productos.php');

				//echo $idproducto;
 			}else{
					$alert='<p class="msg_error">Error al editar el producto</p>';
			}
		}
	}



	//Validar producto GET desde lista
	if (empty($_REQUEST['id'])){
		header('location: lista_productos.php');
	}else{
		$idproducto=$_REQUEST['id'];
		if(!is_numeric($idproducto)){
			header('location: lista_productos.php');
		}
		include "../conexion.php";
		$query_producto=mysqli_query($conexion,"SELECT * FROM producto WHERE (idproducto=$idproducto AND status=1)");// 
		mysqli_close($conexion);
		$result=mysqli_num_rows($query_producto);
		if($result>0){
			while ($data=mysqli_fetch_array($query_producto)) {
				$nombre=$data['nombre'];
				$descripcion=$data['descripcion'];
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
	<title>Actualizar Productos</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Producto</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?php echo $idproducto; ?>">
				<label for='nombre'>Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre del Producto" value="<?php echo $nombre; ?>">
				<label for='referencia'>Referencia</label>
				<input type="text" name="referencia" id="referencia" placeholder="Referencia" value="<?php echo $referencia; ?>">
				<label for="descripcion">Descripción</label>
				<input type="text" name="descripcion" id="descripcion" placeholder="Descripción" value="<?php echo $descripcion; ?>">
				<br>
				<button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Actualizar Producto</button>
				<br>
				<a class="btn_cancel" href="lista_productos.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>