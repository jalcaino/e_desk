<?php
class FichacolegioController extends Zend_Controller_Action
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
							
				
						
					$config = Zend_Registry::get('config');
					$DB = Zend_Db_Table::getDefaultAdapter();
					$functions = new ZendExt_RutinasPhp();
						
				
					//con parametros
					$S_COLEGIO = trim($this->_request->getParam('Colegio'));
					Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$S_COLEGIO);
							
				
		}
		
		
		
		public function mostrarfichaAction()
		{


						$this->_helper->layout->disableLayout();
					
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
					
					
						//con parametros
						$S_COLEGIO = trim($this->_request->getParam('Colegio'));
							
								  
						$sSQL="SELECT 
								SIS03_LABORATORIODESCRIPCION,
								SIS03_ESTADO,
								SIS03_CURSOS,
								SIS03_ASESOR,
								SIS03_PRODUCTOS,
								SIS03_CONEMATLOCAL, 
								SIS03_INTALADONEMATLOCAL,
								SIS03_DETALLE_CURSOS_NIVELES,
								SIS03_NUM_LABORATORIOS,
								SIS03_PROVEEDOR_INTERNET,
								SIS03_NOMBRE_CONTACTO_1,
								SIS03_NOMBRE_CONTACTO_2,
								SIS03_NOMBRE_CONTACTO_3,
								SIS03_FONO_CONTACTO_1,
								SIS03_FONO_CONTACTO_2,
								SIS03_FONO_CONTACTO_3,
								SIS03_EMAIL_CONTACTO_1,
								SIS03_EMAIL_CONTACTO_2,
								SIS03_EMAIL_CONTACTO_3
								FROM 
								e_desk.SIS03_LABORATORIO 
								WHERE 
								SIS03_LABORATORIOID='".$S_COLEGIO."'";
				
						/*
						
						,
								SIS03_TIPO_CONTACTO_1,
								SIS03_TIPO_CONTACTO_2,
								SIS03_TIPO_CONTACTO_3
						
						*/
				
				
						$rowset = $DB->fetchAll($sSQL);
						if (count($rowset) > 0)  
						{
							$row = reset($rowset);
							
							$LABORATORIODESCRIPCION = $row["SIS03_LABORATORIODESCRIPCION"];
							$SIS03_ESTADO = $row["SIS03_ESTADO"];
							$SIS03_CURSOS = $row["SIS03_CURSOS"];
							$SIS03_ASESOR = $row["SIS03_ASESOR"];
							$SIS03_PRODUCTOS = $row["SIS03_PRODUCTOS"];
							$SIS03_CONEMATLOCAL = $row["SIS03_CONEMATLOCAL"];
							$LABORATORIOID=$S_COLEGIO;
	
							$SIS03_INTALADONEMATLOCAL = $row["SIS03_INTALADONEMATLOCAL"];
							$SIS03_DETALLE_CURSOS_NIVELES = $row["SIS03_DETALLE_CURSOS_NIVELES"];
							$SIS03_NUM_LABORATORIOS = $row["SIS03_NUM_LABORATORIOS"];
							$SIS03_PROVEEDOR_INTERNET = $row["SIS03_PROVEEDOR_INTERNET"];
						
							$SIS03_NOMBRE_CONTACTO_1 = $row["SIS03_NOMBRE_CONTACTO_1"];
							$SIS03_NOMBRE_CONTACTO_2 = $row["SIS03_NOMBRE_CONTACTO_2"];
							$SIS03_NOMBRE_CONTACTO_3 = $row["SIS03_NOMBRE_CONTACTO_3"];
						
							$SIS03_FONO_CONTACTO_1 = $row["SIS03_FONO_CONTACTO_1"];
							$SIS03_FONO_CONTACTO_2 = $row["SIS03_FONO_CONTACTO_2"];
							$SIS03_FONO_CONTACTO_3 = $row["SIS03_FONO_CONTACTO_3"];
						
							$SIS03_EMAIL_CONTACTO_1 = $row["SIS03_EMAIL_CONTACTO_1"];
							$SIS03_EMAIL_CONTACTO_2 = $row["SIS03_EMAIL_CONTACTO_2"];
							$SIS03_EMAIL_CONTACTO_3 = $row["SIS03_EMAIL_CONTACTO_3"];
		
							$SIS03_TIPO_CONTACTO_1 = $row["SIS03_TIPO_CONTACTO_1"];
							$SIS03_TIPO_CONTACTO_2 = $row["SIS03_TIPO_CONTACTO_2"];
							$SIS03_TIPO_CONTACTO_3 = $row["SIS03_TIPO_CONTACTO_3"];


							switch ($SIS03_TIPO_CONTACTO_1) {
								case 0:
									$TIPO_CONTACTO_1="Otro";
									break;
								case 1:
									$TIPO_CONTACTO_1="Encargado Laboratorio";
									break;
								case 2:
									$TIPO_CONTACTO_1="Utp / Encargado Proyecto";
									break;
								case 3:
									$TIPO_CONTACTO_1="Directivo";
									break;
								case 4:
									$TIPO_CONTACTO_1="Externo";
									break;
							}

							switch ($SIS03_TIPO_CONTACTO_2) {
								case 0:
									$TIPO_CONTACTO_2="Otro";
									break;
								case 1:
									$TIPO_CONTACTO_2="Encargado Laboratorio";
									break;
								case 2:
									$TIPO_CONTACTO_2="Utp / Encargado Proyecto";
									break;
								case 3:
									$TIPO_CONTACTO_2="Directivo";
									break;
								case 4:
									$TIPO_CONTACTO_2="Externo";
									break;
							}

							switch ($SIS03_TIPO_CONTACTO_3) {
								case 0:
									$TIPO_CONTACTO_3="Otro";
									break;
								case 1:
									$TIPO_CONTACTO_3="Encargado Laboratorio";
									break;
								case 2:
									$TIPO_CONTACTO_3="Utp / Encargado Proyecto";
									break;
								case 3:
									$TIPO_CONTACTO_3="Directivo";
									break;
								case 4:
									$TIPO_CONTACTO_3="Externo";
									break;
							}
	
	
	
	
	
						}else{
						
								$LABORATORIODESCRIPCION="--";	
								$SIS03_ESTADO="--";
								$SIS03_CURSOS="--";
								$SIS03_ASESOR="--";
								$SIS03_PRODUCTOS="--";
								$SIS03_CONEMATLOCAL="--";
								$LABORATORIOID="SIN-INFO";

								$SIS03_INTALADONEMATLOCAL="--";
								$SIS03_DETALLE_CURSOS_NIVELES="--";
								$SIS03_NUM_LABORATORIOS="--";
								$SIS03_PROVEEDOR_INTERNET="--";
								$SIS03_NOMBRE_CONTACTO_1="--";
								$SIS03_NOMBRE_CONTACTO_2="--";
								$SIS03_NOMBRE_CONTACTO_3="--";
								$SIS03_FONO_CONTACTO_1="--";
								$SIS03_FONO_CONTACTO_2="--";
								$SIS03_FONO_CONTACTO_3="--";
								$SIS03_EMAIL_CONTACTO_1="--";
								$SIS03_EMAIL_CONTACTO_2="--";
								$SIS03_EMAIL_CONTACTO_3="--";
								$SIS03_TIPO_CONTACTO_1="--";
								$SIS03_TIPO_CONTACTO_2="--";
								$SIS03_TIPO_CONTACTO_3="--";
								$TIPO_CONTACTO_1="--";
								$TIPO_CONTACTO_2="--";
								$TIPO_CONTACTO_3="--";
		
						
						}

						
		
		
		
		
						////////////---------
						//INICIO ASISTENCIAS ASOCIADAS A SOLICITUD
						//////////////////////////////////////////
						$sSQL="SELECT ED02_SOLICITUDID,ED05_ASISTENCIAID FROM e_desk.ED16_SOLICITUD_ASISTENCIA";
						$rowset = $DB->fetchAll($sSQL);
						$SOLICITUD_ASOCIADA_DIRECTA=0;
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								$IDENTIFICA=$row_datosQuery["ED02_SOLICITUDID"];
								$IDENTIFICA2=$row_datosQuery["ED05_ASISTENCIAID"];
	
								$matriz_match_solicitud_asistencia[$IDENTIFICA]=$row_datosQuery["ED05_ASISTENCIAID"];
								$matriz_match_asistencia_solicitud[$IDENTIFICA2]=$row_datosQuery["ED02_SOLICITUDID"];
	
							}								
						}
						
						//FIN ASISTENCIAS ASOCIADAS A SOLICITUD
						//////////////////////////////////////////
						
						//INICIO INCIDENTES ASOCIADAS A SOLICITUD
						//////////////////////////////////////////
						
						$sSQL="SELECT ED02_SOLICITUDID,ED03_TICKETID FROM e_desk.ED14_SOLICITUD_TICKET";
						$rowset = $DB->fetchAll($sSQL);
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								$IDENTIFICA=$row_datosQuery["ED02_SOLICITUDID"];
								$IDENTIFICA2=$row_datosQuery["ED03_TICKETID"];
								
								$matriz_match_solicitud_incidente[$IDENTIFICA]=$row_datosQuery["ED03_TICKETID"];
								$matriz_match_incidente_solicitud[$IDENTIFICA2]=$row_datosQuery["ED02_SOLICITUDID"];
					
							}								
						}
	
						//FIN INCIDENTES ASOCIADAS A SOLICITUD
						//////////////////////////////////////////
		
						//INICIO INCIDENTES ASOCIADAS A ASISTENCIAS
						//////////////////////////////////////////
						
						$sSQL="SELECT ED03_TICKETID,ED05_ASISTENCIAID FROM e_desk.ED13_TICKET_ASISTENCIA_TECNICA";
						$rowset = $DB->fetchAll($sSQL);
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED03_TICKETID"])!="")
							{
								$IDENTIFICA=$row_datosQuery["ED03_TICKETID"];
								$IDENTIFICA2=$row_datosQuery["ED05_ASISTENCIAID"];
								$matriz_match_incidente_asistencia[$IDENTIFICA]=$row_datosQuery["ED05_ASISTENCIAID"];
								$matriz_match_asistencia_incidente[$IDENTIFICA2]=$row_datosQuery["ED03_TICKETID"];
							}								
						}
	
						//FIN INCIDENTES ASOCIADAS A SOLICITUD
						//////////////////////////////////////////
						///////////-------------
		
		
		
					
						//TICKET
						////////////////////////////
						$ID_FILAS="0";
						
						$sSQL="SELECT 
									s.ED03_TICKETID,
									s.SIS03_LABORATORIOID,
									l.SIS03_LABORATORIODESCRIPCION, 
									s.SIS04_PRODUCTOID,
									DATE_FORMAT(s.ED03_FECHATICKET, '%d/%m/%Y') as ED03_FECHATICKET,
									s.ED03_NOMBRESOLICITANTE,
									s.ED03_TELEFONOSOLICITANTE,
									s.ED03_EMAILSOLICITANTE,
									s.ED03_PRIORIDAD,
									s.ED03_DETALLETICKET,
									s.ED03_TIPOCONTACTO,
									s.ED03_NIVELSOPORTE,
									s.SIS07_CLASIFICADORID, 
									c.SIS07_CLASIFICADORDESCRIPCION,
									s.ED03_ARCHIVOADJUNTO,
									s.ED03_NOMBREARCHIVOADJUNTO,
									s.ED03_TIPOARCHIVOADJUNTO,
									s.ED03_NUMALUMNOSAFECTADOS,
									s.ED03_NIVELDELPROGRAMA,
									s.SIS05_CODIGOMODULO,
									s.ED03_NUMEJERCICIO,
									s.ED03_ESTADO,
									DATE_FORMAT(s.ED03_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION,
									s.ED03_DERIVADO,
									s.SIS01_SECTORID,
									s.ED03_GESTION_INMEDIATA 
									FROM 
									e_desk.ED03_TICKET s
									LEFT JOIN
									e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
									LEFT JOIN
									e_desk.SIS07_CLASIFICADOR c ON s.SIS07_CLASIFICADORID=c.SIS07_CLASIFICADORID 		
									WHERE
									s.SIS03_LABORATORIOID='".$S_COLEGIO."'
									ORDER BY
									s.ED03_FECHATICKET DESC 
									limit 0,10";
						
					
					
					 	$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED03_TICKETID"])!="")
							{
								$ID=$row_datosQuery["ED03_TICKETID"];
								$ID_FILAS.=",".$ID;
								
								$datosticket["$ID"]["ED03_TICKETID"]=$row_datosQuery["ED03_TICKETID"];
								$datosticket["$ID"]["SIS04_PRODUCTOID"]=$row_datosQuery["SIS04_PRODUCTOID"];
								$datosticket["$ID"]["ED03_FECHATICKET"]=$row_datosQuery["ED03_FECHATICKET"];
								$datosticket["$ID"]["ED03_NOMBRESOLICITANTE"]=$row_datosQuery["ED03_NOMBRESOLICITANTE"];
								$datosticket["$ID"]["ED03_NIVELSOPORTE"]=$row_datosQuery["ED03_NIVELSOPORTE"];
								$datosticket["$ID"]["ED03_ESTADO"]=$row_datosQuery["ED03_ESTADO"];
								$datosticket["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
								$datosticket["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
								$datosticket["$ID"]["ED03_TELEFONOSOLICITANTE"]=$row_datosQuery["ED03_TELEFONOSOLICITANTE"];
								$datosticket["$ID"]["ED03_EMAILSOLICITANTE"]=$row_datosQuery["ED03_EMAILSOLICITANTE"];
								$datosticket["$ID"]["ED03_PRIORIDAD"]=$row_datosQuery["ED03_PRIORIDAD"];
								
								if($datosticket["$ID"]["ED03_PRIORIDAD"]=="0")
								   $datosticket["$ID"]["ED03_PRIORIDAD"]="BAJA";
								if($datosticket["$ID"]["ED03_PRIORIDAD"]=="1")
								   $datosticket["$ID"]["ED03_PRIORIDAD"]="ALTA";
								
								
								$datosticket["$ID"]["ED03_DETALLETICKET"]=$row_datosQuery["ED03_DETALLETICKET"];
								$datosticket["$ID"]["ED03_GESTION_INMEDIATA"]=$row_datosQuery["ED03_GESTION_INMEDIATA"];
								
								$datosticket["$ID"]["ED03_TIPOCONTACTO"]=$row_datosQuery["ED03_TIPOCONTACTO"];
								$datosticket["$ID"]["SIS07_CLASIFICADORID"]=$row_datosQuery["SIS07_CLASIFICADORID"];
								$datosticket["$ID"]["SIS07_CLASIFICADORDESCRIPCION"]=$row_datosQuery["SIS07_CLASIFICADORDESCRIPCION"];
								$datosticket["$ID"]["ED03_ARCHIVOADJUNTO"]=$row_datosQuery["ED03_ARCHIVOADJUNTO"];
								$datosticket["$ID"]["ED03_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED03_NOMBREARCHIVOADJUNTO"];
								$datosticket["$ID"]["ED03_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED03_TIPOARCHIVOADJUNTO"];
								$datosticket["$ID"]["ED03_NUMALUMNOSAFECTADOS"]=$row_datosQuery["ED03_NUMALUMNOSAFECTADOS"];
								$datosticket["$ID"]["ED03_NIVELDELPROGRAMA"]=$row_datosQuery["ED03_NIVELDELPROGRAMA"];
								$datosticket["$ID"]["SIS05_CODIGOMODULO"]=$row_datosQuery["SIS05_CODIGOMODULO"];
								$datosticket["$ID"]["ED03_NUMEJERCICIO"]=$row_datosQuery["ED03_NUMEJERCICIO"];
								$datosticket["$ID"]["FECHAULTIMAACTUALIZACION"]=$row_datosQuery["FECHAULTIMAACTUALIZACION"];
								$datosticket["$ID"]["SECTOR"]=$row_datosQuery["SIS01_SECTORID"];
						
								$datos_derivados["$ID"]["ED03_TICKETID"]=$ID;
								$datos_derivados["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED03_DERIVADO"];
								
								
								$datosticket["$ID"]["TEXTO_ASOCIADOS"]="";
				
								if(isset($matriz_match_incidente_asistencia[$ID]))
									$datosticket["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociado a asistencia  : <strong><a href='/Asistencia/index/busqueda/".$matriz_match_incidente_asistencia[$ID]."'>".$matriz_match_incidente_asistencia[$ID]."</a> ]</strong>";
								
								if(isset($matriz_match_incidente_solicitud[$ID]))
									$datosticket["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociado a solicitud  : <strong><a href='/Solicitud/index/busqueda/".$matriz_match_incidente_solicitud[$ID]."'>".$matriz_match_incidente_solicitud[$ID]."</a> ]</strong>";
						
						
						

							}								
						}
					
					
						//INICIO SEGUIMIENTO
						/////////////////////////////
					
						$sSQL="SELECT
									ED04_SEGTICKETID,
									ED03_TICKETID,
									ED01_USUARIOID,
									DATE_FORMAT(ED04_SEGFECHA, '%d/%m/%Y') as ED04_SEGFECHA,
									ED04_SEGCOMENTARIOS,
									ED04_ARCHIVOADJUNTO,
									ED04_NOMBREARCHIVOADJUNTO,
									ED04_TIPOARCHIVOADJUNTO,
									ED04_FECHAULTIMAACTUALIZACION,
									ED04_REGISTRODETALLECAMBIO,
									ED04_SOLUCIONADO 
									FROM
									e_desk.ED04_SEGUIMIENTO_TICKET WHERE ED03_TICKETID in ($ID_FILAS)";
							
						$rowset = $DB->fetchAll($sSQL);
		
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED04_SEGTICKETID"])!="")
							{
							
								$ID=$row_datosQuery["ED04_SEGTICKETID"];
								$datos_seguimiento["$ID"]["ED04_SEGTICKETID"]=$row_datosQuery["ED04_SEGTICKETID"];
								$datos_seguimiento["$ID"]["ED03_TICKETID"]=$row_datosQuery["ED03_TICKETID"];
								$datos_seguimiento["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
								$datos_seguimiento["$ID"]["ED04_SEGFECHA"]=$row_datosQuery["ED04_SEGFECHA"];
								$datos_seguimiento["$ID"]["ED04_SEGCOMENTARIOS"]=$row_datosQuery["ED04_SEGCOMENTARIOS"];
								$datos_seguimiento["$ID"]["ED04_ARCHIVOADJUNTO"]=$row_datosQuery["ED04_ARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED04_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED04_NOMBREARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED04_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED04_TIPOARCHIVOADJUNTO"];
								$datos_seguimiento["$ID"]["ED04_FECHAULTIMAACTUALIZACION"]=$row_datosQuery["ED04_FECHAULTIMAACTUALIZACION"];
								$datos_seguimiento["$ID"]["ED04_REGISTRODETALLECAMBIO"]=$row_datosQuery["ED04_REGISTRODETALLECAMBIO"];
								$datos_seguimiento["$ID"]["ED04_SOLUCIONADO"]=$row_datosQuery["ED04_SOLUCIONADO"];
							
							}								
						}
			

					
					
					
					
						//SOLICITUDES
						////////////////////////////
						
						$sSQL="SELECT 
								s.ED02_SOLICITUDID, 
								s.SIS03_LABORATORIOID, 
								l.SIS03_LABORATORIODESCRIPCION,
								s.SIS04_PRODUCTOID, 
								DATE_FORMAT(s.ED02_FECHASOLICITUD, '%d/%m/%Y') as ED02_FECHASOLICITUD, 
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
								WHERE 
								s.SIS03_LABORATORIOID='".$S_COLEGIO."' 
								ORDER BY 
								s.ED02_FECHASOLICITUD DESC 
								limit 0,10";
						
								
						
						$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED02_SOLICITUDID"])!="")
							{
								$ID=$row_datosQuery["ED02_SOLICITUDID"];
								$datossolicitud["$ID"]["ED02_SOLICITUDID"]=$row_datosQuery["ED02_SOLICITUDID"];
								$datossolicitud["$ID"]["ED02_FECHASOLICITUD"]=$row_datosQuery["ED02_FECHASOLICITUD"];
								$datossolicitud["$ID"]["ED02_DETALLESOLICITUD"]=$row_datosQuery["ED02_DETALLESOLICITUD"];
								$datossolicitud["$ID"]["ED02_NOMBRESOLICITANTE"]=$row_datosQuery["ED02_NOMBRESOLICITANTE"];
								$datossolicitud["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
								$datossolicitud["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
								$datossolicitud["$ID"]["SIS04_PRODUCTOID"]=$row_datosQuery["SIS04_PRODUCTOID"];
								$datossolicitud["$ID"]["ED02_ARCHIVOADJUNTO"]=$row_datosQuery["ED02_ARCHIVOADJUNTO"];
								$datossolicitud["$ID"]["ED02_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED02_NOMBREARCHIVOADJUNTO"];
								$datossolicitud["$ID"]["ED02_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED02_TIPOARCHIVOADJUNTO"];
								$datossolicitud["$ID"]["FECHAINGRESO"]=$row_datosQuery["FECHAINGRESO"];
								$datossolicitud["$ID"]["FECHAULTIMAACTUALIZACION"]=$row_datosQuery["FECHAULTIMAACTUALIZACION"];
								$datossolicitud["$ID"]["ED02_ESTADO"]=$row_datosQuery["ED02_ESTADO"];
						
						
								$datossolicitud["$ID"]["TEXTO_ASOCIADOS"]="";
			
								if(isset($matriz_match_solicitud_asistencia[$ID]))
									$datossolicitud["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Gesti&oacute;n en asistencia  : <strong><a href='/Asistencia/index/busqueda/".$matriz_match_solicitud_asistencia[$ID]."'>".$matriz_match_solicitud_asistencia[$ID]."</a> ]</strong>";
								
								if(isset($matriz_match_solicitud_incidente[$ID]))
									$datossolicitud["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Gesti&oacute;n en incidente  : <strong><a href='/Incidente/index/busqueda/".$matriz_match_solicitud_incidente[$ID]."'>".$matriz_match_solicitud_incidente[$ID]."</a> ]</strong>";
							
						
						
							}
						}
					
						
						
						
						
						//ASISTENCIAS TECNICAS
						////////////////////////////
						$ID_FILAS="0";
						
							$sSQL="SELECT 
								s.ED05_ASISTENCIAID,
								s.SIS03_LABORATORIOID,
								l.SIS03_LABORATORIODESCRIPCION, 
								DATE_FORMAT(s.ED05_FECHAINGRESO, '%d/%m/%Y') as ED05_FECHAINGRESO,
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
								DATE_FORMAT(s.ED05_FECHAULTIMAACTUALIZACION, '%d/%m/%Y') as FECHAULTIMAACTUALIZACION,
								s.ED05_DERIVADO 
								FROM 
								e_desk.ED05_ASISTENCIA_TECNICA s
								LEFT JOIN
								e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID 
								WHERE 
								s.SIS03_LABORATORIOID='".$S_COLEGIO."' 
								ORDER BY
								ED05_FECHAINGRESO 
								DESC 
								limit 0,10";	
							
							
							
						$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{

							if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
							{
								$ID=$row_datosQuery["ED05_ASISTENCIAID"];

								$ID_FILAS.=",".$ID;

								$datosasistencias["$ID"]["ED05_ASISTENCIAID"]=$row_datosQuery["ED05_ASISTENCIAID"];
								$datosasistencias["$ID"]["ED05_FECHAINGRESO"]=$row_datosQuery["ED05_FECHAINGRESO"];
								$datosasistencias["$ID"]["ED05_NOMBRESOLICITANTE"]=$row_datosQuery["ED05_NOMBRESOLICITANTE"];
								$datosasistencias["$ID"]["ED05_DETALLEASISTENCIAREALIZAR"]=$row_datosQuery["ED05_DETALLEASISTENCIAREALIZAR"];
								$datosasistencias["$ID"]["SIS03_LABORATORIOID"]=$row_datosQuery["SIS03_LABORATORIOID"];
								$datosasistencias["$ID"]["SIS03_LABORATORIODESCRIPCION"]=$row_datosQuery["SIS03_LABORATORIODESCRIPCION"];
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
					
					
								$datos_derivados_asis["$ID"]["ED05_ASISTENCIAID"]=$ID;
								$datos_derivados_asis["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED05_DERIVADO"];
													
								
								$datosasistencias["$ID"]["TEXTO_ASOCIADOS"]="";
		
								if(isset($matriz_match_asistencia_solicitud[$ID]))
									$datosasistencias["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociada a solicitud  : <strong><a href='/Solicitud/index/busqueda/".$matriz_match_asistencia_solicitud[$ID]."'>".$matriz_match_asistencia_solicitud[$ID]."</a> ]</strong>";
								
								if(isset($matriz_match_asistencia_incidente[$ID]))
									$datosasistencias["$ID"]["TEXTO_ASOCIADOS"].="<hr>[ Asociada a  incidente  : <strong><a href='/Incidente/index/busqueda/".$matriz_match_asistencia_incidente[$ID]."'>".$matriz_match_asistencia_incidente[$ID]."</a> ]</strong>";
								
		
					
					
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
									ED06_REGISTRODETALLECAMBIO,
									ED06_SOLUCIONADO 
									FROM
									e_desk.ED06_SEGUIMIENTO_ASISTENCIA_TECNICA WHERE ED05_ASISTENCIAID in ($ID_FILAS)";
							
						$rowset = $DB->fetchAll($sSQL);
		
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED06_SEGASISTENCIAID"])!="")
							{
							
								$ID=$row_datosQuery["ED06_SEGASISTENCIAID"];
								$datos_seguimiento_asis["$ID"]["ED06_SEGASISTENCIAID"]=$row_datosQuery["ED06_SEGASISTENCIAID"];
								$datos_seguimiento_asis["$ID"]["ED05_ASISTENCIAID"]=$row_datosQuery["ED05_ASISTENCIAID"];
								$datos_seguimiento_asis["$ID"]["ED01_USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
								$datos_seguimiento_asis["$ID"]["ED06_SEGFECHA"]=$row_datosQuery["ED06_SEGFECHA"];
								$datos_seguimiento_asis["$ID"]["ED06_SEGCOMENTARIOS"]=$row_datosQuery["ED06_SEGCOMENTARIOS"];
								$datos_seguimiento_asis["$ID"]["ED06_ARCHIVOADJUNTO"]=$row_datosQuery["ED06_ARCHIVOADJUNTO"];
								$datos_seguimiento_asis["$ID"]["ED06_NOMBREARCHIVOADJUNTO"]=$row_datosQuery["ED06_NOMBREARCHIVOADJUNTO"];
								$datos_seguimiento_asis["$ID"]["ED06_TIPOARCHIVOADJUNTO"]=$row_datosQuery["ED06_TIPOARCHIVOADJUNTO"];
								$datos_seguimiento_asis["$ID"]["ED06_FECHAULTIMAACTUALIZACION"]=$row_datosQuery["ED06_FECHAULTIMAACTUALIZACION"];
								$datos_seguimiento_asis["$ID"]["ED06_REGISTRODETALLECAMBIO"]=$row_datosQuery["ED06_REGISTRODETALLECAMBIO"];
								$datos_seguimiento_asis["$ID"]["ED06_SOLUCIONADO"]=$row_datosQuery["ED06_SOLUCIONADO"];
							
							}								
						}
			
		
									
												
						
						Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
						Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
    					Zend_Layout::getMvcInstance()->assign('SIS03_ESTADO',$SIS03_ESTADO);
						Zend_Layout::getMvcInstance()->assign('SIS03_CURSOS',$SIS03_CURSOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_ASESOR',$SIS03_ASESOR);
						Zend_Layout::getMvcInstance()->assign('SIS03_PRODUCTOS',$SIS03_PRODUCTOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_CONEMATLOCAL',$SIS03_CONEMATLOCAL);
					
						Zend_Layout::getMvcInstance()->assign('SIS03_INTALADONEMATLOCAL',$SIS03_INTALADONEMATLOCAL);
						Zend_Layout::getMvcInstance()->assign('SIS03_DETALLE_CURSOS_NIVELES',$SIS03_DETALLE_CURSOS_NIVELES);
						Zend_Layout::getMvcInstance()->assign('SIS03_NUM_LABORATORIOS',$SIS03_NUM_LABORATORIOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_NOMBRE_CONTACTO_1',$SIS03_NOMBRE_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_NOMBRE_CONTACTO_2',$SIS03_NOMBRE_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_NOMBRE_CONTACTO_3',$SIS03_NOMBRE_CONTACTO_3);
						
						Zend_Layout::getMvcInstance()->assign('SIS03_PROVEEDOR_INTERNET',$SIS03_PROVEEDOR_INTERNET);
						
						Zend_Layout::getMvcInstance()->assign('SIS03_FONO_CONTACTO_1',$SIS03_FONO_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_FONO_CONTACTO_2',$SIS03_FONO_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_FONO_CONTACTO_3',$SIS03_FONO_CONTACTO_3);
					
						Zend_Layout::getMvcInstance()->assign('SIS03_EMAIL_CONTACTO_1',$SIS03_EMAIL_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_EMAIL_CONTACTO_2',$SIS03_EMAIL_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_EMAIL_CONTACTO_3',$SIS03_EMAIL_CONTACTO_3);

						Zend_Layout::getMvcInstance()->assign('SIS03_TIPO_CONTACTO_1',$TIPO_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_TIPO_CONTACTO_2',$TIPO_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_TIPO_CONTACTO_3',$TIPO_CONTACTO_3);

						if(isset($datosticket))
							Zend_Layout::getMvcInstance()->assign('datosticket',$datosticket);
						
						if(isset($datos_seguimiento))
								Zend_Layout::getMvcInstance()->assign('datos_seguimiento',$datos_seguimiento);
	   	

						
						if(isset($datos_derivados))
							Zend_Layout::getMvcInstance()->assign('datos_derivados',$datos_derivados);
					
						if(isset($datos_derivados_asis))
							Zend_Layout::getMvcInstance()->assign('datos_derivados_asis',$datos_derivados_asis);
						
						
						if(isset($datossolicitud))
							Zend_Layout::getMvcInstance()->assign('datossolicitud',$datossolicitud);
						
						if(isset($datosasistencias))
							Zend_Layout::getMvcInstance()->assign('datosasistencias',$datosasistencias);
				
						if(isset($datos_seguimiento_asis))
								Zend_Layout::getMvcInstance()->assign('datos_seguimiento_asis',$datos_seguimiento_asis);
	   	
		
						


		}


}