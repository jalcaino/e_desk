<?php
class Application_Model_Loginusuario extends Zend_Db_Table_Abstract
{
	
		public function verificaDatosLogin($login,$clave)
		{
				$DB=Zend_Db_Table::getDefaultAdapter();
									
				$sSQL = "SELECT
						u.ED01_USUARIOID,
						u.SIS02_NIVELID,
						n.SIS02_NIVELDESCRIPCION,	
						u.SIS01_SECTORID,
						s.SIS01_SECTORDESCRIPCION,
						u.ED01_NOMBREAPELLIDO,
						u.ED01_EMAIL,
						u.ED01_PASSWORD,
						u.ED01_ESPRIVADO,
						u.ED01_NOTIFICAR
						FROM
						e_desk.ED01_USUARIO u
						LEFT JOIN
						e_desk.SIS01_SECTOR s ON u.SIS01_SECTORID=s.SIS01_SECTORID
						LEFT JOIN
						e_desk.SIS02_NIVEL_USUARIO n ON u.SIS02_NIVELID=n.SIS02_NIVELID
						WHERE 
						u.ED01_USUARIOID = '$login' and u.ED01_PASSWORD='$clave' ";
					
				 		$rowset = $DB->fetchAll($sSQL);
				
						return $rowset;

		}
		
		
		
		public function registraConexion($USUARIOID)
		{
			$DB=Zend_Db_Table::getDefaultAdapter();
		
			///////////////////////////////////
			///GUARDADO REGISTRO ACTIVIDAD
			//////////////////////////////////
			$data_actividad = array(
								'ED01_USUARIOID' => $USUARIOID,
								'ED08_ACCION' => 'LOGIN',
								'ED08_MASINFO' => ''
								);
							
			try {
														
						$DB->getConnection();
						$DB->beginTransaction();
						$DB->insert('e_desk.ED08_USUARIO_ACTIVIDAD', $data_actividad);
						$DB->commit();
			
						return 1;
			
											
				} catch (Zend_Exception $e) {
													
						$DB->rollBack();

						return 0;

			   }									
		

		}

}