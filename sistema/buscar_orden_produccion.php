<?php
	//Lista Orden de produccion
	
	//Validar usuario con acceso a este módulo
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
	<title>Ordenes de Producción</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<?php 
			$busqueda=strtolower($_REQUEST['busqueda']);
			if(empty($busqueda)){
				header("location: lista_ordenes_produccion.php");
				mysqli_close($conexion);
			}
		 ?>

		<h1>Ordenes de Producción</h1>
		<a href="registro_orden_produccion.php" class="btn_new">Crear Orden de Producción</a>
		
		
		<form action="buscar_orden_produccion.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>Número de orden</th>				
				<th>Fecha de Creación</th>
				<th>Fecha Programación</th>
				<th>Descripción</th>
				<!-- <th>Fecha Cierre</th>
				<th>Estado </th> -->
				<th>Usuario Creador </th>
				<th>Acciones</th>
			</tr>

			<?php
				//paginador
				include "../conexion.php";
				$sql_register=mysqli_query($conexion,"
					SELECT COUNT(*) as total_registro 
					FROM ordenesproduccion u 
					INNER JOIN estadosordenproduccion r ON u.idestadoordenproduccion = r.idestadoordenproduccion
					INNER JOIN usuario m ON u.usuario_id = m.idusuario
					WHERE (
						m.nombre LIKE '%$busqueda%' OR 
						u.numeroordenproduccion LIKE '%$busqueda%' OR 
						u.fechaprogramacion LIKE '%$busqueda%' OR 
						u.created_at LIKE '%$busqueda%' OR 
						u.descripcion LIKE '%$busqueda%' OR
						u.fechacierre LIKE '%$busqueda%' OR
						r.estado LIKE '%$busqueda%'
					) AND u.status=1 AND u.idempresa='$idempresa'");
				include "calculonumpaginas.php";

				//Crear lista
				$query = mysqli_query($conexion,"
					SELECT u.idordenproduccion, u.numeroordenproduccion, u.created_at, u.fechaprogramacion, u.descripcion, u.fechacierre, r.estado, m.nombre 
					FROM ordenesproduccion u 
					INNER JOIN estadosordenproduccion r ON u.idestadoordenproduccion = r.idestadoordenproduccion
					LEFT JOIN usuario m ON u.usuario_id = m.idusuario
					WHERE (
						m.nombre LIKE '%$busqueda%' OR 
						u.numeroordenproduccion LIKE '%$busqueda%' OR 
						u.fechaprogramacion LIKE '%$busqueda%' OR 
						u.created_at LIKE '%$busqueda%' OR 
						u.descripcion LIKE '%$busqueda%' OR
						u.fechacierre LIKE '%$busqueda%' OR
						r.estado LIKE '%$busqueda%'
					) AND u.status=1 AND u.idempresa='$idempresa' 
					ORDER BY u.idordenproduccion ASC LIMIT $desde,$por_pagina");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);
				if($result>0){
					while ($data=mysqli_fetch_array($query)) {
						if ($data['idordenproduccion']!=0){	
							?>
								<tr>
									<!-- <td><?php echo $data['idordenproduccion']; ?></td> -->
									<td><?php echo $data['numeroordenproduccion']; ?></td>
									<td><?php echo $data['created_at']; ?></td>
									<td><?php echo $data['fechaprogramacion']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>
									<!-- <td><?php echo $data['fechacierre']; ?></td>
									<td><?php echo $data['estado']; ?></td> -->
									<td><?php echo $data['nombre']; ?></td>
									<td>
										<a class="link_edit" href="editar_orden_produccion.php?id=<?php echo $data['idordenproduccion']; ?>"><i class="fas fa-edit"></i> Editar</a>
										|  
										<a class="link_delete" href="eliminar_confirmar_orden_produccion.php?id=<?php echo $data['idordenproduccion']; ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
									</td>
								</tr>
							<?php
						}
					}
				}
			?>
		</table>
		<?php include "paginador.php"; ?>	
	</section>
	<?php  include "includes/footer.php"; ?>
</body>
</html>