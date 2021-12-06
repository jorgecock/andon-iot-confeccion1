<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}

	include "includes/scripts.php";
	
	/* Validar envio por Post */
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['numeroordenproduccion']) || empty($_POST['descripcion'])) 
		{
			$alert='<p class="msg_error">Los campos "Numero de Orden" y "Descripción" son obligatorios</p>';
		}else{

			$idordenproduccion=$_POST['idordenproduccion'];
			$numeroordenproduccion=$_POST['numeroordenproduccion'];
			$fechaprogramacion=$_POST['fechaprogramacion'];
			$descripcion=$_POST['descripcion'];
			$usuario_id=$_SESSION['idUser'];
			$fecha=date('y-m-d H:i:s');

			include "../conexion.php";
			$sql_update = mysqli_query($conexion,"UPDATE ordenesproduccion SET numeroordenproduccion='$numeroordenproduccion', fechaprogramacion='$fechaprogramacion', descripcion='$descripcion', usuario_id='$usuario_id', updated_at='$fecha' WHERE idordenproduccion=$idordenproduccion ");
			mysqli_close($conexion);

			if($sql_update){
				header('location: lista_ordenes_produccion.php');
			}else{
				$alert='<p class="msg_error">Error al actualizar la orden de producción</p>';
			}
		}
	}


	//Mostrar Datos Recibidos de Get
	if (empty($_GET['id'])){
		header('location: lista_ordenes_produccion.php');
	}
	$idordenproduccion=$_GET['id'];
	include "../conexion.php";
	$sql=mysqli_query($conexion,"SELECT * FROM ordenesproduccion WHERE (idordenproduccion=$idordenproduccion AND status=1)");
	mysqli_close($conexion);
	$result_sql=mysqli_num_rows($sql);
	if ($result_sql==0){
		//header('location: lista_ordenes_produccion.php'); 
	}else{
		while ($data=mysqli_fetch_array($sql)) {
			$numeroordenproduccion=$data['numeroordenproduccion'];
			$fechaprogramacion=$data['fechaprogramacion'];
			$descripcion=$data['descripcion'];	
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Actualizar Orden de Producción</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Orden de Producción</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				
				<!-- ID Orden de Producción -->
				<!-- <label for='idordenproduccion'>Orden de Producción ID: <?php echo $idordenproduccion; ?></label> -->
				<input type="hidden" name="idordenproduccion" value="<?php echo $idordenproduccion; ?>">
				
				<!-- Número de Orden de Producción -->
				<label for='numeroordenproduccion'>Número de orden de produccion</label>
				<input type="text" name="numeroordenproduccion" id="numeroordenproduccion" placeholder="Número de orden de produccion" value="<?php echo $numeroordenproduccion; ?>">

				<!-- Fecha de programación -->	
				<label for='fechaprogramacion'>Fecha de Programación</label>
				<input type="date" name="fechaprogramacion" id="fechaprogramacion" value="<?php echo($fechaprogramacion); ?>">
				
				<!-- Descripción -->	
				<label for="descripcion">Descripción</label>
				<input type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion; ?>">

				<!-- Botones de crear y cancelar -->
				<br>
				<input type="submit" value="Actualizar Orden de Producción" class="btn_save">
				<br>
				<a class="btn_cancel" href="lista_ordenes_produccion.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>