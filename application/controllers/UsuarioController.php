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
						
					
					
					
		}

	
		public function agregarusuarioprocessAction()
		{


					$this->_helper->layout->disableLayout();
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
				
					$edesk_session = new Zend_Session_Namespace('edeskses');
			

					$login=$this->_request->getPost('login');
					$nivel=$this->_request->getPost('nivel');
					$sector=$this->_request->getPost('sector');
					$nombreapellido=$this->_request->getPost('nombreapellido');
					$email=$this->_request->getPost('email');
					$clave=$this->_request->getPost('clave');
					$privado=$this->_request->getPost('privado');
					$notiasig=$this->_request->getPost('notiasig');
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
						
										//echo("KO|".$e->getMessage());
										echo "KO|Se ha producido un error..(30)";
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
													'ED01_NOTIFICAR' => $notiasig,
													'ED01_FECHAINGRESO' => date("Ymdhis"),
													'ED01_FECHAULTIMAACTUALIZACION' => date("Ymdhis")
												);
							
							
												
												#################################
												##ENVIO DE EMAILS
												#################################
												if($email!="")
												{
												$subject="INTERNO - CREACION DE USUARIO E-DESK";
												$body="<u>Estimado Usuario</u><br><br>
													   Con fecha de hoy ".date("d/m/Y")." se ha procedido a la creaci&oacute;n<br><br>
													   del usuario E-DESK Login : ($login) , Clave : ($clave) asociado a su email $email<br><br>
													   Atte.<br>Equipo Compumat.";
												
							
											
													$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
												}							
								
					
												#############################
												##FIN MAIL CREACION USUARIO
												#############################
					
					
												$data_actividad = array(
														'ED01_USUARIOID' => $edesk_session->USUARIOID,
														'ED08_ACCION' => 'AGREGAR USUARIO',
														'ED08_MASINFO' => 'ID:'.$login
														);
									
					
							
												try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('e_desk.ED01_USUARIO', $data);
													$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
							
													$DB->commit();
							
													echo("OK|");
													exit;
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//echo("KO|".$e->getMessage());
													echo "KO|Se ha producido un error..(31)";
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
				
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
							
						$loginusuario=$this->_request->getPost('loginusuario');
				
				
						$USUARIOID="";
						$NIVELID="";
						$SECTORID="";
						$NOMBREAPELLIDO="";
						$EMAIL="";
						$PASSWORD="";
						$ESPRIVADO="";
						$NOTIFICAR="";
						
				
						//validamos que no exista usuario
						$sSQL = "	SELECT 
									ED01_USUARIOID,
									SIS02_NIVELID,
									SIS01_SECTORID,
									ED01_NOMBREAPELLIDO,
									ED01_EMAIL,
									ED01_PASSWORD,
									ED01_ESPRIVADO,
									ED01_NOTIFICAR
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
									$NOTIFICAR=$row_datosQuery["ED01_NOTIFICAR"];
								
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
						
				

						Zend_Layout::getMvcInstance()->assign('USUARIOID',$USUARIOID);
						Zend_Layout::getMvcInstance()->assign('NIVELID',$NIVELID);
						Zend_Layout::getMvcInstance()->assign('SECTORID',$SECTORID);
						Zend_Layout::getMvcInstance()->assign('NOMBREAPELLIDO',$NOMBREAPELLIDO);
						Zend_Layout::getMvcInstance()->assign('EMAIL',$EMAIL);
						Zend_Layout::getMvcInstance()->assign('PASSWORD',$PASSWORD);
						Zend_Layout::getMvcInstance()->assign('ESPRIVADO',$ESPRIVADO);
						Zend_Layout::getMvcInstance()->assign('NOTIFICAR',$NOTIFICAR);
				
		
				
		}

	
		public function editarusuarioprocessAction()
		{
					$this->_helper->layout->disableLayout();
			
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
				
			
					$edesk_session = new Zend_Session_Namespace('edeskses');
			
					$login=$this->_request->getPost('login');
					$nivel=$this->_request->getPost('nivel');
					$sector=$this->_request->getPost('sector');
					$nombreapellido=$this->_request->getPost('nombreapellido');
					$email=$this->_request->getPost('email');
					$clave=$this->_request->getPost('clave');
					$privado=$this->_request->getPost('privado');
					$notiasig=$this->_request->getPost('notiasig');
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
									'ED01_NOTIFICAR' => $notiasig,
									'ED01_FECHAINGRESO' => date("Ymdhis"),
									'ED01_FECHAULTIMAACTUALIZACION' => date("Ymdhis")
								);
			
			
								$where['ED01_USUARIOID = ?'] = $login;
								    
			
								$data_actividad = array(
														'ED01_USUARIOID' => $edesk_session->USUARIOID,
														'ED08_ACCION' => 'EDITAR USUARIO',
														'ED08_MASINFO' => 'ID:'.$login
														);
									
			
	
	
								#################################
								##ENVIO DE EMAILS
								#################################
								if($email!="")
								{
								$subject="INTERNO - EDICION DE USUARIO E-DESK";
								$body="<u>Estimado Usuario</u><br><br>
									   Con fecha de hoy ".date("d/m/Y")." se ha procedido a la modificaci&oacute;n<br><br>
									   del usuario E-DESK Login : ($login) , Clave : ($clave)  asociado a su email $email<br><br>
									   Atte.<br>Equipo Compumat.";
								
								
										$RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
								}							
								
	
	
								#############################
								##FIN MAIL EDICION USUARIO
								#############################
	
			
			
			
			
								try {
			
									$DB->getConnection();
									$DB->beginTransaction();
								    $DB->update('e_desk.ED01_USUARIO', $data, $where);
									$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
													
									
									$DB->commit();
			
									echo("OK|");
									exit;
									
								} catch (Zend_Exception $e) {
			
									$DB->rollBack();
									//echo("KO|".$e->getMessage());
									echo "KO|Se ha producido un error..(32)";
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
					
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
					
						$edesk_session = new Zend_Session_Namespace('edeskses');
			
						$loginusuario=$this->_request->getPost('loginusuario');
					
						$where['ED01_USUARIOID = ?'] = $loginusuario;
			
			
						$data_actividad = array(
														'ED01_USUARIOID' => $edesk_session->USUARIOID,
														'ED08_ACCION' => 'ELIMINAR USUARIO',
														'ED08_MASINFO' => 'ID:'.$loginusuario
														);
				
						try {

							$n = $DB->delete("e_desk.ED01_USUARIO", $where);
							$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
							
							echo "Usuario eliminado correctamente...";

						} catch (Zend_Exception $e) {

							//echo $e->getMessage();
							echo "KO|Se ha producido un error..(33)";

						}
		
		}


		public function listarusuariosAction()
		{
						
						
						
						$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						$DB = Zend_Db_Table::getDefaultAdapter();
						$functions = new ZendExt_RutinasPhp();

					
						$edesk_session = new Zend_Session_Namespace('edeskses');
					
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
					
						//USUARIOS
						////////////////////////////
						$sSQL="SELECT
								u.ED01_USUARIOID,
								u.SIS02_NIVELID,
								n.SIS02_NIVELDESCRIPCION,	
								u.SIS01_SECTORID,
								s.SIS01_SECTORDESCRIPCION,
								u.ED01_NOMBREAPELLIDO,
								u.ED01_EMAIL,
								u.ED01_PASSWORD,
								u.ED01_ESPRIVADO,
								u.ED01_NOTIFICAR,
								DATE_FORMAT(u.ED01_FECHAINGRESO, '%d/%m/%Y') as FECHA_INGRESO,
								DATE_FORMAT(u.ED01_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHA_ACTUALIZACION 
								FROM
								e_desk.ED01_USUARIO u
								LEFT JOIN
								e_desk.SIS01_SECTOR s ON u.SIS01_SECTORID=s.SIS01_SECTORID
								LEFT JOIN
								e_desk.SIS02_NIVEL_USUARIO n ON u.SIS02_NIVELID=n.SIS02_NIVELID
								ORDER BY
								u.ED01_NOMBREAPELLIDO";
					
					
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
									$datosusuarios["$ID"]["SIS02_NIVELDESCRIPCION"]=$row_datosQuery["SIS02_NIVELDESCRIPCION"];
									$datosusuarios["$ID"]["SIS01_SECTORID"]=$row_datosQuery["SIS01_SECTORID"];
									$datosusuarios["$ID"]["SIS01_SECTORDESCRIPCION"]=$row_datosQuery["SIS01_SECTORDESCRIPCION"];
									$datosusuarios["$ID"]["ED01_NOMBREAPELLIDO"]=$row_datosQuery["ED01_NOMBREAPELLIDO"];
									$datosusuarios["$ID"]["ED01_EMAIL"]=$row_datosQuery["ED01_EMAIL"];
									
									
									if($row_datosQuery["ED01_ESPRIVADO"]==1)
										$datosusuarios["$ID"]["ED01_ESPRIVADO"]='SI';
									else
										$datosusuarios["$ID"]["ED01_ESPRIVADO"]='NO';
								
									
									if($row_datosQuery["ED01_NOTIFICAR"]==1)
										$datosusuarios["$ID"]["ED01_NOTIFICAR"]='SI';
									else
										$datosusuarios["$ID"]["ED01_NOTIFICAR"]='NO';
								
									
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
						
						
						Zend_Layout::getMvcInstance()->assign('USUARIOACTUAL',$edesk_session->USUARIOID);
						
						
												
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
        		// action body
    
				$this->_helper->layout->disableLayout();
				
				$DB = Zend_Db_Table::getDefaultAdapter();
				$config = Zend_Registry::get('config');
				$functions = new ZendExt_RutinasPhp();
				
			
				$tipo=$this->_request->getPost('tipo');
				$nivel=$this->_request->getPost('nivel');
				$sector=$this->_request->getPost('sector');
	

				//NIVEL
				////////////////////////////
				$sSQL="SELECT
						SIS02_NIVELID,
						SIS02_NIVELDESCRIPCION
						FROM
						e_desk.SIS02_NIVEL_USUARIO
						WHERE 
						SIS01_SECTORID='$sector'";
			
			
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
							
			
				
				if(isset($datosnivel))
							Zend_Layout::getMvcInstance()->assign('datosnivel',$datosnivel);
			
				if(isset($tipo))
							Zend_Layout::getMvcInstance()->assign('tipo',$tipo);
			
				if(isset($nivel))
							Zend_Layout::getMvcInstance()->assign('nivel',$nivel);
			
				if(isset($sector))
							Zend_Layout::getMvcInstance()->assign('sector',$sector);
			
					
	}

    public function usuarioxnivelAction()
    {
        // action body
    }

    public function usuarioxsectorAction()
    {
      
   				// action body
    			$this->_helper->layout->disableLayout();
				
				$DB = Zend_Db_Table::getDefaultAdapter();
				$config = Zend_Registry::get('config');
				$functions = new ZendExt_RutinasPhp();
				
			
				$sector=$this->_request->getPost('sector');
				$derivado=$this->_request->getPost('derivado');
				$filtronivel=$this->_request->getPost('filtronivel');
	

				//USUARIOS
				////////////////////////////
				$sSQL="SELECT 
						ED01_USUARIOID,
						ED01_NOMBREAPELLIDO 
						FROM 
						e_desk.ED01_USUARIO 
						WHERE 
						ED01_ESPRIVADO=0 and SIS01_SECTORID='$sector' ";
				
				if($filtronivel!="")		
				   $sSQL.=" and SIS02_NIVELID not in ($filtronivel) ";
								
								
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
			
				if(isset($datosusuarios))
							Zend_Layout::getMvcInstance()->assign('datosusuarios',$datosusuarios);
			
			
				if(isset($sector))
							Zend_Layout::getMvcInstance()->assign('derivado',$derivado);
			
   
    }


}