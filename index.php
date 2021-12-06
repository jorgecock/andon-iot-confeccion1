
<?php

	$alert ='';
	session_start();
	if(!empty($_SESSION['active']))
	{
		header('location: sistema/'); 

	} else {   

		if(!empty($_POST))
		{
			if(empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['empresa']))
			{
				$alert='Ingrese usuario, clave y nombre de empresa correctos';
			} else {
				
				require_once "conexion.php";
				$user = mysqli_real_escape_string ($conexion, $_POST['usuario']);
				$pass = md5(mysqli_real_escape_string ($conexion, $_POST['clave']));
				$empresa = mysqli_real_escape_string ($conexion, $_POST['empresa']);
				$query = mysqli_query($conexion, "SELECT u.*, s.nombreempresa, t.rol AS nombrerol 
					FROM usuario u  
					INNER JOIN empresas s ON u.idempresa=s.idempresa
					INNER JOIN rol t ON u.rol=t.idrol
					WHERE usuario = '$user' AND clave='$pass' AND nombreempresa='$empresa' ");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result>0){
					$data=mysqli_fetch_array($query);
				
					$_SESSION['active']= true;
					$_SESSION['idUser']= $data['idusuario'];
					$_SESSION['nombre']=$data['nombre'];
					$_SESSION['email']=$data['correo'];
					$_SESSION['user']=$data['usuario'];
					$_SESSION['rol']=$data['rol'];
					$_SESSION['nombrerol']=$data['nombrerol'];
					$_SESSION['nombreempresa']=$data['nombreempresa'];
					$_SESSION['idempresa']=$data['idempresa'];

					header('location: sistema/');
				}else{

					$alert = 'El usuario o la clave son incorrectas';
					session_destroy();
				}
			}
		}
	} 	
?>

	
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Login | Sistema Conteo e Indicadores </title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<section id="container">
		<form action="" method="post">
			<h3>Sistema<br>Andon-IoT-Confecciones</h3>
			<h3>Iniciar Sesion</h3>
			<img src="img/faro.jpg" width="100" height="100"alt="login">
			<h3>Ingrese Usuario, Contraseña y Nombre de la empresa afiliada.</h3>
			<input type="text" name="usuario" placeholder="Usuario">
			<input type="password" name="clave" placeholder="Contraseña">
			<input type="text" name="empresa" placeholder="Empresa">
			<div class="alert"><?php echo isset($alert)? $alert : ''; ?></div>
			<input type="submit" value="INGRESAR">
		</form>
	</section>

</body>
</html>
