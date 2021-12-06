 <?php
	//Registro TipodedispositivoIoT

	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	

	if (!empty($_POST)) 
	{
		$alert='';
		$tipodispositivoIoT=$_POST['tipodispositivoIoT'];
		$descripcion=$_POST['descripcion'];
		

		if (empty($_POST['tipodispositivoIoT'])) 
		{
			$alert='<p class="msg_error">El campo Tipo Dispositivo es obligatorio</p>';
		}else{
			include "../conexion.php";
			$query= mysqli_query($conexion,"SELECT * FROM tiposdispositivosiot WHERE (tipodispositivoIoT='$tipodispositivoIoT' AND status=1)");
			$result=mysqli_fetch_array($query);
			if ($result>0){
				$alert='<p class="msg_error">El tipo de dispositivo ya existe.</p>';
			}else{
				$query_insert = mysqli_query($conexion,"INSERT INTO tiposdispositivosiot (tipodispositivoIoT,descripcion)
					VALUES ('$tipodispositivoIoT','$descripcion')");
				if($query_insert){
					mysqli_close($conexion);
					header('location: lista_tiposdispositivosIoT.php');
				}else{
					$alert='<p class="msg_error">Error al crear tipo de modulo</p>';
				}
			}
			mysqli_close($conexion);
		}
	}else{
		$tipodispositivoIoT='';
		$descripcion='';
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Registro de Tipo de Dispositivo IoT</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Registro de Tipo de Dispositivo IoT</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for='tipodispositivoIoT'>Tipo de Dispositivo IoT</label>
				<input type="text" name="tipodispositivoIoT" id="tipodispositivoIoT" placeholder="Tipo de dispositivo IoT" value="<?php echo $tipodispositivoIoT; ?>">
				<label for='descripcion'>Descripción</label>
				<input type="text" name="descripcion" id="descripcion" placeholder="Descripcion" value="<?php echo $descripcion; ?>">
				<br>
				<input type="submit" value="Crear Tipo Dispositivo IoT" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_tiposdispositivosIoT.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>