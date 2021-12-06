<?php
	//Registro producto

	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	$idempresa=$_SESSION['idempresa'];
	

	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nombre']) || empty($_POST['referencia'])) 
		{
			$alert='<p class="msg_error">Los campos, Nombre y Referencia son obligatorios</p>';
		}else{
			$nombre=$_POST['nombre'];
			$referencia=$_POST['referencia'];
			$descripcion=$_POST['descripcion'];
			$usuario_id=$_SESSION['idUser'];

			
			include "../conexion.php";
			$query= mysqli_query($conexion,"SELECT * FROM producto WHERE ((referencia='$referencia' OR nombre='$nombre') AND status=1 AND idempresa=$idempresa)");
			$result=mysqli_fetch_array($query);	
			if ($result>0){
				$alert='<p class="msg_error">El nombre del producto o la referencia ya existen</p>';
			}else{
				$query_insert = mysqli_query($conexion,"INSERT INTO producto (nombre, referencia, descripcion, usuario_id, idempresa) VALUES ('$nombre','$referencia','$descripcion', $usuario_id, $idempresa)");
				if($query_insert){
					mysqli_close($conexion);
					header('location: lista_productos.php');
 				}else{
					$alert='<p class="msg_error">Error al crear el producto</p>';
				}
			}
			mysqli_close($conexion);
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Registro de Productos</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Registro de Productos</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post" enctype="multipart/form-data">
				<label for='nombre'>Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre del Producto">
				<label for='referencia'>Referencia</label>
				<input type="text" name="referencia" id="referencia" placeholder="Referencia">
				<label for="descripcion">Descripción</label>
				<input type="text" name="descripcion" id="descripcion" placeholder="Descripción">
				<br>
				<button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Crear Producto</button>
				<br>
				<a class="btn_cancel" href="lista_productos.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>