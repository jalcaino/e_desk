<?php

class InfcolegioController extends Zend_Controller_Action
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
			
			
			$action="/Infcolegio/listar";
			Zend_Layout::getMvcInstance()->assign('action',$action);

	

	}

   public function imprimirAction()
    {
        // action body
    }

    public function graficoAction()
    {
        // action body
    }

	public function listarAction()
    {
        				// action body
  						$this->_helper->layout->disableLayout();
	
						$DB = Zend_Db_Table::getDefaultAdapter();
						$config = Zend_Registry::get('config');
						$functions = new ZendExt_RutinasPhp();
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
					
						//con parametros
						$S_COLEGIO = trim($this->_request->getParam('Colegio'));
						$S_EXCEL = trim($this->_request->getParam('Excel'));
					
							
								  
						$sSQL="SELECT 
								SIS03_LABORATORIODESCRIPCION,
								SIS03_ESTADO,
								SIS03_CURSOS,
								SIS03_ASESOR,
								SIS03_PRODUCTOS,
								SIS03_CONEMATLOCAL,
								SIS03_INTALADONEMATLOCAL, 
								SIS03_TIPO_CONTACTO_1, 
								SIS03_NOMBRE_CONTACTO_1, 
								SIS03_FONO_CONTACTO_1, 
								SIS03_CELULAR_CONTACTO_1, 
								SIS03_EMAIL_CONTACTO_1, 
								SIS03_TIPO_CONTACTO_2, 
								SIS03_NOMBRE_CONTACTO_2, 
								SIS03_FONO_CONTACTO_2, 
								SIS03_CELULAR_CONTACTO_2, 
								SIS03_EMAIL_CONTACTO_2, 
								SIS03_TIPO_CONTACTO_3, 
								SIS03_NOMBRE_CONTACTO_3, 
								SIS03_FONO_CONTACTO_3, 
								SIS03_CELULAR_CONTACTO_3, 
								SIS03_EMAIL_CONTACTO_3, 
								SIS03_NIVELES, 
								SIS03_DISTRIBUIDO, 
								SIS03_INFO_DISTRIBUIDO, 
								DATE_FORMAT(SIS03_FECHA_OPERATIVO, '%d/%m/%Y') as SIS03_FECHA_OPERATIVO,
								SIS03_OTROS_DISPOSITIVOS, 
								SIS03_OTROS_DISPOSITIVOS_INFO, 
								SIS03_DEPENDENCIA, 
								SIS03_NUM_ALUMNOS, 
								SIS03_NUM_PROFESORES, 
								SIS03_NUM_LABORATORIOS, 
								SIS03_TIPO_LABORATORIO, 
								SIS03_DISPOSITIVOS, 
								SIS03_PROVEEDOR_INTERNET, 
								SIS03_COMPU_X_ALUMNO, 
								SIS03_PLATAFORMAS_EN_USO
								FROM 
								e_desk.SIS03_LABORATORIO 
								WHERE 
								SIS03_LABORATORIOID='".$S_COLEGIO."'";
				
				
						$rowset = $DB->fetchAll($sSQL);
						if (count($rowset) > 0)  
						{
							$row = reset($rowset);
							
							$LABORATORIODESCRIPCION = $row["SIS03_LABORATORIODESCRIPCION"];
							$SIS03_ESTADO = $row["SIS03_ESTADO"];
						
							$SIS03_ESTADO="";
							if($row["SIS03_ESTADO"]=="1")
							   $SIS03_ESTADO="Informado";
							if($row["SIS03_ESTADO"]=="2")
							   $SIS03_ESTADO="Pendiente de Contacto";
							if($row["SIS03_ESTADO"]=="3")
							   $SIS03_ESTADO="En Proceso de Implementacion";
							if($row["SIS03_ESTADO"]=="4")
							   $SIS03_ESTADO="Implementado";
							if($row["SIS03_ESTADO"]=="5")
							   $SIS03_ESTADO="Inactivo";
						
						
							$SIS03_CURSOS = $row["SIS03_CURSOS"];
							$SIS03_ASESOR = $row["SIS03_ASESOR"];
							$SIS03_PRODUCTOS = $row["SIS03_PRODUCTOS"];
						
							$SIS03_CONEMATLOCAL="";
							if($row["SIS03_CONEMATLOCAL"]=="0")
							   $SIS03_CONEMATLOCAL="NO";
							if($row["SIS03_CONEMATLOCAL"]=="1")
							   $SIS03_CONEMATLOCAL="SI";
						
							$SIS03_INTALADONEMATLOCAL="";
							if($row["SIS03_INTALADONEMATLOCAL"]=="0")
							   $SIS03_INTALADONEMATLOCAL="NO";
							if($row["SIS03_INTALADONEMATLOCAL"]=="1")
							   $SIS03_INTALADONEMATLOCAL="SI";

							$SIS03_TIPO_CONTACTO_1="";
							if($row["SIS03_TIPO_CONTACTO_1"]=="0")
							   $SIS03_TIPO_CONTACTO_1="Otro";
							if($row["SIS03_TIPO_CONTACTO_1"]=="1")
							   $SIS03_TIPO_CONTACTO_1="Encargado Laboratorio";
							if($row["SIS03_TIPO_CONTACTO_1"]=="2")
							   $SIS03_TIPO_CONTACTO_1="Utp / Encargado Proyecto";
							if($row["SIS03_TIPO_CONTACTO_1"]=="3")
							   $SIS03_TIPO_CONTACTO_1="Directivo";
							if($row["SIS03_TIPO_CONTACTO_1"]=="4")
							   $SIS03_TIPO_CONTACTO_1="Externo";
					
					
					
							$SIS03_NOMBRE_CONTACTO_1 = $row["SIS03_NOMBRE_CONTACTO_1"];
							$SIS03_FONO_CONTACTO_1 = $row["SIS03_FONO_CONTACTO_1"];
							$SIS03_CELULAR_CONTACTO_1 = $row["SIS03_CELULAR_CONTACTO_1"]; 
							$SIS03_EMAIL_CONTACTO_1 = $row["SIS03_EMAIL_CONTACTO_1"];



							$SIS03_TIPO_CONTACTO_2="";
							if($row["SIS03_TIPO_CONTACTO_2"]=="0")
							   $SIS03_TIPO_CONTACTO_2="Otro";
							if($row["SIS03_TIPO_CONTACTO_2"]=="1")
							   $SIS03_TIPO_CONTACTO_2="Encargado Laboratorio";
							if($row["SIS03_TIPO_CONTACTO_2"]=="2")
							   $SIS03_TIPO_CONTACTO_2="Utp / Encargado Proyecto";
							if($row["SIS03_TIPO_CONTACTO_2"]=="3")
							   $SIS03_TIPO_CONTACTO_2="Directivo";
							if($row["SIS03_TIPO_CONTACTO_2"]=="4")
							   $SIS03_TIPO_CONTACTO_2="Externo";


							$SIS03_NOMBRE_CONTACTO_2 = $row["SIS03_NOMBRE_CONTACTO_2"]; 
							$SIS03_FONO_CONTACTO_2 = $row["SIS03_FONO_CONTACTO_2"];
							$SIS03_CELULAR_CONTACTO_2 = $row["SIS03_CELULAR_CONTACTO_2"]; 
							$SIS03_EMAIL_CONTACTO_2 = $row["SIS03_EMAIL_CONTACTO_2"];


							if($row["SIS03_TIPO_CONTACTO_3"]=="0")
							   $SIS03_TIPO_CONTACTO_3="Otro";
							if($row["SIS03_TIPO_CONTACTO_3"]=="1")
							   $SIS03_TIPO_CONTACTO_3="Encargado Laboratorio";
							if($row["SIS03_TIPO_CONTACTO_3"]=="2")
							   $SIS03_TIPO_CONTACTO_3="Utp / Encargado Proyecto";
							if($row["SIS03_TIPO_CONTACTO_3"]=="3")
							   $SIS03_TIPO_CONTACTO_3="Directivo";
							if($row["SIS03_TIPO_CONTACTO_3"]=="4")
							   $SIS03_TIPO_CONTACTO_3="Externo";


							$SIS03_NOMBRE_CONTACTO_3 = $row["SIS03_NOMBRE_CONTACTO_3"]; 
							$SIS03_FONO_CONTACTO_3 = $row["SIS03_FONO_CONTACTO_3"];
							$SIS03_CELULAR_CONTACTO_3 = $row["SIS03_CELULAR_CONTACTO_3"]; 
							$SIS03_EMAIL_CONTACTO_3 = $row["SIS03_EMAIL_CONTACTO_3"];
							$SIS03_NIVELES = $row["SIS03_NIVELES"];
							$SIS03_DISTRIBUIDO = $row["SIS03_DISTRIBUIDO"];
							$SIS03_INFO_DISTRIBUIDO = $row["SIS03_INFO_DISTRIBUIDO"]; 
							$SIS03_FECHA_OPERATIVO = $row["SIS03_FECHA_OPERATIVO"];
							$SIS03_OTROS_DISPOSITIVOS = $row["SIS03_OTROS_DISPOSITIVOS"]; 
							$SIS03_OTROS_DISPOSITIVOS_INFO = $row["SIS03_OTROS_DISPOSITIVOS_INFO"]; 
							$SIS03_DEPENDENCIA = $row["SIS03_DEPENDENCIA"];
							$SIS03_NUM_ALUMNOS = $row["SIS03_NUM_ALUMNOS"];
							$SIS03_NUM_PROFESORES = $row["SIS03_NUM_PROFESORES"]; 
							$SIS03_NUM_LABORATORIOS = $row["SIS03_NUM_LABORATORIOS"]; 
							$SIS03_TIPO_LABORATORIO = $row["SIS03_TIPO_LABORATORIO"];
							$SIS03_DISPOSITIVOS = $row["SIS03_DISPOSITIVOS"];
							$SIS03_PROVEEDOR_INTERNET = $row["SIS03_PROVEEDOR_INTERNET"]; 
							$SIS03_COMPU_X_ALUMNO = $row["SIS03_COMPU_X_ALUMNO"];
							$SIS03_PLATAFORMAS_EN_USO = $row["SIS03_PLATAFORMAS_EN_USO"];
		
				
							$LABORATORIOID=$S_COLEGIO;
	
						}else{
						
								$LABORATORIODESCRIPCION="--";	
								$SIS03_ESTADO="--";
								$SIS03_CURSOS="--";
								$SIS03_ASESOR="--";
								$SIS03_PRODUCTOS="--";
								$SIS03_CONEMATLOCAL="--";
								$LABORATORIOID="SIN-INFO";
					
		
								$SIS03_INTALADONEMATLOCAL = "--";
								$SIS03_TIPO_CONTACTO_1 = "--";
								$SIS03_NOMBRE_CONTACTO_1 = "--";
								$SIS03_FONO_CONTACTO_1 = "--";
								$SIS03_CELULAR_CONTACTO_1 = "--"; 
								$SIS03_EMAIL_CONTACTO_1 = "--";
								$SIS03_TIPO_CONTACTO_2 = "--";
								$SIS03_NOMBRE_CONTACTO_2 = "--"; 
								$SIS03_FONO_CONTACTO_2 = "--";
								$SIS03_CELULAR_CONTACTO_2 = "--"; 
								$SIS03_EMAIL_CONTACTO_2 = "--";
								$SIS03_TIPO_CONTACTO_3 = "--";
								$SIS03_NOMBRE_CONTACTO_3 = "--"; 
								$SIS03_FONO_CONTACTO_3 = "--";
								$SIS03_CELULAR_CONTACTO_3 = "--"; 
								$SIS03_EMAIL_CONTACTO_3 = "--";
								$SIS03_NIVELES = "--";
								$SIS03_DISTRIBUIDO = "--";
								$SIS03_INFO_DISTRIBUIDO = "--"; 
								$SIS03_FECHA_OPERATIVO = "--";
								$SIS03_OTROS_DISPOSITIVOS = "--"; 
								$SIS03_OTROS_DISPOSITIVOS_INFO = "--"; 
								$SIS03_DEPENDENCIA = "--";
								$SIS03_NUM_ALUMNOS = "--";
								$SIS03_NUM_PROFESORES = "--"; 
								$SIS03_NUM_LABORATORIOS = "--"; 
								$SIS03_TIPO_LABORATORIO = "--";
								$SIS03_DISPOSITIVOS = "--";
								$SIS03_PROVEEDOR_INTERNET = "--"; 
								$SIS03_COMPU_X_ALUMNO = "--";
								$SIS03_PLATAFORMAS_EN_USO = "--";
						
						}

						
					
						//TICKET
						////////////////////////////
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
									s.SIS01_SECTORID
									FROM 
									e_desk.ED03_TICKET s
									LEFT JOIN
									e_desk.SIS03_LABORATORIO l ON s.SIS03_LABORATORIOID=l.SIS03_LABORATORIOID
									LEFT JOIN
									e_desk.SIS07_CLASIFICADOR c ON s.SIS07_CLASIFICADORID=c.SIS07_CLASIFICADORID 		
									WHERE
									s.SIS03_LABORATORIOID='".$S_COLEGIO."'
									ORDER BY
									s.ED03_FECHATICKET DESC ";
						
					
					
					 	$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED03_TICKETID"])!="")
							{
								$ID=$row_datosQuery["ED03_TICKETID"];
								
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
								s.ED02_FECHASOLICITUD DESC ";
						
								
						
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
						
							}
						}
					
						
						
						
						
						//ASISTENCIAS TECNICAS
						////////////////////////////
						
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
								DESC ";	
							
							
							
						$rowset = $DB->fetchAll($sSQL);

						foreach($rowset as $row_datosQuery)
						{

							if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
							{
								$ID=$row_datosQuery["ED05_ASISTENCIAID"];

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
					
							}							
						}
							
						
												
												
						Zend_Layout::getMvcInstance()->assign('S_EXCEL',$S_EXCEL);
						Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
						Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
    					Zend_Layout::getMvcInstance()->assign('SIS03_ESTADO',$SIS03_ESTADO);
						Zend_Layout::getMvcInstance()->assign('SIS03_CURSOS',$SIS03_CURSOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_ASESOR',$SIS03_ASESOR);
						Zend_Layout::getMvcInstance()->assign('SIS03_PRODUCTOS',$SIS03_PRODUCTOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_CONEMATLOCAL',$SIS03_CONEMATLOCAL);
					
			
						Zend_Layout::getMvcInstance()->assign('SIS03_INTALADONEMATLOCAL',$SIS03_INTALADONEMATLOCAL);
						Zend_Layout::getMvcInstance()->assign('SIS03_TIPO_CONTACTO_1',$SIS03_TIPO_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_NOMBRE_CONTACTO_1',$SIS03_NOMBRE_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_FONO_CONTACTO_1',$SIS03_FONO_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_CELULAR_CONTACTO_1',$SIS03_CELULAR_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_EMAIL_CONTACTO_1',$SIS03_EMAIL_CONTACTO_1);
						Zend_Layout::getMvcInstance()->assign('SIS03_TIPO_CONTACTO_2',$SIS03_TIPO_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_NOMBRE_CONTACTO_2',$SIS03_NOMBRE_CONTACTO_2); 
						Zend_Layout::getMvcInstance()->assign('SIS03_FONO_CONTACTO_2',$SIS03_FONO_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_CELULAR_CONTACTO_2',$SIS03_CELULAR_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_EMAIL_CONTACTO_2',$SIS03_EMAIL_CONTACTO_2);
						Zend_Layout::getMvcInstance()->assign('SIS03_TIPO_CONTACTO_3',$SIS03_TIPO_CONTACTO_3);
						Zend_Layout::getMvcInstance()->assign('SIS03_NOMBRE_CONTACTO_3',$SIS03_NOMBRE_CONTACTO_3); 
						Zend_Layout::getMvcInstance()->assign('SIS03_FONO_CONTACTO_3',$SIS03_FONO_CONTACTO_3);
						Zend_Layout::getMvcInstance()->assign('SIS03_CELULAR_CONTACTO_3',$SIS03_CELULAR_CONTACTO_3);
						Zend_Layout::getMvcInstance()->assign('SIS03_EMAIL_CONTACTO_3',$SIS03_EMAIL_CONTACTO_3);
						Zend_Layout::getMvcInstance()->assign('SIS03_NIVELES',$SIS03_NIVELES);
						Zend_Layout::getMvcInstance()->assign('SIS03_DISTRIBUIDO',$SIS03_DISTRIBUIDO);
						Zend_Layout::getMvcInstance()->assign('SIS03_INFO_DISTRIBUIDO',$SIS03_INFO_DISTRIBUIDO);
						Zend_Layout::getMvcInstance()->assign('SIS03_FECHA_OPERATIVO',$SIS03_FECHA_OPERATIVO);
						Zend_Layout::getMvcInstance()->assign('SIS03_OTROS_DISPOSITIVOS',$SIS03_OTROS_DISPOSITIVOS); 
						Zend_Layout::getMvcInstance()->assign('SIS03_OTROS_DISPOSITIVOS_INFO',$SIS03_OTROS_DISPOSITIVOS_INFO);
						Zend_Layout::getMvcInstance()->assign('SIS03_DEPENDENCIA',$SIS03_DEPENDENCIA);
						Zend_Layout::getMvcInstance()->assign('SIS03_NUM_ALUMNOS',$SIS03_NUM_ALUMNOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_NUM_PROFESORES',$SIS03_NUM_PROFESORES); 
						Zend_Layout::getMvcInstance()->assign('SIS03_NUM_LABORATORIOS',$SIS03_NUM_LABORATORIOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_TIPO_LABORATORIO',$SIS03_TIPO_LABORATORIO);
						Zend_Layout::getMvcInstance()->assign('SIS03_DISPOSITIVOS',$SIS03_DISPOSITIVOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_PROVEEDOR_INTERNET',$SIS03_PROVEEDOR_INTERNET); 
						Zend_Layout::getMvcInstance()->assign('SIS03_COMPU_X_ALUMNO',$SIS03_COMPU_X_ALUMNO);
						Zend_Layout::getMvcInstance()->assign('SIS03_PLATAFORMAS_EN_USO',$SIS03_PLATAFORMAS_EN_USO);
	
			
						if(isset($datosticket))
							Zend_Layout::getMvcInstance()->assign('datosticket',$datosticket);
						
						if(isset($datossolicitud))
							Zend_Layout::getMvcInstance()->assign('datossolicitud',$datossolicitud);
						
						if(isset($datosasistencias))
							Zend_Layout::getMvcInstance()->assign('datosasistencias',$datosasistencias);
				
						if(isset($S_COLEGIO))
							Zend_Layout::getMvcInstance()->assign('S_COLEGIO',$S_COLEGIO);
				

						
						
						if($S_EXCEL=="1")
						{
							$filename = "informe_colegio_".$LABORATORIOID.".xls";
							
							header("Content-Type: application/vnd.ms-excel");
							header("Content-Disposition: attachment; filename=\"$filename\"");
						
						
						}		

						
					
					
						
	}


}