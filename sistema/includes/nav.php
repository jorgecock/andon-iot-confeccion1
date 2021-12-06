		<nav>
			<ul>
				<!-- Home -->
				<li class="principal">
					<a href="index.php"><i class="fas fa-home"></i> Inicio</a>
				</li>
				
				<!-- IoT -->
				<?php //Registro de dispositivos IoT. 
				if($_SESSION['rol']==1 || $_SESSION['rol']==2 || $_SESSION['rol']==4 || $_SESSION['rol']==5 || $_SESSION['rol']==6){ 
				?>
					<li class="principal">
						<a href="#"><i class="fas fa-microchip"></i> IoT</a>
						<ul>
							<li class="principal"><a href="lista_dispositivosIoT.php">Dispositivos IoT</a></li>
							
							<?php if($_SESSION['rol']==6){ ?>
								<li class="principal"><a href="lista_tiposdispositivosIoT.php">Tipos de dispositivo IoT</a></li>
							<?php } ?>

						</ul>
					</li>
				<?php } ?>

				<!-- Produccion -->
				<li class="principal">
					<a href="#"><i class="fas fa-industry"></i> Produccion</a>
					<ul>
						
						<?php if($_SESSION['rol']==1 || $_SESSION['rol']==2 || $_SESSION['rol']==4 || $_SESSION['rol']==5 || $_SESSION['rol']==6){
							//Permiso para programar conteo tableros 
						?>
							<li><a href="programar.php">Ejecución de Orden de Producción</a></li>
						<?php } ?>
						
						<li><a href="programarTablero.php">Visualizar Tablero en planta</a></li>
						
						<?php if($_SESSION['rol']==1 || $_SESSION['rol']==2 || $_SESSION['rol']==4 || $_SESSION['rol']==5 || $_SESSION['rol']==6){
							//Permiso para exportar datos de eficiencia a excel ?>
							<li><a href="eficienciasaexcel.php">Descargar datos de eficiencia a excel</a></li>
							<li class="principal"><a href="lista_modulos.php">Módulos Producción</a></li>
							<li><a href="lista_ordenes_produccion.php">Ordenes de Producción</a></li>
						<?php } ?>

						<!--
						<li class="principal"><a href="lista_plantas.php">Plantas</a></li>
						<li><a href="#">Balanceo</a></li>
						<li><a href="#">Control de Calidad</a></li>
						<li><a href="#">Inventarios</a></li>
						-->
					</ul>
				</li>



				<?php if($_SESSION['rol']==1 || $_SESSION['rol']==2 || $_SESSION['rol']==4 || $_SESSION['rol']==5 || $_SESSION['rol']==6){
				//Permiso para editar personas ?>
					<li class="principal">
						<a href="#"><i class="fas fa-users"></i> Gestión Humana</a>
						<ul>
							<li class="principal">
								<a href="lista_usuarios.php"><i class="fas fa-users"></i> Usuarios</a>
							</li>
							<!--
							<li class="principal"><a href="#">Novedades</a></li>
							<li class="principal"><a href="#">Curvas Aprendizaje</a></li>
							-->
						</ul>
					</li>
				<?php } ?>


				<li class="principal">
					<a href="#"><i class='far fa-lemon'></i> Productos</a>
					<ul>
						<li class="principal"><a href="lista_productos.php">Productos</a></li>
						<!-- <li class="principal"><a href="lista_operaciones.php">Operaciones</a></li>
						<li class="principal"><a href="registro_ficha_tecnica.php">Insertar Información General de Diseño</a></li> -->
					</ul>
				</li>

				<!--
				<li class="principal">
					<a href="#">Máquinas</a>
					<ul>
						<li class="principal"><a href="lista_maquinas.php">Máquinas</a></li>
						<li class="principal"><a href="#">Mantenimiento</a></li>
					</ul>
				</li>

				<li class="principal">
					<a href="#">CMR/SCM</a>
					<ul>
						<li class="principal"><a href="lista_empresas.php">Empresas</a></li>
						<li><a href="lista_clientes.php">Clientes</a></li>
						<li><a href="#">Proveedores</a></li>
						<li><a href="#">Compras</a></li>
						<li><a href="#">Ventas</a></li>
					</ul>
				</li>

			
				<li class="principal">
					<a href="#">Costos</a>
					<ul>
						<li class="principal"><a href="#">Costos</a></li>
					</ul>
				</li>
				-->

				<li class="principal">
					<a href="acercade.php"><i class="far fa-question-circle"></i> Acerca de</a>
					<ul>
						<li class="principal"><a href="acercade.php">Acerca de</a></li>
					</ul>
				</li>

			</ul>
		</nav>