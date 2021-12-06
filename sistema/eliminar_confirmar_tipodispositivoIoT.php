<?php 
	include "includes/scripts.php";
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
	//mostrar datos a enviar por post
	if(!empty($_POST)){
		
		//seguridad
		if(empty($_POST['idtipodispositivoIoT'])){
			header('location: lista_tiposdispositivosIoT.php');
		}
		
		$idtipodispositivoIoT=$_POST['idtipodispositivoIoT'];
		
		//Query para borrar cambiando el estatus a 0
		include "../conexion.php";
		$fecha=date('y-m-d H:i:s');
		$query_delete=mysqli_query($conexion,"UPDATE  tiposdispositivosiot SET status=0, deleted_at ='$fecha' WHERE idtipodispositivoiot='$idtipodispositivoIoT'");
		mysqli_close($conexion);

		if($query_delete){
			header('location: lista_tiposdispositivosIoT.php');
		}else{
			echo "Error al eliminar";
		}
	}


	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id'])){
		header('location: lista_tiposdispositivosIoT.php');
	}else{
		
		$idtipodispositivoIoT=$_REQUEST['id'];
		include "../conexion.php";
		$query=mysqli_query($conexion,"SELECT * FROM tiposdispositivosiot  WHERE idtipodispositivoiot='$idtipodispositivoIoT'");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if ($result>0){
			while ($data=mysqli_fetch_array($query)) {
				$tipodispositivoIoT=$data['tipodispositivoIoT'];
				$descripcion=$data['descripcion'];
			}
		}else{
			header('location: lista_tiposdispositivosIoT.php');
		}
	}	
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Eliminar Tipo de Dispositivo IoT</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<div class="data_delete">
			<h2>Está seguro de eliminar el registro:</h2>
			<p>Tipo de dispositivo IoT: <span><?php echo $tipodispositivoIoT; ?></span></p>
			<p>Descripcion: <span><?php echo $descripcion; ?></span></p>
			
			<form method="post" action="">
				<input type="hidden" name="idtipodispositivoIoT" value="<?php echo $idtipodispositivoIoT; ?>">
				<a href="lista_tiposdispositivosIoT.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Aceptar" class="btn_ok"> 
			</form>	
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>