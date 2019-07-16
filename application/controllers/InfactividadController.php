<?php

class InfactividadController extends Zend_Controller_Action
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
			
			
			$action="/Infactividad/listar";
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
					
						$texto_columna_1="Nombre Actividad";
						$texto_columna_2="Cantidad";
						$titulo_reporte="Actividades m&aacute;s consultadas";
						
					
						//CONSULTA
						////////////////////////////
						
						
						$sSQL="SELECT
								T.SIS05_CODIGOMODULO as ID,
								T.SIS05_CODIGOMODULO as DESCRIPCION,
								count(*) as CONTADOR
								FROM
								e_desk.ED03_TICKET T ";
																
								
						$sSQL.=" WHERE T.SIS05_CODIGOMODULO!='' and (T.ED03_FECHATICKET+0) >= $calendario_ingles_fechainicio and (T.ED03_FECHATICKET+0) <= $calendario_ingles_fechatermino ";		
									
						$sSQL.=" GROUP BY T.SIS05_CODIGOMODULO ORDER BY CONTADOR DESC ";
					
					
					
						
					 	$rowset = $DB->fetchAll($sSQL);
					
						$ID_FILAS="0";
					
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ID"])!="")
							{
								$CONTADOR_FILAS++;
								
								if($CONTADOR_FILAS>=$CONTADOR_INI && $CONTADOR_FILAS<=$CONTADOR_FIN)
								{
						
										$ID=$row_datosQuery["ID"];
                                      	$datosinforme["$ID"]["ID"]=$row_datosQuery["ID"];
										$datosinforme["$ID"]["DESCRIPCION"]=$row_datosQuery["DESCRIPCION"];
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
						
						if(isset($busqueda))
								Zend_Layout::getMvcInstance()->assign('busqueda',$busqueda);
		
	
	}


}