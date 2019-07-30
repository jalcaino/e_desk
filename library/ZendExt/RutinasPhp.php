<?php

class ZendExt_RutinasPhp
{
	//ENVIO CORREOS
	function envio_correos($from,$to,$subject,$body)
    {
        
						$DB = Zend_Registry::get('db1');

						$data_email = array(
												'origen' => $from,
												'destinatarios' => $to,
												'f_ingreso' => date("Ymdhis"),
												'app_origen' => 'E-DESK',
												'encabezado' => $subject,
												'contenido' => $body,
												'estado_correo' => '0'
											);
							
						
											try {
							
													$DB->getConnection();
													$DB->beginTransaction();
													$DB->insert('bd_correos.correos_soporte', $data_email);
													$DB->commit();
										
													return "OK|";
													
													
												} catch (Zend_Exception $e) {
							
													$DB->rollBack();
													//return ("KO|".$e->getMessage());
													return "KO|Se ha producido un error..";
	
												}
				
						
    }
	
	
	
	//////////////////////////////////////////////////////////
	///INICIO TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///INICIO TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///INICIO TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///INICIO TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///INICIO TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	//////////////////////////////////////////////////////////
	


	function obtiene_usuarios()
    {
        
						$DB = Zend_Registry::get('db1');
      	
						/*
						2 supervisor mesa ayuda
						7 supervisor sac
						3 usuario mesa de ayuda
						4 tecnico laboratorios
						9 - PEDAGOGIA
						6-  PROGRAMACION
						*/
							
		
		
		
						//USUARIOS
						////////////////////////////
						$sSQL="SELECT
								ED01_USUARIOID,
								SIS02_NIVELID,
								ED01_EMAIL
								FROM
								e_desk.ED01_USUARIO
								WHERE 
								ED01_ESPRIVADO=0 and ED01_NOTIFICAR=1 
								ORDER BY
								SIS02_NIVELID";
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
								  $ID=$row_datosQuery["ED01_USUARIOID"];
								  $LISTAUSUARIOS["$ID"]["email"]=$row_datosQuery["ED01_EMAIL"];
								  $LISTAUSUARIOS["$ID"]["nivel"]=$row_datosQuery["SIS02_NIVELID"];
							}	
														
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  		else
							return "0";
				  	


    }



	//////////////////////////////////////////////////////////
	///FIN TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///FIN TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///FIN TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///FIN TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///FIN TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///FIN TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	///FIN TIPOS DE USUARIOS PARA NOTIFICACIONES//////////
	//////////////////////////////////////////////////////////
	


	////////////////////////////////////////////////////////////
	//USUARIOS ASOCIADOS A SOLICITUDES, INCIDENTES, ASISTENCIAS
	/////////////////////////////////////////////////////////////

	function obtiene_colegio_asesor($RBD)
    {
        
						$DB = Zend_Registry::get('db1');
      					
						$sSQL="SELECT
								SIS03_LABORATORIOID,
								ED07_MAILASESOR
								FROM
								e_desk.ED07_LABORATORIO_ASESOR
								WHERE 
								SIS03_LABORATORIOID='$RBD' ";
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["SIS03_LABORATORIOID"])!="")
							{
								  $ID=$row_datosQuery["SIS03_LABORATORIOID"];
								  $LISTAUSUARIOS["$ID"]=$row_datosQuery["ED07_MAILASESOR"];
							}								
						}
				  		
				  		if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  		else
							return "0";
				  		
    }


	function obtiene_usuarios_solicitud_ticket($SOLICITUDID,$TICKETID)
    {
        
						$DB = Zend_Registry::get('db1');
      					
						$sSQL="SELECT
								S.ED02_SOLICITUDID,
								T.ED03_TICKETID,
								U1.ED01_USUARIOID as userid1,
								U1.ED01_EMAIL as email1,
								U2.ED01_USUARIOID as userid2,
								U2.ED01_EMAIL as email2
								FROM
								e_desk.ED02_SOLICITUD S
								LEFT JOIN
								e_desk.ED01_USUARIO U1 on S.ED01_USUARIOID=U1.ED01_USUARIOID
								LEFT JOIN
								e_desk.ED14_SOLICITUD_TICKET ST on S.ED02_SOLICITUDID=ST.ED02_SOLICITUDID
								LEFT JOIN
								e_desk.ED03_TICKET T on ST.ED03_TICKETID=T.ED03_TICKETID
								LEFT JOIN
								e_desk.ED01_USUARIO U2 on T.ED03_DERIVADO=U2.ED01_USUARIOID";
					
						if(trim($SOLICITUDID)!="")
						   $sSQL.=" WHERE S.ED02_SOLICITUDID='$SOLICITUDID'";
					
					
						if(trim($TICKETID)!="")
						   $sSQL.=" WHERE T.ED03_TICKETID='$TICKETID'";
										
					
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								  $LISTAUSUARIOS1["USUARIOID"]=$row_datosQuery["userid1"];
								  $LISTAUSUARIOS1["EMAIL"]=$row_datosQuery["email1"];
								  
								  if(trim($row_datosQuery["userid2"])!="")
								  {
									  $LISTAUSUARIOS2["USUARIOID"]=$row_datosQuery["userid2"];
									  $LISTAUSUARIOS2["EMAIL"]=$row_datosQuery["email2"];
								  }
		
							}								
						}
				  
				  
				  		if(isset($LISTAUSUARIOS1))
						   $MATRIZ_NOTIFICACIONES[0]=$LISTAUSUARIOS1;
						
						if(isset($LISTAUSUARIOS2))
						   $MATRIZ_NOTIFICACIONES[1]=$LISTAUSUARIOS2;
		
		
						if(isset($MATRIZ_NOTIFICACIONES))
						   return $MATRIZ_NOTIFICACIONES;
						else   
						   return "0";
					

    }



	function obtiene_usuarios_solicitud_asistencia($SOLICITUDID,$ASISTENCIAID)
    {
        
						$DB = Zend_Registry::get('db1');
      					
						$sSQL="SELECT
								S.ED02_SOLICITUDID,
								T.ED05_ASISTENCIAID,
								U1.ED01_USUARIOID as userid1,
								U1.ED01_EMAIL as email1,
								U2.ED01_USUARIOID as userid2,
								U2.ED01_EMAIL as email2
								FROM
								e_desk.ED02_SOLICITUD S
								LEFT JOIN
								e_desk.ED01_USUARIO U1 on S.ED01_USUARIOID=U1.ED01_USUARIOID
								LEFT JOIN
								e_desk.ED16_SOLICITUD_ASISTENCIA ST on S.ED02_SOLICITUDID=ST.ED02_SOLICITUDID
								LEFT JOIN
								e_desk.ED05_ASISTENCIA_TECNICA T on ST.ED05_ASISTENCIAID=T.ED05_ASISTENCIAID
								LEFT JOIN
								e_desk.ED01_USUARIO U2 on T.ED05_DERIVADO=U2.ED01_USUARIOID
								";
					
					
					
						if(trim($SOLICITUDID)!="")
						   $sSQL.=" WHERE S.ED02_SOLICITUDID='$SOLICITUDID'";
					
					
						if(trim($ASISTENCIAID)!="")
						   $sSQL.=" WHERE T.ED05_ASISTENCIAID='$ASISTENCIAID'";
										
					
					
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								  
								  $LISTAUSUARIOS1["USUARIOID"]=$row_datosQuery["userid1"];
								  $LISTAUSUARIOS1["EMAIL"]=$row_datosQuery["email1"];
								  
								  if(trim($row_datosQuery["userid2"])!="")
								  {
									  $LISTAUSUARIOS2["USUARIOID"]=$row_datosQuery["userid2"];
									  $LISTAUSUARIOS2["EMAIL"]=$row_datosQuery["email2"];
								  }
		
							}								
						}
				  
					
						if(isset($LISTAUSUARIOS1))
						   $MATRIZ_NOTIFICACIONES[0]=$LISTAUSUARIOS1;
						
						if(isset($LISTAUSUARIOS2))
						   $MATRIZ_NOTIFICACIONES[1]=$LISTAUSUARIOS2;
		
		
						if(isset($MATRIZ_NOTIFICACIONES))
						   return $MATRIZ_NOTIFICACIONES;
						else   
						   return "0";
					

    }




	function obtiene_usuarios_incidente_asistencia($TICKETID,$ASISTENCIAID)
    {
        
						$DB = Zend_Registry::get('db1');
      					
						$sSQL="SELECT
								S.ED03_TICKETID,
								T.ED05_ASISTENCIAID,
								U1.ED01_USUARIOID as userid1,
								U1.ED01_EMAIL as email1,
								U2.ED01_USUARIOID as userid2,
								U2.ED01_EMAIL as email2
								FROM
								e_desk.ED03_TICKET S
								LEFT JOIN
								e_desk.ED01_USUARIO U1 on S.ED03_DERIVADO=U1.ED01_USUARIOID
								LEFT JOIN
								e_desk.ED13_TICKET_ASISTENCIA_TECNICA ST on S.ED03_TICKETID=ST.ED03_TICKETID
								LEFT JOIN
								e_desk.ED05_ASISTENCIA_TECNICA T on ST.ED05_ASISTENCIAID=T.ED05_ASISTENCIAID
								LEFT JOIN
								e_desk.ED01_USUARIO U2 on T.ED05_DERIVADO=U2.ED01_USUARIOID	";
					
					
						if(trim($TICKETID)!="")
						   $sSQL.=" WHERE S.ED03_TICKETID='$TICKETID'";
					
					
						if(trim($ASISTENCIAID)!="")
						   $sSQL.=" WHERE T.ED05_ASISTENCIAID='$ASISTENCIAID'";
										
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED03_TICKETID"])!="")
							{
								  $LISTAUSUARIOS1["USUARIOID"]=$row_datosQuery["userid1"];
								  $LISTAUSUARIOS1["EMAIL"]=$row_datosQuery["email1"];
								  
								  if(trim($row_datosQuery["userid2"])!="")
								  {
									  $LISTAUSUARIOS2["USUARIOID"]=$row_datosQuery["userid2"];
									  $LISTAUSUARIOS2["EMAIL"]=$row_datosQuery["email2"];
								  }
		
							}								
						}
				  
				  
						if(isset($LISTAUSUARIOS1))
						   $MATRIZ_NOTIFICACIONES[0]=$LISTAUSUARIOS1;
						
						if(isset($LISTAUSUARIOS2))
						   $MATRIZ_NOTIFICACIONES[1]=$LISTAUSUARIOS2;
		
		
						if(isset($MATRIZ_NOTIFICACIONES))
						   return $MATRIZ_NOTIFICACIONES;
						else   
						   return "0";

    }





	
	
	//////////////////////LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////LLAMADO A NOTIFICACIONES//////////////////////
		
		
		function notificaciones_solicitudes($ELCOLEGIO)
		{
        
						#################################
						##NOTIFICACIONES
						#################################
						//ASESOR COLEGIO
						$destinadatario1=$this->obtiene_colegio_asesor($ELCOLEGIO);	
						if($destinadatario1!="0")
						{
							foreach($destinadatario1 as $clave => $valor)
							{
								   $MAILS_A_NOTIFICAR["$valor"]=$valor;
							}
						}
						
						//DESTINATARIOS POR AREA
						////////////////////////
						$destinadatario2=$this->obtiene_usuarios();
						if($destinadatario2!="0")
						{
							foreach($destinadatario2 as $clave => $valor)
							{
									/*
									2 supervisor mesa ayuda
									7 supervisor sac
									3 usuario mesa de ayuda
									*/
									if($valor["nivel"]=="2" or $valor["nivel"]=="7" or $valor["nivel"]=="3")
									{
										$ELEMAIL=$valor["email"];
										$MAILS_A_NOTIFICAR["$ELEMAIL"]=$ELEMAIL;
										$USUARIOS_A_NOTIFICAR["$clave"]=$clave;
									}
							
							}
						}
						
						
			
						if(isset($MAILS_A_NOTIFICAR))
						   $MATRIZ_NOTIFICACIONES[0]=$MAILS_A_NOTIFICAR;
						
						if(isset($USUARIOS_A_NOTIFICAR))
						   $MATRIZ_NOTIFICACIONES[1]=$USUARIOS_A_NOTIFICAR;
		
		
						if(isset($MATRIZ_NOTIFICACIONES))
						   return $MATRIZ_NOTIFICACIONES;
						else   
						   return "0";
							

		}



		
		function notificaciones_incidentes_seg($incidenteid,$USUARIO,$DERIVADO)
		{
        
					$DB = Zend_Registry::get('db1');
      
					//validamos que no exista usuario
					$sSQL = "	SELECT 
								s.ED03_TICKETID,
								s.SIS03_LABORATORIOID
								FROM 
								e_desk.ED03_TICKET s
								WHERE 
								s.ED03_TICKETID = '$incidenteid' ";
								
								
							$rowset = $DB->fetchAll($sSQL);
							foreach($rowset as $row_datosQuery)
							{
									if(trim($row_datosQuery["ED03_TICKETID"])!="")
									{
										$ELCOLEGIO=$row_datosQuery["SIS03_LABORATORIOID"];
									}
							}
							
		
		
						#################################
						##NOTIFICACIONES
						#################################
						$destinadatario00=$this->obtiene_usuarios_incidente_asistencia($incidenteid,'');
						if($destinadatario00!="0")
						{
								if(isset($destinadatario00[0]))
								{
									foreach($destinadatario00[0] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
									}
								}

								if(isset($destinadatario00[1]))
								{
									foreach($destinadatario00[1] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
								
									}
								}

						}

		
						$destinadatario0=$this->obtiene_usuarios_solicitud_ticket('',$incidenteid);
						if($destinadatario0!="0")
						{
								if(isset($destinadatario0[0]))
								{
									foreach($destinadatario0[0] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
									}
								}

								if(isset($destinadatario0[1]))
								{
									foreach($destinadatario0[1] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
								
									}
								}

						}

						
						//ASESOR COLEGIO
						$destinadatario1=$this->obtiene_colegio_asesor($ELCOLEGIO);	
						if($destinadatario1!="0")
						{
							foreach($destinadatario1 as $clave => $valor)
							{
								   $MAILS_A_NOTIFICAR["$valor"]=$valor;
							}
						}
						
						
						//DESTINATARIOS POR AREA
						////////////////////////
						$destinadatario2=$this->obtiene_usuarios();
						if($destinadatario2!="0")
						{
							foreach($destinadatario2 as $clave => $valor)
							{
									/*
									2 supervisor mesa ayuda
									7 supervisor sac
									3 usuario mesa de ayuda
									USUARIO DE SESSION O DERIVADO
									*/
									if($valor["nivel"]=="2" or $valor["nivel"]=="7" or $valor["nivel"]=="3" or trim($clave)==trim($USUARIO) or trim($clave)==trim($DERIVADO))
									{
										$ELEMAIL=$valor["email"];
										$MAILS_A_NOTIFICAR["$ELEMAIL"]=$ELEMAIL;
										$USUARIOS_A_NOTIFICAR["$clave"]=$clave;
									}
							
							}
						}
						
			
			
			
						if(isset($MAILS_A_NOTIFICAR))
						   $MATRIZ_NOTIFICACIONES[0]=$MAILS_A_NOTIFICAR;
						
						if(isset($USUARIOS_A_NOTIFICAR))
						   $MATRIZ_NOTIFICACIONES[1]=$USUARIOS_A_NOTIFICAR;
		
		
						if(isset($MATRIZ_NOTIFICACIONES))
						   return $MATRIZ_NOTIFICACIONES;
						else   
						   return "0";
							

		}



		function notificaciones_asistencias_seg($asistenciaid,$USUARIO,$DERIVADO)
		{
        			
						$DB = Zend_Registry::get('db1');
      
					
						$sSQL="SELECT 
								s.ED05_ASISTENCIAID,
								s.SIS03_LABORATORIOID
								FROM 
								e_desk.ED05_ASISTENCIA_TECNICA s
								WHERE 
								s.ED05_ASISTENCIAID = '$asistenciaid' ";	
							
								
							$rowset = $DB->fetchAll($sSQL);
							foreach($rowset as $row_datosQuery)
							{
									if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
									{
											$ELCOLEGIO=$row_datosQuery["SIS03_LABORATORIOID"];
									}

							}
						
				
					
						#################################
						##NOTIFICACIONES
						#################################
						$destinadatario00=$this->obtiene_usuarios_incidente_asistencia('',$asistenciaid);
						if($destinadatario00!="0")
						{
								if(isset($destinadatario00[0]))
								{
									foreach($destinadatario00[0] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
									}
								}

								if(isset($destinadatario00[1]))
								{
									foreach($destinadatario00[1] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
								
									}
								}

						}

		
						$destinadatario0=$this->obtiene_usuarios_solicitud_asistencia('',$asistenciaid);
						if($destinadatario0!="0")
						{
								if(isset($destinadatario0[0]))
								{
									foreach($destinadatario0[0] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
									}
								}

								if(isset($destinadatario0[1]))
								{
									foreach($destinadatario0[1] as $clave => $valor)
									{
										if($clave=="USUARIOID")
											$USUARIOS_A_NOTIFICAR["$valor"]=$valor;
										if($clave=="EMAIL")
											$MAILS_A_NOTIFICAR["$valor"]=$valor;
								
									}
								}

						}


						//ASESOR COLEGIO
						$destinadatario1=$this->obtiene_colegio_asesor($ELCOLEGIO);	
						if($destinadatario1!="0")
						{
							foreach($destinadatario1 as $clave => $valor)
							{
								   $MAILS_A_NOTIFICAR["$valor"]=$valor;
							}
						}
						
						
						//DESTINATARIOS POR AREA
						////////////////////////
						$destinadatario2=$this->obtiene_usuarios();
						if($destinadatario2!="0")
						{
							foreach($destinadatario2 as $clave => $valor)
							{
									/*
									2 supervisor mesa ayuda
									7 supervisor sac
									3 usuario mesa de ayuda
									4 tecnico laboratorios
									USUARIO DE SESSION O DERIVADO
									*/
									if($valor["nivel"]=="2" or $valor["nivel"]=="7" or $valor["nivel"]=="3" or $valor["nivel"]=="4" or trim($clave)==trim($USUARIO) or trim($clave)==trim($DERIVADO))
									{
										$ELEMAIL=$valor["email"];
										$MAILS_A_NOTIFICAR["$ELEMAIL"]=$ELEMAIL;
										$USUARIOS_A_NOTIFICAR["$clave"]=$clave;
									}
							
							}
						}
						
			
						if(isset($MAILS_A_NOTIFICAR))
						   $MATRIZ_NOTIFICACIONES[0]=$MAILS_A_NOTIFICAR;
						
						if(isset($USUARIOS_A_NOTIFICAR))
						   $MATRIZ_NOTIFICACIONES[1]=$USUARIOS_A_NOTIFICAR;
		
		
						if(isset($MATRIZ_NOTIFICACIONES))
						   return $MATRIZ_NOTIFICACIONES;
						else   
						   return "0";
							

		}



	//////////////////////FIN LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////FIN LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////FIN LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////FIN LLAMADO A NOTIFICACIONES//////////////////////
	//////////////////////FIN LLAMADO A NOTIFICACIONES//////////////////////







    ///////////////////////////////// 
	//acceso funcionalidades
 	//acceso funcionalidades
 	//acceso funcionalidades
 	//acceso funcionalidades
 	//acceso funcionalidades
    ///////////////////////////////// 
	

	function rescate_permisos($menu,$submenu,$permisos,$nivelid,$sectorid,$referer)
    {
	
	
					//permisos
					/*
					
					SETEADOS EN APLICATION INI
					
					vectorPermisos.LAB.N3 = Operador Mesa de ayuda
					vectorPermisos.LAB.N3ACC = 1-1@@5-1#6@@6-6@@7-1@@8-1@@9-1@@10-1@@11-1@@12-1
				
					1 - ver
					2 - agregar
					3 - editar
					4 - eliminar
					5 - seguimiento
					6 - generar incidente/asistencia
					*/
				
				
				
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
					$parte_link = explode("/",$referer);
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
				
				
					return $acceso_funcionalidades;
					
		}









} //fin Clase
?>