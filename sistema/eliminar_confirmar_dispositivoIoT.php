<?php 
	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	

	//mostrar datos a enviar por post
	if(!empty($_POST)){
		
		//seguridad
		if(empty($_POST['Aceptar'])){
			header('location: lista_dispositivosIoT.php');
		}

		$iddispositivoiot=$_POST['iddispositivoiot'];
		
		$fecha=date('y-m-d H:i:s');
		include "../conexion.php";
		$query_delete=mysqli_query($conexion,"UPDATE dispositivosiot SET status=0 , deleted_at='$fecha' WHERE iddispositivoIoT=$iddispositivoiot");
		mysqli_close($conexion);

		if($query_delete){
			header('location: lista_dispositivosIoT.php');
		}else{
			echo "Error al eliminar";
		}
	}

	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id'])){
		header('location: lista_dispositivosIoT.php');
	}else{
		
		$iddispositivoiot=$_REQUEST['id'];
		include "../conexion.php";
		$query=mysqli_query($conexion,"SELECT u.modulo, u.firmware, r.tipodispositivoIoT FROM dispositivosiot u INNER JOIN tiposdispositivosiot r on u.tipodispositivoIoT= r.idtipodispositivoiot WHERE u.iddispositivoIoT=$iddispositivoiot");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if ($result>0){
			while ($data=mysqli_fetch_array($query)) {
				$modulo=$data['modulo'];
				$firmware=$data['firmware'];
				$tipodispositivoIoT=$data['tipodispositivoIoT'];
			}

		}else{
			header('location: lista_dispositivosIoT.php'); 
		}
	}
	
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Eliminar Dispositivo IoT</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<div class="data_delete">
			<h2>Está seguro de eliminar el Dispositivo IoT:</h2>
			<p>Id: <span><?php echo $iddispositivoiot; ?></span></p>
			<p>Módulo: <span><?php echo $modulo; ?></span></p>
			<p>Tipo: <span><?php echo $tipodispositivoIoT; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="iddispositivoiot" value="<?php echo $iddispositivoiot; ?>">
				<a href="lista_dispositivosIoT.php" class="btn_cancel">Cancelar</a>
				<input type="submit" name="Aceptar" value="Aceptar" class="btn_ok"> 
			</form>	
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>