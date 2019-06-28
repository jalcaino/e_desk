<?php

 	ini_set('magic_quotes_gpc', 1);

	/**
	 * Created by PhpStorm.
	 * User: jalcaino
	 * Date: 14-03-2019
	 * Time: 11:05
	 */

	//VERSION FUNCIONANDO 02-02-2016
	//Web service de un módulo completo desde materiales Desarrollo a Producción.
	
	require_once ('lib/nusoap.php');
	
	
	//determinando en que ambiente estoy
	///////////////////////////////////////
	$porciones = explode(".",trim($_SERVER['SERVER_NAME']));
	$rest = substr($porciones[0],-3); 
	if($rest=="des")
		$URL_SERV="http://minlocaldes.e-mat.cl/webservice";
	else
		$URL_SERV="http://minlocal.e-mat.cl/webservice";
	///////////////////////////////////////		
	
		
	
	$miURL = $URL_SERV;
	$server = new nusoap_server();
	$server->configureWSDL('mostrarWSDL',$miURL);
	$server->wsdl->schemaTargetNamespace=$miURL;


	$server->register('getXMLtblacceso',
		array('rut' => 'xsd:base64Binary') , // Parametros de entrada CAMBIAR
		array('return' => 'xsd:string') , // Parametros de salida
		$miURL);
		
	

	function reemplaza($string)
	{
		$string=addslashes($string);
		return $string;
	}


	function getXMLtblacceso($rut)
	{
		
			require_once ('include/conexion.php');
			
			$dbh = conectaDB();
			
			$rut_alumno="";
			$MENSAJE="";
			
			//las bases de datos
			$MATRIZ_BDS["basica"]="registromin_local";			
			$MATRIZ_BDS["media"]="registromin_psu";
			
			
			
			
			///////////CONSULTA BASE DE DATOS //////////////
			///////////CONSULTA BASE DE DATOS //////////////
				
			foreach ($MATRIZ_BDS as $clave => $valor)
    		{
				
					$sql="SELECT rut,institucion,lista,habilitado,estadonivelacion,ultimomodulo,datosreinic,datosreinicmeta,datosreinicpru,ruttutor,nombre FROM ".$valor.".tblacceso where rut= ?";
					$sArrDat1= array($rut);
					$sth1 = $dbh->prepare($sql);
					$sth1->execute($sArrDat1);
					$fila = $sth1->fetchAll();
					
					foreach ($fila as $row) 
					{
						 $rut_alumno=$row["rut"];
						 $institucion_alumno=$row["institucion"];
						 $lista_alumno=$row["lista"];
						 $habilitado_alumno=$row["habilitado"];
						 $estadonivelacion_alumno=$row["estadonivelacion"];
						 $ultimomodulo_alumno=$row["ultimomodulo"];
						 $datosreinic_alumno=$row["datosreinic"];
						 $datosreinicmeta_alumno=$row["datosreinicmeta"];
						 $datosreinicpru_alumno=$row["datosreinicpru"];
						 $ruttutor_alumno=$row["ruttutor"];
						 $nombre_alumno=$row["nombre"];
						 $producto=$clave;
				
						 $MENSAJE.="@D_ALUMNO##".$rut_alumno."******".$institucion_alumno."******".$lista_alumno."******".$habilitado_alumno."******".$estadonivelacion_alumno."******";
						 $MENSAJE.=$ultimomodulo_alumno."******".$datosreinic_alumno."******".$datosreinicmeta_alumno."******".$datosreinicpru_alumno."******".$ruttutor_alumno."******".$nombre_alumno."******".$producto;
						
					
					}
				
			}	

						
			
					
			if($rut_alumno=="" && $MENSAJE=="")
			{
			
					$MENSAJE="KO@No existe RUT en el sistema";
					return $MENSAJE;
			
			}else{
			
					
						$MENSAJE="OK".$MENSAJE;
						
						
						foreach ($MATRIZ_BDS as $clave => $valor)
    					{
		
								//EVALUACIONES
								///////////////////
					
								$sql="SELECT ev04_vcIdPrueba,DATE_FORMAT(ev07_dTermino, '%d/%m/%Y') as fecha,DATE_FORMAT(ev07_dTermino, '%H:%i:%s') as hora,ev07_iPuntaje,ev07_iPuntajeNivel FROM ".$valor.".ev07_resultados where ev03_vcRut=? order by ev07_dTermino desc";
								$sArrDat1= array($rut);
								$sth1 = $dbh->prepare($sql);
								$sth1->execute($sArrDat1);
								$fila = $sth1->fetchAll();
								
								foreach ($fila as $row) 
								{
									 $ev_prueba=$row["ev04_vcIdPrueba"];
									 $ev_fecha=$row["fecha"];
									 $ev_hora=$row["hora"];
									 $ev_puntaje=$row["ev07_iPuntaje"];
									 $ev_puntaje_nivel=$row["ev07_iPuntajeNivel"];
									 $producto=$clave;
									 $MENSAJE.="@D_PRUEBA##".$ev_prueba."******".$ev_fecha."******".$ev_hora."******".$ev_puntaje."******".$ev_puntaje_nivel."******".$producto;
								}
					
					
							

						}



						foreach ($MATRIZ_BDS as $clave => $valor)
    					{
		
		
					
								//MULTISESSION
								/////////////////
								$sql="SELECT DATE_FORMAT(fecha, '%d/%m/%Y') as fecha_episodio,DATE_FORMAT(fecha, '%H:%i:%s') as hora_episodio,reinicio,session_logeado as session_anterior,session_actual FROM ".$valor.".tbllogin_session_multiple where rut=? order by fecha desc";
								$sArrDat1= array($rut);
								$sth1 = $dbh->prepare($sql);
								$sth1->execute($sArrDat1);
								$fila = $sth1->fetchAll();
								
								foreach ($fila as $row) 
								{
									 $ses_fecha_episodio=$row["fecha_episodio"];
									 $ses_hora_episodio=$row["hora_episodio"];
									 $ses_reinicio=$row["reinicio"];
									 $ses_session_anterior=$row["session_anterior"];
									 $ses_session_actual=$row["session_actual"];
									 $producto=$clave;
									 
									 $MENSAJE.="@D_MULTISES##".$ses_fecha_episodio."******".$ses_hora_episodio."******".$ses_reinicio."******".$ses_session_anterior."******".$ses_session_actual."******".$producto;
								}
								
						}			
						
						
						foreach ($MATRIZ_BDS as $clave => $valor)
    					{
				
								//MODULOS
								/////////////////
								$sql="SELECT modulo,DATE_FORMAT(ingreso, '%d/%m/%Y') as ingreso_fecha,DATE_FORMAT(ingreso, '%H:%i:%s') as ingreso_hora,DATE_FORMAT(termino, '%d/%m/%Y') as termino_fecha,DATE_FORMAT(termino, '%H:%i:%s') as termino_hora,estado,puntaje,puntajePSU,cuenta,respaldo_reinicio FROM ".$valor.".tblregistro where rut=? order by estado, termino desc";
								$sArrDat1= array($rut);
								$sth1 = $dbh->prepare($sql);
								$sth1->execute($sArrDat1);
								$fila = $sth1->fetchAll();
								
								foreach ($fila as $row) 
								{
									 $mod_modulo=$row["modulo"];
									 $mod_ingreso_fecha=$row["ingreso_fecha"];
									 $mod_ingreso_hora=$row["ingreso_hora"];
									 $mod_termino_fecha=$row["termino_fecha"];
									 $mod_termino_hora=$row["termino_hora"];
									 $mod_estado=$row["estado"];
									 $mod_puntaje=$row["puntaje"];
									 $mod_puntajePSU=$row["puntajePSU"];
									 $mod_cuenta=$row["cuenta"];
									 $mod_respaldo_reinicio=$row["respaldo_reinicio"];
									 $producto=$clave;
							
									 $MENSAJE.="@D_MODULOS##".$mod_modulo."******".$mod_ingreso_fecha."******".$mod_ingreso_hora."******".$mod_termino_fecha."******".$mod_termino_hora."******".$mod_estado."******".$mod_puntaje."******".$mod_puntajePSU."******".$mod_cuenta."******".$mod_respaldo_reinicio."******".$producto;
							
								}
					
					
						}
			
			
						
							
			
			}		
					
		
			$dbh = null;
					
			return $MENSAJE;
	
	}
	

	 $server->service($HTTP_RAW_POST_DATA);
?>