<?php
	$host='localhost';  //$host='us-cdbr-east-04.cleardb.com'; // 
	$user='root'; //$user='bc0d9b171404d1'; // 
	$password=''; //$password='8faae0fb'; //  
	$db='controldeestados1'; //$db='heroku_571592fad775d36'; // 

	$conexion= mysqli_connect($host,$user,$password,$db);
	if(!$conexion){
		echo "error en la conexion";
	} else {
		//echo "ok";
	}
?>

