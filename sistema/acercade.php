<?php
	include "includes/scripts.php";
	session_start(); 
	//if($_SESSION['rol']!=1){
	//	header("location: ./");
	//}
	
?>



<!DOCTYPE html>
<html lang="es">
<head>
	<title>Acerca de</title>
	<meta charset="utf-8">
</head>
<body>
	<?php  include "includes/header.php"; ?>
	
	<br><br><br><br><br>
	<h1>
		Descripción:
	</h1>
	<br>
	<p>
		El sistema ANDON - IoT -Confecciones, es un sistema ANDON, de control visual para monitorear y desplegar en pantalla los avances de producción que han sido programados en un módulo de confecciones, usando cajas botoneras u otros dispositivos IoT para informar cada que se realiza un producto o cuando hay un paro en la línea de producción. El sistema permite el control de inicios y programación de los módulos por parte del administrador (Con acceso limitado de usuario con clave), al igual que la visualización en pantalla gigante para los operarios en planta, del estado de avance de la producción y la eficiencia acumulada en tiempo real.<br><br>

		Para controlar el avance de producción en un módulo de confecciones, debe estar éste registrado en la base de datos al igual que la orden de producción, el producto a ejecutar y el módulo IoT que se usará para el control. Una vez ingresado un producto, este puede ser programado numerosas veces en diferentes módulos, al igual que con las órdenes de producción, ya que una orden puede constar de varios productos que pueden ser programados en varios módulos de confección. <br><br>

		- Para registrar el módulo de confecciones, usar la pestaña "Producción/Módulos".
		- Para registrar una orden de producción usar la pestaña "Producción/Ordenes de producción". 
		- Para registrar el producto a elaborar usar la pestaña "Productos/Productos".
		- Para registrar el módulo IoT usar la pestaña "IoT/Dispositivos IoT". Como pueden haber varios tipos de dispositivos IoT, para registrar un dispositivo IoT, primero debe registrarse el tipo de Dispositivo, mediante la opción "IoT/Tipos de dispositivos IoT"<br><br>

		Para iniciar el control, entrar a la opción "Producción/Ejecutar Orden de producción". Allí se selecciona la orden, el producto o ítem a producir, la cantidad requerida, el tiempo de ritmo en el cual se espera que salga un producto en el punto final del módulo, es decir cada cuanto tiempo sale un producto listo (Este tiempo es usado para calcular la base de eficiencia, y es calculado por el supervisor, acorde al balanceo y la cantidad de operadores y máquinas que disponga en el módulo. El tiempo esperado corresponde a la cantidad de productos por el tiempo de ritmo de salida de cada producto), y el tiempo programado de la jornada para el módulo. Para efectos de cálculo de eficiencias, el primer producto es descontado, ya que este toma más tiempo mientras se llena la cadena de producción. <br> <br>

		Cuando el Administrador valida que si es posible realizar la producción en el tiempo esperado, acorde a la cantidad solicitada y el tiempo entre piezas en el punto final, da Click en aceptar o regresa para recalcular los datos de producción. Luego de aceptarlo el sistema contabiliza los datos llegados desde las cajas IoT, donde cada que se presiona un botón verde es porque se ha terminado una prenda. Si el operario oprime el botón rojo, el módulo de confecciones entra a modo Pausa, hasta que el administrador confirme que se ha solucionado el problema. El administrador por su parte también puede pausar la producción y reiniciarla. Los operarios ven estos estados en pantalla gigante.<br><br>

		En todos los tableros de control de producción cuando se está controlando el conteo, se tiene la opción de cambiar el módulo a monitorear.<br><br>

		El administrador puede crear usuarios, con diferentes roles, mediante la opción "Gestión Humana/Usuarios". Los diferentes tipos de rol, son: Admin, Supervisor, Operario, Gerente y Administrador. Los operarios solo pueden entrar a la opción de visualización en pantalla gigante "Producción/Tablero en Planta".<br><br>

		API<br><br>

		El formato del codigo enviado por la caja iot por método get a la API es:<br><br>
		http://xxxxxxx/controldeestados1/sistema/api/apiIoT.php?iddispositivoiot=1&idtipodispositivoiot=1&boton1=1&boton2=0&voltage=0<br><br>

		donde:<br><br>
		xxxxxxx es la direccion del host donde está montada la aplicacion, sea una direccion IP, un localhost o un nombre de dominio DNS de un Hosting.<br><br>
		iddispositivoiot es el código del dispositivo IoT.<br>
		idtipodispositivoiot es el código del tipo de dispositivo IoT.<br>
		boton1 es el estado del boton verde de aceptacion de operacion. 1= oprimido<br>
		boton2 es el estado del boton rojo de paro. 1=oprimido<br>
		voltage es el valor de medida en voltios del sensor de la caja IoT.<br><br>


	</p>
	<br>
	<h2>
		Créditos:
	</h2>
	<br>
	<p>
		La plataforma Sistema Andón - IoT - Confecciones, ha sido desarrollada por Jorge Andrés Cock, y es propiedad, con copyrights del grupo de investigación INAMOD, del Centro de Formación en Diseño, Confección y Moda, CFDCM, que pertenece al Servicio Nacional de Aprendizaje SENA, Regional Antioquia, <a href="www.sena.edu.co">www.sena.edu.co</a>.
	</p>
	<br>
	<?php  include "includes/footer.php"; ?>
</body>
</html>