<?php
	//Busqueda Dispositivo IoT

	//Validar usuario con acceso a este módulo
	session_start();
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}	

	include "includes/scripts.php";
	$idempresa=$_SESSION['idempresa'];
	$busqueda=strtolower($_REQUEST['busqueda']);
	if(empty($busqueda)){
		header("location: lista_dispositivosIoT.php");
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Dispositivos IoT</title>
</head>
<body>
	<?php  include "includes/header.php"; ?>

	<section id="container">
		<h1>Dispositivos IoT</h1>
		<a href="registro_dispositivoIoT.php" class="btn_new">Crear Disposito IoT</a>
		
		<form action="buscar_dispositivoIoT.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>ID</th>
				<th>Tipo de Dispositivo</th>
				<th>Firmware</th>
				<th>Fecha Creación</th>
				<th>Módulo</th>
				<th>Usuario Creador </th>
				<th>Acciones</th>
			</tr>

			<?php
				//paginador
				include "../conexion.php";
				$sql_register=mysqli_query($conexion,"
					SELECT COUNT(*) as total_registro 
					FROM dispositivosiot u 
					INNER JOIN tiposdispositivosiot r ON u.tipodispositivoIoT=r.idtipodispositivoiot
					LEFT JOIN usuario m ON u.idusuario = m.idusuario
					LEFT JOIN modulos s ON u.modulo = s.idmodulo
					WHERE (
						s.nombremodulo LIKE '%$busqueda%' OR 
						r.tipodispositivoIoT LIKE '%$busqueda%' OR 
						u.firmware LIKE '%$busqueda%' OR 
						m.nombre LIKE '%$busqueda%'
					) AND u.status=1 AND s.idempresa='$idempresa'");
				include "calculonumpaginas.php";

				//Crear lista
				$query = mysqli_query($conexion,"
					SELECT u.iddispositivoIoT, s.nombremodulo AS 'modulo', r.tipodispositivoIoT AS 'tipodispositivoIoT', u.firmware, u.created_at, m.nombre AS 'nombreusuariocreador'
					FROM dispositivosiot u 
					INNER JOIN tiposdispositivosiot r ON u.tipodispositivoIoT=r.idtipodispositivoiot
					LEFT JOIN usuario m ON u.idusuario = m.idusuario
					LEFT JOIN modulos s ON u.modulo = s.idmodulo
					WHERE (
						u.iddispositivoIoT LIKE '%$busqueda%' OR
						s.nombremodulo LIKE '%$busqueda%' OR 
						r.tipodispositivoIoT LIKE '%$busqueda%' OR 
						u.firmware LIKE '%$busqueda%' OR 
						m.nombre LIKE '%$busqueda%'
					) AND u.status=1 AND s.idempresa='$idempresa' ORDER BY u.iddispositivoIoT ASC LIMIT $desde,$por_pagina");
				mysqli_close($conexion);
				
				$result = mysqli_num_rows($query);
				if($result>0){
					while ($data=mysqli_fetch_array($query)) {
						$formato='Y-m-d H:i:s';
						$fecha= DateTime::createFromFormat($formato,$data['created_at']);
						?>
							<tr>
								<td><?php echo $data['iddispositivoIoT']; ?></td>
								<td><?php echo $data['tipodispositivoIoT']; ?></td>
								<td><?php echo $data['firmware']; ?></td>
								<td><?php echo $fecha->format('Y-m-d'); ?></td>
								<td><?php echo $data['modulo']; ?></td>
								<td><?php echo $data['nombreusuariocreador']; ?></td>
								</td>
								<td>
									<a class="link_edit" href="editar_dispositivoIoT.php?id=<?php echo $data['iddispositivoIoT']; ?>"><i class="fas fa-edit"></i> Editar</a>
									|  
									<a class="link_delete" href="eliminar_confirmar_dispositivoIoT.php?id=<?php echo $data['iddispositivoIoT']; ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
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