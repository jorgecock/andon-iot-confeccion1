<?php
	//Registro Modulo
	
	//Validar usuario con acceso a este módulo
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}

	include "includes/scripts.php";
	
	//Validar datos recibidos de forma por Post.

	$alert='';
	$nombre='';
	$descripcion='';
	

	if (!empty($_POST)) 
	{
		if (empty($_POST['nombre']) || empty($_POST['descripcion']))
		{
			$alert='<p class="msg_error">Los campos Nombre y descripción son obligatorios</p>';
		}else{
			$nombre=$_POST['nombre'];
			$descripcion=$_POST['descripcion'];
			$idempresa=$_SESSION['idempresa'];

			include "../conexion.php";
			$query= mysqli_query($conexion,"SELECT * FROM modulos WHERE ((nombremodulo='$nombre') AND status=1 AND idempresa='$idempresa')");
			$result=mysqli_fetch_array($query);
			if ($result>0){
				$alert='<p class="msg_error">El nombre del Módulo ya existe</p>';
			}else{
				$query_insert = mysqli_query($conexion,"INSERT INTO modulos(nombremodulo,descripcion, idempresa)
					VALUES ('$nombre', '$descripcion', '$idempresa')");

				if($query_insert){
					//$alert='<p class="msg_save">Usuario creado Correctamente</p>';
					mysqli_close($conexion);
					header('location: lista_modulos.php');
				}else{
					$alert='<p class="msg_error">Error al crear el módulo</p>';
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
	<title>Registro de Módulo</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Registro de Módulo</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				
				<!-- Nombre -->	
				<label for='nombre'>Nombre del Módulo</label>
				<input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>">
				
				<!-- Descripción -->	
				<label for='descripcion'>Descripción del módulo</label>
				<input type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion; ?>">
				
				<br>

				<!-- Botones de crear y cancelar -->
				<input type="submit" value="Crear Módulo" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_modulos.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>