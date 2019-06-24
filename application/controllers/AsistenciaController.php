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
	}
					
    public function agregarasistenciaAction()
    {
    
				$config = Zend_Registry::get('config');
				
				$DB = Zend_Db_Table::getDefaultAdapter();
			
			
			
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
				$sSQL="SELECT ED01_USUARIOID,ED01_NOMBREAPELLIDO FROM e_desk.ED01_USUARIO WHERE SIS01_SECTORID='LAB'";
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
		
	
	
    }

    public function agregarasistenciaprocessAction()
    {
    
	
					$uploads = '/var/www/html/edesk/public/archivos_upload';
					$uploads_public = '/archivos_upload';
					$separador = '/';
				   

    
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();

		
					$edesk_session = new Zend_Session_Namespace('edeskses');
	
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
									
													echo("KO|".$e->getMessage());
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
												  		'ED05_FECHAULTIMAACTUALIZACION' => date("Ymdhis")
												);
												  		
					
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED05_ASISTENCIA_TECNICA', $data);
							
													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													echo("KO|".$e->getMessage());
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
																

												#############################
												##MAIL CREACION SOLICITUD
												#############################
							
												#################################
												##MAILS A SOPORTE
												#################################
												
												$sSQL="SELECT ED01_EMAIL FROM e_desk.ED01_USUARIO WHERE SIS02_NIVELID in (2,3)";
												$rowset = $DB->fetchAll($sSQL);
												$email="";																	

												
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ED01_EMAIL"])!="")
													{
														if($email=="")
															$email=$row_datosQuery["ED01_EMAIL"];
														else
															$email.=",".$row_datosQuery["ED01_EMAIL"];
														
													}
												}	
											
							
							
												$from="helpdesk@compumat.cl";
												$to=$email;
												$subject="INTERNO - CREACION DE ASISTENCIA E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha generado el asistencia numero : <strong>$nueva_solicitud</strong> <br><br>
													   por el usuario E-DESK Login : ($edesk_session->USUARIOID) <br><br>
													   Atte.<br>Equipo Compumat.";
												
							
												$data_email = array(
														'origen' => $from,
														'destinatarios' => $to,
														'f_ingreso' => date("Ymdhis"),
														'app_origen' => 'E-DESK',
														'encabezado' => $subject,
														'contenido' => $body,
														'estado_correo' => '0'
													);
								
					
												#############################
												##FIN MAIL CREACION USUARIO
												#############################



												#################################
												##ASOCIACION A USUARIO
												#################################
								
												//1 propietario
												$data_usuario1 = array(
														'ED05_ASISTENCIAID' => $nueva_solicitud,
														'ED01_USUARIOID' => $edesk_session->USUARIOID,
														'ED11_TIPOASIGNACION' => '1'
													);
								
												//2 derivado
												$data_usuario2 = array(
														'ED05_ASISTENCIAID' => $nueva_solicitud,
														'ED01_USUARIOID' => $derivado,
														'ED11_TIPOASIGNACION' => '2'
													);
																


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('bd_correos.correos_soporte', $data_email);
													$DB->insert('e_desk.ED11_USUARIO_ASISTENCIA_TECNICA',$data_usuario1);
																
													if(trim($derivado)!="")
													{
														$DB->insert('e_desk.ED11_USUARIO_ASISTENCIA_TECNICA',$data_usuario2);
													}
								
													//hay que consultar por solicitudes asociadas
													//relación
													//y hacer insert
								
								
								
													$DB->commit();
							
													echo("OK|");
													exit;
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													echo("KO|".$e->getMessage());
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
					$config = Zend_Registry::get('config');
					$DB = Zend_Db_Table::getDefaultAdapter();
					
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
								DATE_FORMAT(s.ED05_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION
								FROM 
								e_desk.ED05_ASISTENCIA_TECNICA s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID 
								WHERE 
								s.ED05_ASISTENCIAID = '$asistenciaid' ";	
							
						
								
							$rowset = $DB->fetchAll($sSQL);

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
				$sSQL="SELECT ED01_USUARIOID,ED01_NOMBREAPELLIDO FROM e_desk.ED01_USUARIO WHERE SIS01_SECTORID='LAB'";
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
			
			
			
				
				
				$USUARIOSELECCIONADO=0;

				$sSQL="SELECT
						ED01_USUARIOID
						FROM
						e_desk.ED11_USUARIO_ASISTENCIA_TECNICA
						WHERE
						ED11_TIPOASIGNACION=2 and
						ED05_ASISTENCIAID='$asistenciaid'
						order by
						ED11_FECHAASIGNACION desc
						limit 0,1";
					
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["ED01_USUARIOID"])!="")
					{
						$USUARIOSELECCIONADO=$row_datosQuery["ED01_USUARIOID"];
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
    

					$uploads = '/var/www/html/edesk/public/archivos_upload';
					$uploads_public = '/archivos_upload';
					$separador = '/';
    
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();

		
					$edesk_session = new Zend_Session_Namespace('edeskses');
	
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
									
													echo("KO|".$e->getMessage());
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
															'ED05_FECHAULTIMAACTUALIZACION' => date("Ymdhis")
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
															'ED05_FECHAULTIMAACTUALIZACION' => date("Ymdhis")
														);
													
												}


												$where['ED05_ASISTENCIAID = ?'] = $asistenciaid;
																				

												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->update('e_desk.ED05_ASISTENCIA_TECNICA', $data, $where);
													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													echo("KO|".$e->getMessage());
													exit;	
												}


																

												#############################
												##MAIL CREACION SOLICITUD
												#############################
							
												#################################
												##MAILS A SOPORTE
												#################################
												
												$sSQL="SELECT ED01_EMAIL FROM e_desk.ED01_USUARIO WHERE SIS02_NIVELID in (2,3)";
												$rowset = $DB->fetchAll($sSQL);
												$email="";																	

												
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ED01_EMAIL"])!="")
													{
														if($email=="")
															$email=$row_datosQuery["ED01_EMAIL"];
														else
															$email.=",".$row_datosQuery["ED01_EMAIL"];
														
													}
												}	
											
							
							
												$from="helpdesk@compumat.cl";
												$to=$email;
												$subject="INTERNO - ACTUALIZACION DE ASISTENCIA E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha actualizado el asistencia numero : <strong>$asistenciaid</strong> <br><br>
													   por el usuario E-DESK Login : ($edesk_session->USUARIOID) <br><br>
													   Atte.<br>Equipo Compumat.";
												
							
												$data_email = array(
														'origen' => $from,
														'destinatarios' => $to,
														'f_ingreso' => date("Ymdhis"),
														'app_origen' => 'E-DESK',
														'encabezado' => $subject,
														'contenido' => $body,
														'estado_correo' => '0'
													);
								
					
					
												#############################
												##FIN MAIL CREACION USUARIO
												#############################


												#################################
												##ASOCIACION A USUARIO
												#################################
								
												$USUARIOSELECCIONADO="XX";
	
												$sSQL="SELECT
														ED01_USUARIOID
														FROM
														e_desk.ED11_USUARIO_ASISTENCIA_TECNICA
														WHERE
														ED11_TIPOASIGNACION=2 and
														ED05_ASISTENCIAID='$asistenciaid'
														order by
														ED11_FECHAASIGNACION desc
														limit 0,1";
													
												$rowset = $DB->fetchAll($sSQL);
								
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ED01_USUARIOID"])!="")
													{
														$USUARIOSELECCIONADO=$row_datosQuery["ED01_USUARIOID"];
													}								
												}
											
								
												//2 derivado
												$data_usuario2 = array(
														'ED05_ASISTENCIAID' => $asistenciaid,
														'ED01_USUARIOID' => $derivado,
														'ED11_TIPOASIGNACION' => '2'
													);

												
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('bd_correos.correos_soporte', $data_email);
																
													if(trim($derivado)!=trim($USUARIOSELECCIONADO))
													{
														$DB->insert('e_desk.ED11_USUARIO_ASISTENCIA_TECNICA',$data_usuario2);
													}
													//hay que consultar por solicitudes asociadas
													//relación
													//y hacer insert
								
								
															
													$DB->commit();
							
													echo("OK|");
													exit;
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													echo("KO|".$e->getMessage());
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
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
				
						$asistenciaid=$this->_request->getPost('asistenciaid');
					
						$where['ED05_ASISTENCIAID = ?'] = $asistenciaid;
			
						try {

							$n = $DB->delete("e_desk.ED05_ASISTENCIA_TECNICA", $where);
							$n2 = $DB->delete("e_desk.ED11_USUARIO_ASISTENCIA_TECNICA", $where);
							$n3 = $DB->delete("e_desk.ED13_TICKET_ASISTENCIA_TECNICA", $where);
							
							//FALTA AQUI LOG DE ACCION

							echo "Asistencia eliminada correctamente...";

						} catch (Zend_Exception $e) {

							echo $e->getMessage();

						}
		
	
	
    }

    public function listarasistenciasAction()
    {
    
	
	
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
					
						$CONTADOR_INI=1;
						$CONTADOR_FIN=15;
						$PAGINA=1;

						$lapagina=$this->_request->getPost('pagina');
						$busqueda=$this->_request->getPost('busqueda');
						
						
						if($lapagina!="")
						{
								$PAGINA=$lapagina;
								$CONTADOR_INI=(($lapagina*15)+1)-15;
								$CONTADOR_FIN=($lapagina*15);
						}
			
						

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
								DATE_FORMAT(s.ED05_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION
								FROM 
								e_desk.ED05_ASISTENCIA_TECNICA s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID ";
																
								
						
						if(trim($busqueda)!="")
								$sSQL.=" WHERE s.ED05_ESTADO like '%".$busqueda."%' OR s.SIS03_LABORATORIOID like '".$busqueda."' OR s.ED05_ASISTENCIAID like '".$busqueda."' ";		
							
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
										
								
								}						
							}								
						}
					


						$sSQL="SELECT
								ED01_USUARIOID,
								ED05_ASISTENCIAID
								FROM
								e_desk.ED11_USUARIO_ASISTENCIA_TECNICA
								WHERE
								ED11_TIPOASIGNACION=2 
								order by
								ED11_FECHAASIGNACION desc, 
								ED05_ASISTENCIAID ";
							
						$rowset = $DB->fetchAll($sSQL);
		
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
							
								$ID=$row_datosQuery["ED05_ASISTENCIAID"];
								
								if(!isset($datos_derivados["$ID"]))
								{
									$datos_derivados["$ID"]["ED05_ASISTENCIAID"]=$row_datosQuery["ED05_ASISTENCIAID"];
									$datos_derivados["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
							
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
		
    
	
	
	
	
	}


}