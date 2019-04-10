<?php
class UsuarioController extends Zend_Controller_Action
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

		public function agregarusuarioAction()
		{
						
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
					
					
					
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
						
					
					
					
		}

	
		public function agregarusuarioprocessAction()
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
					
								  $no_existe=0;
			
			
								  //validamos que no exista usuario
								  $sSQL = "SELECT * FROM e_desk.ED01_USUARIO ";
								  $sSQL .= "WHERE ED01_USUARIOID = '$login'"; 
									
		
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

		
									if($no_existe==0)
									{
		
										
												//insertamos con try
												$data = array(
													'ED01_USUARIOID' => $login,
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
							
							
							
												#############################
												##MAIL CREACION USUARIO
												#############################
							
												$from="helpdesk@compumat.cl";
												$to=$email;
												$subject="INTERNO - CREACION DE USUARIO E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha procedido a a la creaci&oacute;n<br><br>
													   del usuario E-DESK ($login) asociado a su email $email<br><br>
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
													$DB->insert('e_desk.ED01_USUARIO', $data);
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
									
											echo("KO|Ya existe el login de usuario, ingrese otro");
											exit;

									
									}


					}else{
					
								echo("KO|Accion invalida");
								exit;
					}		
			
	
	
		}
	
	
		public function editarusuarioAction()
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

	
		public function editarusuarioprocessAction()
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
									   Con fecha de hoy ".date("d/m/Y")." se ha procedido a a la modificaci&oacute;n<br><br>
									   del usuario E-DESK ($login) asociado a su email $email<br><br>
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
	
	
	
		public function eliminarusuarioAction()
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


		public function listarusuariosAction()
		{
						
						
						
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
					
						$CONTADOR_USUARIOS_INI=1;
						$CONTADOR_USUARIOS_FIN=15;
						$PAGINA=1;

						$lapagina=$this->_request->getPost('pagina');
						
						if($lapagina!="")
						{
								$PAGINA=$lapagina;
								$CONTADOR_USUARIOS_INI=(($lapagina*15)+1)-15;
								$CONTADOR_USUARIOS_FIN=($lapagina*15);
						}
			
						

						$CONTADOR_USUARIOS=0;
					
						//ALUMNOS
						////////////////////////////
						$sSQL="SELECT
								ED01_USUARIOID,
								SIS02_NIVELID,
								SIS01_SECTORID,
								ED01_NOMBREAPELLIDO,
								ED01_EMAIL,
								ED01_PASSWORD,
								ED01_ESPRIVADO,
								ED01_AVISARASIGNACION,
								ED01_AVISARSOLICITUD,
								DATE_FORMAT(ED01_FECHAINGRESO, '%d/%m/%Y') as FECHA_INGRESO,
								DATE_FORMAT(ED01_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHA_ACTUALIZACION 
								FROM
								e_desk.ED01_USUARIO
								ORDER BY
								ED01_NOMBREAPELLIDO";
					
					
					 	$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
								$CONTADOR_USUARIOS++;
								
								if($CONTADOR_USUARIOS>=$CONTADOR_USUARIOS_INI && $CONTADOR_USUARIOS<=$CONTADOR_USUARIOS_FIN)
								{
									$ID=$row_datosQuery["ED01_USUARIOID"];
									$datosusuarios["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
									$datosusuarios["$ID"]["SIS02_NIVELID"]=$row_datosQuery["SIS02_NIVELID"];
									$datosusuarios["$ID"]["SIS01_SECTORID"]=$row_datosQuery["SIS01_SECTORID"];
									$datosusuarios["$ID"]["ED01_NOMBREAPELLIDO"]=$row_datosQuery["ED01_NOMBREAPELLIDO"];
									$datosusuarios["$ID"]["ED01_EMAIL"]=$row_datosQuery["ED01_EMAIL"];
									$datosusuarios["$ID"]["ED01_ESPRIVADO"]=$row_datosQuery["ED01_ESPRIVADO"];
									$datosusuarios["$ID"]["ED01_AVISARASIGNACION"]=$row_datosQuery["ED01_AVISARASIGNACION"];
									$datosusuarios["$ID"]["ED01_AVISARSOLICITUD"]=$row_datosQuery["ED01_AVISARSOLICITUD"];
									$datosusuarios["$ID"]["FECHA_INGRESO"]=$row_datosQuery["FECHA_INGRESO"];
									$datosusuarios["$ID"]["FECHA_ACTUALIZACION"]=$row_datosQuery["FECHA_ACTUALIZACION"];
								}						
							}								
						}
					
					
						$NUM_PAGINAS=intval($CONTADOR_USUARIOS/15);
						$RESTO_PAGINAS = $CONTADOR_USUARIOS%15;
						if($RESTO_PAGINAS>0)
						   $NUM_PAGINAS++;
						   
						   	
					
						if(isset($datosusuarios))
								Zend_Layout::getMvcInstance()->assign('datosusuarios',$datosusuarios);
				
						if(isset($PAGINA))
								Zend_Layout::getMvcInstance()->assign('pagina',$PAGINA);
						
						if(isset($CONTADOR_USUARIOS))
								Zend_Layout::getMvcInstance()->assign('total_usuarios',$CONTADOR_USUARIOS);
					
						if(isset($NUM_PAGINAS))
								Zend_Layout::getMvcInstance()->assign('num_paginas',$NUM_PAGINAS);
					
					
						if(isset($CONTADOR_USUARIOS_INI))
								Zend_Layout::getMvcInstance()->assign('usuario_ini',$CONTADOR_USUARIOS_INI);
						
						if(isset($CONTADOR_USUARIOS_FIN))
								Zend_Layout::getMvcInstance()->assign('usuario_fin',$CONTADOR_USUARIOS_FIN);
						
						
		}



}