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
    
					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();

					$colegio=$this->_request->getPost('colegio');
					$producto=$this->_request->getPost('producto');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
					$archivo=$this->_request->getPost('archivo');
					$accion=$this->_request->getPost('grabar');
			
					$porciones = explode("|",$colegio);
				
					$edesk_session = new Zend_Session_Namespace('edeskses');
	
				
			        
					///////////
					$adapter = new Zend_File_Transfer_Adapter_Http();
					$adapter->addValidator('Count',false, array('min'=>1, 'max'=>3))
					->addValidator('Size',false,array('max' => 10000))
					->addValidator('Extension',false,array('extension' => 'txt','case' => true));
					
					
					$adapter->setDestination("/var/www/html/edesk/public/");
					
					$files = $adapter->getFileInfo();
					
					foreach($files as $fieldname=>$fileinfo)
					{
						if (($adapter->isUploaded($fileinfo['name']))&& ($adapter->isValid($fileinfo['archivo'])))
						{
							$adapter->receive($fileinfo[name]);
						}
					
					}
					
					print_r($files);
					exit;
					
					///////////
					
					
					
					
					    
			
			
					if($accion=="grabar")
					{
					
								  	$no_existe=0;
						
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
														$no_existe=1;
													}
									
												} catch (Zend_Exception $e) {
									
													echo("KO|".$e->getMessage());
													exit;	
											
												}
			
									}		
		



		
									if($no_existe==1)
									{
			
												//insertamos con try
												$data = array(
													'SIS03_LABORATORIOID' => $ELCOLEGIO,
													'SIS04_PRODUCTOID' => $producto,
													'ED02_FECHASOLICITUD' => $calendario,
													'ED02_DETALLESOLICITUD' => $detalle,
													'ED02_ARCHIVOADJUNTO' => $login,
													'ED02_NOMBREARCHIVOADJUNTO' => $archivo,
													'ED02_TIPOARCHIVOADJUNTO' => $archivo,
													'ED02_NOMBRESOLICITANTE' => $edesk_session->login,
													'ED02_FECHAINGRESO' => date("Ymdhis"),
													'ED02_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
													'ED02_ESTADO' => 'PEN'
												);
													
							
							
												#############################
												##MAIL CREACION SOLICITUD
												#############################
							
												$from="helpdesk@compumat.cl";
												$to=$email;
												$subject="INTERNO - CREACION DE SOLICITUD E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha generado una nueva solicitud <br><br>
													   de usuario E-DESK Login : ($edesk_session->login) <br><br>
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
					
												//FALTA
												//NUMERO SOLICITUD
												//DETALLE EN LISTADO
												//LOGIN DE USUARIO
												//A QUIEN LE MANDO MAIL???
												
					
					
					
							
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED02_SOLICITUD', $data);
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

    public function editarsolicitudAction()
    {
    
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						$DB = Zend_Db_Table::getDefaultAdapter();
						
			
			
						$loginusuario=$this->_request->getPost('loginusuario');
				
				
						$USUARIOID="";
						$NIVELID="";
						$SECTORID="";
						$NOMBREAPELLIDO="";
						$EMAIL="";
						$PASSWORD="";
						$ESPRIVADO="";
						$AVISARASIGNACION="";
						$AVISARSOLICITUD="";

				
						//validamos que no exista usuario
						$sSQL = "	SELECT 
									ED01_USUARIOID,
									SIS02_NIVELID,
									SIS01_SECTORID,
									ED01_NOMBREAPELLIDO,
									ED01_EMAIL,
									ED01_PASSWORD,
									ED01_ESPRIVADO,
									ED01_AVISARASIGNACION,
									ED01_AVISARSOLICITUD
									FROM
									e_desk.ED01_USUARIO WHERE ED01_USUARIOID = '$loginusuario'"; 
							
							 		$rowset = $DB->fetchAll($sSQL);

							foreach($rowset as $row_datosQuery)
							{
								if(trim($row_datosQuery["ED01_USUARIOID"])!="")
								{
								
									$USUARIOID=$row_datosQuery["ED01_USUARIOID"];
									$NIVELID=$row_datosQuery["SIS02_NIVELID"];
									$SECTORID=$row_datosQuery["SIS01_SECTORID"];
									$NOMBREAPELLIDO=$row_datosQuery["ED01_NOMBREAPELLIDO"];
									$EMAIL=$row_datosQuery["ED01_EMAIL"];
									$PASSWORD=$row_datosQuery["ED01_PASSWORD"];
									$ESPRIVADO=$row_datosQuery["ED01_ESPRIVADO"];
									$AVISARASIGNACION=$row_datosQuery["ED01_AVISARASIGNACION"];
									$AVISARSOLICITUD=$row_datosQuery["ED01_AVISARSOLICITUD"];
								
								}								
							}
							
		
			
						//NIVEL
						////////////////////////////
						$sSQL="SELECT
								SIS02_NIVELID,
								SIS02_NIVELDESCRIPCION
								FROM
								e_desk.SIS02_NIVEL_USUARIO";
					
					
					 	$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["SIS02_NIVELID"])!="")
							{
								$ID=$row_datosQuery["SIS02_NIVELID"];
								$datosnivel["$ID"]["SIS02_NIVELID"]=$row_datosQuery["SIS02_NIVELID"];
								$datosnivel["$ID"]["SIS02_NIVELDESCRIPCION"]=$row_datosQuery["SIS02_NIVELDESCRIPCION"];
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
					
					
					
						if(isset($datosnivel))
							Zend_Layout::getMvcInstance()->assign('datosnivel',$datosnivel);
					
					
						if(isset($datossector))
							Zend_Layout::getMvcInstance()->assign('datossector',$datossector);
						
				

						Zend_Layout::getMvcInstance()->assign('USUARIOID',$USUARIOID);
						Zend_Layout::getMvcInstance()->assign('NIVELID',$NIVELID);
						Zend_Layout::getMvcInstance()->assign('SECTORID',$SECTORID);
						Zend_Layout::getMvcInstance()->assign('NOMBREAPELLIDO',$NOMBREAPELLIDO);
						Zend_Layout::getMvcInstance()->assign('EMAIL',$EMAIL);
						Zend_Layout::getMvcInstance()->assign('PASSWORD',$PASSWORD);
						Zend_Layout::getMvcInstance()->assign('ESPRIVADO',$ESPRIVADO);
						Zend_Layout::getMvcInstance()->assign('AVISARASIGNACION',$AVISARASIGNACION);
						Zend_Layout::getMvcInstance()->assign('AVISARSOLICITUD',$AVISARSOLICITUD);

		
	
	}

    public function editarsolicitudprocessAction()
    {
    $this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();

					$login=$this->_request->getPost('login');
					$nivel=$this->_request->getPost('nivel');
					$sector=$this->_request->getPost('sector');
					$nombreapellido=$this->_request->getPost('nombreapellido');
					$email=$this->_request->getPost('email');
					$clave=$this->_request->getPost('clave');
					$privado=$this->_request->getPost('privado');
					$notiasig=$this->_request->getPost('notiasig');
					$notisoli=$this->_request->getPost('notisoli');
					$accion=$this->_request->getPost('accion');
			
			
			
					if($accion=="grabar")
					{
					
								//insertamos con try
								$data = array(
									'SIS02_NIVELID' => $nivel,
									'SIS01_SECTORID' => $sector,
									'ED01_NOMBREAPELLIDO' => $nombreapellido,
									'ED01_EMAIL' => $email,
									'ED01_PASSWORD' => MD5($clave),
									'ED01_ESPRIVADO' => $privado,
									'ED01_AVISARASIGNACION' => $notiasig,
									'ED01_AVISARSOLICITUD' => $notisoli,
									'ED01_FECHAINGRESO' => date("Ymdhis"),
									'ED01_FECHAULTIMAACTUALIZACION' => date("Ymdhis")
								);
			
			
								$where['ED01_USUARIOID = ?'] = $login;
								    
			
	
	
								#############################
								##MAIL EDICION USUARIO
								#############################
			
								$from="helpdesk@compumat.cl";
								$to=$email;
								$subject="INTERNO - EDICION DE USUARIO E-DESK";
								$body="<u>Estimado Usuario</u><br><br>
									   Con fecha de hoy ".date("d/m/Y")." se ha procedido a la modificaci&oacute;n<br><br>
									   del usuario E-DESK Login : ($login) , Clave : ($clave)  asociado a su email $email<br><br>
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
								##FIN MAIL EDICION USUARIO
								#############################
	
			
			
			
			
								try {
			
									$DB->getConnection();
									$DB->beginTransaction();
								    $DB->update('e_desk.ED01_USUARIO', $data, $where);
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
					
								echo("KO|Accion invalida");
								exit;
					}		
		
		
		
	
	}

    public function eliminarsolicitudAction()
    {
    
	
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
				
						$loginusuario=$this->_request->getPost('loginusuario');
					
						$where['ED01_USUARIOID = ?'] = $loginusuario;
			
						try {

							$n = $DB->delete("e_desk.ED01_USUARIO", $where);

							echo "Usuario eliminado correctamente...";

						} catch (Zend_Exception $e) {

							echo $e->getMessage();

						}
		
	
	}

    public function listarsolicitudesAction()
    {
    
	
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
					
						$CONTADOR_INI=1;
						$CONTADOR_FIN=15;
						$PAGINA=1;

						$lapagina=$this->_request->getPost('pagina');
						
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
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
								ORDER BY ED02_ESTADO desc, 
								ED02_FECHASOLICITUD desc ";
					
					
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
						
	
	
	}


}