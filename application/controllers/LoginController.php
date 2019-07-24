<?php
class LoginController extends Zend_Controller_Action
{

		public function init()
		{
			/* Initialize action controller here */
		}
	
		public function indexAction()
		{
						
						$this->_helper->layout->disableLayout();
						
						
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

		public function validarusuarioAction()
		{
						
						$this->_helper->layout->disableLayout();
					
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
											

						###########################		
						##inicio validacion sesion
						###########################	
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
				
						###########################		
						##fin validacion sesion
						###########################	
		
						$login=$this->_request->getPost('login');
						$clave=$this->_request->getPost('clave');
						$recuerdame=$this->_request->getPost('recuerdame');
				
				
				
						$ENCONTRO=0;
				
						$clave_md5=md5($clave);			
									
						$sSQL = "SELECT
								u.ED01_USUARIOID,
								u.SIS02_NIVELID,
								n.SIS02_NIVELDESCRIPCION,	
								u.SIS01_SECTORID,
								s.SIS01_SECTORDESCRIPCION,
								u.ED01_NOMBREAPELLIDO,
								u.ED01_EMAIL,
								u.ED01_PASSWORD,
								u.ED01_ESPRIVADO,
								u.ED01_NOTIFICAR
								FROM
								e_desk.ED01_USUARIO u
								LEFT JOIN
								e_desk.SIS01_SECTOR s ON u.SIS01_SECTORID=s.SIS01_SECTORID
								LEFT JOIN
								e_desk.SIS02_NIVEL_USUARIO n ON u.SIS02_NIVELID=n.SIS02_NIVELID
								WHERE 
								u.ED01_USUARIOID = '$login' and u.ED01_PASSWORD='$clave_md5' ";
							
							
							
					 		$rowset = $DB->fetchAll($sSQL);

							foreach($rowset as $row_datosQuery)
							{
								if(trim($row_datosQuery["ED01_USUARIOID"])!="")
								{
								
									$USUARIOID=$row_datosQuery["ED01_USUARIOID"];
									$NIVELID=$row_datosQuery["SIS02_NIVELID"];
									$NIVELDESCRIPCION=$row_datosQuery["SIS02_NIVELDESCRIPCION"];
									$SECTORID=$row_datosQuery["SIS01_SECTORID"];
									$SECTORDESCRIPCION=$row_datosQuery["SIS01_SECTORDESCRIPCION"];
									$NOMBREAPELLIDO=$row_datosQuery["ED01_NOMBREAPELLIDO"];
									$EMAIL=$row_datosQuery["ED01_EMAIL"];
									$ESPRIVADO=$row_datosQuery["ED01_ESPRIVADO"];
									$NOTIFICAR=$row_datosQuery["ED01_NOTIFICAR"];
									
									 //30 minutos
								    $edesk_session->setExpirationSeconds(60*60*24*1);
								
									$edesk_session->ID=session_id();
									$edesk_session->USUARIOID=$USUARIOID;
									$edesk_session->NIVELID=$NIVELID;
									$edesk_session->NIVELDESCRIPCION=$NIVELDESCRIPCION;
									$edesk_session->SECTORID=$SECTORID;
									$edesk_session->SECTORDESCRIPCION=$SECTORDESCRIPCION;
									$edesk_session->NOMBREAPELLIDO=$NOMBREAPELLIDO;
									$edesk_session->EMAIL=$EMAIL;
									$edesk_session->ESPRIVADO=$ESPRIVADO;
									$edesk_session->NOTIFICAR=$NOTIFICAR;
									
									Zend_Registry::set('session', $edesk_session);
									
									
									
									
									$ENCONTRO=1;
								
								}								
							}
							
		
							if($ENCONTRO==0)
							{
							
							
										if(isset($_COOKIE['cok_login'])) unset($_COOKIE['cok_login']);
										if(isset($_COOKIE['cok_clave'])) unset($_COOKIE['cok_clave']);
								
										setcookie('cok_login', null, -1, '/');
										setcookie('cok_clave', null, -1, '/');
							
							
							
										echo "KO|Usuario no existe en el sistema!!!";
							}else{
								
								
										//para recordar datos en el formulario
										if($recuerdame==1)
										{
											setcookie("cok_login",$login,time()+(60*60*24*365),"/");
											setcookie("cok_clave",$clave,time()+(60*60*24*365),"/");
											setcookie("cok_recuerdame","1",time()+(60*60*24*365),"/");
										
										}else{
										
												if(isset($_COOKIE['cok_login'])) unset($_COOKIE['cok_login']);
												if(isset($_COOKIE['cok_clave'])) unset($_COOKIE['cok_clave']);
												if(isset($_COOKIE['cok_recuerdame'])) unset($_COOKIE['cok_recuerdame']);
										
												setcookie('cok_login', null, -1, '/');
												setcookie('cok_clave', null, -1, '/');
												setcookie('cok_recuerdame', null, -1, '/');
										
										}					
								
								
										///////////////////////////////////
										///GUARDADO REGISTRO ACTIVIDAD
										//////////////////////////////////
										$data_actividad = array(
															'ED01_USUARIOID' => $edesk_session->USUARIOID,
															'ED08_ACCION' => 'LOGIN',
															'ED08_MASINFO' => ''
															);
																				
														
										try {
																					
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													$DB->commit();
																		
											} catch (Zend_Exception $e) {
																				
													$DB->rollBack();
										   }									
										///////////////////////////////////
										///FIN GUARDADO REGISTRO ACTIVIDAD
										//////////////////////////////////
															
								
										echo "OK|";
								 
								 }	
		
		}


		public function recordarclaveAction()
		{
						
						$this->_helper->layout->disableLayout();
						
		}
		
		
		public function recordarclaveprocessAction()
		{
						
						//generamos una nueva contraseña de largos 10
						//////////////////////////////////////////////
						$longitud=10;
						$key = '';
						$pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
						$max = strlen($pattern)-1;
						for($i=0;$i < $longitud;$i++) $key.= $pattern{mt_rand(0,$max)};
						///////////////////////////////////////////////
 						$clave=$key;
													
					
  						//////////////////////////////////////////////
						//////////////////////////////////////////////
						
						$this->_helper->layout->disableLayout();
						
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
				

						$email=$this->_request->getPost('email');
						
						$ENCONTRO=0;
				
										
						$sSQL = "SELECT 
									ED01_USUARIOID,
									ED01_PASSWORD
									FROM
									e_desk.ED01_USUARIO WHERE ED01_EMAIL = '$email'"; 
							
				
							
					 		$rowset = $DB->fetchAll($sSQL);

							foreach($rowset as $row_datosQuery)
							{
								if(trim($row_datosQuery["ED01_USUARIOID"])!="")
								{
								
									$USUARIOID=$row_datosQuery["ED01_USUARIOID"];
									$ENCONTRO=1;
								}								
							}
		
		
							if($ENCONTRO==0)
							{
										echo "KO|Email no existe en el sistema!!!";
							}else{
			
			
			
										$key_md5=md5($key);			
									
										//insertamos con try
										$data = array(
											'ED01_PASSWORD' => $key_md5
										);
					
					
										$where['ED01_USUARIOID = ?'] = $USUARIOID;
											
					
									
										#################################
										##ENVIO DE EMAILS
										#################################
										if($email!="")
										{
												
										$subject="INTERNO - RECORDAR CLAVE DE USUARIO E-DESK";
										$body="<u>Estimado Usuario</u><br><br>
											   Con fecha de hoy ".date("d/m/Y")." se ha procedido a la recuperaci&oacute;n<br><br>
											   de la clave E-DESK Login : (<strong>$USUARIOID</strong>) , Nueva Clave : (<strong>$clave</strong>)  asociado a su email $email<br><br>
											   Atte.<br>Equipo Compumat.";
										
												$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
										}							
								
			
										#############################
										##FIN MAIL EDICION USUARIO
										#############################
			
			
										///////////////////////////////////
										///GUARDADO REGISTRO ACTIVIDAD
										//////////////////////////////////
										$data_actividad = array(
															'ED01_USUARIOID' => $USUARIOID,
															'ED08_ACCION' => 'RECORDAR CLAVE',
															'ED08_MASINFO' => 'NUEVA CLAVE:'.$clave 
															);
										///////////////////////////////////
										///FIN GUARDADO REGISTRO ACTIVIDAD
										//////////////////////////////////
					
					
										try {
					
											$DB->getConnection();
											$DB->beginTransaction();
											$DB->update('e_desk.ED01_USUARIO', $data, $where);
											$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
												
											
											$DB->commit();
						
											echo "OK|$USUARIOID|$key";
											exit;
											
										} catch (Zend_Exception $e) {
					
											$DB->rollBack();
											//echo("KO|".$e->getMessage());
											echo "KO|Se ha producido un error..(21)";
											exit;	
										}
				
								 
								 }	
						
	
	
	
	
		}



		public function notificacionAction()
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
				
				$tipo=$this->_request->getPost('tipo');
						
				if($tipo=="0")
				{
					$TIPO_DESPLIEGUE=0;	
					$this->_helper->layout->disableLayout();
			
				
				}elseif($tipo=="3")
				{
					$this->_helper->layout->disableLayout();
			
			
					$valores=$this->_request->getPost('valores');
					$concepto=$this->_request->getPost('concepto');
				
					if($concepto=="solicitud")
					{
							$data3 = array(
											'ED17_LEIDO' => '1'
										 );

				
							$where3['ED02_SOLICITUDID = ?'] = $valores;
							$where3['ED01_USUARIOID = ?'] = trim($edesk_session->USUARIOID);
							
					
							try {
			
									$DB->getConnection();
									$DB->beginTransaction();
				
									$DB->update('e_desk.ED17_USUARIO_NOTIFICADO_SOLICITUD', $data3, $where3);
					
			
									$DB->commit();
									
								} catch (Zend_Exception $e) {
			
									$DB->rollBack();
									//echo("KO|".$e->getMessage());
									echo "KO|Se ha producido un error..(22)";
									exit;	
								}

					
					}
				 
				
				
					if($concepto=="asistencia")
					{
				
				
						///ASISTEBCIA
						$data1 = array(
										'ED11_LEIDO' => '1'
									 );
							
						$where1['ED05_ASISTENCIAID = ?'] = trim($valores);
						$where1['ED01_USUARIOID = ?'] = trim($edesk_session->USUARIOID);
						
						$DB->update('e_desk.ED11_USUARIO_NOTIFICADO_ASISTENCIA', $data1, $where1);
						///ASISTEBCIA
									
				
				
						$sSQL = "SELECT 
								E.ED06_SEGASISTENCIAID as SEGID
								FROM 
								e_desk.ED12_USUARIO_NOTIFICADO_SEG_ASIS E
								INNER JOIN
								e_desk.ED06_SEGUIMIENTO_ASISTENCIA_TECNICA T on E.ED06_SEGASISTENCIAID=T.ED06_SEGASISTENCIAID
								WHERE
								E.ED12_LEIDO=0 and 
								E.ED01_USUARIOID='".trim($edesk_session->USUARIOID)."' and 
								T.ED05_ASISTENCIAID='".$valores."'";
						
							
							$rowset = $DB->fetchAll($sSQL);
				
							foreach($rowset as $row_datosQuery)
							{
									if(trim($row_datosQuery["SEGID"])!="")
									{
										
											$data = array(
															'ED12_LEIDO' => '1'
														 );
				
											
											$where['ED01_USUARIOID = ?'] = trim($edesk_session->USUARIOID);
											$where['ED06_SEGASISTENCIAID = ?'] = trim($row_datosQuery["SEGID"]);
																
								
									   	    $DB->update('e_desk.ED12_USUARIO_NOTIFICADO_SEG_ASIS', $data, $where);
									
									}
			
							}
				
				
					
					}
				 
				
				
				
				
					if($concepto=="incidente")
					{
				
						
							///INCIDENTE
							$data1 = array(
											'ED10_LEIDO' => '1'
										 );
								
							$where1['ED03_TICKETID = ?'] = trim($valores);
							$where1['ED01_USUARIOID = ?'] = trim($edesk_session->USUARIOID);
							$DB->update('e_desk.ED10_USUARIO_NOTIFICADO_TICKET', $data1, $where1);
							///INCIDENTE
							
						
						
							$sSQL = "SELECT 
										E.ED04_SEGTICKETID as SEGID
										FROM 
										e_desk.ED09_USUARIO_NOTIFICADO_SEG_TICKET E
										INNER JOIN
										e_desk.ED04_SEGUIMIENTO_TICKET T on E.ED04_SEGTICKETID=T.ED04_SEGTICKETID
										WHERE
										E.ED09_LEIDO=0 and 
										E.ED01_USUARIOID='".trim($edesk_session->USUARIOID)."' and 
										T.ED03_TICKETID='".$valores."'";

								
							$rowset = $DB->fetchAll($sSQL);
				
							foreach($rowset as $row_datosQuery)
							{
									if(trim($row_datosQuery["SEGID"])!="")
									{
										
											$data = array(
															'ED09_LEIDO' => '1'
														 );
				
											
											$where['ED01_USUARIOID = ?'] = trim($edesk_session->USUARIOID);
											$where['ED04_SEGTICKETID = ?'] = trim($row_datosQuery["SEGID"]);
																
				
								
									   	    $DB->update('e_desk.ED09_USUARIO_NOTIFICADO_SEG_TICKET', $data, $where);
									
				
				
									}
			
							}
				
				
					
					}
				 
				
				
					 exit;	
				
				}else{
						$TIPO_DESPLIEGUE=1;	
					 }	
						
				
		
				$CONTADOR_NOTIFICACIONES=0;
		

				//incidentes
				////////////////
				$sSQL = "SELECT 
						E.ED04_SEGTICKETID as SEGID, 
						DATE_FORMAT(E.ED09_FECHANOTIFICACION, '%d/%m/%Y') as FECHANOTIFICACION,
						T.ED03_TICKETID as MAESTROID,
						T.ED04_SEGCOMENTARIOS as SEGCOMENTARIOS,
						T.ED01_USUARIOID as USUARIO_ORIGEN,
						E.ED09_FECHANOTIFICACION as FECHA_TEMS
						FROM 
						e_desk.ED09_USUARIO_NOTIFICADO_SEG_TICKET E
						INNER JOIN
						e_desk.ED04_SEGUIMIENTO_TICKET T on E.ED04_SEGTICKETID=T.ED04_SEGTICKETID
						WHERE
						E.ED09_LEIDO=0 and E.ED01_USUARIOID='".trim($edesk_session->USUARIOID)."'
						ORDER BY
						E.ED09_FECHANOTIFICACION asc";			
				
				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["SEGID"])!="")
						{
							$ID=trim($row_datosQuery["FECHA_TEMS"]).trim($row_datosQuery["SEGID"]);
							$matriz_notificacion["$ID"]["TIPO"]="1";
							$matriz_notificacion["$ID"]["FECHANOTIFICACION"]=trim($row_datosQuery["FECHANOTIFICACION"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
							$matriz_notificacion["$ID"]["SEGCOMENTARIOS"]=trim($row_datosQuery["SEGCOMENTARIOS"]);
							$matriz_notificacion["$ID"]["USUARIO_ORIGEN"]=trim($row_datosQuery["USUARIO_ORIGEN"]);
							$CONTADOR_NOTIFICACIONES++;
						}

				}
		
		

				$sSQL = "SELECT
							E.ED03_TICKETID as SEGID,
							DATE_FORMAT(T.ED10_FECHANOTIFICACION, '%d/%m/%Y') as FECHANOTIFICACION,
							E.ED03_TICKETID as MAESTROID,
							E.ED03_DETALLETICKET as SEGCOMENTARIOS,
							T.ED01_USUARIOID as USUARIO_ORIGEN,
							T.ED10_FECHANOTIFICACION as FECHA_TEMS
							FROM
							e_desk.ED03_TICKET E
							INNER JOIN e_desk.ED10_USUARIO_NOTIFICADO_TICKET T ON E.ED03_TICKETID=T.ED03_TICKETID
							WHERE
							T.ED10_LEIDO=0 and T.ED01_USUARIOID='".trim($edesk_session->USUARIOID)."'
							ORDER BY
							T.ED10_FECHANOTIFICACION asc ";


				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["SEGID"])!="")
						{
							$ID=trim($row_datosQuery["FECHA_TEMS"]).trim($row_datosQuery["SEGID"]);
							$matriz_notificacion["$ID"]["TIPO"]="1";
							$matriz_notificacion["$ID"]["FECHANOTIFICACION"]=trim($row_datosQuery["FECHANOTIFICACION"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
							$matriz_notificacion["$ID"]["SEGCOMENTARIOS"]=trim($row_datosQuery["SEGCOMENTARIOS"]);
							$matriz_notificacion["$ID"]["USUARIO_ORIGEN"]=trim($row_datosQuery["USUARIO_ORIGEN"]);
							$CONTADOR_NOTIFICACIONES++;
						
						}

				}
		
		
		
		
				//asistencias
				////////////////
				
		
				$sSQL = "SELECT 
						E.ED06_SEGASISTENCIAID as SEGID, 
						DATE_FORMAT(E.ED12_FECHANOTIFICACION, '%d/%m/%Y') as FECHANOTIFICACION,
						T.ED05_ASISTENCIAID as MAESTROID,
						T.ED06_SEGCOMENTARIOS as SEGCOMENTARIOS,
						T.ED01_USUARIOID as USUARIO_ORIGEN,
						E.ED12_FECHANOTIFICACION as FECHA_TEMS
						FROM 
						e_desk.ED12_USUARIO_NOTIFICADO_SEG_ASIS E
						INNER JOIN
						e_desk.ED06_SEGUIMIENTO_ASISTENCIA_TECNICA T on E.ED06_SEGASISTENCIAID=T.ED06_SEGASISTENCIAID
						WHERE
						E.ED12_LEIDO=0 and E.ED01_USUARIOID='".trim($edesk_session->USUARIOID)."'
						ORDER BY
						E.ED12_FECHANOTIFICACION asc";			
				
				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["SEGID"])!="")
						{
							$ID=trim($row_datosQuery["FECHA_TEMS"]).trim($row_datosQuery["SEGID"]);
							$matriz_notificacion["$ID"]["TIPO"]="2";
							$matriz_notificacion["$ID"]["FECHANOTIFICACION"]=trim($row_datosQuery["FECHANOTIFICACION"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
							$matriz_notificacion["$ID"]["SEGCOMENTARIOS"]=trim($row_datosQuery["SEGCOMENTARIOS"]);
							$matriz_notificacion["$ID"]["USUARIO_ORIGEN"]=trim($row_datosQuery["USUARIO_ORIGEN"]);
						
							$CONTADOR_NOTIFICACIONES++;
						
						}

				}


				$sSQL = "SELECT
							E.ED05_ASISTENCIAID as SEGID,
							DATE_FORMAT(T.ED11_FECHANOTIFICACION, '%d/%m/%Y') as FECHANOTIFICACION,
							E.ED05_ASISTENCIAID as MAESTROID,
							E.ED05_DETALLEASISTENCIAREALIZAR as SEGCOMENTARIOS,
							T.ED01_USUARIOID as USUARIO_ORIGEN,
							T.ED11_TIPONOTIFICACION as FECHA_TEMS
							FROM
							e_desk.ED05_ASISTENCIA_TECNICA E
							INNER JOIN e_desk.ED11_USUARIO_NOTIFICADO_ASISTENCIA T ON E.ED05_ASISTENCIAID=T.ED05_ASISTENCIAID
							WHERE
							T.ED11_LEIDO=0 and T.ED01_USUARIOID='".trim($edesk_session->USUARIOID)."'
							ORDER BY
							T.ED11_FECHANOTIFICACION asc";


				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["SEGID"])!="")
						{
							$ID=trim($row_datosQuery["FECHA_TEMS"]).trim($row_datosQuery["SEGID"]);
							$matriz_notificacion["$ID"]["TIPO"]="2";
							$matriz_notificacion["$ID"]["FECHANOTIFICACION"]=trim($row_datosQuery["FECHANOTIFICACION"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
							$matriz_notificacion["$ID"]["SEGCOMENTARIOS"]=trim($row_datosQuery["SEGCOMENTARIOS"]);
							$matriz_notificacion["$ID"]["USUARIO_ORIGEN"]=trim($row_datosQuery["USUARIO_ORIGEN"]);
						
							$CONTADOR_NOTIFICACIONES++;
						
						}

				}

		
		
		
		
		
		
		
				//solicitudes
				////////////////
				
				$sSQL = "SELECT 
						E.ED02_SOLICITUDID as SEGID, 
						DATE_FORMAT(E.ED17_FECHANOTIFICACION, '%d/%m/%Y') as FECHANOTIFICACION,
						T.ED02_SOLICITUDID as MAESTROID,
						T.ED02_DETALLESOLICITUD as SEGCOMENTARIOS,
						T.ED01_USUARIOID as USUARIO_ORIGEN,
						E.ED17_FECHANOTIFICACION as FECHA_TEMS
						FROM 
						e_desk.ED17_USUARIO_NOTIFICADO_SOLICITUD E
						INNER JOIN
						e_desk.ED02_SOLICITUD T on E.ED02_SOLICITUDID=T.ED02_SOLICITUDID
						WHERE
						E.ED17_LEIDO=0 and E.ED01_USUARIOID='".trim($edesk_session->USUARIOID)."'
						ORDER BY
						E.ED17_FECHANOTIFICACION asc";			
				
				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["SEGID"])!="")
						{
							$ID=trim($row_datosQuery["FECHA_TEMS"]).trim($row_datosQuery["SEGID"]);
							$matriz_notificacion_sol["$ID"]["SEGID"]=$ID;
							$matriz_notificacion_sol["$ID"]["FECHANOTIFICACION"]=trim($row_datosQuery["FECHANOTIFICACION"]);
							$matriz_notificacion_sol["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
							$matriz_notificacion_sol["$ID"]["SEGCOMENTARIOS"]=trim($row_datosQuery["SEGCOMENTARIOS"]);
							$matriz_notificacion_sol["$ID"]["USUARIO_ORIGEN"]=trim($row_datosQuery["USUARIO_ORIGEN"]);
						
							$CONTADOR_NOTIFICACIONES++;
						
						}

				}
				
				
			
				if(isset($matriz_notificacion))
				{
					ksort($matriz_notificacion);
					
					Zend_Layout::getMvcInstance()->assign('matriz_notificacion',$matriz_notificacion);
				}					
			
				if(isset($matriz_notificacion_sol))
					Zend_Layout::getMvcInstance()->assign('matriz_notificacion_sol',$matriz_notificacion_sol);
	
				
				
				Zend_Layout::getMvcInstance()->assign('CONTADOR_NOTIFICACIONES',$CONTADOR_NOTIFICACIONES);
				
				Zend_Layout::getMvcInstance()->assign('TIPO_DESPLIEGUE',$TIPO_DESPLIEGUE);
									
						
						
		}

		public function menuperfilAction()
		{
						
						$this->_helper->layout->disableLayout();
				
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
						
						###########################		
						##inicio validacion sesion
						###########################	
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
				
						###########################		
						##fin validacion sesion
						###########################	
				
						Zend_Layout::getMvcInstance()->assign('nivelid',trim($edesk_session->NIVELID));
						Zend_Layout::getMvcInstance()->assign('sectorid',trim($edesk_session->SECTORID));
						Zend_Layout::getMvcInstance()->assign('menu',$config['vectorMenu']);
						Zend_Layout::getMvcInstance()->assign('submenu',$config['vectorSubMenu']);
						Zend_Layout::getMvcInstance()->assign('permisos',$config['vectorPermisos']);
				
				
						
		}


		public function sesionAction()
		{
						
						$this->_helper->layout->disableLayout();
						$session = new Zend_Session_Namespace('edeskses');
						
		}


		public function infousuarioAction()
		{
		
						$this->_helper->layout->disableLayout();
						
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
				

						###########################		
						##inicio validacion sesion
						###########################	
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
				
						###########################		
						##fin validacion sesion
						###########################	
				
						Zend_Layout::getMvcInstance()->assign('nombreapellido',trim($edesk_session->NOMBREAPELLIDO));
					    Zend_Layout::getMvcInstance()->assign('nivel',trim($edesk_session->NIVELID));
					    Zend_Layout::getMvcInstance()->assign('niveldescripcion',trim($edesk_session->NIVELDESCRIPCION));
					    Zend_Layout::getMvcInstance()->assign('sector',trim($edesk_session->SECTORID));
						Zend_Layout::getMvcInstance()->assign('sectordescripcion',trim($edesk_session->SECTORDESCRIPCION));
						
						
		}


		public function logoutAction()
		{
						
						$this->_helper->layout->disableLayout();
						Zend_Session::namespaceUnset('edeskses');
						
						header('location:/');
						exit;	
						
						
		}



}