<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
	
        /* Initialize action controller here */
    	$this->_helper->layout->disableLayout();
					
	}

    public function indexAction()
    {

		###########################		
		##inicio validacion sesion
		###########################	
		
		$edesk_session = new Zend_Session_Namespace('edeskses');

		if(trim($edesk_session->ID)!="" && trim($edesk_session->USUARIOID)!="" &&  trim($edesk_session->NIVELID)!="")
		{
			header('location:/Fichacolegio');
			exit;		
		}

		###########################		
		##fin validacion sesion
		###########################	




	

    }


}