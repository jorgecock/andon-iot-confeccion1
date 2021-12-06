<?php
	$host='us-cdbr-east-04.cleardb.com'; // $host='localhost';  //
	$user='bc0d9b171404d1'; // $user='root'; //
	$password='8faae0fb'; //  $password=''; //
	$db='heroku_571592fad775d36'; // $db='controldeestados1'; //

	$conexion= mysqli_connect($host,$user,$password,$db);
	if(!$conexion){
		echo "error en la conexion";
	} else {
		//echo "ok";
	}
?>

