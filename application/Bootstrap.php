<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    //equivalente a session start
    protected function _initSession()
    {
       session_name("edeskses"); 
	   session_set_cookie_params(0, '/', '.e-mat.cl');
	
	   Zend_Session::start();
    }

	//cargamos configuracion de application.ini
	protected function _initConfig() 
	{
		Zend_Registry::set('config', $this->getOptions());
	}
    protected function _initDb()
    {

        $this->bootstrap('multidb');
        $resource = $this->getPluginResource('multidb');

        Zend_Registry::set('db1', $resource->getDb('db1'));
    }

}