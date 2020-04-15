<?php
	
	header("Content-Type:application/json");
	
	include "../includes/config.php";
	include "../includes/utils.php";

	$dbConn =  connect($db);


	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
	
    	$VAR_DATOS = $_REQUEST["datos"];
    	
		
		if(isset($VAR_DATOS["rbd"]) && isset($VAR_DATOS["bd"]) && isset($VAR_DATOS["tabla"]) && isset($VAR_DATOS["valores"]))
		{
		
				$LA_RBD=$VAR_DATOS["rbd"];
				$LA_BD=$VAR_DATOS["bd"];
				$LA_TABLA=$VAR_DATOS["tabla"];
			
		
				$NUM_DATOS=0;
																										
				
				$ELARCHIVO="/var/www/html/edesk/public/servicios/load/".$LA_RBD.$LA_BD.$LA_TABLA.".csv";	
					
				if (file_exists($ELARCHIVO))
				{
					unlink($ELARCHIVO);
				}
				
					
					
				$archivo = fopen($ELARCHIVO,"w+b");
				if($archivo == false) 
				{
				 		$STATUS="ERROR AL CREAR ARCHIVO";
						$json_response = json_encode($STATUS);
						echo $json_response;
						exit;
		
				}
				else
				{
		
						foreach ($VAR_DATOS["valores"] as $clave => $valor)
						{
							fwrite($archivo,'"'.implode('";"',$valor).'"'."\r\n");
							$NUM_DATOS++;
						}
				
					    fflush($archivo);
				}
	
				fclose($archivo);
			
				/*
				if($LA_TABLA=="alumno_indicadores_mensuales")
				{
				
							$json_response = json_encode("NUM DATOS :".$NUM_DATOS);
							echo $json_response;
							exit;
				}*/
						
			
				
				$SQL="LOAD DATA LOCAL INFILE '".$ELARCHIVO."' REPLACE INTO TABLE ".$LA_BD.".".$LA_TABLA." FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n'";
	
	
				if($NUM_DATOS>0)
				{
					try {
			
							$statement = $dbConn->prepare($SQL);
							$statement->execute();
							
							$STATUS="OK_REG_TABLA_".$LA_BD.".".$LA_TABLA;

							$json_response = json_encode($STATUS);
							echo $json_response;
							exit;
					
					
					} catch (Exception $e) {
				
							$json_response = json_encode("ERROR INSERT: / ".$SQL." / ".$e->getMessage());
							echo $json_response;
							exit;
					}
				
				}else{
		
						$STATUS="SIN DATOS";
						$json_response = json_encode($STATUS);
						echo $json_response;
						exit;
		
					}					
				
						
		}else{
		
				$STATUS="FALTAN PARAMETROS";
				$json_response = json_encode($STATUS);
				echo $json_response;
				exit;
		
		}	
	
	}else{
	
	
				$STATUS="METODO INCORRECTO";
				$json_response = json_encode($STATUS);
				echo $json_response;
				exit;
	
	}
?>