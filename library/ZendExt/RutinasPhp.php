<?php
class ZendExt_RutinasPhp
{
	////////////////////////////////////////////
	//TIPOS DE USUARIOS PARA NOTIFICACIONES
	////////////////////////////////////////////
	

    function obtiene_usuarios_mesa_ayuda()
    {
        
						$DB = Zend_Registry::get('db1');
      
	  					/*	
						2 - supervisor mesa ayuda
						3 - mesa de ayuda
						7 - supervisor sac
						4,6,9 - otros usuarios
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
								SIS02_NIVELID=3
								ORDER BY
								SIS02_NIVELID";
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
								  $ID=$row_datosQuery["ED01_USUARIOID"];	
							
								  $LISTAUSUARIOS["$ID"]["EMAIL"]=$row_datosQuery["ED01_EMAIL"];
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }



    function obtiene_usuarios_supervisor_mesa_ayuda()
    {
        
						$DB = Zend_Registry::get('db1');
      
	  					/*	
						2 - supervisor mesa ayuda
						3 - mesa de ayuda
						7 - supervisor sac
						4,6,9 - otros usuarios
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
								SIS02_NIVELID=2
								ORDER BY
								SIS02_NIVELID";
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
								  $ID=$row_datosQuery["ED01_USUARIOID"];
								  $LISTAUSUARIOS["$ID"]["EMAIL"]=$row_datosQuery["ED01_EMAIL"];
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }



	function obtiene_usuarios_supervisor_sac()
    {
        
						$DB = Zend_Registry::get('db1');
      
	  					/*	
						2 - supervisor mesa ayuda
						3 - mesa de ayuda
						7 - supervisor sac
						4,6,9 - otros usuarios
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
								SIS02_NIVELID=7
								ORDER BY
								SIS02_NIVELID";
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
								  $ID=$row_datosQuery["ED01_USUARIOID"];
								  $LISTAUSUARIOS["$ID"]["EMAIL"]=$row_datosQuery["ED01_EMAIL"];
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }



	function obtiene_usuarios_otros()
    {
        
						$DB = Zend_Registry::get('db1');
      
	  					/*	
						2 - supervisor mesa ayuda
						3 - mesa de ayuda
						7 - supervisor sac
						4,6,9 - otros usuarios
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
								SIS02_NIVELID in (4,6,9)
								ORDER BY
								SIS02_NIVELID";
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
								  $ID=$row_datosQuery["ED01_USUARIOID"];
								  $LISTAUSUARIOS["$ID"]["EMAIL"]=$row_datosQuery["ED01_EMAIL"];
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }


	////////////////////////////////////////////////////////////
	//USUARIOS ASOCIADOS A SOLICITUDES, INCIDENTES, ASISTENCIAS
	/////////////////////////////////////////////////////////////




	function obtiene_colegio_asesor()
    {
        
						$DB = Zend_Registry::get('db1');
      					
						$sSQL="SELECT
								E.SIS03_LABORATORIOID,
								E.ED07_MAILASESOR
								FROM
								e_desk.ED07_LABORATORIO_ASESOR";
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["SIS03_LABORATORIOID"])!="")
							{
								  $ID=$row_datosQuery["SIS03_LABORATORIOID"];
								  $LISTAUSUARIOS["$ID"]["EMAIL"]=$row_datosQuery["ED07_MAILASESOR"];
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }





	function obtiene_usuarios_solicitud_ticket()
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
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								  $ID=$row_datosQuery["ED02_SOLICITUDID"];
								  $ID2=$row_datosQuery["ED03_TICKETID"];
								  
								  $LISTAUSUARIOS["$ID"]["SOLICITUD"]->USUARIOID=$row_datosQuery["userid1"];
								  $LISTAUSUARIOS["$ID"]["SOLICITUD"]->EMAIL=$row_datosQuery["email1"];
								  
								  if(trim($row_datosQuery["userid2"])!="")
								  {
									  $LISTAUSUARIOS["$ID"]["TICKET"]->USUARIOID=$row_datosQuery["userid2"];
									  $LISTAUSUARIOS["$ID"]["TICKET"]->EMAIL=$row_datosQuery["email2"];
								  }
		
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }



	function obtiene_usuarios_solicitud_asistencia()
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
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								  $ID=$row_datosQuery["ED02_SOLICITUDID"];
								  $ID2=$row_datosQuery["ED05_ASISTENCIAID"];
								  
								  $LISTAUSUARIOS["$ID"]["SOLICITUD"]->USUARIOID=$row_datosQuery["userid1"];
								  $LISTAUSUARIOS["$ID"]["SOLICITUD"]->EMAIL=$row_datosQuery["email1"];
								  
								  if(trim($row_datosQuery["userid2"])!="")
								  {
									  $LISTAUSUARIOS["$ID"]["ASISTENCIA"]->USUARIOID=$row_datosQuery["userid2"];
									  $LISTAUSUARIOS["$ID"]["ASISTENCIA"]->EMAIL=$row_datosQuery["email2"];
								  }
		
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }




	function obtiene_usuarios_incidente_asistencia()
    {
        
						$DB = Zend_Registry::get('db1');
      					
						$sSQL="SELECT
								S.ED03_TICKETID,
								T.ED05_ASISTENCIAID,
								U1.ED01_USUARIOID,
								U1.ED01_EMAIL,
								U2.ED01_USUARIOID,
								U2.ED01_EMAIL
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
					
					
						$rowset = $DB->fetchAll($sSQL);
				
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED03_TICKETID"])!="")
							{
								  $ID=$row_datosQuery["ED03_TICKETID"];
								  $ID2=$row_datosQuery["ED05_ASISTENCIAID"];
								  
								  $LISTAUSUARIOS["$ID"]["TICKET"]->USUARIOID=$row_datosQuery["userid1"];
								  $LISTAUSUARIOS["$ID"]["TICKET"]->EMAIL=$row_datosQuery["email1"];
								  
								  if(trim($row_datosQuery["userid2"])!="")
								  {
									  $LISTAUSUARIOS["$ID"]["ASISTENCIA"]->USUARIOID=$row_datosQuery["userid2"];
									  $LISTAUSUARIOS["$ID"]["ASISTENCIA"]->EMAIL=$row_datosQuery["email2"];
								  }
		
							}								
						}
				  
						if (isset($LISTAUSUARIOS))
							return $LISTAUSUARIOS;
				  

    }




    ///////////////////////////////// 
	//acceso funcionalidades
    ///////////////////////////////// 
	
	
	
	

	function rescate_permisos($menu,$submenu,$permisos,$nivelid,$sectorid,$referer)
    {
	
	
					//permisos
					/*
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