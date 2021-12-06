<?php 
	include "includes/scripts.php";
	session_start();
	if($_SESSION['rol']!=1){
		header("location: ./");
	}
	
	
	//mostrar datos a enviar por post
	if(!empty($_POST)){
		
		//seguridad
		if($_POST['id']==1 || empty($_POST['id'])){
			header('location: lista_usuarios.php');
			//exit;
		}
		
		$id=$_POST['id'];

		//Query para borrar cambiando el estatus a 0
		include "../conexion.php";
		$fecha=date('y-m-d H:i:s');
		$query_delete=mysqli_query($conexion,"UPDATE usuario SET status=0, deleted_at ='$fecha' WHERE idusuario=$id");
		mysqli_close($conexion);

		if($query_delete){
			header('location: lista_usuarios.php');
		}else{
			echo "Error al eliminar";
		}
		

	}


	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id']) || $_REQUEST['id']==1){
		header('location: lista_usuarios.php');
	}else{
		$id=$_REQUEST['id'];
		include "../conexion.php";
		$query=mysqli_query($conexion,"SELECT u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r on u.rol= r.idrol WHERE u.idusuario='$id'");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if ($result>0){
			while ($data=mysqli_fetch_array($query)) {
				$nombre=$data['nombre'];
				$usuario=$data['usuario'];
				$rol=$data['rol'];
			}

		}else{
			header('location: lista_usuarios.php'); 
		}
	} 

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Eliminar Usuario</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<i class="fas fa-user-times fa-7x" style="color: #12a4c6"></i>
			<br><br>
			<h2>Está seguro de eliminar el registro:</h2>
			<p>Nombre: <span><?php echo $nombre; ?></span></p>
			<p>Usuario: <span><?php echo $usuario; ?></span></p>
			<p>Rol: <span><?php echo $rol; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<a href="lista_usuarios.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
				<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>
			</form>	
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>