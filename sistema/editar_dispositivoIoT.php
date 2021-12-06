<?php
	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	$idempresa=$_SESSION['idempresa'];
	
	/* Validar envio por Post */
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['iddispositivoIoT']) || empty($_POST['modulo']) || empty($_POST['firmware']) || empty($_POST['tipodispositivoIoT'])) 
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios</p>';
		}else{
			
			$iddispositivoIoT=$_POST['iddispositivoIoT'];/* mmmm  verificar*/
			$modulo=$_POST['modulo'];
			$firmware=$_POST['firmware'];
			$tipodispositivoIoT=$_POST['tipodispositivoIoT'];
			$fecha=date('y-m-d H:i:s');
			
			include "../conexion.php";
			$sql_update = mysqli_query($conexion,"UPDATE dispositivosiot SET modulo=$modulo, firmware='$firmware', tipodispositivoIoT=$tipodispositivoIoT, updated_at='$fecha' WHERE iddispositivoIoT='$iddispositivoIoT' ");
			if($sql_update){
				header('location: lista_dispositivosIoT.php');
			}else{
				$alert='<p class="msg_error">Error al actualizar el dispositivo IoT</p>';
			}
			mysqli_close($conexion);
		}
	}


	//Mostrar Datos Recibidos de Get
	if (empty($_GET['id'])){
		header('location: lista_dispositivosIoT.php');
	}
	$iddispositivoIoT=$_GET['id'];
	include "../conexion.php";
	$sql=mysqli_query($conexion,"SELECT * FROM dispositivosiot WHERE (iddispositivoIoT=$iddispositivoIoT AND status=1)");
	mysqli_close($conexion);
	$result_sql=mysqli_num_rows($sql);
	if ($result_sql==0){
		header('location: lista_dispositivosIoT.php'); 
	}else{
		while ($data=mysqli_fetch_array($sql)) {
			$modulo=$data['modulo'];
			$firmware=$data['firmware'];
			$tipodispositivoIoT=$data['tipodispositivoIoT'];
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Actualizar Dispositivo IoT</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Dispositivo IoT</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				
				<label for='iddispositivoIoT'>Id Dispositivo IoT: <?php echo $iddispositivoIoT?></label>
				<input type="hidden" name="iddispositivoIoT" id="iddispositivoIoT" placeholder="Id Dispositivo IoT" value="<?php echo $iddispositivoIoT; ?>"              >

				<label for='modulo'>Módulo</label>



				<?php
					include "../conexion.php";
					$query_tipo = mysqli_query($conexion,"SELECT * FROM modulos WHERE idempresa='$idempresa'");
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
				


				<label for='firmware'>Firmware</label>
				<input type="text" name="firmware" id="firmware" placeholder="Firmware" value="<?php echo $firmware; ?>">
			
				<label for="tipodispositivoIoT">Tipo de dispositivo IoT</label>
				<?php
					include "../conexion.php";
					$query_tiposdispositivosiot = mysqli_query($conexion,"SELECT * FROM tiposdispositivosiot");
					mysqli_close($conexion);
					$result_tiposdispositivosiot = mysqli_num_rows($query_tiposdispositivosiot);
				?>

				<select name="tipodispositivoIoT" id="tipodispositivoIoT" class="notItemOne">
					<?php 
						if($result_tiposdispositivosiot>0){
							while ($tiposdispositivosiot_a= mysqli_fetch_array($query_tiposdispositivosiot)) { ?>		
						
							<option value="<?php echo $tiposdispositivosiot_a['idtipodispositivoiot']; ?>"     
								
								<?php if($tipodispositivoIoT==$tiposdispositivosiot_a["tipodispositivoIoT"]){
									echo " selected";} ?>>
								<?php echo $tiposdispositivosiot_a["tipodispositivoIoT"];?>		
							</option><?php
							
							}
						}
					?>
					
				</select>
				<br>
				<input type="submit" value="Actualizar Dispositivo IoT" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_dispositivosIoT.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>