<?php

class InfincidenteController extends Zend_Controller_Action
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
			
			
			$action="/Infincidente/listar";
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
					
					
						$CONTADOR_INI=1;
						$CONTADOR_FIN=15;
						$PAGINA=1;

						$lapagina=$this->_request->getPost('pagina');
						$fechainicio=$this->_request->getPost('fechainicio');
						$fechatermino=$this->_request->getPost('fechatermino');
						
					
						$porciones_fechainicio = explode("/",$fechainicio);
						$calendario_ingles_fechainicio=$porciones_fechainicio[2].$porciones_fechainicio[1].$porciones_fechainicio[0];
						
						$porciones_fechatermino = explode("/",$fechatermino);
						$calendario_ingles_fechatermino=$porciones_fechatermino[2].$porciones_fechatermino[1].$porciones_fechatermino[0];
										
					
										
						if($lapagina!="")
						{
								$PAGINA=$lapagina;
								$CONTADOR_INI=(($lapagina*15)+1)-15;
								$CONTADOR_FIN=($lapagina*15);
						}
			
			
			
						$CONTADOR_FILAS=0;
					
						$texto_columna_1="Operaci&oacute;n";
						$texto_columna_2="Estado";
						$texto_columna_3="Cantidad";
						$titulo_reporte="Solicitudes / Incidentes / Asistencias x FECHA ";
						
					
						//CONSULTA
						////////////////////////////
						$sSQL="SELECT
								E.ED02_ESTADO as ESTADO,
								count(*) as CONTADOR,
								'SOLICITUDES' as OPERACION
								FROM
								e_desk.ED02_SOLICITUD E
								WHERE
								(E.ED02_FECHASOLICITUD+0) >= $calendario_ingles_fechainicio and (E.ED02_FECHASOLICITUD+0) <= $calendario_ingles_fechatermino
								GROUP BY
								E.ED02_ESTADO
								UNION
								SELECT
								E.ED03_ESTADO as ESTADO,
								count(*) as CONTADOR,
								'INCIDENTES' as OPERACION
								FROM
								e_desk.ED03_TICKET E
								WHERE
								(E.ED03_FECHATICKET+0)  >= $calendario_ingles_fechainicio and (E.ED03_FECHATICKET+0) <= $calendario_ingles_fechatermino
								GROUP BY
								E.ED03_ESTADO
								UNION
								SELECT
								'INCIDENTES DERIVADOS + 7 DIAS' as ESTADO,
								count(*) as CONTADOR,
								'INCIDENTES' as OPERACION
								FROM
								e_desk.ED03_TICKET E
								WHERE 
								E.ED03_FECHATICKET<DATE_ADD(now(),INTERVAL -1 WEEK) and E.ED03_ESTADO='DERIVADO'
								GROUP BY
								E.ED03_ESTADO
								UNION
								SELECT
								E.ED05_ESTADO as ESTADO,
								count(*) as CONTADOR,
								'ASISTENCIAS' as OPERACION
								FROM
								e_desk.ED05_ASISTENCIA_TECNICA E
								WHERE
								(E.ED05_FECHAINGRESO+0) >= $calendario_ingles_fechainicio and (E.ED05_FECHAINGRESO+0) <= $calendario_ingles_fechatermino 
								GROUP BY
								E.ED05_ESTADO
								UNION
								SELECT
								'ASISTENCIAS DERIVADAS + 7 DIAS' as ESTADO,
								count(*) as CONTADOR,
								'ASISTENCIAS' as OPERACION
								FROM
								e_desk.ED05_ASISTENCIA_TECNICA E
								WHERE
								E.ED05_FECHAINGRESO<DATE_ADD(now(),INTERVAL -1 WEEK) and E.ED05_ESTADO='DERIVADO'
								GROUP BY
								E.ED05_ESTADO
								ORDER BY
								OPERACION DESC,
								ESTADO";
						
					 	$rowset = $DB->fetchAll($sSQL);
					
						$ID_FILAS="0";
					
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["OPERACION"])!="")
							{
								$CONTADOR_FILAS++;
								
								if($CONTADOR_FILAS>=$CONTADOR_INI && $CONTADOR_FILAS<=$CONTADOR_FIN)
								{
						
										$ID=$CONTADOR_FILAS;
                                      	$datosinforme["$ID"]["OPERACION"]=$row_datosQuery["OPERACION"];
										$datosinforme["$ID"]["ESTADO"]=$row_datosQuery["ESTADO"];
										$datosinforme["$ID"]["CONTADOR"]=$row_datosQuery["CONTADOR"];
										
								}						
							}								
						}
					
					
						$NUM_PAGINAS=intval($CONTADOR_FILAS/15);
						$RESTO_PAGINAS = $CONTADOR_FILAS%15;
						if($RESTO_PAGINAS>0)
						   $NUM_PAGINAS++;
						   
					
						
					
						Zend_Layout::getMvcInstance()->assign('texto_columna_1',$texto_columna_1);
						Zend_Layout::getMvcInstance()->assign('texto_columna_2',$texto_columna_2);
						Zend_Layout::getMvcInstance()->assign('texto_columna_3',$texto_columna_3);
						Zend_Layout::getMvcInstance()->assign('titulo_reporte',$titulo_reporte);
						Zend_Layout::getMvcInstance()->assign('fechainicio',$fechainicio);
						Zend_Layout::getMvcInstance()->assign('fechatermino',$fechatermino);
					
					
						if(isset($datosinforme))
								Zend_Layout::getMvcInstance()->assign('datosinforme',$datosinforme);
					
						if(isset($PAGINA))
								Zend_Layout::getMvcInstance()->assign('pagina',$PAGINA);
						
						if(isset($CONTADOR_FILAS))
								Zend_Layout::getMvcInstance()->assign('total_filas',$CONTADOR_FILAS);
					
						if(isset($NUM_PAGINAS))
								Zend_Layout::getMvcInstance()->assign('num_paginas',$NUM_PAGINAS);
					
						if(isset($CONTADOR_INI))
								Zend_Layout::getMvcInstance()->assign('registro_ini',$CONTADOR_INI);
						
						if(isset($CONTADOR_FIN))
								Zend_Layout::getMvcInstance()->assign('registro_fin',$CONTADOR_FIN);
						
						
	
	}


}