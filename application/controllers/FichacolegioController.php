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
							
								  
						$sSQL="SELECT SIS03_LABORATORIODESCRIPCION FROM e_desk.SIS03_LABORATORIO WHERE SIS03_LABORATORIOID='".$S_COLEGIO."'";
				
						$rowset = $DB->fetchAll($sSQL);
						if (count($rowset) > 0)  
						{
							$row = reset($rowset);
							$LABORATORIODESCRIPCION = $row["SIS03_LABORATORIODESCRIPCION"];
							$LABORATORIOID=$S_COLEGIO;
	
						}else{
						
								$LABORATORIODESCRIPCION="--";	
								$LABORATORIOID="SIN-INFO";
					
						
						}
						
						
	
						Zend_Layout::getMvcInstance()->assign('LABORATORIOID',$LABORATORIOID);
						Zend_Layout::getMvcInstance()->assign('LABORATORIODESCRIPCION',$LABORATORIODESCRIPCION);
    	
		}


}