<?php
include "../functions.php";
$mensaje=array("1");

if(!empty($_GET)) { //se ejecuta si se reciben parametros

	if (isset($_GET['iddispositivoiot']) AND isset($_GET['idtipodispositivoiot'])){  //se ejecuta si llega el iddispositivo y idtipodispositivo
		
		//Datos GET recibidos
		$iddispositivoIoTrecibido=$_GET['iddispositivoiot'];
		$idtipodispositivoIoTrecibido=$_GET['idtipodispositivoiot'];
		include "../conexion.php";
		

		//Verifica si hay registros de dispositivo del iddispositivoIoT
		$query1 = mysqli_query($conexion,"
				SELECT * FROM dispositivosiot WHERE iddispositivoIoT=$iddispositivoIoTrecibido");
		$result=mysqli_num_rows($query1);
		
		if ($result>0){ //se ejecuta lo dentro del if si el dispositivo está registrado
			$data=mysqli_fetch_array($query1);
			//Hay registros de dispositivo del iddispositivoIoT


			// verifica que el tipo dispositivo recibido desde el modulo iot corresponde con el registrado en la base de datos
			//******si dispositivo coincide con el de la base de datos se ejecita lo dentro del if
 			if($idtipodispositivoIoTrecibido==$data['tipodispositivoIoT']){
				//corresponden el tipo dispositivo recibido desde el modulo iot corresponde con el registrado en la base de datos


				if ( $_GET['idtipodispositivoiot']==1 AND isset($_GET['boton1']) AND isset($_GET['boton2']) AND isset($_GET['voltage'])){ 
					//***************Dispositivo tipo 1*******
					//// Modulo Iot solo con botenes para conteo de prendas hechas al final de linea y paro por error, y medicion de voltaje
					//voltage

					$voltage=$_GET['voltage']; //voltaje medido en dispositivo
					$mod=$data['modulo']; //modulo en el que está registrado el dispositivo.

					//consulta el estado de conteo para el modulo al cual se tiene registrado el dispositivo IoT
					$query2 = mysqli_query($conexion," SELECT * FROM modulos WHERE idmodulo=$mod");
					$result2=mysqli_fetch_array($query2);
		
					//datos iniciales
					$tiempoactual=strtotime("now"); //monto actual unix en segundos
					$estadoactual=$result2['estado']; //estado en que se encuentra el control de tableros en modulo, para dispoditivo 1 solo tendrá efecto en estado =3
					
					//datos de entrada
					$ordendeprod=$result2['ordendeprod']; //id de orden de produccion
					$itemaproducir=$result2['itemaproducir']; //id de producto a hacer
					$unidadesesperadas=$result2['unidadesesperadas'];//unidades esperadas totales
					$tiempocicloesperado=$result2['tiempocicloesperado']; //tiempo ciclo esperado en minutos
					
					//productos hechos
					$productoshechos=$result2['productoshechos']; //productos hechos totales
					$prodhechosdespausaini = $result2['prodhechosdespausaini']; //productos hechos luego de una pausa o inicio

					// -------------calculo de tiempo acumulado
					$tiempoacumuladoanterior=$result2['tiempoacumulado']; //tiempo en minutos acumulado de trabajo descontando pausas, al momento de entrar desde la ultima pausa.
					$tiemporegistroanterior=$result2['tiemporegistro']; //minuto en que se registro el ultimo producto antes del actual
					$tiempoultimoproducto=$tiempoactual-$tiemporegistroanterior;
					$nuevotiempoacumulado=$tiempoultimoproducto+$tiempoacumuladoanterior; // tiempo acumulado anterior + el tiempo que se lleva desde el inicio de la ultima pausa.
					//pausas

					//calculo pausas
					$pausashechas=$result2['pausashechas']; //cantidad de pausas hechas
					//$momentoinidespausa=$result2['momentoinidespausa']; //minuto en que se comienza o reinicia despues de pausa
					//$tiempopasadodesdeultimoreinicio=($tiempoactual-$momentoinidespausa);//tiempo pasado desde inicio o luego de pausa hasta el momento actual
					
					
				
					if($estadoactual==3){
						//se ejecuta si el estado actual es 3


						if($_GET['boton1']==1 AND $_GET['boton2']==0){  
							//Boton tarea hecha presionado fin de producto 
						
							$nuevosproductoshechos=$productoshechos+1; //calculo de productos hechos, nuevos productos hechos en la ultima hora
							$nuevosprodhechosdespausini=$prodhechosdespausaini+1;//calculo de productos hechos despues del inicio o de una pausa, en un periodo de trabajo segido sin pausas
					
							$productosesperadosalmomento=$nuevotiempoacumulado/($tiempocicloesperado*60); //cantidad esperada a registrar cada hora segun el tiempo que ha transcurrido y el tiempo de ciclo esperado.
							$eficienciaacumulada = ($nuevosproductoshechos/$productosesperadosalmomento)*100;
						

							if ($nuevosprodhechosdespausini <= 1){
								//validar si no es el primer producto luego de inicio o pausa para descartar los valores en el promedio por ser un tiempo mas largo
								//primer producto luego de inicio o de una pausa para no tomar en cuenta en los promedios.
								$ultimotiempodeproduccion=0;

							}else{ //segundo producto en adelante.
								//segundo producto en adelante.
								$ultimotiempodeproduccion=($tiempoactual-$tiemporegistroanterior);
							}
						
							
							if ($nuevosproductoshechos >= $unidadesesperadas){ //validar si termino
								$siguenteestado=6;
								//cambio de estado por terminar las piezas.
							}else{
								$siguenteestado=3;
							}


							//actualización de estados de la tabla modulos
							$query3 = mysqli_query($conexion,"
								UPDATE modulos 
								SET productoshechos=$nuevosproductoshechos, estado=$siguenteestado, tiemporegistro=$tiempoactual,   tiemporegistroanterior=$tiemporegistroanterior, ultimotiempodeproduccion = $ultimotiempodeproduccion, voltage = $voltage, prodhechosdespausaini=$nuevosprodhechosdespausini, tiempoacumulado=$nuevotiempoacumulado,eficienciaacumulada=$eficienciaacumulada
								WHERE idmodulo=$mod"); 

								//registro del momento de cada elemento a producir en una orden de produccion
							$query4 = mysqli_query($conexion,"
								INSERT INTO registrotiempos (ordendeprod, itemaproducir, idmodulo) 
								VALUES ($ordendeprod, $itemaproducir, $mod)");

							if ($query3 AND $query4){
								 
								$mensaje = array("Estado"=>"Ok","Respuesta" =>"pieza hecha +1", "iddispositivoIoT"=>$_GET['iddispositivoiot'],"idtipodispositivoIoT"=>$_GET['idtipodispositivoiot'],"Modulo"=>$mod, "Unidades esperadas"=>$unidadesesperadas, "Productos Hechos"=>$nuevosproductoshechos,"Estado Actual"=>$estadoactual,"Voltage"=>$voltage);
							
								//***********************************************************************************
								$periodo=3600; //3600; //lapso de tiempo en segundos en el cual se registra cada valor de eficiencia
								$cambiotiempo=intval($nuevotiempoacumulado/$periodo)-intval($tiempoacumuladoanterior/$periodo); 
								//***********************************************************************************

								/*echo ("Tiempo Anterior: ".$tiempoacumuladoanterior."<br>");
								echo ("Nuevo Tiempo: ".$nuevotiempoacumulado."<br>");
								echo ("Registro de cada hora: ".$cambiotiempo."<br><br>");*/

								//cada hora se debe cacer un registro en la tabla registro eficiencias
								if ($cambiotiempo>=1){

									$query4 = mysqli_query($conexion,"
										INSERT INTO registroeficiencias (id, ordendeprod, itemaproducir, cantidadesperada, cantidadhecha, eficiencia, fechahora, modulo) VALUES (NULL, $ordendeprod, $itemaproducir, $productosesperadosalmomento ,$nuevosproductoshechos ,$eficienciaacumulada, current_timestamp(), $mod)");
									if ($query4){"bien";}
								}

							}else{ //se ejecuta mensaje si no puede incrementar la base de datos
								// echo("consulta 3 ".$query3."<br>consulta 4 ".$query4)."<br>"
								//no pudo incrementar la base de datos
								$mensaje = array("Estado"=>"Error","Respuesta" =>"No pudo incrementar en base de datos", "iddispositivoIoT"=>$_GET['iddispositivoiot'],"idtipodispositivoIoT"=>$_GET['idtipodispositivoiot'],"Modulo"=>$mod,"Estado Actual"=>$estadoactual,"Voltage"=>$voltage);
							}

						}	


						//Boton de paro de modulo presionado
						elseif($_GET['boton1']==0 AND $_GET['boton2']==1) { //Boton de paro de modulo presionado
							//incrementar contador parte hecha
							
							$siguenteestado=5; //estado de error por paro desde la linea pulsando el boton rojo
							$pausashechas=$pausashechas+1; //incrementa las pausas
							
							$query3 = mysqli_query($conexion,"
								UPDATE modulos 
								SET estado=$siguenteestado, voltage = $voltage, pausashechas=$pausashechas, tiempoacumulado=$nuevotiempoacumulado, momentodepausa=$tiempoactual
								WHERE idmodulo=$mod");
							$mensaje = array("Estado"=>"Ok","Respuesta" =>"Paro por error  pieza en la linea","idtipodispositivoIoT"=>$_GET['idtipodispositivoiot'], "iddispositivoIoT"=>$_GET['iddispositivoiot'], "Voltage"=>$voltage);

						} 

						//ensaje de que la info de los botones no es acorde al tipo de modulo 1 para tomar desiciones
						else {
							//Mensaje de que la info de los botones no es acorde al tipo de modulo 1 para tomar desiciones
							$mensaje = array("Estado"=>"Error","Respuesta" =>"Parametros invalidos para el Dispositivo tipo 1");
						}
					
					}else{ //se ejecuta el else con mensaje de que el modulo no está en estado de conteo
					//mensaje de que el modulo no está en estado de conteo
					$mensaje = array("Estado"=>"Error","Respuesta" =>"Modulo no esta en estado de conteo", "iddispositivoIoT"=>$_GET['iddispositivoiot'],"idtipodispositivoIoT"=>$_GET['idtipodispositivoiot'],"Modulo"=>$mod,"Modulo"=>$mod,"Estado Actual"=>$estadoactual, "Voltage"=>$voltage);
					}
				
				} else { 
					//******dispositivo tipo 2 u otros, se continua con mas elses para cada tipo de dispositivo u otro para versiones  nuevas del software
					//if ( $_GET['idtipodispositivoiot']==N AND (Parametros esperados)
					//******dispositivo tipo 2, 3 u otro tipo de dispositivo
					//cuando se diseñe un dispositivo 2 aqui se pondrá el código que debe invocar. 
					$mensaje = array("Estado"=>"Ok","Respuesta" =>"Dispositivo diferente al tipo 1 o falta Parametros","iddispositivoIoT"=>$_GET['iddispositivoiot'],"idtipodispositivoIoT"=>$_GET['idtipodispositivoiot']);
				}

			} else { //******dispositivo no coincide con el de la base de datos
				//mensaje de que el tipo de dispositivo enviado por el modulo iot no corresponde con el matriculado en la base de datos
				$mensaje = array("Estado"=>"Error","Respuesta"=>"El tipo de dispositivo recibido por el modulo no corresponde con el matriculado en la base de datos");
			}		
	
		} else { //se ejecuta lo dentro del else  si el dispositivo no está registrado
			//mensaje de que no se encuetra el dispositivo
			$mensaje = array("Estado"=>"Error","Respuesta"=>"No encontro registro del dispositivo $iddispositivoIoTrecibido ");
		} 
		
		mysqli_close($conexion);

	} else { //se ejecuta si faltan parámetros iddispositivo o idtipodispositivo
		//mensaje faltan parametros
		$mensaje = array("Estado"=>"Error","Respuesta "=>"Faltan parametros");
	}

} else { //Se ejecuta si no hay parametros
	// Mensaje de sin parametros
	$mensaje = array("Estado"=>"Error","Respuesta"=>"Sin parametros");
}

echo json_encode($mensaje);

?>