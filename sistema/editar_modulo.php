<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	include "../conexion.php";
	
	/* Validar envio por Post */
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nombremodulo']) || empty($_POST['descripcion'])) 
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios</p>';
		}else{
			
			$idmodulo=$_POST['idmodulo'];/* mmmm  verificar*/
			$nombremodulo=$_POST['nombremodulo'];
			$descripcion=$_POST['descripcion'];
			$fecha=date('y-m-d H:i:s');
			$sql_update = mysqli_query($conexion,"UPDATE modulos SET nombremodulo='$nombremodulo', descripcion='$descripcion', updated_at='$fecha' WHERE idmodulo='$idmodulo' ");
				
			if($sql_update){
				header('location: lista_modulos.php');
			}else{
				$alert='<p class="msg_error">Error al actualizar el Módulo</p>';
			}
		}
	}


	//Mostrar Datos Recibidos de Get
	if (empty($_GET['id'])){
		header('location: lista_modulos.php');
	}
	$idmodulo=$_GET['id'];
	$sql=mysqli_query($conexion,"SELECT * FROM modulos WHERE (idmodulo=$idmodulo AND status=1)");
	$result_sql=mysqli_num_rows($sql);
	if ($result_sql==0){
		header('location: lista_modulos.php'); 
	}else{
		while ($data=mysqli_fetch_array($sql)) {
			$nombremodulo=$data['nombremodulo'];
			$descripcion=$data['descripcion'];
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php  include "includes/scripts.php"; ?> 
	<title>Actualizar Módulos</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Módulo</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for='idmodulo'> ************************************** <!-- Id Modulo: <?php echo $idmodulo; ?> -->   </label>
				<input type="hidden" name="idmodulo" value="<?php echo $idmodulo; ?>">
				
				<label for='nombremodulo'>Nombre del módulo</label>
				<input type="text" name="nombremodulo" id="nombremodulo" placeholder="Nombre del Módulo" value="<?php echo $nombremodulo; ?>">
				<label for="descripcion">Descripción</label>
				<input type="text" name="descripcion" id="descripcion" placeholder="Descripción" value="<?php echo $descripcion; ?>">
				<br>
				<input type="submit" value="Actualizar Módulo" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_modulos.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>