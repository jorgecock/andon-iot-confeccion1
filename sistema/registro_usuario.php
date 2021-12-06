<?php
 	//Registro Usuario

 	include "includes/scripts.php";
 	
	session_start();
	if($_SESSION['rol']!=1 AND $_SESSION['rol']!=6){
		header("location: ./");
	}
	$idempresa=$_SESSION['idempresa'];
	
	if (!empty($_POST)) 
	{
		$alert='';
		$nombre=$_POST['nombre'];
		$email=$_POST['correo'];
		$usuario=$_POST['usuario'];
		$clave=md5($_POST['clave']);
		$rol=$_POST['rol'];
		$fecha=date('y-m-d H:i:s');

		if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol'])) 
			{
				$alert='<p class="msg_error">Todos los campos son obligatorios</p>';
			}else{
				include "../conexion.php";
				$query= mysqli_query($conexion,"SELECT * FROM usuario WHERE ((usuario='$usuario' OR correo='$email') AND status=1 AND idempresa=$idempresa)");
				$result=mysqli_fetch_array($query);
				if ($result>0){
					$alert='<p class="msg_error">El usuario o el correo ya existen</p>';
				}else{
					$query_insert = mysqli_query($conexion,"INSERT INTO usuario(nombre,correo,usuario,clave,rol,created_at,idempresa)
						VALUES ('$nombre','$email','$usuario','$clave','$rol','$fecha','$idempresa')");
					if($query_insert){
						//$alert='<p class="msg_save">Usuario creado Correctamente</p>';
						mysqli_close($conexion);
						header('location: lista_usuarios.php');
					}else{
						$alert='<p class="msg_error">Error al crear el usuario</p>';
					}
				}
				mysqli_close($conexion);
			}
	}else{
		$nombre='';
		$email='';
		$usuario='';
		$rol=1;
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Registro de Usuarios</title>
</head>

<body>
	<?php  include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-user-plus"></i> Registro de usuarios</h1>
			<hr>
			<div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for='nombre'>Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre; ?>">
				<label for='correo'>Correo Electrónico</label>
				<input type="email" name="correo" id="correo" placeholder="Correo Electrónico" value="<?php echo $email; ?>">
				<label for="usuario">Usuario</label>
				<input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
				<label for="clave">Clave</label>
				<input type="password" name="clave" id="clave" placeholder="Clave de Acceso">
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
				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Crear Usuario</button>
				<br>
				<a class="btn_cancel" href="lista_usuarios.php">Cancelar</a>
			</form>
		</div>
	</section>

	<?php  include "includes/footer.php"; ?>
</body>
</html>