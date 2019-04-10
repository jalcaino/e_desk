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
						
						
					
		}

		public function validarusuarioAction()
		{
						
						$this->_helper->layout->disableLayout();
						$DB = Zend_Db_Table::getDefaultAdapter();


						###########################		
						##inicio validacion sesion
						###########################	
						
						$edesk_session = new Zend_Session_Namespace('edeskses');
				
						###########################		
						##fin validacion sesion
						###########################	
		
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
									e_desk.ED01_USUARIO WHERE ED01_USUARIOID = '$login' and ED01_PASSWORD=MD5($clave)"; 
							
				
							
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
		
				
									 //30 minutos
								    $edesk_session->setExpirationSeconds(1800);
								
									$edesk_session->ID=session_id();
									$edesk_session->USUARIOID=$USUARIOID;
									$edesk_session->NIVELID=$NIVELID;
									$edesk_session->SECTORID=$SECTORID;
									$edesk_session->NOMBREAPELLIDO=$NOMBREAPELLIDO;
									$edesk_session->EMAIL=$EMAIL;
									$edesk_session->ESPRIVADO=$ESPRIVADO;
									$edesk_session->AVISARASIGNACION=$AVISARASIGNACION;
									$edesk_session->AVISARSOLICITUD=$AVISARSOLICITUD; 
								
									Zend_Registry::set('session', $edesk_session);
									
									
									
									$ENCONTRO=1;
								
								}								
							}
							
		
							if($ENCONTRO==0)
							{
										echo "KO|Usuario no existe en el sistema!!!";
							}else{
										echo "OK|";
								 
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


		public function logoutAction()
		{
						
						$this->_helper->layout->disableLayout();
						Zend_Session::namespaceUnset('edeskses');
						
						header('location:/');
						exit;	
						
						
		}



}