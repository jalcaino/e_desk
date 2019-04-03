<?php
class LoginController extends Zend_Controller_Action
{

		public function init()
		{
			/* Initialize action controller here */
		}
	
		public function indexAction()
		{
						
						$this->_helper->layout->disableLayout();
						$session = new Zend_Session_Namespace('edeskses');
					
		}

		public function validarusuarioAction()
		{
						
						$this->_helper->layout->disableLayout();
						$this->session = new Zend_Session_Namespace('edeskses');
						$DB = Zend_Db_Table::getDefaultAdapter();
		
		
						$login=$this->_request->getPost('login');
						$clave=$this->_request->getPost('clave');
				
						$ENCONTRO=0;
				
										
						$sSQL = "SELECT 
									ED01_USUARIOID,
									SIS02_NIVELID,
									SIS01_SECTORID,
									ED01_NOMBREAPELLIDO,
									ED01_EMAIL,
									ED01_ESPRIVADO,
									ED01_AVISARASIGNACION,
									ED01_AVISARSOLICITUD
									FROM
									e_desk.ED01_USUARIO WHERE ED01_USUARIOID = '$login' and ED01_PASSWORD='$clave'"; 
							
				
							
					 		$rowset = $DB->fetchAll($sSQL);

							foreach($rowset as $row_datosQuery)
							{
								if(trim($row_datosQuery["ED01_USUARIOID"])!="")
								{
								
									$USUARIOID=$row_datosQuery["ED01_USUARIOID"];
									$NIVELID=$row_datosQuery["SIS02_NIVELID"];
									$SECTORID=$row_datosQuery["SIS01_SECTORID"];
									$NOMBREAPELLIDO=$row_datosQuery["ED01_NOMBREAPELLIDO"];
									$EMAIL=$row_datosQuery["ED01_EMAIL"];
									$ESPRIVADO=$row_datosQuery["ED01_ESPRIVADO"];
									$AVISARASIGNACION=$row_datosQuery["ED01_AVISARASIGNACION"];
									$AVISARSOLICITUD=$row_datosQuery["ED01_AVISARSOLICITUD"];
		
		
		
									$details[$USUARIOID]=array(
															'USUARIOID'=>$USUARIOID,
															'NIVELID'=>$NIVELID,
															'SECTORID'=>$SECTORID,
															'NOMBREAPELLIDO'=>$NOMBREAPELLIDO,
															'EMAIL'=>$EMAIL,
															'ESPRIVADO'=>$ESPRIVADO,
															'AVISARASIGNACION'=>$AVISARASIGNACION,
															'AVISARSOLICITUD'=>$AVISARSOLICITUD 
									);
									
									//$this->session->DATOS_USUARIO=$details; 
									$ENCONTRO=1;
								
								}								
							}
							
		
							if($ENCONTRO==0)
							{
			
			
										echo "Usuario no existe en el sistema!!!";
			
			
							}else{
		
										echo "Si existe<br><br>";
										
										print_r($this->session);
		
								 }	
				
		
		
		}


		public function recordarclaveAction()
		{
						
						$this->_helper->layout->disableLayout();
		
						
		}
		
		
		public function recordarclaveprocessAction()
		{
						
						$this->_helper->layout->disableLayout();
						
		}



		public function notificacionAction()
		{
						
						$this->_helper->layout->disableLayout();
						$session = new Zend_Session_Namespace('edeskses');
										
						
		}

		public function menuperfilAction()
		{
						
						$this->_helper->layout->disableLayout();
						$session = new Zend_Session_Namespace('edeskses');
						
						
		}


		public function sesionAction()
		{
						
						$this->_helper->layout->disableLayout();
						$session = new Zend_Session_Namespace('edeskses');
						
		}


		public function infousuarioAction()
		{
						
						$this->_helper->layout->disableLayout();
						$session = new Zend_Session_Namespace('edeskses');
						
						
		}

}