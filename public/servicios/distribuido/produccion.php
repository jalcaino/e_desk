<?
		include "../includes/config.php";
		include "../includes/utils.php";

		$dbConn =  connect($db);
	
		//para extracción, los acentos
		$dbConn->exec("set names utf8");
	
		
		$CONTADOR_ARREGLO_TOTAL=0;
	
		$var_rbd="'";

		$sql = "SELECT SIS03_LABORATORIOID,ED18_AMBIENTE_ACTUAL FROM e_desk.ED18_INVENTARIO_DISTRIBUIDOS where ED18_ACTIVO=1 and ED18_ES_DISTRIBUIDO=1";
		foreach ($dbConn->query($sql) as $row) 
		{
			
			$ID_AMBIENTE=$row['ED18_AMBIENTE_ACTUAL'];
			
			//si ya esiste el ambiente	
			if(isset($MATRIZ_AMBIENTE["$ID_AMBIENTE"]))
				$MATRIZ_AMBIENTE["$ID_AMBIENTE"].="|".$row['SIS03_LABORATORIOID'];
			else
				$MATRIZ_AMBIENTE["$ID_AMBIENTE"]=$row['SIS03_LABORATORIOID'];
	
		}


	


		foreach ($MATRIZ_AMBIENTE as $VALOR_AMBIENTE => $VALOR_RBD)
		{
					
					echo "<br><br>[".$VALOR_RBD." / ".$VALOR_AMBIENTE."]<br><br>";
				
					$str = str_replace("|", "','",$VALOR_RBD);
		
					$var_rbd=$str;
						
					  
					//para reemplazar varios datos
					$arrFrom = array("='RBD'");
					$arrTo = array(" in ('".$var_rbd."')");
				  
					$DATOS_TOTALES = array();
					$CONTADOR_ARREGLO_TOTAL=0;									
				  
			
					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					
					
					
					foreach ($MATRIZ_BDS as $BD_ORIG => $TABLA)
					{
					
					
						//ONLINE
						if(intval($VALOR_AMBIENTE)==1)
						{
								$BD_FROM=$BD_ORIG;
								$BD_TO="loc_".$BD_ORIG;
						}else{
						
								$BD_FROM="loc_".$BD_ORIG;
								$BD_TO=$BD_ORIG;
						}
					
					
						foreach ($TABLA as $TABLA_DEF => $FILTRO)
						{
								
										$EL_FILTRO=str_replace($arrFrom,$arrTo,$FILTRO);
										
										if(isset($matriz_datos)) unset($matriz_datos);
								
										$CONTADOR=0;
									
										$SQL="REPLACE INTO ".$BD_TO.".".$TABLA_DEF." SELECT * FROM ".$BD_FROM.".".$TABLA_DEF." where ".$EL_FILTRO;
								
				
									try {
					
											$statement = $dbConn->prepare($SQL);
											$statement->execute();
						
											echo "OK REPLACE : ".$SQL."<br>";
						
										
										} catch (Exception $e) {
											
												echo "<br>ERROR : ".$e->getMessage()." | ".$SQL."<br><br>";
								
										}
								
								
					
						} //foreach
					
					
					
					} //foreach
					
					
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					


	  }//foreach de emat distribuidos

		
	  echo "<pre>";
	  print_r($MATRIZ_AMBIENTE);
 	  echo "</pre>";
	 ?>