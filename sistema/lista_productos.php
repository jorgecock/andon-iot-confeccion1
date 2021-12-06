<?php
	session_start(); 
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	include "includes/scripts.php";
	$idempresa=$_SESSION['idempresa'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Productos</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<h1>Productos</h1>
		<a href="registro_producto.php" class="btn_new">Crear Producto</a>
		
		<form action="buscar_producto.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Referencia</th>
				<th>Descripción</th>
				<th>Usuario creador</th>
				<th>Fecha Creación</th>
				<th>Acciones</th>
			</tr>

			<?php
				//paginador
				include "../conexion.php";
				$sql_register=mysqli_query($conexion,"
					SELECT COUNT(*) as total_registro 
					FROM producto 
					WHERE status=1 AND idempresa=$idempresa");
				include "calculonumpaginas.php";

				//Crear lista
				$query = mysqli_query($conexion,"
					SELECT u.idproducto, u.nombre AS 'nombreproducto', u.referencia, u.descripcion, r.nombre AS 'nombreusuario', u.created_at
					FROM producto u 
					INNER JOIN usuario r ON u.usuario_id = r.idusuario
					WHERE u.status=1 AND u.idempresa='$idempresa'
					ORDER BY u.idproducto ASC 
					LIMIT $desde,$por_pagina");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);
				
				//Desplegar lista
				if($result>0){
					while ($data=mysqli_fetch_array($query)) {
						?>
							<tr class="row<?php echo $data['idproducto']; ?>">
								<td><?php echo $data['idproducto']; ?></td>
								<td><?php echo $data['nombreproducto']; ?></td>
								<td><?php echo $data['referencia']; ?></td>
								<td><?php echo $data['descripcion']; ?></td>
								<td><?php echo $data['nombreusuario']; ?></td>
								<td><?php echo $data['created_at']; ?></td>
								
								<?php 
									//verificacion de usuario
									if($_SESSION['rol']==1 || $_SESSION['rol']==2 || $_SESSION['rol']==4 || $_SESSION['rol']==5 || $_SESSION['rol']==6){ ?>
								<td>
									<a class="link_edit" href="editar_producto.php?id=<?php echo $data['idproducto']; ?>"><i class="far fa-edit"></i> Editar</a>
									|  
									<a class="link_delete del_product" href="eliminar_confirmar_producto.php?id=<?php echo $data['idproducto']; ?>"><i class="far fa-trash-alt"></i> Eliminar</a>
								</td>
								<?php } ?>
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