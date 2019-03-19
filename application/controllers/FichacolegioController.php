<?php
class FichacolegioController extends Zend_Controller_Action
{

		public function init()
		{
			/* Initialize action controller here */
		}
	
		public function indexAction()
		{
						
						
						//$this->_helper->layout->disableLayout();
						$config = Zend_Registry::get('config');
						
						$DB = Zend_Db_Table::getDefaultAdapter();
					
						//con parametros
						$S_COLEGIO = trim($this->_request->getParam('Colegio'));
							
								  
						$sSQL="SELECT 
								SIS03_LABORATORIODESCRIPCION,
								SIS03_ESTADO,
								SIS03_CURSOS,
								SIS03_ASESOR,
								SIS03_PRODUCTOS,
								SIS03_CONEMATLOCAL 
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
							$SIS03_CURSOS = $row["SIS03_CURSOS"];
							$SIS03_ASESOR = $row["SIS03_ASESOR"];
							$SIS03_PRODUCTOS = $row["SIS03_PRODUCTOS"];
							$SIS03_CONEMATLOCAL = $row["SIS03_CONEMATLOCAL"];
							$LABORATORIOID=$S_COLEGIO;
	
						}else{
						
								$LABORATORIODESCRIPCION="--";	
								$SIS03_ESTADO="--";;
								$SIS03_CURSOS="--";;
								$SIS03_ASESOR="--";;
								$SIS03_PRODUCTOS="--";;
								$SIS03_CONEMATLOCAL="--";;
								$LABORATORIOID="SIN-INFO";
					
						
						}
						
					
						//TICKET
						////////////////////////////
						$sSQL="SELECT
								ED03_TICKETID,
								SIS04_PRODUCTOID,
								ED03_FECHATICKET,
								ED03_NOMBRESOLICITANTE,
								ED03_NIVELSOPORTE,
								ED03_CLASIFICADOR,
								ED03_ESTADO
								FROM
								e_desk.ED03_TICKET
								WHERE
								SIS03_LABORATORIOID='".$S_COLEGIO."'
								ORDER BY
								ED03_FECHATICKET DESC 
								limit 0,10";
							
					
					
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
								$datosticket["$ID"]["ED03_CLASIFICADOR"]=$row_datosQuery["ED03_CLASIFICADOR"];
								$datosticket["$ID"]["ED03_ESTADO"]=$row_datosQuery["ED03_ESTADO"];
							}								
						}
					
					
					
					
						//SOLICITUDES
						////////////////////////////
						$sSQL="SELECT
								ED02_SOLICITUDID,
								ED02_FECHASOLICITUD,
								ED02_DETALLESOLICITUD,
								ED02_NOMBRESOLICITANTE
								FROM
								e_desk.ED02_SOLICITUD where SIS03_LABORATORIOID='".$S_COLEGIO."' 
								order by 
								ED02_FECHASOLICITUD DESC 
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
							}
						}
					
						
						
						
						
						//ASISTENCIAS TECNICAS
						////////////////////////////
						$sSQL="SELECT
								ED05_ASISTENCIAID,
								ED05_FECHAINGRESO,
								ED05_NOMBRESOLICITANTE,
								ED05_DETALLEASISTENCIAREALIZAR
								FROM
								e_desk.ED05_ASISTENCIA_TECNICA
								WHERE
								SIS03_LABORATORIOID='".$S_COLEGIO."'
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
								$datosasistencias["$ID"]["ED05_ASISTENCIAID"]=$row_datosQuery["ED05_ASISTENCIAID"];
								$datosasistencias["$ID"]["ED05_FECHAINGRESO"]=$row_datosQuery["ED05_FECHAINGRESO"];
								$datosasistencias["$ID"]["ED05_NOMBRESOLICITANTE"]=$row_datosQuery["ED05_NOMBRESOLICITANTE"];
								$datosasistencias["$ID"]["ED05_DETALLEASISTENCIAREALIZAR"]=$row_datosQuery["ED05_DETALLEASISTENCIAREALIZAR"];
							}							
						}
							
												
						
						Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
						Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
    					Zend_Layout::getMvcInstance()->assign('SIS03_ESTADO',$SIS03_ESTADO);
						Zend_Layout::getMvcInstance()->assign('SIS03_CURSOS',$SIS03_CURSOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_ASESOR',$SIS03_ASESOR);
						Zend_Layout::getMvcInstance()->assign('SIS03_PRODUCTOS',$SIS03_PRODUCTOS);
						Zend_Layout::getMvcInstance()->assign('SIS03_CONEMATLOCAL',$SIS03_CONEMATLOCAL);
					
						if(isset($datosticket))
							Zend_Layout::getMvcInstance()->assign('datosticket',$datosticket);
						
						if(isset($datossolicitud))
							Zend_Layout::getMvcInstance()->assign('datossolicitud',$datossolicitud);
						
						if(isset($datosasistencias))
							Zend_Layout::getMvcInstance()->assign('datosasistencias',$datosasistencias);
							
				
		}


}