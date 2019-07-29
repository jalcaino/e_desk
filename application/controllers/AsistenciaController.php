<?php

class AsistenciaController extends Zend_Controller_Action
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
								if(isset($edesk_session->BUSQUEDA_ASIS) && trim($edesk_session->BUSQUEDA_ASIS)!="")
								{
									Zend_Layout::getMvcInstance()->assign('busqueda',$edesk_session->BUSQUEDA_ASIS);
								}
							}
		
					
	
	
	}
					
    public function agregarasistenciaAction()
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
			
			
				//echo "/////$solicitudid////////<br>";
			
				$incidenteid=$this->_request->getParam('incidenteid');
				if(!isset($incidenteid) || $incidenteid=="")
				   $incidenteid=0;
				
			
				if($incidenteid!=0)
				{
			
					//validamos que no exista usuario
					$sSQL = "	SELECT 
								s.ED03_TICKETID,
								s.SIS03_LABORATORIOID,
								l.SIS03_LABORATORIODESCRIPCION, 
								s.SIS04_PRODUCTOID,
								s.ED03_NOMBRESOLICITANTE,
								s.ED03_DETALLETICKET
								FROM 
								e_desk.ED03_TICKET s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
								LEFT JOIN
								e_desk.SIS07_CLASIFICADOR c ON s.SIS07_CLASIFICADORID=c.SIS07_CLASIFICADORID
								WHERE 
								s.ED03_TICKETID = '$incidenteid' ";
								
								
							$rowset = $DB->fetchAll($sSQL);

							foreach($rowset as $row_datosQuery)
							{
							
									if(trim($row_datosQuery["ED03_TICKETID"])!="")
									{
									
											$LABORATORIOID=$row_datosQuery["SIS03_LABORATORIOID"];
											$LABORATORIODESCRIPCION=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
											$PRODUCTOID=$row_datosQuery["SIS04_PRODUCTOID"];
											$DETALLESOLICITUD=$row_datosQuery["ED03_DETALLETICKET"];
											$NOMBRESOLICITANTE=$row_datosQuery["ED03_NOMBRESOLICITANTE"];
										
									}

							}
							
				}
			
			
				//echo "/////$incidenteid////////<br>";
			
			
			
								
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
			
			
				//USUARIOS
				////////////////////////////
				$sSQL="SELECT ED01_USUARIOID,ED01_NOMBREAPELLIDO FROM e_desk.ED01_USUARIO WHERE ED01_ESPRIVADO=0 and SIS02_NIVELID in (2,4) ";
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["ED01_USUARIOID"])!="")
					{
						$ID=$row_datosQuery["ED01_USUARIOID"];
						$datosusuarios["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
						$datosusuarios["$ID"]["ED01_NOMBREAPELLIDO"]=$row_datosQuery["ED01_NOMBREAPELLIDO"];
					}								
				}
			
			
					
			
			
				if(isset($datoscolegio))
					Zend_Layout::getMvcInstance()->assign('datoscolegio',$datoscolegio);
			
			
				if(isset($datosusuarios))
						Zend_Layout::getMvcInstance()->assign('datosusuarios',$datosusuarios);
		
	
	
				if($solicitudid!=0)
				{
					Zend_Layout::getMvcInstance()->assign('solicitudid',$solicitudid);
				}			
			
				if($incidenteid!=0)
				{
					Zend_Layout::getMvcInstance()->assign('incidenteid',$incidenteid);
				}			
			
				if($solicitudid!=0 || $incidenteid!=0)
				{
					Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
					Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
					Zend_Layout::getMvcInstance()->assign('PRODUCTOID',$PRODUCTOID);
					Zend_Layout::getMvcInstance()->assign('DETALLESOLICITUD',$DETALLESOLICITUD);
					Zend_Layout::getMvcInstance()->assign('NOMBRESOLICITANTE',$NOMBRESOLICITANTE);
				}
	
	
    }

    public function agregarasistenciaprocessAction()
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

					$incidenteid=$this->_request->getParam('incidenteid');
					if(!isset($incidenteid) || $incidenteid=="")
					   $incidenteid=0;
	
	
	
					$colegio=$this->_request->getPost('colegio');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
					$nombreapellido=$this->_request->getPost('nombreapellido');
					$telefono=$this->_request->getPost('telefono');
					$email=$this->_request->getPost('email');
					$prioridad=$this->_request->getPost('prioridad');
					$tipocontacto=$this->_request->getPost('tipocontacto');
					$fecha_realizarce=$this->_request->getPost('fecha_realizarce');
					$estado=$this->_request->getPost('estado');
					$derivado=$this->_request->getPost('derivado');
					$archivo=$this->_request->getPost('archivo');
					$accion=$this->_request->getPost('accion');
			
					$porciones = explode("|",$colegio);
				
					$porciones_fecha = explode("/",$calendario);
					$calendario_ingles=$porciones_fecha[2]."-".$porciones_fecha[1]."-".$porciones_fecha[0];
							
					
			
					if($accion=="grabar")
					{
					
								  	$existe=0;
						
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
													echo "KO|Se ha producido un error..(1)";
													exit;	
											
												}
			
									}		
		

									//existe laboratorio
									/////////////////////
									if($existe==1)
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
												  		'ED05_FECHAINGRESO' => date("Ymdhis"),
												  		'ED05_NOMBRESOLICITANTE' => $nombreapellido,
												  		'ED05_TELEFONOSOLICITANTE' => $telefono,
												  		'ED05_EMAILSOLICITANTE' => $email,
												  		'ED05_PRIORIDAD' => $prioridad,
												  		'ED05_DETALLEASISTENCIAREALIZAR' => $detalle,
												  		'ED05_TIPOCONTACTO' => $tipocontacto,
												  		'ED05_ARCHIVOADJUNTO' => $fileRuta,
												  		'ED05_NOMBREARCHIVOADJUNTO' => $fileName,
												  		'ED05_TIPOARCHIVOADJUNTO' => $fileType,
												  		'ED05_FECHAREALIZARCE' => $calendario_ingles,
												  		'ED05_ESTADO' => $estado,
												  		'ED05_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
												  		'ED01_USUARIOID' => $edesk_session->USUARIOID,
														'ED05_DERIVADO' => $derivado
												);
												  		
					
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED05_ASISTENCIA_TECNICA', $data);
							
													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(2)";
													exit;	
												}


												#################################
												##RESCATAMOS ULTIMO ID INGRESADO
												#################################
												$sSQL="SELECT max(ED05_ASISTENCIAID) as ingresado FROM e_desk.ED05_ASISTENCIA_TECNICA";
												$rowset = $DB->fetchAll($sSQL);
												
												$nueva_solicitud=0;
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ingresado"])!="")
													{
														$nueva_solicitud=$row_datosQuery["ingresado"];
													}
												}	
																

												
												if($solicitudid!=0)
												{
													$data_solicitud_asistencia = array(
														'ED02_SOLICITUDID' => $solicitudid,
														'ED05_ASISTENCIAID' => $nueva_solicitud
													);
												
													$data_solicitud_asistencia_estado = array(
														'ED02_ESTADO' => 'DERIVADO'
													);
											
													$where_estado['ED02_SOLICITUDID = ?'] = $solicitudid;

												}	

												######################################
												##FIN ASOCIACION ASISTENCIA SOLICITUD
												######################################




												######################################
												##INICIO ASOCIACION ASISTENCIA INCIDENTE
												######################################

												if($incidenteid!=0)
												{
													$data_incidente_asistencia = array(
														'ED03_TICKETID' => $incidenteid,
														'ED05_ASISTENCIAID' => $nueva_solicitud
													);


													$data_asistencia_ticket_estado = array(
														'ED03_ESTADO' => 'DERIVADO'
													);
											
													$where_estado['ED03_TICKETID = ?'] = $incidenteid;

												}	

												######################################
												##FIN ASOCIACION ASISTENCIA SOLICITUD
												######################################


				
												$data_actividad = array(
															'ED01_USUARIOID' => $edesk_session->USUARIOID,
															'ED08_ACCION' => 'AGREGAR ASISTENCIA',
															'ED08_MASINFO' => 'NUM:'.$nueva_solicitud
															);
											

												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
											
													if($solicitudid!=0)
													{
														$DB->insert('e_desk.ED16_SOLICITUD_ASISTENCIA',$data_solicitud_asistencia);
														$DB->update('e_desk.ED02_SOLICITUD', $data_solicitud_asistencia_estado, $where_estado);

													}	

													if($incidenteid!=0)
													{
														$DB->insert('e_desk.ED13_TICKET_ASISTENCIA_TECNICA',$data_incidente_asistencia);
														$DB->update('e_desk.ED03_TICKET', $data_asistencia_ticket_estado, $where_estado);
													}	
								
								
								
													$DB->commit();
							
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(3)";
													exit;	
												}





												#################################
												##INICIO NOTIFICACIONES
												#################################
											
												$email="";
												
												$destinadatarios=$functions->notificaciones_asistencias_seg($nueva_solicitud,$edesk_session->USUARIOID,$edesk_session->EMAIL);	
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
																		   'ED05_ASISTENCIAID' => $nueva_solicitud,
																		   'ED11_TIPONOTIFICACION' => '1',
																		   'ED11_LEIDO' => '0',
																		   'ED11_FECHANOTIFICACION' => date("Ymdhis")
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
													$subject="INTERNO - CREACION DE ASISTENCIA E-DESK";
													$body="<u>Estimado Usuario</u><br><br>
														   Con fecha de hoy ".date("d/m/Y")." se ha generado el asistencia numero : <strong>$nueva_solicitud</strong> <br><br>
														   por el usuario E-DESK Login : ($edesk_session->USUARIOID) <br><br>
														   Atte.<br>Equipo Compumat.";
													
								
													$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
												}							
								

												######################################
												##INICIO ASOCIACION ASISTENCIA SOLICITUD
												######################################



												try {
							
													$DB->getConnection();
													$DB->beginTransaction();


													//INICIO NOTIFICACIONES USUARIO
													/////////////////////////////////
													if(isset($USUARIOS_A_NOTIFICAR) && count($USUARIOS_A_NOTIFICAR)>0)
													{
														foreach($USUARIOS_A_NOTIFICAR as $clave => $valor)
														{
															$DB->insert('e_desk.ED11_USUARIO_NOTIFICADO_ASISTENCIA',$data_usuario[$valor]);
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
													echo "KO|Se ha producido un error..(4)";
													exit;	
												}









									}else{
									
											echo("KO|No existe el colegio a asociar la solicitud");
											exit;

									
									}


					}else{
					
								echo("KO|Accion invalida");
								exit;
					}		
			
	
	
	
    }

    public function editarasistenciaAction()
    {
    
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
				
					$asistenciaid=$this->_request->getPost('asistenciaid');
			
					$LABORATORIOID="";
					$LABORATORIODESCRIPCION="";
					$FECHAASISTENCIA="";
					$NOMBRESOLICITANTE="";
					$TELEFONOSOLICITANTE="";
					$EMAILSOLICITANTE="";
					$PRIORIDAD="";
					$DETALLEASISTENCIAREALIZAR="";
					$TIPOCONTACTO="";
					$ARCHIVOADJUNTO="";
					$NOMBREARCHIVOADJUNTO="";
					$TIPOARCHIVOADJUNTO="";
					$ESTADO="";			
				
				
					
					//validamos que no exista usuario
						
					$sSQL="SELECT 
								s.ED05_ASISTENCIAID,
								s.SIS03_LABORATORIOID,
								l.SIS03_LABORATORIODESCRIPCION, 
								DATE_FORMAT(s.ED05_FECHAINGRESO, '%d/%m/%Y') as FECHAASISTENCIA,
								s.ED05_NOMBRESOLICITANTE,
								s.ED05_TELEFONOSOLICITANTE,
								s.ED05_EMAILSOLICITANTE,
								s.ED05_PRIORIDAD,
								s.ED05_DETALLEASISTENCIAREALIZAR,
								DATE_FORMAT(s.ED05_FECHAREALIZARCE, '%d/%m/%Y') as FECHAREALIZARCE,
								s.ED05_TIPOCONTACTO,
								s.ED05_ESTADO,
								s.ED05_ARCHIVOADJUNTO,
								s.ED05_NOMBREARCHIVOADJUNTO,
								s.ED05_TIPOARCHIVOADJUNTO,
								DATE_FORMAT(s.ED05_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION,
								s.ED05_DERIVADO 
								FROM 
								e_desk.ED05_ASISTENCIA_TECNICA s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID 
								WHERE 
								s.ED05_ASISTENCIAID = '$asistenciaid' ";	
							
								
							$rowset = $DB->fetchAll($sSQL);

							$USUARIOSELECCIONADO=0;

							foreach($rowset as $row_datosQuery)
							{
							
									if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
									{
									
											$LABORATORIOID=$row_datosQuery["SIS03_LABORATORIOID"];
											$LABORATORIODESCRIPCION=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
											$FECHAASISTENCIA=$row_datosQuery["FECHAASISTENCIA"];
											$NOMBRESOLICITANTE=$row_datosQuery["ED05_NOMBRESOLICITANTE"];
											$TELEFONOSOLICITANTE=$row_datosQuery["ED05_TELEFONOSOLICITANTE"];
											$EMAILSOLICITANTE=$row_datosQuery["ED05_EMAILSOLICITANTE"];
											$PRIORIDAD=$row_datosQuery["ED05_PRIORIDAD"];
											$DETALLEASISTENCIAREALIZAR=$row_datosQuery["ED05_DETALLEASISTENCIAREALIZAR"];
											$TIPOCONTACTO=$row_datosQuery["ED05_TIPOCONTACTO"];
											$ARCHIVOADJUNTO=$row_datosQuery["ED05_ARCHIVOADJUNTO"];
											$NOMBREARCHIVOADJUNTO=$row_datosQuery["ED05_NOMBREARCHIVOADJUNTO"];
											$TIPOARCHIVOADJUNTO=$row_datosQuery["ED05_TIPOARCHIVOADJUNTO"];
											$ESTADO=$row_datosQuery["ED05_ESTADO"];		
											$USUARIOSELECCIONADO=$row_datosQuery["ED05_DERIVADO"];
							
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
			
			
				//USUARIOS
				////////////////////////////
				$sSQL="SELECT ED01_USUARIOID,ED01_NOMBREAPELLIDO FROM e_desk.ED01_USUARIO WHERE ED01_ESPRIVADO=0 and SIS02_NIVELID in (2,4)";
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["ED01_USUARIOID"])!="")
					{
						$ID=$row_datosQuery["ED01_USUARIOID"];
						$datosusuarios["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
						$datosusuarios["$ID"]["ED01_NOMBREAPELLIDO"]=$row_datosQuery["ED01_NOMBREAPELLIDO"];
					}								
				}
			
		
			
				
				if(isset($datoscolegio))
					Zend_Layout::getMvcInstance()->assign('datoscolegio',$datoscolegio);
			
			
				if(isset($datosusuarios))
						Zend_Layout::getMvcInstance()->assign('datosusuarios',$datosusuarios);

				
		
				Zend_Layout::getMvcInstance()->assign('ASISTENCIAID',$asistenciaid);
				Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
				Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
				Zend_Layout::getMvcInstance()->assign('FECHAASISTENCIA',$FECHAASISTENCIA);
				Zend_Layout::getMvcInstance()->assign('NOMBRESOLICITANTE',$NOMBRESOLICITANTE);
				Zend_Layout::getMvcInstance()->assign('TELEFONOSOLICITANTE',$TELEFONOSOLICITANTE);
				Zend_Layout::getMvcInstance()->assign('EMAILSOLICITANTE',$EMAILSOLICITANTE);
				Zend_Layout::getMvcInstance()->assign('PRIORIDAD',$PRIORIDAD);
				Zend_Layout::getMvcInstance()->assign('DETALLEASISTENCIAREALIZAR',$DETALLEASISTENCIAREALIZAR);
				Zend_Layout::getMvcInstance()->assign('TIPOCONTACTO',$TIPOCONTACTO);
				Zend_Layout::getMvcInstance()->assign('ARCHIVOADJUNTO',$ARCHIVOADJUNTO);
				Zend_Layout::getMvcInstance()->assign('NOMBREARCHIVOADJUNTO',$NOMBREARCHIVOADJUNTO);
				Zend_Layout::getMvcInstance()->assign('TIPOARCHIVOADJUNTO',$TIPOARCHIVOADJUNTO);
				Zend_Layout::getMvcInstance()->assign('ESTADO',$ESTADO);		
				Zend_Layout::getMvcInstance()->assign('USUARIOSELECCIONADO',$USUARIOSELECCIONADO);		
				
				
    }

    public function editarasistenciaprocessAction()
    {
    


					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
					$edesk_session = new Zend_Session_Namespace('edeskses');
					$uploads = $config['ruta_path'];
					$uploads_public = $config['ruta_carpeta'];
					$separador = '/';


	
					$asistenciaid=$this->_request->getPost('asistenciaid');
					$colegio=$this->_request->getPost('colegio');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
					$nombreapellido=$this->_request->getPost('nombreapellido');
					$telefono=$this->_request->getPost('telefono');
					$email=$this->_request->getPost('email');
					$prioridad=$this->_request->getPost('prioridad');
					$tipocontacto=$this->_request->getPost('tipocontacto');
					$fecha_realizarce=$this->_request->getPost('fecha_realizarce');
					$estado=$this->_request->getPost('estado');
					$derivado=$this->_request->getPost('derivado');
					$archivo=$this->_request->getPost('archivo');
					$accion=$this->_request->getPost('accion');
			
					$porciones = explode("|",$colegio);
				
					$porciones_fecha = explode("/",$calendario);
					$calendario_ingles=$porciones_fecha[2]."-".$porciones_fecha[1]."-".$porciones_fecha[0];
					
					
					
			
					if($accion=="grabar")
					{
					
								  	$existe=0;
						
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
													echo "KO|Se ha producido un error..(5)";
													exit;	
											
												}
			
									}		
		

									//existe laboratorio
									/////////////////////
									if($existe==1)
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
															'ED05_NOMBRESOLICITANTE' => $nombreapellido,
															'ED05_TELEFONOSOLICITANTE' => $telefono,
															'ED05_EMAILSOLICITANTE' => $email,
															'ED05_PRIORIDAD' => $prioridad,
															'ED05_DETALLEASISTENCIAREALIZAR' => $detalle,
															'ED05_TIPOCONTACTO' => $tipocontacto,
															'ED05_ARCHIVOADJUNTO' => $fileRuta,
															'ED05_NOMBREARCHIVOADJUNTO' => $fileName,
															'ED05_TIPOARCHIVOADJUNTO' => $fileType,
															'ED05_FECHAREALIZARCE' => $calendario_ingles,
															'ED05_ESTADO' => $estado,
															'ED05_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
															'ED05_DERIVADO' => $derivado
														);
												
			
												}else{
												
														$data = array(
															'SIS03_LABORATORIOID' => $ELCOLEGIO,
															'ED05_NOMBRESOLICITANTE' => $nombreapellido,
															'ED05_TELEFONOSOLICITANTE' => $telefono,
															'ED05_EMAILSOLICITANTE' => $email,
															'ED05_PRIORIDAD' => $prioridad,
															'ED05_DETALLEASISTENCIAREALIZAR' => $detalle,
															'ED05_TIPOCONTACTO' => $tipocontacto,
															'ED05_FECHAREALIZARCE' => $calendario_ingles,
															'ED05_ESTADO' => $estado,
															'ED05_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
															'ED05_DERIVADO' => $derivado
														);
													
												}


												$where['ED05_ASISTENCIAID = ?'] = $asistenciaid;
												
												
												$data_actividad = array(
														'ED01_USUARIOID' => $edesk_session->USUARIOID,
														'ED08_ACCION' => 'EDITAR ASISTENCIA',
														'ED08_MASINFO' => 'NUM:'.$asistenciaid
														);
										

												###########BUSCAMOS SOLICITUDES ASOCIADAS###################
												###########BUSCAMOS SOLICITUDES ASOCIADAS###################
												###########BUSCAMOS SOLICITUDES ASOCIADAS###################
												

												$sSQL="SELECT ED02_SOLICITUDID FROM e_desk.ED16_SOLICITUD_ASISTENCIA WHERE ED05_ASISTENCIAID='$asistenciaid'";

												$rowset = $DB->fetchAll($sSQL);
												$SOLICITUD_ASOCIADA_DIRECTA=0;
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
													{
														$SOLICITUD_ASOCIADA_DIRECTA=$row_datosQuery["ED02_SOLICITUDID"];
													}								
												}
						
												if($SOLICITUD_ASOCIADA_DIRECTA!=0)
												{													
													$data_solicitud_directa_estado = array(
														'ED02_ESTADO' => $estado
													);
											
											
													$where_directa_estado['ED02_SOLICITUDID = ?'] = $SOLICITUD_ASOCIADA_DIRECTA;
												}
				

												###########BUSCAMOS INCIDENTES ASOCIADOS###################
												###########BUSCAMOS INCIDENTES ASOCIADOS###################
												###########BUSCAMOS INCIDENTES ASOCIADOS###################
												

												$sSQL="SELECT ED03_TICKETID FROM e_desk.ED13_TICKET_ASISTENCIA_TECNICA WHERE ED05_ASISTENCIAID='$asistenciaid'";
												$rowset = $DB->fetchAll($sSQL);
												$INCIDENTE_ASOCIADO=0;
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ED03_TICKETID"])!="")
													{
														$INCIDENTE_ASOCIADO=$row_datosQuery["ED03_TICKETID"];
													}								
												}
				
				
												if($INCIDENTE_ASOCIADO!=0)
												{													
													$data_incidente_asociado_estado = array(
														'ED03_ESTADO' => $estado
													);
											
													$where_incidente_asociado_estado['ED03_TICKETID = ?'] = $INCIDENTE_ASOCIADO;
												}
		
				
				
												###########FIN BUSCAMOS INCIDENTES ASOCIADOS###################
												###########FIN BUSCAMOS INCIDENTES ASOCIADOS###################
												###########FIN BUSCAMOS INCIDENTES ASOCIADOS###################
															
				
				
											
												$SOLICITUD_ASOCIADA=0;
												if($INCIDENTE_ASOCIADO!=0)
												{
														//INICIO INCIDENTE ASOCIADO A SOLICITUD
														//////////////////////////////////////
														$sSQL="SELECT ED02_SOLICITUDID FROM e_desk.ED14_SOLICITUD_TICKET WHERE ED03_TICKETID='$INCIDENTE_ASOCIADO' ";
														$rowset = $DB->fetchAll($sSQL);
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
												}																


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->update('e_desk.ED05_ASISTENCIA_TECNICA', $data, $where);
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													

													if($SOLICITUD_ASOCIADA!=0 && $estado!="PENDIENTE")
													{													
														$DB->update('e_desk.ED02_SOLICITUD', $data_solicitud_ticket_estado, $where_estado);
													}	

													if($SOLICITUD_ASOCIADA_DIRECTA!=0 && $estado!="PENDIENTE")
													{													
														$DB->update('e_desk.ED02_SOLICITUD', $data_solicitud_directa_estado, $where_directa_estado);
													}	


													if($INCIDENTE_ASOCIADO!=0 && $estado!="PENDIENTE")
													{													
														$DB->update('e_desk.ED03_TICKET', $data_incidente_asociado_estado, $where_incidente_asociado_estado);
													}	


													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(6)";
													exit;	
												}



												#################################
												##INICIO NOTIFICACIONES
												#################################
											
												$email="";
												
												$destinadatarios=$functions->notificaciones_asistencias_seg($asistenciaid,$edesk_session->USUARIOID,$edesk_session->EMAIL);	
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
																		   'ED05_ASISTENCIAID' => $asistenciaid,
																		   'ED11_TIPONOTIFICACION' => '1',
																		   'ED11_LEIDO' => '0',
																		   'ED11_FECHANOTIFICACION' => date("Ymdhis")
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
												$subject="INTERNO - ACTUALIZACION DE ASISTENCIA E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha actualizado el asistencia numero : <strong>$asistenciaid</strong> <br><br>
													   por el usuario E-DESK Login : ($edesk_session->USUARIOID) <br><br>
													   Atte.<br>Equipo Compumat.";
												
							
													$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
												}							
								
							
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();


													//INICIO NOTIFICACIONES USUARIO
													/////////////////////////////////
													if(isset($USUARIOS_A_NOTIFICAR) && count($USUARIOS_A_NOTIFICAR)>0)
													{
														foreach($USUARIOS_A_NOTIFICAR as $clave => $valor)
														{
															$DB->insert('e_desk.ED11_USUARIO_NOTIFICADO_ASISTENCIA',$data_usuario[$valor]);
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
													echo "KO|Se ha producido un error..(7)";
													exit;	
												}



									}else{
									
											echo("KO|No existe el colegio a asociar la solicitud");
											exit;

									
									}


					}else{
					
								echo("KO|Accion invalida");
								exit;
					}		
			
	
    }




    public function eliminarasistenciaAction()
    {
    
						$this->_helper->layout->disableLayout();
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
				
				
						$asistenciaid=$this->_request->getPost('asistenciaid');
					
						$where['ED05_ASISTENCIAID = ?'] = $asistenciaid;
			
			
						$data_actividad = array(
												'ED01_USUARIOID' => $edesk_session->USUARIOID,
												'ED08_ACCION' => 'ELIMINAR ASISTENCIA',
												'ED08_MASINFO' => 'NUM:'.$asistenciaid
												);
					
			
						try {

							$n = $DB->delete("e_desk.ED05_ASISTENCIA_TECNICA", $where);
							$n3 = $DB->delete("e_desk.ED13_TICKET_ASISTENCIA_TECNICA", $where);
							$n5 = $DB->delete("e_desk.ED11_USUARIO_NOTIFICADO_ASISTENCIA", $where);
							$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
							
							//FALTA AQUI LOG DE ACCION

							echo "Asistencia eliminada correctamente...";

						} catch (Zend_Exception $e) {

							//echo $e->getMessage();
							echo "KO|Se ha producido un error..(8)";

						}
		
	
	
    }

    public function listarasistenciasAction()
    {
    
	
	
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						$DB = Zend_Db_Table::getDefaultAdapter();
						$functions = new ZendExt_RutinasPhp();
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
					
					
						$CONTADOR_INI=1;
						$CONTADOR_FIN=15;
						$PAGINA=1;

						$lapagina=$this->_request->getPost('pagina');
						$busqueda=$this->_request->getPost('busqueda');
						$edesk_session->BUSQUEDA_ASIS=$busqueda;
						
					
						if($lapagina!="")
						{
								$PAGINA=$lapagina;
								$CONTADOR_INI=(($lapagina*15)+1)-15;
								$CONTADOR_FIN=($lapagina*15);
						}
			
			
			
					//INICIO ASISTENCIAS ASOCIADAS A SOLICITUD
					//////////////////////////////////////////
					
					$sSQL="SELECT ED02_SOLICITUDID,ED05_ASISTENCIAID FROM e_desk.ED16_SOLICITUD_ASISTENCIA";
					$rowset = $DB->fetchAll($sSQL);
					$SOLICITUD_ASOCIADA_DIRECTA=0;
					foreach($rowset as $row_datosQuery)
					{
						if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
						{
							$IDENTIFICA=$row_datosQuery["ED05_ASISTENCIAID"];
							$matriz_match_solicitud_asistencia[$IDENTIFICA]=$row_datosQuery["ED02_SOLICITUDID"];
						}								
					}
					
					//FIN ASISTENCIAS ASOCIADAS A SOLICITUD
					//////////////////////////////////////////
					
					//INICIO INCIDENTES ASOCIADAS A ASISTENCIAS
					//////////////////////////////////////////
					
					$sSQL="SELECT ED03_TICKETID,ED05_ASISTENCIAID FROM e_desk.ED13_TICKET_ASISTENCIA_TECNICA";
					$rowset = $DB->fetchAll($sSQL);
					foreach($rowset as $row_datosQuery)
					{
						if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
						{
							$IDENTIFICA=$row_datosQuery["ED05_ASISTENCIAID"];
							$matriz_match_asistencia_incidente[$IDENTIFICA]=$row_datosQuery["ED03_TICKETID"];
						}								
					}


					//FIN INCIDENTES ASOCIADAS A SOLICITUD
					//////////////////////////////////////////
					
				
			
			
			
			
						

						$CONTADOR_FILAS=0;
					
						//ASISTENCIAS
						////////////////////////////
						$sSQL="SELECT 
								s.ED05_ASISTENCIAID,
								s.SIS03_LABORATORIOID,
								l.SIS03_LABORATORIODESCRIPCION, 
								DATE_FORMAT(s.ED05_FECHAINGRESO, '%d/%m/%Y') as FECHAASISTENCIA,
								s.ED05_NOMBRESOLICITANTE,
								s.ED05_TELEFONOSOLICITANTE,
								s.ED05_EMAILSOLICITANTE,
								s.ED05_PRIORIDAD,
								s.ED05_DETALLEASISTENCIAREALIZAR,
								DATE_FORMAT(s.ED05_FECHAREALIZARCE, '%d/%m/%Y') as FECHAREALIZARCE,
								s.ED05_TIPOCONTACTO,
								s.ED05_ESTADO,
								s.ED05_ARCHIVOADJUNTO,
								s.ED05_NOMBREARCHIVOADJUNTO,
								s.ED05_TIPOARCHIVOADJUNTO,
								DATE_FORMAT(s.ED05_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION,
								s.ED05_DERIVADO
								FROM 
								e_desk.ED05_ASISTENCIA_TECNICA s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID ";
																
							
						if(trim($busqueda)!="")
								$sSQL.=" WHERE s.ED05_ESTADO like '%".$busqueda."%' OR s.SIS03_LABORATORIOID='".$busqueda."' OR s.ED05_ASISTENCIAID='".$busqueda."' ";		
						
					
						$sSQL.=" ORDER BY ED05_ASISTENCIAID desc ";
					
						
					 	$rowset = $DB->fetchAll($sSQL);
					
						$ID_FILAS="0";
					
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
							{
								$CONTADOR_FILAS++;
								
								if($CONTADOR_FILAS>=$CONTADOR_INI && $CONTADOR_FILAS<=$CONTADOR_FIN)
								{
						
										$ID=$row_datosQuery["ED05_ASISTENCIAID"];
										$ID_FILAS.=",".$ID;
								
										$datosasistencias["$ID"]["ED05_ASISTENCIAID"]=$row_datosQuery["ED05_ASISTENCIAID"];
										$datosasistencias["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
										$datosasistencias["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
										$datosasistencias["$ID"]["FECHAASISTENCIA"]=$row_datosQuery["FECHAASISTENCIA"];
										$datosasistencias["$ID"]["ED05_NOMBRESOLICITANTE"]=$row_datosQuery["ED05_NOMBRESOLICITANTE"];
										$datosasistencias["$ID"]["ED05_TELEFONOSOLICITANTE"]=$row_datosQuery["ED05_TELEFONOSOLICITANTE"];
										$datosasistencias["$ID"]["ED05_EMAILSOLICITANTE"]=$row_datosQuery["ED05_EMAILSOLICITANTE"];
										$datosasistencias["$ID"]["ED05_PRIORIDAD"]=$row_datosQuery["ED05_PRIORIDAD"];
										
										if($datosasistencias["$ID"]["ED05_PRIORIDAD"]=="0")
										   $datosasistencias["$ID"]["ED05_PRIORIDAD"]="BAJA";
										if($datosasistencias["$ID"]["ED05_PRIORIDAD"]=="1")
										   $datosasistencias["$ID"]["ED05_PRIORIDAD"]="ALTA";
										
										
										$datosasistencias["$ID"]["ED05_DETALLEASISTENCIAREALIZAR"]=$row_datosQuery["ED05_DETALLEASISTENCIAREALIZAR"];
										$datosasistencias["$ID"]["FECHAREALIZARCE"]=$row_datosQuery["FECHAREALIZARCE"];
										$datosasistencias["$ID"]["ED05_TIPOCONTACTO"]=$row_datosQuery["ED05_TIPOCONTACTO"];
										$datosasistencias["$ID"]["ED05_ESTADO"]=$row_datosQuery["ED05_ESTADO"];
										$datosasistencias["$ID"]["ED05_ARCHIVOADJUNTO"]=$row_datosQuery["ED05_ARCHIVOADJUNTO"];
										$datosasistencias["$ID"]["ED05_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED05_NOMBREARCHIVOADJUNTO"];
										$datosasistencias["$ID"]["ED05_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED05_TIPOARCHIVOADJUNTO"];
										$datosasistencias["$ID"]["FECHAULTIMAACTUALIZACION"]=$row_datosQuery["FECHAULTIMAACTUALIZACION"];
										
							
										$datos_derivados["$ID"]["ED05_ASISTENCIAID"]=$ID;
										$datos_derivados["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED05_DERIVADO"];
															
							
										$datosasistencias["$ID"]["TEXTO_ASOCIADOS"]="";
				
										if(isset($matriz_match_solicitud_asistencia[$ID]))
											$datosasistencias["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociada a solicitud  : <strong>".$matriz_match_solicitud_asistencia[$ID]." ]</strong>";
										
										if(isset($matriz_match_asistencia_incidente[$ID]))
											$datosasistencias["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociada a  incidente  : <strong>".$matriz_match_asistencia_incidente[$ID]." ]</strong>";
										
				
								
								
								
								
								}						
							}								
						}
					

					
						//INICIO SEGUIMIENTO
						/////////////////////////////
						$sSQL="SELECT
									ED06_SEGASISTENCIAID,
									ED05_ASISTENCIAID,
									ED01_USUARIOID,
									DATE_FORMAT(ED06_SEGFECHA, '%d/%m/%Y') as ED06_SEGFECHA,
									ED06_SEGCOMENTARIOS,
									ED06_ARCHIVOADJUNTO,
									ED06_NOMBREARCHIVOADJUNTO,
									ED06_TIPOARCHIVOADJUNTO,
									ED06_FECHAULTIMAACTUALIZACION,
									ED06_REGISTRODETALLECAMBIO
									FROM
									e_desk.ED06_SEGUIMIENTO_ASISTENCIA_TECNICA WHERE ED05_ASISTENCIAID in ($ID_FILAS)";
							
						$rowset = $DB->fetchAll($sSQL);
		
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED06_SEGASISTENCIAID"])!="")
							{
							
								$ID=$row_datosQuery["ED06_SEGASISTENCIAID"];
								$datos_seguimiento["$ID"]["ED06_SEGASISTENCIAID"]=$row_datosQuery["ED06_SEGASISTENCIAID"];
								$datos_seguimiento["$ID"]["ED05_ASISTENCIAID"]=$row_datosQuery["ED05_ASISTENCIAID"];
								$datos_seguimiento["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
								$datos_seguimiento["$ID"]["ED06_SEGFECHA"]=$row_datosQuery["ED06_SEGFECHA"];
								$datos_seguimiento["$ID"]["ED06_SEGCOMENTARIOS"]=$row_datosQuery["ED06_SEGCOMENTARIOS"];
								$datos_seguimiento["$ID"]["ED06_ARCHIVOADJUNTO"]=$row_datosQuery["ED06_ARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED06_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED06_NOMBREARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED06_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED06_TIPOARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED06_FECHAULTIMAACTUALIZACION"]=$row_datosQuery["ED06_FECHAULTIMAACTUALIZACION"];
								$datos_seguimiento["$ID"]["ED06_REGISTRODETALLECAMBIO"]=$row_datosQuery["ED06_REGISTRODETALLECAMBIO"];
							
							}								
						}
			
			
					
					
						$NUM_PAGINAS=intval($CONTADOR_FILAS/15);
						$RESTO_PAGINAS = $CONTADOR_FILAS%15;
						if($RESTO_PAGINAS>0)
						   $NUM_PAGINAS++;
						   
						
						if(isset($datos_seguimiento))
								Zend_Layout::getMvcInstance()->assign('datos_seguimiento',$datos_seguimiento);
	   	
					
						if(isset($datosasistencias))
								Zend_Layout::getMvcInstance()->assign('datosasistencias',$datosasistencias);
	
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


}