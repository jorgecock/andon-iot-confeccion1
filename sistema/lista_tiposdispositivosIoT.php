<?php
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php  include "includes/scripts.php"; ?>
	<title>Tipos de dispositivos IoT</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		

		<h1>Tipos de Dispositivos IoT</h1>
		<a href="registro_tipodispositivoIoT.php" class="btn_new">Crear Tipo de Dispositivo IoT</a>

		<form action="buscar_tipodispositivoIoT.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda">
			
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Tipo de Dispositivo IoT</th>
				<th>Descripción</th>
				<th>Acciones</th>
			</tr>

			<?php
				//paginador
				include "../conexion.php";
				$sql_register=mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM tiposdispositivosiot WHERE status=1");
			
				include "calculonumpaginas.php";

				//Crear lista
				$query = mysqli_query($conexion,"SELECT idtipodispositivoiot, tipodispositivoIoT, descripcion FROM tiposdispositivosiot WHERE status=1 ORDER BY idtipodispositivoiot ASC LIMIT $desde,$por_pagina");
				mysqli_close($conexion);
				$result = mysqli_num_rows($query);

				if($result>0){
					while ($data=mysqli_fetch_array($query)) {
						?>
							<tr>
								<td><?php echo $data['idtipodispositivoiot']; ?></td>
								<td><?php echo $data['tipodispositivoIoT']; ?></td>
								<td><?php echo $data['descripcion']; ?></td>
								<td>
									<a class="link_edit" href="editar_tipodispositivoIoT.php?id=<?php echo $data['idtipodispositivoiot']; ?>"><i class="fas fa-edit"></i> Editar</a>
									|  
									<a class="link_delete" href="eliminar_confirmar_tipodispositivoIoT.php?id=<?php echo $data['idtipodispositivoiot']; ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
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