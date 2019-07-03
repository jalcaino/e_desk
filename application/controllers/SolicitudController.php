<?php
class SolicitudController extends Zend_Controller_Action
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

    public function agregarsolicitudAction()
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
			
					
			
			
				if(isset($datoscolegio))
					Zend_Layout::getMvcInstance()->assign('datoscolegio',$datoscolegio);
			
			
			
			
				if(isset($datosproducto))
					Zend_Layout::getMvcInstance()->assign('datosproducto',$datosproducto);
				
			
					
	
	}

    public function agregarsolicitudprocessAction()
    {


					$uploads = '/var/www/html/edesk/public/archivos_upload';
					$uploads_public = '/archivos_upload';
					$separador = '/';
				   

    
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();

		
					$edesk_session = new Zend_Session_Namespace('edeskses');
	
					$colegio=$this->_request->getPost('colegio');
					$producto=$this->_request->getPost('producto');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
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
													'SIS04_PRODUCTOID' => $producto,
													'ED02_FECHASOLICITUD' => $calendario_ingles,
													'ED02_DETALLESOLICITUD' => $detalle,
													'ED02_ARCHIVOADJUNTO' => $fileRuta,
													'ED02_NOMBREARCHIVOADJUNTO' => $fileName,
													'ED02_TIPOARCHIVOADJUNTO' => $fileType,
													'ED02_NOMBRESOLICITANTE' => $edesk_session->NOMBREAPELLIDO,
													'ED02_FECHAINGRESO' => date("Ymdhis"),
													'ED02_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
													'ED02_ESTADO' => 'PENDIENTE',
													'ED01_USUARIOID' => $edesk_session->USUARIOID 
												);
												

					
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED02_SOLICITUD', $data);
							
													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													echo("KO|".$e->getMessage());
													exit;	
												}


												#################################
												##RESCATAMOS ULTIMO ID INGRESADO
												#################################
												$sSQL="SELECT max(ED02_SOLICITUDID) as ingresado FROM e_desk.ED02_SOLICITUD";
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
												
												$sSQL="SELECT ED01_EMAIL FROM e_desk.ED01_USUARIO WHERE ED01_ESPRIVADO=0 and (SIS02_NIVELID in (2,3) or ED01_USUARIOID='".$edesk_session->USUARIOID."') ";
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
												$subject="INTERNO - CREACION DE SOLICITUD E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha generado la solicitud numero : <strong>$nueva_solicitud</strong> <br><br>
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
								
								
												$data_actividad = array(
															'ED01_USUARIOID' => $edesk_session->USUARIOID,
															'ED08_ACCION' => 'AGREGAR SOLICITUD',
															'ED08_MASINFO' => 'NUM:'.$nueva_solicitud
															);
											
								
												#############################
												##FIN MAIL CREACION USUARIO
												#############################


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('bd_correos.correos_soporte', $data_email);
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
							
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


    public function editarsolicitudAction()
    {
    
						
					$this->_helper->layout->disableLayout();
					$config = Zend_Registry::get('config');
					$DB = Zend_Db_Table::getDefaultAdapter();
					
					$solicitudid=$this->_request->getPost('solicitudid');
			
					
					$SOLICITUDID="";
					$LABORATORIOID="";
					$PRODUCTOID="";
					$FECHASOLICITUD="";
					$DETALLESOLICITUD="";
					$ARCHIVOADJUNTO="";
					$NOMBREARCHIVOADJUNTO="";
					$TIPOARCHIVOADJUNTO="";
					$NOMBRESOLICITANTE="";
					$ED02_ESTADO="";
			
			
				
					//validamos que no exista usuario
					$sSQL = "	SELECT 
								s.ED02_SOLICITUDID, 
								s.SIS03_LABORATORIOID, 
								l.SIS03_LABORATORIODESCRIPCION, 
								s.SIS04_PRODUCTOID, 
								DATE_FORMAT(s.ED02_FECHASOLICITUD, '%d/%m/%Y') as FECHASOLICITUD, 
								s.ED02_DETALLESOLICITUD, 
								s.ED02_ARCHIVOADJUNTO, 
								s.ED02_NOMBREARCHIVOADJUNTO, 
								s.ED02_TIPOARCHIVOADJUNTO, 
								s.ED02_NOMBRESOLICITANTE,
								s.ED02_ESTADO 
								FROM 
								e_desk.ED02_SOLICITUD s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
								WHERE 
								s.ED02_SOLICITUDID = '$solicitudid' ";
								
					
								
							$rowset = $DB->fetchAll($sSQL);

							foreach($rowset as $row_datosQuery)
							{
							
									if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
									{
									
											$SOLICITUDID=$row_datosQuery["ED02_SOLICITUDID"];
											$LABORATORIOID=$row_datosQuery["SIS03_LABORATORIOID"];
											$LABORATORIODESCRIPCION=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
											$PRODUCTOID=$row_datosQuery["SIS04_PRODUCTOID"];
											$FECHASOLICITUD=$row_datosQuery["FECHASOLICITUD"];
											$DETALLESOLICITUD=$row_datosQuery["ED02_DETALLESOLICITUD"];
											$ARCHIVOADJUNTO=$row_datosQuery["ED02_ARCHIVOADJUNTO"];
											$NOMBREARCHIVOADJUNTO=$row_datosQuery["ED02_NOMBREARCHIVOADJUNTO"];
											$TIPOARCHIVOADJUNTO=$row_datosQuery["ED02_TIPOARCHIVOADJUNTO"];
											$NOMBRESOLICITANTE=$row_datosQuery["ED02_NOMBRESOLICITANTE"];
											$ED02_ESTADO=$row_datosQuery["ED02_ESTADO"];
								
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
					

					if(isset($datoscolegio))
						Zend_Layout::getMvcInstance()->assign('datoscolegio',$datoscolegio);
				
				
					if(isset($datosproducto))
						Zend_Layout::getMvcInstance()->assign('datosproducto',$datosproducto);
					

						Zend_Layout::getMvcInstance()->assign('SOLICITUDID',$SOLICITUDID);
						Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
						Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
						Zend_Layout::getMvcInstance()->assign('PRODUCTOID',$PRODUCTOID);
						Zend_Layout::getMvcInstance()->assign('FECHASOLICITUD',$FECHASOLICITUD);
						Zend_Layout::getMvcInstance()->assign('DETALLESOLICITUD',$DETALLESOLICITUD);
						Zend_Layout::getMvcInstance()->assign('ARCHIVOADJUNTO',$ARCHIVOADJUNTO);
						Zend_Layout::getMvcInstance()->assign('NOMBREARCHIVOADJUNTO',$NOMBREARCHIVOADJUNTO);
						Zend_Layout::getMvcInstance()->assign('TIPOARCHIVOADJUNTO',$TIPOARCHIVOADJUNTO);
						Zend_Layout::getMvcInstance()->assign('NOMBRESOLICITANTE',$NOMBRESOLICITANTE);
						Zend_Layout::getMvcInstance()->assign('ED02_ESTADO',$ED02_ESTADO);
				
				
				
	
	}

    public function editarsolicitudprocessAction()
    {
    			
		
					$uploads = '/var/www/html/edesk/public/archivos_upload';
					$uploads_public = '/archivos_upload';
					$separador = '/';
				   

    
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();

		
					$edesk_session = new Zend_Session_Namespace('edeskses');
	

					$solicitudid=$this->_request->getPost('solicitudid');
					$colegio=$this->_request->getPost('colegio');
					$producto=$this->_request->getPost('producto');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
					$archivo=$this->_request->getPost('archivo');
					$estado=$this->_request->getPost('estado');
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
															'SIS04_PRODUCTOID' => $producto,
															'ED02_FECHASOLICITUD' => $calendario_ingles,
															'ED02_DETALLESOLICITUD' => $detalle,
															'ED02_ARCHIVOADJUNTO' => $fileRuta,
															'ED02_NOMBREARCHIVOADJUNTO' => $fileName,
															'ED02_TIPOARCHIVOADJUNTO' => $fileType,
															'ED02_NOMBRESOLICITANTE' => $edesk_session->USUARIOID,
															'ED02_FECHAINGRESO' => date("Ymdhis"),
															'ED02_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
															'ED02_ESTADO' => $estado 
														);
												
												}else{
												
														$data = array(
															'SIS03_LABORATORIOID' => $ELCOLEGIO,
															'SIS04_PRODUCTOID' => $producto,
															'ED02_FECHASOLICITUD' => $calendario_ingles,
															'ED02_DETALLESOLICITUD' => $detalle,
															'ED02_NOMBRESOLICITANTE' => $edesk_session->USUARIOID,
															'ED02_FECHAINGRESO' => date("Ymdhis"),
															'ED02_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
															'ED02_ESTADO' => $estado
														);
													
												}


												$where['ED02_SOLICITUDID = ?'] = $solicitudid;
																				


												$data_actividad = array(
															'ED01_USUARIOID' => $edesk_session->USUARIOID,
															'ED08_ACCION' => 'EDITAR SOLICITUD',
															'ED08_MASINFO' => 'NUM:'.$solicitudid
															);
											

												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->update('e_desk.ED02_SOLICITUD', $data, $where);
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
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
												
												$sSQL="SELECT ED01_EMAIL FROM e_desk.ED01_USUARIO WHERE ED01_ESPRIVADO=0 and (SIS02_NIVELID in (2,3) or ED01_USUARIOID='".$edesk_session->USUARIOID."') ";
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
												$subject="INTERNO - ACTUALIZACION DE SOLICITUD E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha actualizado la solicitud numero : <strong>$solicitudid</strong> <br><br>
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


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('bd_correos.correos_soporte', $data_email);
							
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

    public function eliminarsolicitudAction()
    {
    
	
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
						$edesk_session = new Zend_Session_Namespace('edeskses');
	
				
						$solicitudid=$this->_request->getPost('solicitudid');
					
						$where['ED02_SOLICITUDID = ?'] = $solicitudid;
			
			
						$data_actividad = array(
									'ED01_USUARIOID' => $edesk_session->USUARIOID,
									'ED08_ACCION' => 'ELIMINAR SOLICITUD',
									'ED08_MASINFO' => 'NUM:'.$solicitudid
									);
					
			
						try {

							$n = $DB->delete("e_desk.ED02_SOLICITUD", $where);
							$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
							//FALTA AQUI LOG DE ACCION

							echo "Solicitud eliminada correctamente...";

						} catch (Zend_Exception $e) {

							echo $e->getMessage();

						}
		
			
	
				
	}

    public function listarsolicitudesAction()
    {
    
	
	
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
						$edesk_session = new Zend_Session_Namespace('edeskses');
					
					
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
					
						//SOLICITUDES
						////////////////////////////
						$sSQL="SELECT 
								s.ED02_SOLICITUDID, 
								s.SIS03_LABORATORIOID, 
								l.SIS03_LABORATORIODESCRIPCION,
								s.SIS04_PRODUCTOID, 
								DATE_FORMAT(s.ED02_FECHASOLICITUD, '%d/%m/%Y') as FECHASOLICITUD, 
								s.ED02_DETALLESOLICITUD, 
								s.ED02_ARCHIVOADJUNTO, 
								s.ED02_NOMBREARCHIVOADJUNTO, 
								s.ED02_TIPOARCHIVOADJUNTO, 
								s.ED02_NOMBRESOLICITANTE, 
								DATE_FORMAT(s.ED02_FECHAINGRESO, '%d/%m/%Y') as FECHAINGRESO,
								DATE_FORMAT(s.ED02_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION,
								s.ED02_ESTADO 
								FROM 
								e_desk.ED02_SOLICITUD s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID ";
								
						
						if(trim($busqueda)!="")
								$sSQL.=" WHERE s.ED02_ESTADO like '%".$busqueda."%' OR s.SIS03_LABORATORIOID like '".$busqueda."' OR s.ED02_SOLICITUDID like '".$busqueda."' ";		
							
						$sSQL.=" ORDER BY ED02_SOLICITUDID desc ";
					
					
					 	$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								$CONTADOR_FILAS++;
								
								if($CONTADOR_FILAS>=$CONTADOR_INI && $CONTADOR_FILAS<=$CONTADOR_FIN)
								{
						
									$ID=$row_datosQuery["ED02_SOLICITUDID"];
									$datossolicitudes["$ID"]["ED02_SOLICITUDID"]=$row_datosQuery["ED02_SOLICITUDID"];
									$datossolicitudes["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
									$datossolicitudes["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
									$datossolicitudes["$ID"]["SIS04_PRODUCTOID"]=$row_datosQuery["SIS04_PRODUCTOID"];
									$datossolicitudes["$ID"]["FECHASOLICITUD"]=$row_datosQuery["FECHASOLICITUD"];
									$datossolicitudes["$ID"]["ED02_DETALLESOLICITUD"]=$row_datosQuery["ED02_DETALLESOLICITUD"];
									$datossolicitudes["$ID"]["ED02_ARCHIVOADJUNTO"]=$row_datosQuery["ED02_ARCHIVOADJUNTO"];
									$datossolicitudes["$ID"]["ED02_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED02_NOMBREARCHIVOADJUNTO"];
									$datossolicitudes["$ID"]["ED02_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED02_TIPOARCHIVOADJUNTO"];
									$datossolicitudes["$ID"]["ED02_NOMBRESOLICITANTE"]=$row_datosQuery["ED02_NOMBRESOLICITANTE"];
									$datossolicitudes["$ID"]["FECHAINGRESO"]=$row_datosQuery["FECHAINGRESO"];
									$datossolicitudes["$ID"]["FECHAULTIMAACTUALIZACION"]=$row_datosQuery["FECHAULTIMAACTUALIZACION"];
									$datossolicitudes["$ID"]["ED02_ESTADO"]=$row_datosQuery["ED02_ESTADO"];
							
								}						
							}								
						}
					
					
					
					
					
					
					
					
						$NUM_PAGINAS=intval($CONTADOR_FILAS/15);
						$RESTO_PAGINAS = $CONTADOR_FILAS%15;
						if($RESTO_PAGINAS>0)
						   $NUM_PAGINAS++;
						   
						   	
					
						if(isset($datossolicitudes))
								Zend_Layout::getMvcInstance()->assign('datossolicitudes',$datossolicitudes);
				
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
					
					//permisos
					/*
					1 - ver
					2 - agregar
					3 - editar
					4 - eliminar
					5 - seguimiento
					6 - generar incidente/asistencia
					*/
				
					
						
					$menu=$config['vectorMenu'];
					$submenu=$config['vectorSubMenu'];
					$permisos=$config['vectorPermisos'];
					$nivelid=trim($edesk_session->NIVELID);
					$sectorid=trim($edesk_session->SECTORID);
				
					$nivel_compuesto="N".$nivelid."ACC";
					$sector_compuesto=$sectorid;
				
					if(isset($sector_compuesto) && isset($permisos) && isset($permisos[$sector_compuesto]))
					$arreglo=$permisos[$sector_compuesto];
					
					$ACCESOS="";
				
					if(isset($arreglo))
					{
						foreach($arreglo as $clave => $valor)
						{
							if($clave==$nivel_compuesto)
							{
								$ACCESOS=$valor;
							}
						}
					}
					
					
					$IDFINAL=0;
					$parte_link = explode("/",$_SERVER['HTTP_REFERER']);
					$ELCONTROLLER=trim($parte_link[3]);
				
					foreach($menu as $clave => $valor)
					{
							$ELLINK="";
							$ELSUB=0;
							$ELID=0;
				
							foreach($valor as $clave2 => $valor2)
							{
								if($clave2=="LINK") $ELLINK=$valor2;
								if($clave2=="SUB") $ELSUB=$valor2;
								if($clave2=="ID") $ELID=$valor2;
							}
				
							if($ELSUB==0)
							{
							
								if($ELCONTROLLER==trim(str_replace('/', '',$ELLINK)))
								{  
										$IDFINAL=$ELID;
										break;
								} 
							
							}else{
										foreach($submenu as $clave3 => $valor3)
										{
												$ELLINK2="";
												$ELPADRE="";
												$ELID2=0;
				
												foreach($valor3 as $clave3 => $valor3)
												{
													if($clave3=="LINK") $ELLINK2=$valor3;
													if($clave3=="PADRE") $ELPADRE=$valor3;
													if($clave3=="ID") $ELID2=$valor3;
				
												}
									
												if($ELPADRE==$ELID)
												{
													if($ELCONTROLLER==trim(str_replace('/', '',$ELLINK2)))
													{ 
														$IDFINAL=$ELID2;
														break;
													} 
												
												}
									
										}				
												
								}
					
					}	
				
					$PERMISOSFINAL=0;
				
					$arregloacceso = explode("@@",$ACCESOS);
					foreach($arregloacceso as $llave => $valores)
					{
						$arregloaccesoper = explode("-",$valores);
						if($arregloaccesoper[0]==$IDFINAL)
						{
						   $PERMISOSFINAL=$arregloaccesoper[1];				
							
						   $arreglopermisos = explode("#",$PERMISOSFINAL);
						
							if(count($arreglopermisos)>1)
							{
							
								for($i=0;$i<count($arreglopermisos);$i++)
								{
									$IDENPER=$arreglopermisos[$i];
									$acceso_funcionalidades[$IDENPER]=1;
						   		}
							
							
							
							}else{
							
								for($i=1;$i<=$PERMISOSFINAL;$i++)
								{
									$acceso_funcionalidades[$i]=1;
						   		}
							
							}
						 
						
							break;
						
						}
					}
					
					
					if(isset($acceso_funcionalidades))
						Zend_Layout::getMvcInstance()->assign('acceso_funcionalidades',$acceso_funcionalidades);
					
				
					//echo "-----".$ACCESOS."/".$IDFINAL."/-----<br><br>";
					//echo "-----".print_r($acceso_funcionalidades)."-----";
					
					
					#############################
					##FIN RESCATE PERMISOS
					#############################
					
						
	
	
	
	
	
	}


}