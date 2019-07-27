<?php
class ZendExt_ValidaLoginUsuario extends Zend_Controller_Action
{

    public function consultaDatosLogin($login,$clave)
	{

		$clave_md5=md5($clave);			
			
        $consulta = new Application_Model_Loginusuario();
        $result = $consulta->verificaDatosLogin($login,$clave_md5);

		if (!empty($result))
		{
				return json_encode(array('error' => false,'ED01_USUARIOID' => $result[0]['ED01_USUARIOID'],'SIS02_NIVELID' => $result[0]['SIS02_NIVELID'],'SIS02_NIVELDESCRIPCION' => $result[0]['SIS02_NIVELDESCRIPCION'],'SIS01_SECTORID' => $result[0]['SIS01_SECTORID'],'SIS01_SECTORDESCRIPCION' => $result[0]['SIS01_SECTORDESCRIPCION'],'ED01_NOMBREAPELLIDO' => $result[0]['ED01_NOMBREAPELLIDO'],'ED01_EMAIL' => $result[0]['ED01_EMAIL'],'ED01_PASSWORD' => $result[0]['ED01_PASSWORD'],'ED01_ESPRIVADO' => $result[0]['ED01_ESPRIVADO'],'ED01_NOTIFICAR' => $result[0]['ED01_NOTIFICAR']));
		}
        else{   
     	       return json_encode(array('error' =>true));             
        }

    }


    public function registraConexion($USUARIOID)
	{

        $consulta = new Application_Model_Loginusuario();
        $result = $consulta->registraConexion($USUARIOID);

		if (!empty($result))
			   return json_encode(array('error' => false));
        else   
     	       return json_encode(array('error' =>true));             
        
	}



} //fin Clase
?>