<?php

class LoginusuarioController extends ZendExt_ValidaLoginUsuario
{

    public function init()
    {
    	$this->initView();
    	$this->view->baseUrl = $this->_request->getBaseUrl();
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
		$config = Zend_Registry::get('config');
		$functions = new ZendExt_RutinasPhp();
							

		###########################		
		##inicio validacion sesion
		###########################	
		
		$edesk_session = new Zend_Session_Namespace('edeskses');

		###########################		
		##fin validacion sesion
		###########################	


        $login=$this->_request->getPost('login');
		$clave=$this->_request->getPost('clave');
		$recuerdame=$this->_request->getPost('recuerdame');

        $result=$this->consultaDatosLogin($login,$clave);
		$json = json_decode($result, true);


		if(!$json['error'])
		{
			
			//30 minutos
			$edesk_session->setExpirationSeconds(60*60*24*1);
		
			$edesk_session->ID=session_id();
			$edesk_session->USUARIOID=$json['ED01_USUARIOID'];
			$edesk_session->NIVELID=$json['SIS02_NIVELID'];
			$edesk_session->NIVELDESCRIPCION=$json['SIS02_NIVELDESCRIPCION'];
			$edesk_session->SECTORID=$json['SIS01_SECTORID'];
			$edesk_session->SECTORDESCRIPCION=$json['SIS01_SECTORDESCRIPCION'];
			$edesk_session->NOMBREAPELLIDO=$json['ED01_NOMBREAPELLIDO'];
			$edesk_session->EMAIL=$json['ED01_EMAIL'];
			$edesk_session->ESPRIVADO=$json['ED01_ESPRIVADO'];
			$edesk_session->NOTIFICAR=$json['ED01_NOTIFICAR'];
			
			Zend_Registry::set('session', $edesk_session);

	
			
			//para recordar datos en el formulario
			if($recuerdame==1)
			{
				setcookie("cok_login",$login,time()+(60*60*24*365),"/");
				setcookie("cok_clave",$clave,time()+(60*60*24*365),"/");
				setcookie("cok_recuerdame","1",time()+(60*60*24*365),"/");
			
			}else{
			
					if(isset($_COOKIE['cok_login'])) unset($_COOKIE['cok_login']);
					if(isset($_COOKIE['cok_clave'])) unset($_COOKIE['cok_clave']);
					if(isset($_COOKIE['cok_recuerdame'])) unset($_COOKIE['cok_recuerdame']);
			
					setcookie('cok_login', null, -1, '/');
					setcookie('cok_clave', null, -1, '/');
					setcookie('cok_recuerdame', null, -1, '/');
			
			}					
	
	
	
			$result=$this->registraConexion($edesk_session->USUARIOID);

	
			echo "OK|";

		
		}else{
		
		
					if(isset($_COOKIE['cok_login'])) unset($_COOKIE['cok_login']);
					if(isset($_COOKIE['cok_clave'])) unset($_COOKIE['cok_clave']);
			
					setcookie('cok_login', null, -1, '/');
					setcookie('cok_clave', null, -1, '/');
		
		
		
					echo "KO|Usuario no existe en el sistema!!!";
	
		
		}		
	


    }

}