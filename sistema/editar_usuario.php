<?php
	session_start();
	if($_SESSION['rol']!=1 AND $_SESSION['rol']!=6){
		header("location: ./");
	}
	$idempresa=$_SESSION['idempresa'];
	/* Validar envio por Post */
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])) 
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios</p>';
		}else{
			$idusuario=$_POST['id'];/* mmmm  verificar*/
			$nombre=$_POST['nombre'];
			$email=$_POST['correo'];
			$usuario=$_POST['usuario'];
			$clave=md5($_POST['clave']);
			$rol=$_POST['rol'];
			$fecha=date('y-m-d H:i:s');

			include "../conexion.php";
			$query= mysqli_query($conexion,"SELECT * FROM usuario 
											WHERE ((usuario='$usuario' OR correo='$email') AND idusuario!=$idusuario)");
			$result=mysqli_fetch_array($query);
			$result=mysqli_num_rows($query);
			if ($result>0){
				$alert='<p class="msg_error">El usuario o el correo ya existe</p>';
			}else{
				if(empty($_POST['clave'])){
					$sql_update = mysqli_query($conexion,"UPDATE usuario SET nombre='$nombre', correo='$email', usuario='$usuario', rol='$rol', updated_at='$fecha' WHERE idUsuario='$idusuario' ");
				}else{
					$sql_update = mysqli_query($conexion,"UPDATE usuario SET nombre='$nombre', correo='$email', usuario='$usuario', clave='$clave', rol='$rol', updated_at='$fecha' WHERE idUsuario='$idusuario' ");
				}

				if($sql_update){
					//$alert='<p class="msg_save">Usuario Actualizado Correctamente</p>';
					mysqli_close($conexion);
					header('location: lista_usuarios.php');
				}else{
					$alert='<p class="msg_error">Error al actualizar el usuario</p>';

				}
			}
			mysqli_close($conexion);
		}	
	}

	//Mostrar Datos Recibidos de Get
	if (empty($_REQUEST['id'])){
		header('location: lista_usuarios.php');	
	}
	$idusuario=$_REQUEST['id'];
	include "../conexion.php";
	$sql=mysqli_query($conexion,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.rol as idrol, r.rol as rol FROM usuario u INNER JOIN rol r on u.rol= r.idrol WHERE (u.idusuario=$idusuario AND u.status=1)");
	mysqli_close($conexion);
	$result_sql=mysqli_num_rows($sql);
	if ($result_sql==0){
		header('location: lista_usuarios.php'); 
	}else{
		while ($data=mysqli_fetch_array($sql)) {
			$idusuario=$data['idusuario'];
			$nombre=$data['nombre'];
			$correo=$data['correo'];
			$usuario=$data['usuario'];
			$rol=$data['rol'];
			$idrol=$data['idrol'];
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php  include "includes/scripts.php"; ?> 
	<title>Actualizar Usuario</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-edit"></i> Editar Usuario</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $idusuario; ?>">
				<label for='nombre'>Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre; ?>">
				<label for='correo'>Correo Electrónico</label>
				<input type="email" name="correo" id="correo" placeholder="Correo Electrónico" value="<?php echo $correo; ?>">
				<label for="usuario">Usuario</label>
				<input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
				<label for="clave">Clave</label>
				<input type="password" name="clave" id="clave" placeholder="Clave de Acceso" >
				<label for="rol">Tipo de usuario</label>

				<?php
					include "../conexion.php";
					$query_rol = mysqli_query($conexion,"SELECT * FROM rol");
					mysqli_close($conexion);
					$result_rol = mysqli_num_rows($query_rol);
				?>

				<select name="rol" id="rol" class="notItemOne">
					<?php 
						if($result_rol>0){
							for ($i=0;$i<$result_rol-1;$i++){
								$rol_a= mysqli_fetch_array($query_rol); ?>
								<option value="<?php echo $rol_a["idrol"]; ?>"      
									<?php if($rol==$rol_a["idrol"]){echo " selected";} ?>>
									<?php echo $rol_a["rol"]; ?>
								</option><?php
							}
						}
					?>
					
				</select>
				<br>
				<button type="submit" class="btn_save"><i class="fas fa-edit"></i> Editar Usuario</button>

				<br>
				<a class="btn_cancel" href="lista_usuarios.php"><i class="fas fa-ban"></i> Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>