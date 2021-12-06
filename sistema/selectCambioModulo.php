<!-- Cambio de modulo  -->
<hr size="8px" color="black" />
Número de módulo a seguir.<br>
<select id="mySelect" name="selectmod" onchange="cambiodemodulo(this.value)">
	<?php
		//obtener numero de modulos configurados a hacer seguimiento para select 
		include "../conexion.php";
		
		$query1 = mysqli_query($conexion,"SELECT * FROM modulos WHERE status=1 AND idempresa=$idempresa");
		mysqli_close($conexion);
		$result1=mysqli_num_rows($query1);
		//echo $result1;
		if($result1==0){
			header("location: sinmodulosproductosordenes.php");
		}


		for($i=1;$i<=$result1;$i++){
			$tipoa= mysqli_fetch_array($query1);
	?>	
				
			<option value="<?php echo $tipoa['idmodulo']; ?>" <?php echo ($tipoa['idmodulo']==$mod)? "selected":""; ?> >
				<?php echo $tipoa['nombremodulo'];?>
			</option>
				
	<?php 
		}
	?>
</select>