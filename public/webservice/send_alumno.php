<div class="col-md-12">
   <div class="panel panel-info">

							<?php
						
							
								$LABORATORIOID="";
							
								if(isset($_POST["rut"]) && trim($_POST["rut"])!="")
								{
							
										require_once ('lib/nusoap.php');
												
										$cliente =  new nusoap_client('http://minlocal.e-mat.cl/webservice/serv_alumno.php');
										if($err=$cliente->getError())
										{
												echo "<div class='panel-heading'>Error : ".$err."</div>";
												
										}else{
								
														$elrut=trim($_POST["rut"]);
																										
														$return = $cliente->call('getXMLtblacceso', array('rut'=>$elrut));
																							
										
														$filas_respuesta = explode("@",$return);
										
										
										
														if(!isset($filas_respuesta[0]))
														{
														
															echo "<div class='panel-heading'>Error al realizarce la consulta en el sistema</div>";
											
														}elseif(isset($filas_respuesta[0]) && trim($filas_respuesta[0])=="KO"){
														
															echo "<div class='panel-heading'>Alumno no existe en el sistema!!</div>";
														
														}elseif(isset($filas_respuesta[0]) && trim($filas_respuesta[0])=="OK"){
														
														
															for($i=1;$i<count($filas_respuesta);$i++)
															{
															
																
																
																	$columna_fila = explode("##",$filas_respuesta[$i]);
																
																	if($columna_fila[0]=="D_ALUMNO")
																	{
																
																		$sub_columna_fila = explode("******",$columna_fila[1]);
																		
																		$MATRIZ_ALUMNOS[$i]["RUT"]=$sub_columna_fila[0];
																		$MATRIZ_ALUMNOS[$i]["INSTITUCION"]=$sub_columna_fila[1];
																		
																		$LABORATORIOID=$MATRIZ_ALUMNOS[$i]["INSTITUCION"];
																		
																		$MATRIZ_ALUMNOS[$i]["LISTA"]=$sub_columna_fila[2];
																		$MATRIZ_ALUMNOS[$i]["HABILITADO"]=$sub_columna_fila[3];
																		$MATRIZ_ALUMNOS[$i]["NIVEL"]=$sub_columna_fila[4];
																		$MATRIZ_ALUMNOS[$i]["ULTIMOMODULO"]=$sub_columna_fila[5];
																		$MATRIZ_ALUMNOS[$i]["DATOSREINIC"]=$sub_columna_fila[6];
																		$MATRIZ_ALUMNOS[$i]["DATOSREINICMETA"]=$sub_columna_fila[7];
																		$MATRIZ_ALUMNOS[$i]["DATOSREINICPRU"]=$sub_columna_fila[8];
																		$MATRIZ_ALUMNOS[$i]["RUTTUTOR"]=$sub_columna_fila[9];
																		$MATRIZ_ALUMNOS[$i]["NOMBRE"]=$sub_columna_fila[10];
																		$MATRIZ_ALUMNOS[$i]["PRODUCTO"]=strtoupper($sub_columna_fila[11]);
																	
																	
																	}
													
													
																	if($columna_fila[0]=="D_PRUEBA")
																	{
																		$sub_columna_fila = explode("******",$columna_fila[1]);
																		
																		$MATRIZ_PRUEBAS[$i]["ev_prueba"]=$sub_columna_fila[0];
																		$MATRIZ_PRUEBAS[$i]["ev_fecha"]=$sub_columna_fila[1];
																		$MATRIZ_PRUEBAS[$i]["ev_hora"]=$sub_columna_fila[2];
																		$MATRIZ_PRUEBAS[$i]["ev_puntaje"]=$sub_columna_fila[3];
																		$MATRIZ_PRUEBAS[$i]["ev_puntaje_nivel"]=$sub_columna_fila[4];
																		$MATRIZ_PRUEBAS[$i]["PRODUCTO"]=strtoupper($sub_columna_fila[5]);
																		
																	}
														
																	if($columna_fila[0]=="D_MULTISES")
																	{
																		$sub_columna_fila = explode("******",$columna_fila[1]);
																		
																		$MATRIZ_SES[$i]["ses_fecha_episodio"]=$sub_columna_fila[0];
																		$MATRIZ_SES[$i]["ses_hora_episodio"]=$sub_columna_fila[1];
																		$MATRIZ_SES[$i]["ses_reinicio"]=$sub_columna_fila[2];
																		$MATRIZ_SES[$i]["ses_session_anterior"]=$sub_columna_fila[3];
																		$MATRIZ_SES[$i]["ses_session_actual"]=$sub_columna_fila[4];
																		$MATRIZ_SES[$i]["PRODUCTO"]=strtoupper($sub_columna_fila[5]);
																		
																	}
															
															
																	if($columna_fila[0]=="D_MODULOS")
																	{
																		$sub_columna_fila = explode("******",$columna_fila[1]);
																		
																		$MATRIZ_MOD[$i]["mod_modulo"]=$sub_columna_fila[0];
																		$MATRIZ_MOD[$i]["mod_ingreso_fecha"]=$sub_columna_fila[1];
																		$MATRIZ_MOD[$i]["mod_ingreso_hora"]=$sub_columna_fila[2];
																		$MATRIZ_MOD[$i]["mod_termino_fecha"]=$sub_columna_fila[3];
																		$MATRIZ_MOD[$i]["mod_termino_hora"]=$sub_columna_fila[4];
																		$MATRIZ_MOD[$i]["mod_estado"]=$sub_columna_fila[5];
																		$MATRIZ_MOD[$i]["mod_puntaje"]=$sub_columna_fila[6];
																		$MATRIZ_MOD[$i]["mod_puntajePSU"]=$sub_columna_fila[7];
																		$MATRIZ_MOD[$i]["mod_cuenta"]=$sub_columna_fila[8];
																		$MATRIZ_MOD[$i]["mod_respaldo_reinicio"]=$sub_columna_fila[9];
																		$MATRIZ_MOD[$i]["PRODUCTO"]=strtoupper($sub_columna_fila[10]);
																		
																	}
																	
															
															
															}
										
										?>
								 
															 <div class="panel-heading">
																  Informaci&oacute;n Alumno
															 </div>
                                                        
															 <?php
                                                                
																if(isset($MATRIZ_ALUMNOS))
                                                                {
                                                                    foreach($MATRIZ_ALUMNOS as $clave => $valor)
                                                                    {
                                                                    ?>
															 <div  class="row text-center contact-info">
													        	 <div class="col-lg-12 col-md-12 col-sm-12">
																	 <hr />
																	 <span>
																		 <strong>RUT : </strong>  <?=$valor["RUT"]?> / 
																	 </span>
																	 <span>
																		 <strong>NOMBRE : </strong>  <?=$valor["NOMBRE"]?> / 
																	 </span>
																	  <span>
																		 <strong>CURSO : </strong>  <?=$valor["LISTA"]?> 
																	 </span>
																	 <hr />
																 </div>
													          </div>
															 <div  class="row pad-top-botm client-info">
																 <div class="col-lg-6 col-md-6 col-sm-6">
																     <b>Habilitado :</b> <?=$valor["HABILITADO"]?>
																	 <br />
																	 <b>Nivel :</b> <?=$valor["NIVEL"]?>
																	 <br />
																	 <b>Ultimo M&oacute;dulo :</b> <?=$valor["ULTIMOMODULO"]?>
																	 <br />
																	 <b>Datos Reinicio :</b> <?=$valor["DATOSREINIC"]?>
																 	 <br />
																 </div>
																  <div class="col-lg-6 col-md-6 col-sm-6">
																	 <b>Datos Reinicio Meta :</b> <?=$valor["DATOSREINICMETA"]?>
																	 <br />
																	 <b>Datos Reinicio Pru :</b> <?=$valor["DATOSREINICPRU"]?>
																	 <br />
																	 <b>Rut Tutor :</b> <?=$valor["RUTTUTOR"]?>
																	 <br />
																	 <b>Base de Datos :</b> <strong><font color="<?=($valor["PRODUCTO"]=="BASICA")?"#FF9966":"#0099FF"?>"><?=$valor["PRODUCTO"]?></strong></font>
															     	<br />
																 </div>
															</div>
								 						<?
																	}
															}		
												
														?>
                                 
															 <div class="row">
																 <div class="col-lg-12 col-md-12 col-sm-12">
																   <div class="table-responsive">
																				 						
																					<hr />
																					<table class="table table-hover">
																						<thead>
																							<tr>
																								<th colspan="5">EVALUACIONES</th>
																							</tr>
																						
																							<tr>
																								<th>BD</th>
																								<th>PRUEBA</th>
																								<th>FECHA</th>
																								<th>HORA</th>
																								<th>PUNTAJE</th>
																								<th>PUNTAJE NIVEL</th>
																							</tr>
																						</thead>
																						<tbody>
																					
																						   <?php
																							if(isset($MATRIZ_PRUEBAS))
																							{
																								foreach($MATRIZ_PRUEBAS as $clave => $valor)
																								{
																								?>
																								<tr>
																									<td><strong><font color="<?=($valor["PRODUCTO"]=="BASICA")?"#FF9966":"#0099FF"?>"><?=$valor["PRODUCTO"]?></font></strong></td>
                                                                                                	<td><?php echo $valor["ev_prueba"]?></td>
																									<td><?php echo $valor["ev_fecha"]?></td>
																									<td><?php echo $valor["ev_hora"]?></td>
																									<td><?php echo $valor["ev_puntaje"]?></td>
																									<td><?php echo $valor["ev_puntaje_nivel"]?></td>
																								</tr>
																								<?
																								}
																							}		
																							?>
																							
																					
																					
																						</tbody>
																				   </table>
				
																	   </div>
																 </div>
															 </div>
				
				
															
															 <div class="row">
																 <div class="col-lg-12 col-md-12 col-sm-12">
																   <div class="table-responsive">
															
															
																					<table class="table table-hover">
																						<thead>
																							<tr>
																								<th colspan="9">ACTIVIDADES</th>
																							</tr>
																							<tr>
																								<th>BD</th>
																							    <th>MODULO</th>
																								<th>FECHA INGRESO</th>
																								<th>HORA INGRESO</th>
																								<th>FECHA TERMINO</th>
																								<th>HORA TERMINO</th>
																								<th>ESTADO</th>
																								<th>PUNTAJE</th>
																								<th>PUNTAJE PSU</th>
																								<th>CUENTA</th>
																								<th>RESPALDO REINICIO</th>
																					    	</tr>
																						</thead>
																						<tbody>
																						
																							<?php
																							if(isset($MATRIZ_MOD))
																							{
																								foreach($MATRIZ_MOD as $clave => $valor)
																								{
																								?>
																								<tr>
																									<td><strong><font color="<?=($valor["PRODUCTO"]=="BASICA")?"#FF9966":"#0099FF"?>"><?=$valor["PRODUCTO"]?></font></strong></td>
                                                                                                    <td><?php echo $valor["mod_modulo"]?></td>
																									<td><?php echo $valor["mod_ingreso_fecha"]?></td>
																									<td><?php echo $valor["mod_ingreso_hora"]?></td>
																									<td><?php echo $valor["mod_termino_fecha"]?></td>
																									<td><?php echo $valor["mod_termino_hora"]?></td>
																									<td><?php echo $valor["mod_estado"]?></td>
																									<td><?php echo $valor["mod_puntaje"]?></td>
																									<td><?php echo $valor["mod_puntajePSU"]?></td>
																									<td><?php echo $valor["mod_cuenta"]?></td>
																									<td><?php echo $valor["mod_respaldo_reinicio"]?></td>
																					    		</tr>
																								<?
																								}
																							}		
																							?>
																						
																						
																						
																						
																						</tbody>
																				   </table>
															
															
																	   </div>
															
																 </div>
															 </div>
				
															
															 <div class="row">
																 <div class="col-lg-12 col-md-12 col-sm-12">
																   <div class="table-responsive">
															
															
																					<table class="table table-hover">
																						<thead>
																							<tr>
																								<th colspan="4">MULTI SESION</th>
																							</tr>
																							<tr>
																								<th>BD</th>
																							    <th>FECHA</th>
                                                                                                <th>HORA</th>
																								<th>REINICIO</th>
																								<th>SESION ANTERIOR</th>
																								<th>SESION ACTUAL</th>
																							</tr>
																						</thead>
																						<tbody>
																							
																							<?php
																							if(isset($MATRIZ_SES))
																							{
																								foreach($MATRIZ_SES as $clave => $valor)
																								{
																								?>
																								<tr>
																									<td><strong><font color="<?=($valor["PRODUCTO"]=="BASICA")?"#FF9966":"#0099FF"?>"><?=$valor["PRODUCTO"]?></font></strong></td>
                                                                                                    <td><?php echo $valor["ses_fecha_episodio"]?></td>
																								    <td><?php echo $valor["ses_hora_episodio"]?></td>
																									<td><?php echo $valor["ses_reinicio"]?></td>
																									<td><?php echo $valor["ses_session_anterior"]?></td>
																									<td><?php echo $valor["ses_session_actual"]?></td>
																								</tr>
																								<?
																								}
																							}		
																							?>
																							
																						</tbody>
																				   </table>
																		
																	   
																	   </div>
															
																 </div>
															 </div>
				
				
								<?
							
								}
						
						}
			
								
				}else{
				
						echo "<div class='panel-heading'>Debe ingresar rut!!</div>";
					
				
				}
				?>		 
				<script>
					<? if(trim($LABORATORIOID)!=""){?>
						$("#Colegio").val('<?=$LABORATORIOID?>');
						consulta_colegio('<?=$LABORATORIOID?>');
					<? } ?>
				</script>


		 </div>
	 </div>
						
