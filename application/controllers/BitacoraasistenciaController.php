<?php

class BitacoraasistenciaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
   
        // action body
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

    public function agregarbitacoraasistenciaAction()
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
				
			
				$asistenciaid=$this->_request->getParam('asistenciaid');
			
			
				Zend_Layout::getMvcInstance()->assign('ASISTENCIAID',$asistenciaid);
			
			
		
		
    }

    public function agregarbitacoraasistenciaprocessAction()
    {
	        		// action body
    
    
					$this->_helper->layout->disableLayout();
				
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
					$edesk_session = new Zend_Session_Namespace('edeskses');
								
					$uploads = $config['ruta_path'];
					$uploads_public = $config['ruta_carpeta'];
					$separador = '/';

	
					$asistenciaid=$this->_request->getPost('asistenciaid');
					$calendario=$this->_request->getPost('calendario');
					$detalle=$this->_request->getPost('detalle');
					$resuelto=$this->_request->getPost('resuelto');
					$archivo=$this->_request->getPost('archivo');
					$accion=$this->_request->getPost('accion');
			
				
					$porciones_fecha = explode("/",$calendario);
					$calendario_ingles=$porciones_fecha[2]."-".$porciones_fecha[1]."-".$porciones_fecha[0];
							
					
			
					if($accion=="grabar")
					{
					
									//ye existe laboratorio
								  	$existe=1;
														

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
					
												$COMENTARIOS="Usuario introduce comentarios de asistencia";
												
												if($resuelto=="SI")
												{
													$COMENTARIOS.="<br>Atencion queda en estado gestionado";
												
													$data_update = array(
														'ED05_ESTADO' => 'GESTIONADO',
													);
												
													$where['ED05_ASISTENCIAID = ?'] = $asistenciaid;
													
												}
					
												//insertamos con try
												$data = array(
														  'ED05_ASISTENCIAID' => $asistenciaid,
														  'ED01_USUARIOID' => $edesk_session->USUARIOID,
														  'ED06_SEGFECHA' => $calendario_ingles,
														  'ED06_SEGCOMENTARIOS' => $detalle,
														  'ED06_ARCHIVOADJUNTO' => $fileRuta,
														  'ED06_NOMBREARCHIVOADJUNTO' => $fileName,
														  'ED06_TIPOARCHIVOADJUNTO' => $fileType,
														  'ED06_FECHAULTIMAACTUALIZACION' => date("Ymdhis"),
														  'ED06_REGISTRODETALLECAMBIO' => $COMENTARIOS,
														  'ED06_SOLUCIONADO' => $resuelto 
												
												);
												  		
					
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED06_SEGUIMIENTO_ASISTENCIA_TECNICA', $data);
													
													if($resuelto=="SI")
													{
														$DB->update('e_desk.ED05_ASISTENCIA_TECNICA', $data_update, $where);
													}										
									
							
													$DB->commit();
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(9)";
													exit;	
												}


												#################################
												##RESCATAMOS ULTIMO ID INGRESADO
												#################################
												$sSQL="SELECT max(ED06_SEGASISTENCIAID) as ingresado FROM e_desk.ED06_SEGUIMIENTO_ASISTENCIA_TECNICA";
												$rowset = $DB->fetchAll($sSQL);
												
												$nueva_solicitud=0;
												foreach($rowset as $row_datosQuery)
												{
													if(trim($row_datosQuery["ingresado"])!="")
													{
														$nueva_solicitud=$row_datosQuery["ingresado"];
													}
												}	
																


												#################################
												##INICIO NOTIFICACIONES
												#################################
											
												$email="";
												
												$destinadatarios=$functions->notificaciones_asistencias_seg($asistenciaid,$edesk_session->USUARIOID,"");	
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
																	$email.=";".$valor;
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
																		  'ED06_SEGASISTENCIAID' => $nueva_solicitud,
																		  'ED12_TIPONOTIFICACION' => '1',
																		  'ED12_LEIDO' => '0',
																		  'ED12_FECHANOTIFICACION' => date("Ymdhis")
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
												$subject="INTERNO - GESTION DE ASISTENCIA E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha generado gestion en la asistencia numero : <strong>$asistenciaid</strong> <br><br>
													   por el usuario E-DESK Login : ($edesk_session->USUARIOID) <br><br>
													   Atte.<br>Equipo Compumat.";
												
							
													$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
												}							


								
												$data_actividad = array(
																	'ED01_USUARIOID' => $edesk_session->USUARIOID,
																	'ED08_ACCION' => 'AGREGAR BITACORA ASISTENCIA',
																	'ED08_MASINFO' => 'SEGUIMIENTO NUM:'.$nueva_solicitud." / ASISTENCIA NUM:".$asistenciaid 
																	);


												try {
							
													$DB->getConnection();
													$DB->beginTransaction();

													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
													//INICIO NOTIFICACIONES USUARIO
													/////////////////////////////////
													if(isset($USUARIOS_A_NOTIFICAR) && count($USUARIOS_A_NOTIFICAR)>0)
													{
														foreach($USUARIOS_A_NOTIFICAR as $clave => $valor)
														{
															$DB->insert('e_desk.ED12_USUARIO_NOTIFICADO_SEG_ASIS',$data_usuario[$valor]);
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
													echo "KO|Se ha producido un error..(10)";
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

    public function editarbitacoraasistenciaAction()
    {
        // action body
    }

    public function editarbitacoraasistenciaprocessAction()
    {
        // action body
    }

    public function eliminarbitacoraasistenciaAction()
    {
        // action body
    }

    public function listarbitacoraasistenciasAction()
    {
        // action body
    }


}













