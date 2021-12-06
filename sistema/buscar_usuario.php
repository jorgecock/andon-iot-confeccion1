<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	include "../conexion.php";
	$idempresa=$_SESSION['idempresa'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php  include "includes/scripts.php"; ?>
	<title>Usuarios</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<?php 
			$busqueda=strtolower($_REQUEST['busqueda']);
			if(empty($busqueda)){
				header("location: lista_usuarios.php");
				mysqli_close($conexion);
			}
		 ?>

		<h1>Usuarios</h1>
		<a href="registro_usuario.php" class="btn_new">Crear usuario</a>
		
		
		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Correo</th>
				<th>Usuario</th>
				<th>Rol</th>
				<th>Acciones</th>
			</tr>

			<?php
				//paginador
				$sql_register=mysqli_query($conexion,"
					SELECT COUNT(*) as total_registro 
					FROM usuario u 
					INNER JOIN rol r ON u.rol=r.idrol 
					WHERE (
						u.idusuario LIKE '%$busqueda%' OR 
						u.nombre LIKE '%$busqueda%' OR 
						u.correo LIKE '%$busqueda%' OR 
						u.usuario LIKE '%$busqueda%' OR 
						r.rol LIKE '%$busqueda%'
					) AND u.status=1  AND u.idempresa=$idempresa");
				include "calculonumpaginas.php";


				//Crear lista
				$query = mysqli_query($conexion,"
					SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol 
					FROM usuario u 
					INNER JOIN rol r ON u.rol = r.idrol 
					WHERE (
						u.idusuario LIKE '%$busqueda%' OR 
						u.nombre LIKE '%$busqueda%' OR 
						u.correo LIKE '%$busqueda%' OR 
						u.usuario LIKE '%$busqueda%' OR 
						r.rol LIKE '%$busqueda%'
						) AND u.status=1  AND u.idempresa=$idempresa
					ORDER BY u.idusuario ASC 
					LIMIT $desde,$por_pagina");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);
				if($result>0){
					while ($data=mysqli_fetch_array($query)) {
						?>
							<tr>
								<td><?php echo $data['idusuario']; ?></td>
								<td><?php echo $data['nombre']; ?></td>
								<td><?php echo $data['correo']; ?></td>
								<td><?php echo $data['usuario']; ?></td>
								<td><?php echo $data['rol']; ?></td>
								<td>
									<a class="link_edit" href="editar_usuario.php?id=<?php echo $data['idusuario']; ?>">Editar</a>
									
									<?php if($data['idusuario']!=1){ ?>
										|  <a class="link_delete" href="eliminar_confirmar_usuario.php?id=<?php echo $data['idusuario']; ?>">Eliminar</a>
										<?php
										} 
									?>
								</td>
							</tr>
						<?php
					}
				}
			?>
		</table>
		<?php include "paginador.php"; ?>	
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>