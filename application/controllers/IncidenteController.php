<?php

class IncidenteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
						###########################		
						##inicio validacion sesion
						###########################		
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
	
						if(trim($edesk_session->ID)=="" || trim($edesk_session->USUARIOID)=="" || trim($edesk_session->NIVELID)=="")
						{
							header('location:/');
							exit;		
						}
					
						###########################		
						##fin validacion sesion
						###########################		
						
						
						$busqueda=$this->_request->getParam('busqueda');
						if(isset($busqueda) && $busqueda!="")
							Zend_Layout::getMvcInstance()->assign('busqueda',$busqueda);
						else{
								if(isset($edesk_session->BUSQUEDA_INCI) && trim($edesk_session->BUSQUEDA_INCI)!="")
								{
									Zend_Layout::getMvcInstance()->assign('busqueda',$edesk_session->BUSQUEDA_INCI);
								}
							}
		
			
			
					
    }

    public function agregarincidenteAction()
    {
    
				$DB = Zend_Db_Table::getDefaultAdapter();
				$config = Zend_Registry::get('config');
				$functions = new ZendExt_RutinasPhp();
							
			
				###########################		
				##inicio validacion sesion
				###########################		
				
				$edesk_session = new Zend_Session_Namespace('edeskses');
	
				if(trim($edesk_session->ID)=="" || trim($edesk_session->USUARIOID)=="" || trim($edesk_session->NIVELID)=="")
				{
					header('location:/');
					exit;		
				}
			
				###########################		
				##fin validacion sesion
				###########################		
				
			
				$solicitudid=$this->_request->getParam('solicitudid');
				if(!isset($solicitudid) || $solicitudid=="")
				   $solicitudid=0;
				   
				if($solicitudid!=0)
				{
					
					$LABORATORIOID="";
					$LABORATORIODESCRIPCION="";
					$PRODUCTOID="";
					$DETALLESOLICITUD="";
					$NOMBRESOLICITANTE="";
					
					$sSQL = "	SELECT 
								s.SIS03_LABORATORIOID, 
								l.SIS03_LABORATORIODESCRIPCION, 
								s.SIS04_PRODUCTOID, 
								s.ED02_DETALLESOLICITUD, 
								s.ED02_NOMBRESOLICITANTE
								FROM 
								e_desk.ED02_SOLICITUD s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
								WHERE 
								s.ED02_SOLICITUDID = '$solicitudid' ";
								
								
							$rowset = $DB->fetchAll($sSQL);
	
							foreach($rowset as $row_datosQuery)
							{
							
									if(trim($row_datosQuery["SIS03_LABORATORIOID"])!="")
									{
											$LABORATORIOID=$row_datosQuery["SIS03_LABORATORIOID"];
											$LABORATORIODESCRIPCION=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
											$PRODUCTOID=$row_datosQuery["SIS04_PRODUCTOID"];
											$DETALLESOLICITUD=$row_datosQuery["ED02_DETALLESOLICITUD"];
											$NOMBRESOLICITANTE=$row_datosQuery["ED02_NOMBRESOLICITANTE"];
									}
	
							}
					
					}			
			
			
			
				//COLEGIOS
				////////////////////////////
				$sSQL="SELECT
						SIS03_LABORATORIOID,
						SIS03_LABORATORIODESCRIPCION
						FROM
						e_desk.SIS03_LABORATORIO 
						ORDER BY
						SIS03_LABORATORIOID";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS03_LABORATORIOID"])!="")
					{
						$ID=$row_datosQuery["SIS03_LABORATORIOID"];
						$datoscolegio["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
						$datoscolegio["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
					}								
				}
			
			
			
				//CLASIFICADOR
				////////////////////////////
				$sSQL="SELECT
						SIS07_CLASIFICADORID,
						SIS07_NIVELID,
						SIS07_CLASIFICADORDESCRIPCION
						FROM
						e_desk.SIS07_CLASIFICADOR
						ORDER BY
						SIS07_NIVELID";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS07_CLASIFICADORID"])!="")
					{
						$ID=$row_datosQuery["SIS07_CLASIFICADORID"];
						$datosclasificador["$ID"]["SIS07_CLASIFICADORID"]=$row_datosQuery["SIS07_CLASIFICADORID"];
						$datosclasificador["$ID"]["SIS07_NIVELID"]=$row_datosQuery["SIS07_NIVELID"];
						$datosclasificador["$ID"]["SIS07_CLASIFICADORDESCRIPCION"]=$row_datosQuery["SIS07_CLASIFICADORDESCRIPCION"];
					}								
				}
			
			
			
				//MODULO
				////////////////////////////
				$sSQL="SELECT SIS05_CODIGOMODULO FROM e_desk.SIS05_MODULO order by SIS05_CODIGOMODULO";
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS05_CODIGOMODULO"])!="")
					{
						$ID=$row_datosQuery["SIS05_CODIGOMODULO"];
						$datosmodulos["$ID"]["SIS05_CODIGOMODULO"]=$row_datosQuery["SIS05_CODIGOMODULO"];
					}								
				}
			
			
			
			
				//PRODUCTOS
				////////////////////////////
				$sSQL="SELECT
						SIS04_PRODUCTOID,
						SIS04_PRODUCTODESCRIPCION
						FROM
						e_desk.SIS04_PRODUCTO
						ORDER BY
						SIS04_PRODUCTOID";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS04_PRODUCTOID"])!="")
					{
						$ID=$row_datosQuery["SIS04_PRODUCTOID"];
						$datosproducto["$ID"]["SIS04_PRODUCTOID"]=$row_datosQuery["SIS04_PRODUCTOID"];
						$datosproducto["$ID"]["SIS04_PRODUCTODESCRIPCION"]=$row_datosQuery["SIS04_PRODUCTODESCRIPCION"];
					}								
				}
			
					
				
				//SECTOR
				////////////////////////////
				$sSQL="SELECT
						SIS01_SECTORID,
						SIS01_SECTORDESCRIPCION
						FROM
						e_desk.SIS01_SECTOR";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS01_SECTORID"])!="")
					{
						$ID=$row_datosQuery["SIS01_SECTORID"];
						$datossector["$ID"]["SIS01_SECTORID"]=$row_datosQuery["SIS01_SECTORID"];
						$datossector["$ID"]["SIS01_SECTORDESCRIPCION"]=$row_datosQuery["SIS01_SECTORDESCRIPCION"];
					}								
				}
						
				
					
				if(isset($datossector))
					Zend_Layout::getMvcInstance()->assign('datossector',$datossector);
					
					
					
			
				if(isset($datosproducto))
					Zend_Layout::getMvcInstance()->assign('datosproducto',$datosproducto);
			
			
				if(isset($datoscolegio))
					Zend_Layout::getMvcInstance()->assign('datoscolegio',$datoscolegio);
			
			
				if(isset($datosclasificador))
					Zend_Layout::getMvcInstance()->assign('datosclasificador',$datosclasificador);
		
		
				if(isset($datosmodulos))
					Zend_Layout::getMvcInstance()->assign('datosmodulos',$datosmodulos);
		
	
	
	
				if($solicitudid!=0)
				{
					Zend_Layout::getMvcInstance()->assign('solicitudid',$solicitudid);
					Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
					Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
					Zend_Layout::getMvcInstance()->assign('PRODUCTOID',$PRODUCTOID);
					Zend_Layout::getMvcInstance()->assign('DETALLESOLICITUD',$DETALLESOLICITUD);
					Zend_Layout::getMvcInstance()->assign('NOMBRESOLICITANTE',$NOMBRESOLICITANTE);
				}			
			
	
	
	
	
    }

    public function agregarincidenteprocessAction()
    {
    
	
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
					$edesk_session = new Zend_Session_Namespace('edeskses');
				
					$uploads = $config['ruta_path'];
					$uploads_public = $config['ruta_carpeta'];
					$separador = '/';
	
	
					$solicitudid=$this->_request->getParam('solicitudid');
					if(!isset($solicitudid) || $solicitudid=="")
					   $solicitudid=0;
				
	
	
	
					$colegio=$this->_request->getPost('colegio');
					$producto=$this->_request->getPost('producto');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
					$nombreapellido=$this->_request->getPost('nombreapellido');
					$telefono=$this->_request->getPost('telefono');
					$email=$this->_request->getPost('email');
					$prioridad=$this->_request->getPost('prioridad');
					$tipocontacto=$this->_request->getPost('tipocontacto');
					$nivelsoporte=$this->_request->getPost('nivelsoporte');
					$clasificadores=$this->_request->getPost('clasificadores');
					$modulo=$this->_request->getPost('modulo');
					$num_pregunta=$this->_request->getPost('num_pregunta');
					$num_alumnos=$this->_request->getPost('num_alumnos');
					$nivel=$this->_request->getPost('nivel');
					$estado=$this->_request->getPost('estado');
					$derivado=$this->_request->getPost('derivado');
					$sector=$this->_request->getPost('sector');
					$archivo=$this->_request->getPost('archivo');
					$accion=$this->_request->getPost('accion');
			
					$porciones = explode("|",$colegio);
					$porciones_clasificador = explode("|",$clasificadores);
					$clasificadores_final = $porciones_clasificador[0];
				
					$porciones_fecha = explode("/",$calendario);
					$calendario_ingles=$porciones_fecha[2]."-".$porciones_fecha[1]."-".$porciones_fecha[0];
							
					
			
					if($accion=="grabar")
					{
					
								  	$existe=0;
								  	$existe_c=0;
						
									if(isset($porciones[0]) && trim($porciones[0])!="")
									{
		
											$ELCOLEGIO=trim($porciones[0]);
					
											$sSQL="SELECT
													SIS03_LABORATORIOID 
													FROM
													e_desk.SIS03_LABORATORIO 
													WHERE 
													SIS03_LABORATORIOID = '".trim($porciones[0])."'";
									
							
											  try {
									
													$rowset = $DB->fetchAll($sSQL);
													if (count($rowset) > 0) 
													{
														$existe=1;
													}
									
												} catch (Zend_Exception $e) {
									
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(13)";
													exit;	
											
												}
			
									}		
		
					
									if(isset($porciones_clasificador[0]) && trim($porciones_clasificador[0])!="")
									{
		
											$sSQL="SELECT
													SIS07_CLASIFICADORID
													FROM
													e_desk.SIS07_CLASIFICADOR 
													WHERE 
													SIS07_CLASIFICADORID = '".trim($porciones_clasificador[0])."'";
											
											  try {
									
													$rowset = $DB->fetchAll($sSQL);
													if (count($rowset) > 0) 
													{
														$existe_c=1;
													}
									
												} catch (Zend_Exception $e) {
									
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(34)";
													exit;	
											
												}
			
									}		
		
		
									//existe laboratorio
									/////////////////////
									if($existe==1 && $existe_c==1)
									{
			
												 $path = $uploads;
												 $fileName = null; 
												 $fileRuta = null; 
												 $fileType = null; 
											
												 $upload = new Zend_File_Transfer_Adapter_Http();
												 $upload->setDestination($path);
							
							 			
										
												####INICIO ARCHIVOS#########
												####INICIO ARCHIVOS#########
												
												 $files = $upload->getFileInfo();
							
												 foreach ($files as $file => $info) 
												 {
							
													 if (!$upload->isUploaded($file)) {
												
													
													 } else {
												
															 $fileName = str_replace(' ', '_', strtolower($info['name']));
															 $upload->addFilter('Rename', array('target' => $path.$separador.$fileName, 'overwrite' => true));
															 $fileRuta = $uploads_public.$separador.$fileName;
															 $fileType = $info['type'];	
															 
															 
													 }
							
													 if (!$upload->isValid($file)) {
											
												
															echo("KO|Archivo invalido");
															exit;	
											
													 } else {
							
																 if ($upload->receive($info['name'])) 
																 {
													
													
																 }
							
													 }
							
												 }
												
												 
												####FIN ARCHIVOS#########
												####FIN ARCHIVOS#########
					
					
												//insertamos con try
												$data = array(
														'SIS03_LABORATORIOID' => $ELCOLEGIO,
												  		'SIS04_PRODUCTOID' => $producto,
												  		'ED03_FECHATICKET' => $calendario_ingles,
												  		'ED03_NOMBRESOLICITANTE' => $nombreapellido,
												  		'ED03_TELEFONOSOLICITANTE' => $telefono,
												  		'ED03_EMAILSOLICITANTE' => $email,
												  		'ED03_PRIORIDAD' => $prioridad,
												  		'ED03_DETALLETICKET' => $detalle,
												  		'ED03_TIPOCONTACTO' => $tipocontacto,
												  		'ED03_NIVELSOPORTE' => $nivelsoporte,
												  		'SIS07_CLASIFICADORID' => $clasificadores_final, 
												  		'ED03_ARCHIVOADJUNTO' => $fileRuta,
												  		'ED03_NOMBREARCHIVOADJUNTO' => $fileName,
												  		'ED03_TIPOARCHIVOADJUNTO' => $fileType,
												  		'ED03_NUMALUMNOSAFECTADOS' => $num_alumnos,
												  		'ED03_NIVELDELPROGRAMA' => $nivel,
												  		'SIS05_CODIGOMODULO' => $modulo,
												  		'ED03_NUMEJERCICIO' => $num_pregunta,
												  		'ED03_ESTADO' => $estado,
												  		'ED03_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
														'ED01_USUARIOID' => $edesk_session->USUARIOID,
												  		'ED03_DERIVADO' => $derivado,
														'SIS01_SECTORID' => $sector
														
												 );
												  		
					
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED03_TICKET', $data);
							
													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(14)";
													exit;	
												}


												#################################
												##RESCATAMOS ULTIMO ID INGRESADO
												#################################
												$sSQL="SELECT max(ED03_TICKETID) as ingresado FROM e_desk.ED03_TICKET";
												$rowset = $DB->fetchAll($sSQL);
												
												$nueva_solicitud=0;
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ingresado"])!="")
													{
														$nueva_solicitud=$row_datosQuery["ingresado"];
													}
												}	
																

												######################################
												##INICIO ASOCIACION INCIDENTE SOLICITUD
												######################################

												if($solicitudid!=0)
												{
													$data_solicitud_ticket = array(
														'ED02_SOLICITUDID' => $solicitudid,
														'ED03_TICKETID' => $nueva_solicitud
													);
											
											
													$data_solicitud_ticket_estado = array(
														'ED02_ESTADO' => 'DERIVADO'
													);
											
											
													$where_estado['ED02_SOLICITUDID = ?'] = $solicitudid;
											
												}	

												######################################
												##FIN ASOCIACION INCIDENTE SOLICITUD
												######################################




												#################################
												##ASOCIACION A USUARIO
												#################################
								
												$data_actividad = array(
													'ED01_USUARIOID' => $edesk_session->USUARIOID,
													'ED08_ACCION' => 'INSERTAR TICKET INCIDENTE',
													'ED08_MASINFO' => 'NUM:'.$nueva_solicitud
												);


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
								
													if($solicitudid!=0)
													{
														$DB->insert('e_desk.ED14_SOLICITUD_TICKET',$data_solicitud_ticket);
														$DB->update('e_desk.ED02_SOLICITUD', $data_solicitud_ticket_estado, $where_estado);
													}	

													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(15)";
													exit;	
												}





												#################################
												##INICIO NOTIFICACIONES
												#################################
											
												$email="";
												
												$destinadatarios=$functions->notificaciones_incidentes_seg($nueva_solicitud,$edesk_session->USUARIOID,$edesk_session->EMAIL);	
												if($destinadatarios!="0")
												{
													
														if(isset($destinadatarios[0]))
														{
															foreach($destinadatarios[0] as $clave => $valor)
															{
																$MAILS_A_NOTIFICAR["$valor"]=$valor;
																if($email=="")
																	$email=$valor;
																else
																	$email.=",".$valor;
															}
														}
					
					
														if(isset($destinadatarios[1]))
														{
															foreach($destinadatarios[1] as $clave => $valor)
															{
																if(!isset($USUARIOS_A_NOTIFICAR["$valor"]))
																{
																
																	$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
																	$data_usuario[$valor] = array(
																			  'ED01_USUARIOID' => $valor,
																			  'ED03_TICKETID' => $nueva_solicitud,
																			  'ED10_TIPONOTIFICACION' => '1',
																			  'ED10_LEIDO' => '0',
																			  'ED10_FECHANOTIFICACION' => date("Ymdhis")
																		);
																}
															}
														}
					
					
					
					
												}

												#################################
												##FIN NOTIFICACIONES
												#################################
							

												
							
							
												#################################
												##ENVIO DE EMAILS
												#################################
												if($email!="")
												{
												$subject="INTERNO - CREACION DE INCIDENTE E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha generado el incidente numero : <strong>$nueva_solicitud</strong> <br><br>
													   por el usuario E-DESK Login : ($edesk_session->USUARIOID) <br><br>
													   Atte.<br>Equipo Compumat.";
												
							
												
													$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
												}							
								
					
												#############################
												##FIN MAIL CREACION USUARIO
												#############################


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();


													//INICIO NOTIFICACIONES USUARIO
													/////////////////////////////////
													if(isset($USUARIOS_A_NOTIFICAR) && count($USUARIOS_A_NOTIFICAR)>0)
													{
														foreach($USUARIOS_A_NOTIFICAR as $clave => $valor)
														{
															$DB->insert('e_desk.ED10_USUARIO_NOTIFICADO_TICKET',$data_usuario[$valor]);
														}
													}												
													//FIN NOTIFICACIONES USUARIO
													/////////////////////////////////


													$DB->commit();
							
													echo("OK|");
													exit;
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(16)";
													exit;	
												}



									}else{
									
											echo("KO|No existe el colegio / clasificador a asociar a la solicitud");
											exit;

									
									}


					}else{
					
								echo("KO|Accion invalida");
								exit;
					}		
			
	
	
	
    }

    public function editarincidenteAction()
    {
    
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
					
					$incidenteid=$this->_request->getPost('incidenteid');
			
					$LABORATORIOID="";
					$LABORATORIODESCRIPCION="";
					$PRODUCTOID="";
					$FECHATICKET="";
					$NOMBRESOLICITANTE="";
					$TELEFONOSOLICITANTE="";
					$EMAILSOLICITANTE="";
					$PRIORIDAD="";
					$DETALLETICKET="";
					$TIPOCONTACTO="";
					$NIVELSOPORTE="";
					$CLASIFICADORID=""; 
					$CLASIFICADORDESCRIPCION=""; 
					$ARCHIVOADJUNTO="";
					$NOMBREARCHIVOADJUNTO="";
					$TIPOARCHIVOADJUNTO="";
					$NUMALUMNOSAFECTADOS="";
					$NIVELDELPROGRAMA="";
					$CODIGOMODULO="";
					$NUMEJERCICIO="";
					$ESTADO="";			
				
				
					//validamos que no exista usuario
					$sSQL = "	SELECT 
								s.ED03_TICKETID,
								s.SIS03_LABORATORIOID,
								l.SIS03_LABORATORIODESCRIPCION, 
								s.SIS04_PRODUCTOID,
								DATE_FORMAT(s.ED03_FECHATICKET, '%d/%m/%Y') as FECHATICKET,
								s.ED03_NOMBRESOLICITANTE,
								s.ED03_TELEFONOSOLICITANTE,
								s.ED03_EMAILSOLICITANTE,
								s.ED03_PRIORIDAD,
								s.ED03_DETALLETICKET,
								s.ED03_TIPOCONTACTO,
								s.ED03_NIVELSOPORTE,
								s.SIS07_CLASIFICADORID, 
								c.SIS07_CLASIFICADORDESCRIPCION,
								s.ED03_ARCHIVOADJUNTO,
								s.ED03_NOMBREARCHIVOADJUNTO,
								s.ED03_TIPOARCHIVOADJUNTO,
								s.ED03_NUMALUMNOSAFECTADOS,
								s.ED03_NIVELDELPROGRAMA,
								s.SIS05_CODIGOMODULO,
								s.ED03_NUMEJERCICIO,
								s.ED03_ESTADO,
								s.ED03_DERIVADO,
								s.SIS01_SECTORID 
								FROM 
								e_desk.ED03_TICKET s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
								LEFT JOIN
								e_desk.SIS07_CLASIFICADOR c ON s.SIS07_CLASIFICADORID=c.SIS07_CLASIFICADORID
								WHERE 
								s.ED03_TICKETID = '$incidenteid' ";
								
								
							$rowset = $DB->fetchAll($sSQL);



							$USUARIOSELECCIONADO=0;
		

							foreach($rowset as $row_datosQuery)
							{
							
									if(trim($row_datosQuery["ED03_TICKETID"])!="")
									{
									
											$LABORATORIOID=$row_datosQuery["SIS03_LABORATORIOID"];
											$LABORATORIODESCRIPCION=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
											$PRODUCTOID=$row_datosQuery["SIS04_PRODUCTOID"];
											$FECHATICKET=$row_datosQuery["FECHATICKET"];
											$NOMBRESOLICITANTE=$row_datosQuery["ED03_NOMBRESOLICITANTE"];
											$TELEFONOSOLICITANTE=$row_datosQuery["ED03_TELEFONOSOLICITANTE"];
											$EMAILSOLICITANTE=$row_datosQuery["ED03_EMAILSOLICITANTE"];
											$PRIORIDAD=$row_datosQuery["ED03_PRIORIDAD"];
											$DETALLETICKET=$row_datosQuery["ED03_DETALLETICKET"];
											$TIPOCONTACTO=$row_datosQuery["ED03_TIPOCONTACTO"];
											$NIVELSOPORTE=$row_datosQuery["ED03_NIVELSOPORTE"];
											$CLASIFICADORID=$row_datosQuery["SIS07_CLASIFICADORID"];
											$CLASIFICADORDESCRIPCION=$row_datosQuery["SIS07_CLASIFICADORDESCRIPCION"];
											$ARCHIVOADJUNTO=$row_datosQuery["ED03_ARCHIVOADJUNTO"];
											$NOMBREARCHIVOADJUNTO=$row_datosQuery["ED03_NOMBREARCHIVOADJUNTO"];
											$TIPOARCHIVOADJUNTO=$row_datosQuery["ED03_TIPOARCHIVOADJUNTO"];
											$NUMALUMNOSAFECTADOS=$row_datosQuery["ED03_NUMALUMNOSAFECTADOS"];
											$NIVELDELPROGRAMA=$row_datosQuery["ED03_NIVELDELPROGRAMA"];
											$CODIGOMODULO=$row_datosQuery["SIS05_CODIGOMODULO"];
											$NUMEJERCICIO=$row_datosQuery["ED03_NUMEJERCICIO"];
											$ESTADO=$row_datosQuery["ED03_ESTADO"];		
											$USUARIOSELECCIONADO=$row_datosQuery["ED03_DERIVADO"];
											$SECTOR=$row_datosQuery["SIS01_SECTORID"];
				
									}

							}
							
		
				//COLEGIOS
				////////////////////////////
				$sSQL="SELECT
						SIS03_LABORATORIOID,
						SIS03_LABORATORIODESCRIPCION
						FROM
						e_desk.SIS03_LABORATORIO 
						ORDER BY
						SIS03_LABORATORIOID";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS03_LABORATORIOID"])!="")
					{
						$ID=$row_datosQuery["SIS03_LABORATORIOID"];
						$datoscolegio["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
						$datoscolegio["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
					}								
				}
			
			
			
			
				//CLASIFICADOR
				////////////////////////////
				$sSQL="SELECT
						SIS07_CLASIFICADORID,
						SIS07_NIVELID,
						SIS07_CLASIFICADORDESCRIPCION
						FROM
						e_desk.SIS07_CLASIFICADOR
						ORDER BY
						SIS07_NIVELID";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS07_CLASIFICADORID"])!="")
					{
						$ID=$row_datosQuery["SIS07_CLASIFICADORID"];
						$datosclasificador["$ID"]["SIS07_CLASIFICADORID"]=$row_datosQuery["SIS07_CLASIFICADORID"];
						$datosclasificador["$ID"]["SIS07_NIVELID"]=$row_datosQuery["SIS07_NIVELID"];
						$datosclasificador["$ID"]["SIS07_CLASIFICADORDESCRIPCION"]=$row_datosQuery["SIS07_CLASIFICADORDESCRIPCION"];
					}								
				}
			
			
			
				//MODULO
				////////////////////////////
				$sSQL="SELECT SIS05_CODIGOMODULO FROM e_desk.SIS05_MODULO order by SIS05_CODIGOMODULO";
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS05_CODIGOMODULO"])!="")
					{
						$ID=$row_datosQuery["SIS05_CODIGOMODULO"];
						$datosmodulos["$ID"]["SIS05_CODIGOMODULO"]=$row_datosQuery["SIS05_CODIGOMODULO"];
					}								
				}
			
			
			
			
				//PRODUCTOS
				////////////////////////////
				$sSQL="SELECT
						SIS04_PRODUCTOID,
						SIS04_PRODUCTODESCRIPCION
						FROM
						e_desk.SIS04_PRODUCTO
						ORDER BY
						SIS04_PRODUCTOID";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS04_PRODUCTOID"])!="")
					{
						$ID=$row_datosQuery["SIS04_PRODUCTOID"];
						$datosproducto["$ID"]["SIS04_PRODUCTOID"]=$row_datosQuery["SIS04_PRODUCTOID"];
						$datosproducto["$ID"]["SIS04_PRODUCTODESCRIPCION"]=$row_datosQuery["SIS04_PRODUCTODESCRIPCION"];
					}								
				}
			
			
			
				//SECTOR
				////////////////////////////
				$sSQL="SELECT
						SIS01_SECTORID,
						SIS01_SECTORDESCRIPCION
						FROM
						e_desk.SIS01_SECTOR";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS01_SECTORID"])!="")
					{
						$ID=$row_datosQuery["SIS01_SECTORID"];
						$datossector["$ID"]["SIS01_SECTORID"]=$row_datosQuery["SIS01_SECTORID"];
						$datossector["$ID"]["SIS01_SECTORDESCRIPCION"]=$row_datosQuery["SIS01_SECTORDESCRIPCION"];
					}								
				}
						
				
					
				if(isset($datossector))
					Zend_Layout::getMvcInstance()->assign('datossector',$datossector);
					

					
			
				if(isset($datosproducto))
					Zend_Layout::getMvcInstance()->assign('datosproducto',$datosproducto);
			
			
				if(isset($datoscolegio))
					Zend_Layout::getMvcInstance()->assign('datoscolegio',$datoscolegio);
			
			
				if(isset($datosclasificador))
					Zend_Layout::getMvcInstance()->assign('datosclasificador',$datosclasificador);
		
		
				if(isset($datosmodulos))
					Zend_Layout::getMvcInstance()->assign('datosmodulos',$datosmodulos);
		
	
			
		
				Zend_Layout::getMvcInstance()->assign('INCIDENTEID',$incidenteid);
				Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
				Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
				Zend_Layout::getMvcInstance()->assign('PRODUCTOID',$PRODUCTOID);
				Zend_Layout::getMvcInstance()->assign('FECHATICKET',$FECHATICKET);
				Zend_Layout::getMvcInstance()->assign('NOMBRESOLICITANTE',$NOMBRESOLICITANTE);
				Zend_Layout::getMvcInstance()->assign('TELEFONOSOLICITANTE',$TELEFONOSOLICITANTE);
				Zend_Layout::getMvcInstance()->assign('EMAILSOLICITANTE',$EMAILSOLICITANTE);
				Zend_Layout::getMvcInstance()->assign('PRIORIDAD',$PRIORIDAD);
				Zend_Layout::getMvcInstance()->assign('DETALLETICKET',$DETALLETICKET);
				Zend_Layout::getMvcInstance()->assign('TIPOCONTACTO',$TIPOCONTACTO);
				Zend_Layout::getMvcInstance()->assign('NIVELSOPORTE',$NIVELSOPORTE);
				Zend_Layout::getMvcInstance()->assign('SECTOR',$SECTOR);
				Zend_Layout::getMvcInstance()->assign('CLASIFICADORID',$CLASIFICADORID);
				Zend_Layout::getMvcInstance()->assign('CLASIFICADORDESCRIPCION',$CLASIFICADORDESCRIPCION);
				Zend_Layout::getMvcInstance()->assign('ARCHIVOADJUNTO',$ARCHIVOADJUNTO);
				Zend_Layout::getMvcInstance()->assign('NOMBREARCHIVOADJUNTO',$NOMBREARCHIVOADJUNTO);
				Zend_Layout::getMvcInstance()->assign('TIPOARCHIVOADJUNTO',$TIPOARCHIVOADJUNTO);
				Zend_Layout::getMvcInstance()->assign('NUMALUMNOSAFECTADOS',$NUMALUMNOSAFECTADOS);
				Zend_Layout::getMvcInstance()->assign('NIVELDELPROGRAMA',$NIVELDELPROGRAMA);
				Zend_Layout::getMvcInstance()->assign('CODIGOMODULO',$CODIGOMODULO);
				Zend_Layout::getMvcInstance()->assign('NUMEJERCICIO',$NUMEJERCICIO);
				Zend_Layout::getMvcInstance()->assign('ESTADO',$ESTADO);		
				Zend_Layout::getMvcInstance()->assign('USUARIOSELECCIONADO',$USUARIOSELECCIONADO);		
				
				
				
    }

    public function editarincidenteprocessAction()
    {
    

			
					$this->_helper->layout->disableLayout();
				
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
					$edesk_session = new Zend_Session_Namespace('edeskses');
					$uploads = $config['ruta_path'];
					$uploads_public = $config['ruta_carpeta'];
					$separador = '/';
	

					$incidenteid=$this->_request->getPost('incidenteid');
					$colegio=$this->_request->getPost('colegio');
					$producto=$this->_request->getPost('producto');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
					$nombreapellido=$this->_request->getPost('nombreapellido');
					$telefono=$this->_request->getPost('telefono');
					$email=$this->_request->getPost('email');
					$prioridad=$this->_request->getPost('prioridad');
					$tipocontacto=$this->_request->getPost('tipocontacto');
					$nivelsoporte=$this->_request->getPost('nivelsoporte');
					$clasificadores=$this->_request->getPost('clasificadores');
					$modulo=$this->_request->getPost('modulo');
					$num_pregunta=$this->_request->getPost('num_pregunta');
					$num_alumnos=$this->_request->getPost('num_alumnos');
					$nivel=$this->_request->getPost('nivel');
					$estado=$this->_request->getPost('estado');
					$derivado=$this->_request->getPost('derivado');
					$sector=$this->_request->getPost('sector');
					$archivo=$this->_request->getPost('archivo');
					$accion=$this->_request->getPost('accion');
			
					$porciones = explode("|",$colegio);
					$porciones_clasificador = explode("|",$clasificadores);
					$clasificadores_final = $porciones_clasificador[0];
				
					$porciones_fecha = explode("/",$calendario);
					$calendario_ingles=$porciones_fecha[2]."-".$porciones_fecha[1]."-".$porciones_fecha[0];
							
					
			
					if($accion=="grabar")
					{
					
								  	$existe=0;
									$existe_c=0;
						
						
									if(isset($porciones[0]) && trim($porciones[0])!="")
									{
		
											$ELCOLEGIO=trim($porciones[0]);
					
											$sSQL="SELECT
													SIS03_LABORATORIOID 
													FROM
													e_desk.SIS03_LABORATORIO 
													WHERE 
													SIS03_LABORATORIOID = '".trim($porciones[0])."'";
									
							
											  try {
									
													$rowset = $DB->fetchAll($sSQL);
													if (count($rowset) > 0) 
													{
														$existe=1;
													}
									
												} catch (Zend_Exception $e) {
									
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(17)";
													exit;	
											
												}
			
									}		
		
									if(isset($porciones_clasificador[0]) && trim($porciones_clasificador[0])!="")
									{
		
											$sSQL="SELECT
													SIS07_CLASIFICADORID
													FROM
													e_desk.SIS07_CLASIFICADOR 
													WHERE 
													SIS07_CLASIFICADORID = '".trim($porciones_clasificador[0])."'";
									
							
											  try {
									
													$rowset = $DB->fetchAll($sSQL);
													if (count($rowset) > 0) 
													{
														$existe_c=1;
													}
									
												} catch (Zend_Exception $e) {
									
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(35)";
													exit;	
											
												}
			
									}		
		
		
									//existe laboratorio
									/////////////////////
									if($existe==1 && $existe_c==1)
									{
			
												 $path = $uploads;
												 $fileName = null; 
												 $fileRuta = null; 
												 $fileType = null; 
											
												 $upload = new Zend_File_Transfer_Adapter_Http();
												 $upload->setDestination($path);
							
							 			
										
												####INICIO ARCHIVOS#########
												####INICIO ARCHIVOS#########
												
												 $files = $upload->getFileInfo();
							
												 foreach ($files as $file => $info) 
												 {
							
													 if (!$upload->isUploaded($file)) {
												
													
													 } else {
												
															 $fileName = str_replace(' ', '_', strtolower($info['name']));
															 $upload->addFilter('Rename', array('target' => $path.$separador.$fileName, 'overwrite' => true));
															 $fileRuta = $uploads_public.$separador.$fileName;
															 $fileType = $info['type'];	
															 
															 
													 }
							
													 if (!$upload->isValid($file)) {
											
												
															echo("KO|Archivo invalido");
															exit;	
											
													 } else {
							
																 if ($upload->receive($info['name'])) 
																 {
													
													
																 }
							
													 }
							
												 }
												
												 
												####FIN ARCHIVOS#########
												####FIN ARCHIVOS#########
					
												//actualizamos con try

												if($fileName)
												{
														$data = array(
															'SIS03_LABORATORIOID' => $ELCOLEGIO,
															'SIS04_PRODUCTOID' => $producto,
															'ED03_FECHATICKET' => $calendario_ingles,
															'ED03_NOMBRESOLICITANTE' => $nombreapellido,
															'ED03_TELEFONOSOLICITANTE' => $telefono,
															'ED03_EMAILSOLICITANTE' => $email,
															'ED03_PRIORIDAD' => $prioridad,
															'ED03_DETALLETICKET' => $detalle,
															'ED03_TIPOCONTACTO' => $tipocontacto,
															'ED03_NIVELSOPORTE' => $nivelsoporte,
															'SIS07_CLASIFICADORID' => $clasificadores_final, 
															'ED03_ARCHIVOADJUNTO' => $fileRuta,
															'ED03_NOMBREARCHIVOADJUNTO' => $fileName,
															'ED03_TIPOARCHIVOADJUNTO' => $fileType,
															'ED03_NUMALUMNOSAFECTADOS' => $num_alumnos,
															'ED03_NIVELDELPROGRAMA' => $nivel,
															'SIS05_CODIGOMODULO' => $modulo,
															'ED03_NUMEJERCICIO' => $num_pregunta,
															'ED03_ESTADO' => $estado,
															'ED03_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
															'ED03_DERIVADO' => $derivado,
															'SIS01_SECTORID' => $sector
														);
												
			
												}else{
												
														$data = array(
															'SIS03_LABORATORIOID' => $ELCOLEGIO,
															'SIS04_PRODUCTOID' => $producto,
															'ED03_FECHATICKET' => $calendario_ingles,
															'ED03_NOMBRESOLICITANTE' => $nombreapellido,
															'ED03_TELEFONOSOLICITANTE' => $telefono,
															'ED03_EMAILSOLICITANTE' => $email,
															'ED03_PRIORIDAD' => $prioridad,
															'ED03_DETALLETICKET' => $detalle,
															'ED03_TIPOCONTACTO' => $tipocontacto,
															'ED03_NIVELSOPORTE' => $nivelsoporte,
															'SIS07_CLASIFICADORID' => $clasificadores_final, 
															'ED03_NUMALUMNOSAFECTADOS' => $num_alumnos,
															'ED03_NIVELDELPROGRAMA' => $nivel,
															'SIS05_CODIGOMODULO' => $modulo,
															'ED03_NUMEJERCICIO' => $num_pregunta,
															'ED03_ESTADO' => $estado,
															'ED03_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
															'ED03_DERIVADO' => $derivado,
															'SIS01_SECTORID' => $sector
														);
													
												}


												$where['ED03_TICKETID = ?'] = $incidenteid;
													
											
												$data_actividad = array(
													'ED01_USUARIOID' => $edesk_session->USUARIOID,
													'ED08_ACCION' => 'EDITAR TICKET INCIDENTE',
													'ED08_MASINFO' => 'NUM:'.$incidenteid
												);
		
															
												
												
												//INICIO INCIDENTE ASOCIADO A SOLICITUD
												//////////////////////////////////////
												$sSQL="SELECT ED02_SOLICITUDID FROM e_desk.ED14_SOLICITUD_TICKET WHERE ED03_TICKETID='$incidenteid' ";
												$rowset = $DB->fetchAll($sSQL);
												$SOLICITUD_ASOCIADA=0;
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
													{
														$SOLICITUD_ASOCIADA=$row_datosQuery["ED02_SOLICITUDID"];
													}								
												}
						
												if($SOLICITUD_ASOCIADA!=0)
												{													
													$data_solicitud_ticket_estado = array(
														'ED02_ESTADO' => $estado
													);
											
											
													$where_estado['ED02_SOLICITUDID = ?'] = $SOLICITUD_ASOCIADA;
												}
															
												//FIN INCIDENTE ASOCIADO A SOLICITUD
												//////////////////////////////////////
															
																				

												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->update('e_desk.ED03_TICKET', $data, $where);
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
													if($SOLICITUD_ASOCIADA!=0 && $estado!="PENDIENTE")
													{													
														$DB->update('e_desk.ED02_SOLICITUD', $data_solicitud_ticket_estado, $where_estado);
													}	
											
											
											
													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(18)";
													exit;	
												}


																
												#################################
												##INICIO NOTIFICACIONES
												#################################
											
												$email="";
												
												$destinadatarios=$functions->notificaciones_incidentes_seg($incidenteid,$edesk_session->USUARIOID,$edesk_session->EMAIL);	
												if($destinadatarios!="0")
												{
													
														if(isset($destinadatarios[0]))
														{
															foreach($destinadatarios[0] as $clave => $valor)
															{
																$MAILS_A_NOTIFICAR["$valor"]=$valor;
																if($email=="")
																	$email=$valor;
																else
																	$email.=",".$valor;
															}
														}
					
					
														if(isset($destinadatarios[1]))
														{
															foreach($destinadatarios[1] as $clave => $valor)
															{
																
																if(!isset($USUARIOS_A_NOTIFICAR["$valor"]))
																{
																	$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
																	$data_usuario[$valor] = array(
																			  'ED01_USUARIOID' => $valor,
																			  'ED03_TICKETID' => $incidenteid,
																			  'ED10_TIPONOTIFICACION' => '1',
																			  'ED10_LEIDO' => '0',
																			  'ED10_FECHANOTIFICACION' => date("Ymdhis")
																		);
																}	
															}
														}
					
					
					
					
												}

												#################################
												##FIN NOTIFICACIONES
												#################################
							

											
							
							
												#################################
												##ENVIO DE EMAILS
												#################################
												if($email!="")
												{
												$subject="INTERNO - ACTUALIZACION DE INCIDENTE E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha actualizado el incidente numero : <strong>$incidenteid</strong> <br><br>
													   por el usuario E-DESK Login : ($edesk_session->USUARIOID) <br><br>
													   Atte.<br>Equipo Compumat.";
												
							
													$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
												}							
								
					
												#############################
												##FIN MAIL CREACION USUARIO
												#############################


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();


													//INICIO NOTIFICACIONES USUARIO
													/////////////////////////////////
													if(isset($USUARIOS_A_NOTIFICAR) && count($USUARIOS_A_NOTIFICAR)>0)
													{
														foreach($USUARIOS_A_NOTIFICAR as $clave => $valor)
														{
															$DB->insert('e_desk.ED10_USUARIO_NOTIFICADO_TICKET',$data_usuario[$valor]);
														}
													}												
													//FIN NOTIFICACIONES USUARIO
													/////////////////////////////////


													$DB->commit();
							
													echo("OK|");
													exit;
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(19)";
													exit;	
												}

											
											

									}else{
									
											echo("KO|No existe el colegio / clasificador a asociar a la solicitud");
											exit;

									
									}


					}else{
					
								echo("KO|Accion invalida");
								exit;
					}		
			
	
	
		
	
    }

    public function eliminarincidenteAction()
    {
    
						$this->_helper->layout->disableLayout();
				
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
				
						$edesk_session = new Zend_Session_Namespace('edeskses');
	
						$incidenteid=$this->_request->getPost('incidenteid');
					
						$where['ED03_TICKETID = ?'] = $incidenteid;
		
		
						$data_actividad = array(
										'ED01_USUARIOID' => $edesk_session->USUARIOID,
										'ED08_ACCION' => 'ELIMINAR TICKET INCIDENTE',
										'ED08_MASINFO' => 'NUM:'.$incidenteid
									);

			
						try {

							$n = $DB->delete("e_desk.ED03_TICKET", $where);
							$n3 = $DB->delete("e_desk.ED14_SOLICITUD_TICKET", $where);
							$n4 = $DB->delete("e_desk.ED04_SEGUIMIENTO_TICKET", $where);
							$n5 = $DB->delete("e_desk.ED10_USUARIO_NOTIFICADO_TICKET", $where);
							$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
							
							//FALTA AQUI LOG DE ACCION

							echo "Incidente eliminado correctamente...";

						} catch (Zend_Exception $e) {

							//echo $e->getMessage();
							echo "KO|Se ha producido un error..(20)";

						}
		
	
	
    }

    public function listarincidentesAction()
    {
    
	
	
						$this->_helper->layout->disableLayout();
	
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
					
					
						$CONTADOR_INI=1;
						$CONTADOR_FIN=15;
						$PAGINA=1;

						$lapagina=$this->_request->getPost('pagina');
						$busqueda=$this->_request->getPost('busqueda');
						$edesk_session->BUSQUEDA_INCI=$busqueda;
						
							
										
						if($lapagina!="")
						{
								$PAGINA=$lapagina;
								$CONTADOR_INI=(($lapagina*15)+1)-15;
								$CONTADOR_FIN=($lapagina*15);
						}
			
			
			
			
					//INICIO INCIDENTES ASOCIADAS A SOLICITUD
					//////////////////////////////////////////
					
					$sSQL="SELECT ED02_SOLICITUDID,ED03_TICKETID FROM e_desk.ED14_SOLICITUD_TICKET";
					$rowset = $DB->fetchAll($sSQL);
					foreach($rowset as $row_datosQuery)
					{
						if(trim($row_datosQuery["ED03_TICKETID"])!="")
						{
							$IDENTIFICA=$row_datosQuery["ED03_TICKETID"];
							$matriz_match_solicitud_incidente[$IDENTIFICA]=$row_datosQuery["ED02_SOLICITUDID"];
						}								
					}

					//FIN INCIDENTES ASOCIADAS A SOLICITUD
					//////////////////////////////////////////
					
				

					//INICIO INCIDENTES ASOCIADAS A ASISTENCIAS
					//////////////////////////////////////////
					
					$sSQL="SELECT ED03_TICKETID,ED05_ASISTENCIAID FROM e_desk.ED13_TICKET_ASISTENCIA_TECNICA";
					$rowset = $DB->fetchAll($sSQL);
					foreach($rowset as $row_datosQuery)
					{
						if(trim($row_datosQuery["ED03_TICKETID"])!="")
						{
							$IDENTIFICA=$row_datosQuery["ED03_TICKETID"];
							$matriz_match_asistencia_incidente[$IDENTIFICA]=$row_datosQuery["ED05_ASISTENCIAID"];
						}								
					}


					//FIN INCIDENTES ASOCIADAS A SOLICITUD
					//////////////////////////////////////////

			
			
			
						$CONTADOR_FILAS=0;
					
						//INCIDENTES
						////////////////////////////
						$sSQL="SELECT 
								s.ED03_TICKETID,
								s.SIS03_LABORATORIOID,
								l.SIS03_LABORATORIODESCRIPCION, 
								s.SIS04_PRODUCTOID,
								DATE_FORMAT(s.ED03_FECHATICKET, '%d/%m/%Y') as FECHATICKET,
								s.ED03_NOMBRESOLICITANTE,
								s.ED03_TELEFONOSOLICITANTE,
								s.ED03_EMAILSOLICITANTE,
								s.ED03_PRIORIDAD,
								s.ED03_DETALLETICKET,
								s.ED03_TIPOCONTACTO,
								s.ED03_NIVELSOPORTE,
								s.SIS07_CLASIFICADORID, 
								c.SIS07_CLASIFICADORDESCRIPCION,
								s.ED03_ARCHIVOADJUNTO,
								s.ED03_NOMBREARCHIVOADJUNTO,
								s.ED03_TIPOARCHIVOADJUNTO,
								s.ED03_NUMALUMNOSAFECTADOS,
								s.ED03_NIVELDELPROGRAMA,
								s.SIS05_CODIGOMODULO,
								s.ED03_NUMEJERCICIO,
								s.ED03_ESTADO,
								DATE_FORMAT(s.ED03_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION,
								s.ED03_DERIVADO,
								s.SIS01_SECTORID 
								FROM 
								e_desk.ED03_TICKET s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
								LEFT JOIN
								e_desk.SIS07_CLASIFICADOR c ON s.SIS07_CLASIFICADORID=c.SIS07_CLASIFICADORID ";
																
								
						if(trim($busqueda)!="")
								$sSQL.=" WHERE s.ED03_ESTADO like '%".$busqueda."%' OR s.SIS03_LABORATORIOID='".$busqueda."' OR s.ED03_TICKETID='".$busqueda."' OR s.SIS05_CODIGOMODULO='".$busqueda."' ";		

					
									
						$sSQL.=" ORDER BY ED03_TICKETID desc ";
					
						
					 	$rowset = $DB->fetchAll($sSQL);
					
						$ID_FILAS="0";
					
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED03_TICKETID"])!="")
							{
								$CONTADOR_FILAS++;
								
								if($CONTADOR_FILAS>=$CONTADOR_INI && $CONTADOR_FILAS<=$CONTADOR_FIN)
								{
						
										$ID=$row_datosQuery["ED03_TICKETID"];
                                        $ID_FILAS.=",".$ID;
										$datossolicitudes["$ID"]["ED03_TICKETID"]=$row_datosQuery["ED03_TICKETID"];
										$datossolicitudes["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
										$datossolicitudes["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
										$datossolicitudes["$ID"]["SIS04_PRODUCTOID"]=$row_datosQuery["SIS04_PRODUCTOID"];
										$datossolicitudes["$ID"]["FECHATICKET"]=$row_datosQuery["FECHATICKET"];
										$datossolicitudes["$ID"]["ED03_NOMBRESOLICITANTE"]=$row_datosQuery["ED03_NOMBRESOLICITANTE"];
										$datossolicitudes["$ID"]["ED03_TELEFONOSOLICITANTE"]=$row_datosQuery["ED03_TELEFONOSOLICITANTE"];
										$datossolicitudes["$ID"]["ED03_EMAILSOLICITANTE"]=$row_datosQuery["ED03_EMAILSOLICITANTE"];
										$datossolicitudes["$ID"]["ED03_PRIORIDAD"]=$row_datosQuery["ED03_PRIORIDAD"];
										
										if($datossolicitudes["$ID"]["ED03_PRIORIDAD"]=="0")
										   $datossolicitudes["$ID"]["ED03_PRIORIDAD"]="BAJA";
										if($datossolicitudes["$ID"]["ED03_PRIORIDAD"]=="1")
										   $datossolicitudes["$ID"]["ED03_PRIORIDAD"]="ALTA";
										
										
										$datossolicitudes["$ID"]["ED03_DETALLETICKET"]=$row_datosQuery["ED03_DETALLETICKET"];
										$datossolicitudes["$ID"]["ED03_TIPOCONTACTO"]=$row_datosQuery["ED03_TIPOCONTACTO"];
										$datossolicitudes["$ID"]["ED03_NIVELSOPORTE"]=$row_datosQuery["ED03_NIVELSOPORTE"];
										$datossolicitudes["$ID"]["SIS07_CLASIFICADORID"]=$row_datosQuery["SIS07_CLASIFICADORID"];
										$datossolicitudes["$ID"]["SIS07_CLASIFICADORDESCRIPCION"]=$row_datosQuery["SIS07_CLASIFICADORDESCRIPCION"];
										$datossolicitudes["$ID"]["ED03_ARCHIVOADJUNTO"]=$row_datosQuery["ED03_ARCHIVOADJUNTO"];
										$datossolicitudes["$ID"]["ED03_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED03_NOMBREARCHIVOADJUNTO"];
										$datossolicitudes["$ID"]["ED03_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED03_TIPOARCHIVOADJUNTO"];
										$datossolicitudes["$ID"]["ED03_NUMALUMNOSAFECTADOS"]=$row_datosQuery["ED03_NUMALUMNOSAFECTADOS"];
										$datossolicitudes["$ID"]["ED03_NIVELDELPROGRAMA"]=$row_datosQuery["ED03_NIVELDELPROGRAMA"];
										$datossolicitudes["$ID"]["SIS05_CODIGOMODULO"]=$row_datosQuery["SIS05_CODIGOMODULO"];
										$datossolicitudes["$ID"]["ED03_NUMEJERCICIO"]=$row_datosQuery["ED03_NUMEJERCICIO"];
										$datossolicitudes["$ID"]["FECHAULTIMAACTUALIZACION"]=$row_datosQuery["FECHAULTIMAACTUALIZACION"];
										$datossolicitudes["$ID"]["ED03_ESTADO"]=$row_datosQuery["ED03_ESTADO"];
										$datossolicitudes["$ID"]["SECTOR"]=$row_datosQuery["SIS01_SECTORID"];
								
										$datos_derivados["$ID"]["ED03_TICKETID"]=$ID;
										$datos_derivados["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED03_DERIVADO"];
								
										$datossolicitudes["$ID"]["TEXTO_ASOCIADOS"]="";
				
										if(isset($matriz_match_asistencia_incidente[$ID]))
											$datossolicitudes["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociado a asistencia  : <strong>".$matriz_match_asistencia_incidente[$ID]." ]</strong>";
										
										if(isset($matriz_match_solicitud_incidente[$ID]))
											$datossolicitudes["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociado a solicitud  : <strong>".$matriz_match_solicitud_incidente[$ID]." ]</strong>";
								
										
								}						
							}								
						}
					


								
								
			


						//INICIO SEGUIMIENTO
						/////////////////////////////
					
						$sSQL="SELECT
									ED04_SEGTICKETID,
									ED03_TICKETID,
									ED01_USUARIOID,
									DATE_FORMAT(ED04_SEGFECHA, '%d/%m/%Y') as ED04_SEGFECHA,
									ED04_SEGCOMENTARIOS,
									ED04_ARCHIVOADJUNTO,
									ED04_NOMBREARCHIVOADJUNTO,
									ED04_TIPOARCHIVOADJUNTO,
									ED04_FECHAULTIMAACTUALIZACION,
									ED04_REGISTRODETALLECAMBIO
									FROM
									e_desk.ED04_SEGUIMIENTO_TICKET WHERE ED03_TICKETID in ($ID_FILAS)";
							
						$rowset = $DB->fetchAll($sSQL);
		
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED04_SEGTICKETID"])!="")
							{
							
								$ID=$row_datosQuery["ED04_SEGTICKETID"];
								$datos_seguimiento["$ID"]["ED04_SEGTICKETID"]=$row_datosQuery["ED04_SEGTICKETID"];
								$datos_seguimiento["$ID"]["ED03_TICKETID"]=$row_datosQuery["ED03_TICKETID"];
								$datos_seguimiento["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
								$datos_seguimiento["$ID"]["ED04_SEGFECHA"]=$row_datosQuery["ED04_SEGFECHA"];
								$datos_seguimiento["$ID"]["ED04_SEGCOMENTARIOS"]=$row_datosQuery["ED04_SEGCOMENTARIOS"];
								$datos_seguimiento["$ID"]["ED04_ARCHIVOADJUNTO"]=$row_datosQuery["ED04_ARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED04_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED04_NOMBREARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED04_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED04_TIPOARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED04_FECHAULTIMAACTUALIZACION"]=$row_datosQuery["ED04_FECHAULTIMAACTUALIZACION"];
								$datos_seguimiento["$ID"]["ED04_REGISTRODETALLECAMBIO"]=$row_datosQuery["ED04_REGISTRODETALLECAMBIO"];
							
							}								
						}
			

					
					
					
						$NUM_PAGINAS=intval($CONTADOR_FILAS/15);
						$RESTO_PAGINAS = $CONTADOR_FILAS%15;
						if($RESTO_PAGINAS>0)
						   $NUM_PAGINAS++;
						   
						   	
						if(isset($datos_seguimiento))
								Zend_Layout::getMvcInstance()->assign('datos_seguimiento',$datos_seguimiento);
	   	
					
						if(isset($datossolicitudes))
								Zend_Layout::getMvcInstance()->assign('datossolicitudes',$datossolicitudes);
	
						if(isset($datos_derivados))
								Zend_Layout::getMvcInstance()->assign('datos_derivados',$datos_derivados);
				
						if(isset($PAGINA))
								Zend_Layout::getMvcInstance()->assign('pagina',$PAGINA);
						
						if(isset($CONTADOR_FILAS))
								Zend_Layout::getMvcInstance()->assign('total_filas',$CONTADOR_FILAS);
					
						if(isset($NUM_PAGINAS))
								Zend_Layout::getMvcInstance()->assign('num_paginas',$NUM_PAGINAS);
					
						if(isset($CONTADOR_INI))
								Zend_Layout::getMvcInstance()->assign('registro_ini',$CONTADOR_INI);
						
						if(isset($CONTADOR_FIN))
								Zend_Layout::getMvcInstance()->assign('registro_fin',$CONTADOR_FIN);
						
						if(isset($busqueda))
								Zend_Layout::getMvcInstance()->assign('busqueda',$busqueda);
		
    
	
	
	
					#############################
					##INICIO RESCATE PERMISOS
					#############################
					$menu=$config['vectorMenu'];
					$submenu=$config['vectorSubMenu'];
					$permisos=$config['vectorPermisos'];
					$nivelid=trim($edesk_session->NIVELID);
					$sectorid=trim($edesk_session->SECTORID);
					$referer=$_SERVER['HTTP_REFERER'];
				
			        $acceso_funcionalidades=$functions->rescate_permisos($menu,$submenu,$permisos,$nivelid,$sectorid,$referer);
				   
					if(isset($acceso_funcionalidades))
						Zend_Layout::getMvcInstance()->assign('acceso_funcionalidades',$acceso_funcionalidades);
					
				
					//echo "-----".$ACCESOS."/".$IDFINAL."/-----<br><br>";
					//echo "-----".print_r($acceso_funcionalidades)."-----";
					
					
					#############################
					##FIN RESCATE PERMISOS
					#############################
					
						
	
	
	
	}


    public function utilidadesAction()
    {
        
				$this->_helper->layout->disableLayout();
				
				$DB = Zend_Db_Table::getDefaultAdapter();
				$config = Zend_Registry::get('config');
				$functions = new ZendExt_RutinasPhp();
				
				
				$tipo=$this->_request->getPost('tipo');
				$nivel=$this->_request->getPost('nivel');
				$clasificador=$this->_request->getPost('clasificador');
				
	
				//CLASIFICADOR
				////////////////////////////
				$sSQL="SELECT
						SIS07_CLASIFICADORID,
						SIS07_NIVELID,
						SIS07_CLASIFICADORDESCRIPCION
						FROM
						e_desk.SIS07_CLASIFICADOR
						WHERE
						SIS07_NIVELID='$nivel' 
						ORDER BY
						SIS07_NIVELID";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["SIS07_CLASIFICADORID"])!="")
					{
						$ID=$row_datosQuery["SIS07_CLASIFICADORID"];
						$datosclasificador["$ID"]["SIS07_CLASIFICADORID"]=$row_datosQuery["SIS07_CLASIFICADORID"];
						$datosclasificador["$ID"]["SIS07_NIVELID"]=$row_datosQuery["SIS07_NIVELID"];
						$datosclasificador["$ID"]["SIS07_CLASIFICADORDESCRIPCION"]=$row_datosQuery["SIS07_CLASIFICADORDESCRIPCION"];
					}								
				}
			
	
				if(isset($datosclasificador))
							Zend_Layout::getMvcInstance()->assign('datosclasificador',$datosclasificador);
				
							Zend_Layout::getMvcInstance()->assign('tipo',$tipo);
							Zend_Layout::getMvcInstance()->assign('clasificador',$clasificador);
							
				if(isset($datosclasificador["$clasificador"]["SIS07_CLASIFICADORDESCRIPCION"]))
							Zend_Layout::getMvcInstance()->assign('clasificadordescripcion',$datosclasificador["$clasificador"]["SIS07_CLASIFICADORDESCRIPCION"]);
	
	
	}


}