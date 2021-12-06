<?php
	//Lista Modulos

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
	<title>Módulos</title>
	<meta charset="UTF-8">
	<meta http-equiv="refresh" content="5">
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<h1>Módulos</h1>
		<a href="registro_modulo.php" class="btn_new">Crear Módulo</a>
		
		<form action="buscar_modulo.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Estado</th>
				<th>Orden prod</th>
				<th>Producto Asignado</th>
				<th>Cantidad Asignada</th>
				<th>Cantidad Hecha</th>
				<th>Eficiencia Acumulada</th>
				<th>Acciones</th>
			</tr>

			<?php
				//paginador
				include "../conexion.php";
				$sql_register=mysqli_query($conexion,"
					SELECT COUNT(*) as total_registro 
					FROM modulos u
					LEFT JOIN ordenesproduccion r ON u.ordendeprod=r.idordenproduccion  
					INNER JOIN estados v ON u.estado=v.idestado
					LEFT	 JOIN producto s ON u.itemaproducir=s.idproducto
					WHERE u.status=1 AND u.idempresa='$idempresa'");
				include "calculonumpaginas.php";
				
				//Crear lista
				$query = mysqli_query($conexion,"
					SELECT u.* , r.numeroordenproduccion, v.estado AS nomestado, s.nombre AS itemaproducir
					FROM modulos u
					LEFT JOIN ordenesproduccion r ON u.ordendeprod=r.idordenproduccion  
					INNER JOIN estados v ON u.estado=v.idestado
					LEFT	 JOIN producto s ON u.itemaproducir=s.idproducto
					WHERE u.status=1 AND u.idempresa='$idempresa'
					ORDER BY idmodulo ASC LIMIT $desde,$por_pagina");
				mysqli_close($conexion);

				$result = mysqli_num_rows($query);
				if($result>0){
					while ($data=mysqli_fetch_array($query)) {
						?>
							<tr>
								<td><?php echo $data['idmodulo']; ?></td>
								<td><?php echo $data['nombremodulo']; ?></td>
								<td><?php echo $data['descripcion']; ?></td>
								<td><?php echo $data['nomestado']; ?></td>
								<td><?php echo $data['numeroordenproduccion']; ?></td>
								<td><?php echo $data['itemaproducir']; ?></td>
								<td><?php echo $data['unidadesesperadas']; ?></td>
								<td><?php echo $data['productoshechos']; ?></td>
								<td><?php echo $data['eficienciaacumulada']; ?></td>

								<td>
									<a class="link_edit" href="editar_modulo.php?id=<?php echo $data['idmodulo']; ?>"><i class="fas fa-edit"></i> Editar
									</a>
									|  
									<a class="link_delete" href="eliminar_confirmar_modulo.php?id=<?php echo $data['idmodulo']; ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
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