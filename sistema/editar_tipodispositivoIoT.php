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
		if (empty($_POST['tipodispositivoIoT'])) 
		{
			$alert='<p class="msg_error">El campo Tipo Dispositivo es necesarios</p>';
		}else{
			
			$idtipodispositivoIoT=$_POST['idtipodispositivoIoT'];
			$tipodispositivoIoT=$_POST['tipodispositivoIoT'];
			$descripcion=$_POST['descripcion'];
			$fecha=date('y-m-d H:i:s');
			$sql_update = mysqli_query($conexion,"UPDATE tiposdispositivosiot SET tipodispositivoIoT='$tipodispositivoIoT', descripcion='$descripcion', updated_at='$fecha' WHERE idtipodispositivoIoT=$idtipodispositivoIoT");
				

			if($sql_update){
				header('location: lista_tiposdispositivosIoT.php');
			}else{
				$alert='<p class="msg_error">Error al actualizar el dispositivo IoT</p>';
			}
		}
	}


	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id'])){
		mysqli_close($conexion);
		header('location: lista_tiposdispositivosIoT.php');
	}
	$idtipodispositivoIoT=$_REQUEST['id'];
	$sql=mysqli_query($conexion,"SELECT * FROM tiposdispositivosIoT WHERE (idtipodispositivoiot=$idtipodispositivoIoT AND status=1)");
	mysqli_close($conexion);
	$result_sql=mysqli_num_rows($sql);
	if ($result_sql==0){
		header('location: lista_tiposdispositivosIoT.php'); 
	}else{
		while ($data=mysqli_fetch_array($sql)) {
			$tipodispositivoIoT=$data['tipodispositivoIoT'];
			$descripcion=$data['descripcion'];
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php  include "includes/scripts.php"; ?> 
	<title>Actualizar Tipo de Dispositivo IoT</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Tipo de Dispositivo IoT</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for='idtipodispositivoIoT'>Id Tipo de Dispositivo IoT: <?php echo $idtipodispositivoIoT; ?></label>
				<input type="hidden" name="idtipodispositivoIoT" value="<?php echo $idtipodispositivoIoT; ?>">
				<label for='tipodispositivoIoT'>Tipo de Dispositivo IoT</label>
				<input type="text" name="tipodispositivoIoT" id="tipodispositivoIoT" placeholder="Tipo de dispositivo IoT" value="<?php echo $tipodispositivoIoT; ?>">
				<label for='descripcion'>Descripción</label>
				<input type="text" name="descripcion" id="descripcion" placeholder="Descripción" value="<?php echo $descripcion; ?>">
				<br>
				<input type="submit" value="Actualizar Tipo de Dispositivo IoT" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_tiposdispositivosIoT.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>