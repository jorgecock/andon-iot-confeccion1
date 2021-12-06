 <?php
	//Registro  Dispositivo IoT

	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	$idempresa=$_SESSION['idempresa'];

	if (!empty($_POST)) 
	{
		$alert='';
		$modulo=$_POST['modulo'];
		$tipodispositivoIoT=$_POST['tipodispositivoIoT'];
		$firmware=$_POST['firmware'];
		$idusuario=$_SESSION['idUser'];


		if (empty($_POST['modulo']) || empty($_POST['tipodispositivoIoT']) || empty($_POST['firmware'])) 
		{
			$alert='<p class="msg_error">Los campos: Modulo, Tipo de dispositivo y Firmwares son obligatorios</p>';
		}else{
			include "../conexion.php";
			$query= mysqli_query($conexion,"SELECT * FROM dispositivosiot WHERE modulo='$modulo' AND status=1");
			$result=mysqli_fetch_array($query);

			if ($result>0){
				$alert='<p class="msg_error">El módulo ya tiene asociado un dispositivo IoT.</p>';
			}else{
				$query_insert = mysqli_query($conexion,"INSERT INTO dispositivosiot (modulo, tipodispositivoIoT, firmware, idusuario)
					VALUES ($modulo, $tipodispositivoIoT,'$firmware',$idusuario)");
				if($query_insert){
					//$alert='<p class="msg_save">Usuario creado Correctamente</p>';
					mysqli_close($conexion);
					header('location: lista_dispositivosIoT.php');
				}else{
					$alert='<p class="msg_error">Error al crear el Dispositivo IoT</p>';
				}
			}
			mysqli_close($conexion);
		}
	}else{
		$tipodispositivoIoT='';
		$modulo='';
		$firmware='';
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Registro de Dispositivo IoT</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Registro de Dispositivo IoT</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for='modulo'>Módulo</label>
		
				<?php
					include "../conexion.php";
					$query_tipo = mysqli_query($conexion,"SELECT * FROM modulos WHERE idempresa=$idempresa");
					mysqli_close($conexion);
					$result_tipo = mysqli_num_rows($query_tipo);
				?>
				<select name="modulo" id="modulo">
					<?php 
						if($result_tipo>0){
							while ($tipoa= mysqli_fetch_array($query_tipo)) { ?>
								<option value="<?php echo $tipoa["idmodulo"]; ?>"
									<?php if($modulo==$tipoa["idmodulo"]){echo " selected";} ?>>	
									<?php echo $tipoa["nombremodulo"]; ?>
								</option><?php
							}
						}
					?>				
				</select>


				<label for="firmware">Firmware</label>
				<input type="text" name="firmware" id="firmware" placeholder="Firmware" value="<?php echo $firmware; ?>">
				
				<label for="tipodispositivoIoT">Tipo de Dispositivo IoT</label>




				<!--  select para escogir el tipo de dispositivo -->
				<?php
					include "../conexion.php";
					$query_tipo = mysqli_query($conexion,"SELECT * FROM tiposdispositivosiot");
					mysqli_close($conexion);
					$result_tipo = mysqli_num_rows($query_tipo);
				?>
				<select name="tipodispositivoIoT" id="tipodispositivoIoT">
					<?php 
						if($result_tipo>0){
							while ($tipoa= mysqli_fetch_array($query_tipo)) { ?>
								<option value="<?php echo $tipoa["idtipodispositivoiot"]; ?>"
									<?php if($tipodispositivoIoT==$tipoa["idtipodispositivoiot"]){echo " selected";} ?>>	
									<?php echo $tipoa["tipodispositivoIoT"]; ?>
								</option><?php
							}
						}
					?>				
				</select>





				<br>
				<input type="submit" value="Crear Dispositivo IoT" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_dispositivosIoT.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>