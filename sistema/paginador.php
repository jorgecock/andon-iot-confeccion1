		<?php 
			if($total_paginas>1){
		?>
			<div class='paginador'>
				<ul>
					<?php 
						if($pagina!=1){ 
							?>
							<li><a href='?pagina=1'><i class="fas fa-step-backward"></i></a></li>
							<li><a href='?pagina= <?php echo $pagina-1 ?>'><i class="fas fa-caret-left fa-lg"></i></a></li>
							<?php	
						}
						for($i=1;$i<=$total_paginas;$i++){
							if($i==$pagina){
								echo '<li class="pageSelected">'.$i.'</li>';
							}else{
								echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
							}
						}
					
						if($pagina!=$total_paginas){ 
							?>
							<li><a href='?pagina= <?php echo $pagina+1 ?>'><i class="fas fa-caret-right fa-lg"></i></a></li>
							<li><a href='?pagina= <?php echo $total_paginas ?>'><i class="fas fa-step-forward"></i></a></li>
							<?php	
						}
					?>
				</ul>
			</div>
		<?php 
			}
		?>