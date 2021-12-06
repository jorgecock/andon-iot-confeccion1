<?php
	//Registro Orden de produccion
	
	//Validar usuario con acceso a este módulo
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}

	include "includes/scripts.php";
	$idempresa=$_SESSION['idempresa'];
	
	//Validar datos recibidos de forma por Post.
	
	$alert='';
	$numeroorden='';
	$fechaprogramacion=date('Y-m-d');
	$descripcion='';

	if (!empty($_POST)) 
	{
		if (empty($_POST['numeroorden']) || empty($_POST['descripcion'])) 
		{
			$alert='<p class="msg_error">Los campos "Numero de Orden" y "Descripción" son obligatorios</p>';
		}else{
			$numeroorden=$_POST['numeroorden'];
			$fechaprogramacion=$_POST['fechaprogramacion'];
			$descripcion=$_POST['descripcion'];
			$usuario_id=$_SESSION['idUser'];

			include "../conexion.php";
			$query= mysqli_query($conexion,"SELECT * FROM ordenesproduccion WHERE (numeroordenproduccion='$numeroorden' AND status=1 AND idempresa='$idempresa')");
			$result=mysqli_fetch_array($query);
			if ($result>0){
				$alert='<p class="msg_error">La orden de producción ya fue creada</p>';
			}else{
				$query_insert = mysqli_query($conexion,"INSERT INTO ordenesproduccion (	numeroordenproduccion,fechaprogramacion,descripcion, usuario_id, idempresa)
					VALUES ($numeroorden, '$fechaprogramacion','$descripcion',$usuario_id, $idempresa)");
				if($query_insert){
					//$alert='<p class="msg_save">Orden de producción creada Correctamente</p>';
					mysqli_close($conexion);
					header('location: lista_ordenes_produccion.php');
				}else{
					$alert='<p class="msg_error">Error al crear la Orden de Producción</p>';
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
	<title>Registro de Orden de Producción</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Registro de Orden de Producción</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				
				<!-- Número de Orden de Producción -->
				<label for='numeroorden'>Número de Orden de Producción</label>
				<input type="text" name="numeroorden" id="numeroorden" value="<?php echo $numeroorden; ?>">
				
				<!-- Fecha de programación -->	
				<label for='fechaprogramacion'>Fecha de Programación</label>
				<input type="date" name="fechaprogramacion" id="fechaprogramacion" value="<?php echo($fechaprogramacion); ?>">

				<!-- Descripción -->	
				<label for="descripcion">Descripción</label>
				<input type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion; ?>">
				
				<!-- Botones de crear y cancelar -->
				<br>
				<input type="submit" value="Crear Orden de Producción" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_ordenes_produccion.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>