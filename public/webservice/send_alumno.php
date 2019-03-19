<div class="col-md-12">
   <div class="panel panel-info">

							<?php
						
							
								if(isset($_POST["rut"]) && trim($_POST["rut"])!="" && isset($_POST["rbd"]) && trim($_POST["rbd"])!="")
								{
							
										require_once ('lib/nusoap.php');
												
										$cliente =  new nusoap_client('http://minlocal.e-mat.cl/webservice/serv_alumno.php');
										if($err=$cliente->getError())
										{
												echo "<div class='panel-heading'>Error : ".$err."</div>";
												
										}else{
								
														$elrut=trim($_POST["rut"]);
														$elrbd=trim($_POST["rbd"]);
														
														$return = $cliente->call('getXMLtblacceso', array('rut'=>$elrut,'rbd'=>$elrbd));
										
													
										
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
																	
																		$ALUMNO_RUT=$sub_columna_fila[0];
																		$ALUMNO_INSTITUCION=$sub_columna_fila[1];
																		$ALUMNO_LISTA=$sub_columna_fila[2];
																		$ALUMNO_HABILITADO=$sub_columna_fila[3];
																		$ALUMNO_NIVEL=$sub_columna_fila[4];
																		$ALUMNO_ULTIMOMODULO=$sub_columna_fila[5];
																		$ALUMNO_DATOSREINIC=$sub_columna_fila[6];
																		$ALUMNO_DATOSREINICMETA=$sub_columna_fila[7];
																		$ALUMNO_DATOSREINICPRU=$sub_columna_fila[8];
																		$ALUMNO_RUTTUTOR=$sub_columna_fila[9];
																		$ALUMNO_NOMBRE=$sub_columna_fila[10];
																	
																	}
													
													
																	if($columna_fila[0]=="D_PRUEBA")
																	{
																		$sub_columna_fila = explode("******",$columna_fila[1]);
																		
																		$MATRIZ_PRUEBAS[$i]["ev_prueba"]=$sub_columna_fila[0];
																		$MATRIZ_PRUEBAS[$i]["ev_fecha"]=$sub_columna_fila[1];
																		$MATRIZ_PRUEBAS[$i]["ev_hora"]=$sub_columna_fila[2];
																		$MATRIZ_PRUEBAS[$i]["ev_puntaje"]=$sub_columna_fila[3];
																		$MATRIZ_PRUEBAS[$i]["ev_puntaje_nivel"]=$sub_columna_fila[4];
																		
																	}
														
																	if($columna_fila[0]=="D_MULTISES")
																	{
																		$sub_columna_fila = explode("******",$columna_fila[1]);
																		
																		$MATRIZ_SES[$i]["ses_fecha_episodio"]=$sub_columna_fila[0];
																		$MATRIZ_SES[$i]["ses_hora_episodio"]=$sub_columna_fila[1];
																		$MATRIZ_SES[$i]["ses_reinicio"]=$sub_columna_fila[2];
																		$MATRIZ_SES[$i]["ses_session_anterior"]=$sub_columna_fila[3];
																		$MATRIZ_SES[$i]["ses_session_actual"]=$sub_columna_fila[4];
																		
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
																		
																	}
																	
															
															
															}
										
										?>
								 
															 <div class="panel-heading">
																  Informaci&oacute;n Alumno
															 </div>
														
															 <div  class="row text-center contact-info">
																 <div class="col-lg-12 col-md-12 col-sm-12">
																	 <hr />
																	 <span>
																		 <strong>RUT : </strong>  <?=$ALUMNO_RUT?> / 
																	 </span>
																	 <span>
																		 <strong>NOMBRE : </strong>  <?=$ALUMNO_NOMBRE?> / 
																	 </span>
																	  <span>
																		 <strong>CURSO : </strong>  <?=$ALUMNO_LISTA?> 
																	 </span>
																	 <hr />
																 </div>
															 </div>
															 <div  class="row pad-top-botm client-info">
																 <div class="col-lg-6 col-md-6 col-sm-6">
																     <b>Habilitado :</b> <?=$ALUMNO_HABILITADO?>
																	 <br />
																	 <b>Nivel :</b> <?=$ALUMNO_NIVEL?>
																	 <br />
																	 <b>Ultimo M&oacute;dulo :</b> <?=$ALUMNO_ULTIMOMODULO?>
																	 <br />
																	 <b>Datos Reinicio :</b> <?=$ALUMNO_DATOSREINIC?>
																 	 <br />
																 </div>
																  <div class="col-lg-6 col-md-6 col-sm-6">
																	 <b>Datos Reinicio Meta :</b> <?=$ALUMNO_DATOSREINICMETA?>
																	 <br />
																	 <b>Datos Reinicio Pru :</b> <?=$ALUMNO_DATOSREINICPRU?>
																	 <br />
																	 <b>Rut Tutor :</b> <?=$ALUMNO_RUTTUTOR?>
																 	<br />
																 </div>
															</div>
								 
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



		 </div>
	 </div>
						
