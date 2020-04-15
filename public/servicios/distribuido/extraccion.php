<?
	include "../includes/config.php";
	include "../includes/utils.php";

	$dbConn =  connect($db);

	//para extracción, los acentos
	$dbConn->exec("set names utf8");


	header("Content-Type:application/json");
	
	$CONTADOR_ARREGLO_TOTAL=0;
	

	if ($_SERVER['REQUEST_METHOD'] == 'GET')
	{
			if (isset($_GET['RBD']) && trim($_GET['RBD'])!="")
			{
				  
				  
					$var_rbd=trim($_GET['RBD']);
				  	$var_bd=trim($_GET['bd']);
				    $var_tabla=trim($_GET['tabla']);
				  
				  
				  
					//para reemplazar varios datos
					$arrFrom = array("'RBD'");
					$arrTo = array("'".$var_rbd."'");
				  
				  
				  	$DATOS_TOTALES = array();
					$CONTADOR_ARREGLO_TOTAL=0;									
				  
				  

					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					#################POR AHORA VOLQUEMOS DATOS#####################
					
					
					
					foreach ($MATRIZ_BDS as $BD_ORIG => $TABLA)
					{
					
						$BD_RESP="loc_".$BD_ORIG;
					
					
					    if(trim($BD_RESP)==trim($var_bd))
						{
					
					
									foreach ($TABLA as $TABLA_DEF => $FILTRO)
									{
													
								
											if(trim($var_tabla)==trim($TABLA_DEF))
											{
								
													$EL_FILTRO=str_replace($arrFrom,$arrTo,$FILTRO);
													
													if(isset($matriz_datos)) unset($matriz_datos);
																
								
													$CONTADOR=0;
													$sql="SELECT * FROM ".$BD_RESP.".".$TABLA_DEF." where ".$EL_FILTRO;
										
													//echo $sql."\n\n";
											
												try {
								
														$stmt = $dbConn->query($sql)->fetchAll();
													
													
														foreach ($stmt as $row) 
														{
															foreach($row as $clave => $valor)
															{
																  if(!is_int($clave))
																  {																			  
																	  if(is_null($valor))
																		 $valor="NULL";
																	  
																	  $matriz_datos[$CONTADOR]["$clave"]=json_encode($valor,JSON_UNESCAPED_UNICODE);
																  	//,JSON_UNESCAPED_UNICODE
																  }
															
															}
															$CONTADOR++;
														}
													
													
													
													} catch (Exception $e) {
														
															$DATOS_TOTALES["errors"]="ERROR : ".$e->getMessage()." | ".$sql;
															echo json_encode($DATOS_TOTALES);
															exit;
											
													}
											
											
													
													if(isset($matriz_datos))
													{
														//si hay datos para esta tabla
														$data_array = array(
																  "bd"  => $BD_ORIG,
																  "tabla" => $TABLA_DEF,
																  "valores" => json_encode($matriz_datos,JSON_UNESCAPED_UNICODE)
															);
									
														//,JSON_UNESCAPED_UNICODE
									
														//se encodea con json
														//$DATOS_TOTALES[$CONTADOR_ARREGLO_TOTAL]=json_encode($data_array,JSON_UNESCAPED_UNICODE);
														$CONTADOR_ARREGLO_TOTAL++;
											
													}	
								
											
											}//si es la tabla seleccionada
								
								
									} //foreach
								
							}//si es la bd seleccionada
					
					
					} //foreach
					
					
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					#############FIN POR AHORA VOLQUEMOS DATOS#####################
					
			
			
				  if($CONTADOR_ARREGLO_TOTAL>0)
				  {	
					
						//echo json_encode($DATOS_TOTALES);
						echo json_encode($data_array);
						exit;
				  }else{
				  
				  		$DATOS_TOTALES["errors"]="NO HAY DATOS";
					 	echo json_encode($DATOS_TOTALES);
				        exit;
					  }	

			
			}else{
			  		$DATOS_TOTALES["errors"]="NO VIENE RBD";
				 	echo json_encode($DATOS_TOTALES);
				    exit;
				  }	
	 

	}else{
	  		$DATOS_TOTALES["errors"]="NO ES METODO GET";
		 	echo json_encode($DATOS_TOTALES);
		    exit;
		  }	
	 
?>