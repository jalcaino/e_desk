<?php

class InfaccesoController extends Zend_Controller_Action
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
			
			
			$action="/Infacceso/listar";
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
						$usuario=$this->_request->getPost('usuario');
					
					
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
					
						$texto_columna_1="Usuario";
						$texto_columna_2="Fecha Acceso";
						$texto_columna_3="Acci&oacute;n";
						$texto_columna_4="+Info";
						
						$titulo_reporte="Actividad Usuarios";
						
					
						//CONSULTA
						////////////////////////////
					
						$sSQL="SELECT
								ED01_USUARIOID,
								DATE_FORMAT(ED08_FECHAHORA, '%d/%m/%Y %H-%i-%s') as FECHA,
								ED08_ACCION,
								ED08_MASINFO
								FROM
								e_desk.ED08_USUARIO_ACTIVIDAD ";
																
								
						$sSQL.=" WHERE DATE_FORMAT(ED08_FECHAHORA, '%Y%m%d') >= $calendario_ingles_fechainicio and DATE_FORMAT(ED08_FECHAHORA, '%Y%m%d') <= $calendario_ingles_fechatermino ";		
									
						
						if($usuario!="-")
							$sSQL.=" and ED01_USUARIOID='$usuario' ";
						
									
						$sSQL.=" ORDER BY ED08_FECHAHORA DESC ";
					
						
						
					 	$rowset = $DB->fetchAll($sSQL);
					
					
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
								$CONTADOR_FILAS++;
								
								if($CONTADOR_FILAS>=$CONTADOR_INI && $CONTADOR_FILAS<=$CONTADOR_FIN)
								{
						
										$ID=$CONTADOR_FILAS;
                                      	$datosinforme["$ID"]["USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
										$datosinforme["$ID"]["FECHA"]=$row_datosQuery["FECHA"];
										$datosinforme["$ID"]["ACCION"]=$row_datosQuery["ED08_ACCION"];
										$datosinforme["$ID"]["MASINFO"]=$row_datosQuery["ED08_MASINFO"];
										
								}						
							}								
						}
					
					
						$NUM_PAGINAS=intval($CONTADOR_FILAS/15);
						$RESTO_PAGINAS = $CONTADOR_FILAS%15;
						if($RESTO_PAGINAS>0)
						   $NUM_PAGINAS++;
						   
					
						
						$sSQL="SELECT 
								ED01_USUARIOID, 
								ED01_NOMBREAPELLIDO 
								FROM 
								e_desk.ED01_USUARIO 
								ORDER BY
								ED01_NOMBREAPELLIDO ";


					 	$rowset = $DB->fetchAll($sSQL);
					
						foreach($rowset as $row_datosQuery)
						{
							if(trim($row_datosQuery["ED01_USUARIOID"])!="")
							{
										$ID=$row_datosQuery["ED01_USUARIOID"];
                                      	$datosusuarios["$ID"]["USUARIOID"]=$row_datosQuery["ED01_USUARIOID"];
										$datosusuarios["$ID"]["NOMBREAPELLIDO"]=$row_datosQuery["ED01_NOMBREAPELLIDO"];
							}								
						}

					
						
					
						Zend_Layout::getMvcInstance()->assign('texto_columna_1',$texto_columna_1);
						Zend_Layout::getMvcInstance()->assign('texto_columna_2',$texto_columna_2);
						Zend_Layout::getMvcInstance()->assign('texto_columna_3',$texto_columna_3);
						Zend_Layout::getMvcInstance()->assign('texto_columna_4',$texto_columna_4);
						Zend_Layout::getMvcInstance()->assign('titulo_reporte',$titulo_reporte);
						Zend_Layout::getMvcInstance()->assign('fechainicio',$fechainicio);
						Zend_Layout::getMvcInstance()->assign('fechatermino',$fechatermino);
						Zend_Layout::getMvcInstance()->assign('usuario',$usuario);
					

						if(isset($datosusuarios))
								Zend_Layout::getMvcInstance()->assign('datosusuarios',$datosusuarios);

					
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