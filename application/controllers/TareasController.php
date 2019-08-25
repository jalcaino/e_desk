<?php

class TareasController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function avisonotificacionesAction()
    {
      	  
		  		$this->_helper->layout->disableLayout();
				
	
				$DB = Zend_Db_Table::getDefaultAdapter();
				$config = Zend_Registry::get('config');
				$functions = new ZendExt_RutinasPhp();
		
		
				//USUARIOS
				////////////////////////////
				$sSQL="SELECT
						u.ED01_USUARIOID,
						u.ED01_EMAIL
						FROM
						e_desk.ED01_USUARIO u ";
			
			
				$rowset = $DB->fetchAll($sSQL);

				foreach($rowset as $row_datosQuery)
				{
					if(trim($row_datosQuery["ED01_USUARIOID"])!="")
					{
							$ID=$row_datosQuery["ED01_USUARIOID"];
							$datosusuarios["$ID"]=$row_datosQuery["ED01_EMAIL"];
						
					}
				}
		
				
				
		
				//incidentes
				////////////////
				$sSQL = "SELECT 
						T.ED03_TICKETID as MAESTROID,
						E.ED01_USUARIOID as USUARIO_DESTINO
						FROM 
						e_desk.ED09_USUARIO_NOTIFICADO_SEG_TICKET E
						INNER JOIN
						e_desk.ED04_SEGUIMIENTO_TICKET T on E.ED04_SEGTICKETID=T.ED04_SEGTICKETID
						WHERE
						E.ED09_LEIDO=0 and E.ED09_FECHANOTIFICACION < DATE_ADD(now(),INTERVAL -1 DAY) ";			
				
				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["USUARIO_DESTINO"])!="")
						{
							$ID=trim($row_datosQuery["USUARIO_DESTINO"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
						}

				}
		
		

				$sSQL = "SELECT
							E.ED03_TICKETID as MAESTROID,
							T.ED01_USUARIOID as USUARIO_DESTINO
							FROM
							e_desk.ED03_TICKET E
							INNER JOIN e_desk.ED10_USUARIO_NOTIFICADO_TICKET T ON E.ED03_TICKETID=T.ED03_TICKETID
							WHERE
							T.ED10_LEIDO=0 and T.ED10_FECHANOTIFICACION < DATE_ADD(now(),INTERVAL -1 DAY)";


				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["USUARIO_DESTINO"])!="")
						{
							$ID=trim($row_datosQuery["USUARIO_DESTINO"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
						}

				}
		
		
		
		
				//asistencias
				////////////////
				
		
				$sSQL = "SELECT 
						T.ED05_ASISTENCIAID as MAESTROID,
						E.ED01_USUARIOID as USUARIO_DESTINO 
						FROM 
						e_desk.ED12_USUARIO_NOTIFICADO_SEG_ASIS E
						INNER JOIN
						e_desk.ED06_SEGUIMIENTO_ASISTENCIA_TECNICA T on E.ED06_SEGASISTENCIAID=T.ED06_SEGASISTENCIAID
						WHERE
						E.ED12_LEIDO=0 and E.ED12_FECHANOTIFICACION < DATE_ADD(now(),INTERVAL -1 DAY) ";			
				
				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["USUARIO_DESTINO"])!="")
						{
							$ID=trim($row_datosQuery["USUARIO_DESTINO"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
						
						}

				}


				$sSQL = "SELECT
							E.ED05_ASISTENCIAID as MAESTROID,
							T.ED01_USUARIOID as USUARIO_DESTINO
							FROM
							e_desk.ED05_ASISTENCIA_TECNICA E
							INNER JOIN e_desk.ED11_USUARIO_NOTIFICADO_ASISTENCIA T ON E.ED05_ASISTENCIAID=T.ED05_ASISTENCIAID
							WHERE
							T.ED11_LEIDO=0 and T.ED11_FECHANOTIFICACION < DATE_ADD(now(),INTERVAL -1 DAY) ";


				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["USUARIO_DESTINO"])!="")
						{
							$ID=trim($row_datosQuery["USUARIO_DESTINO"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
						
						}

				}

		
		
		
		
		
		
		
				//solicitudes
				////////////////
				
				$sSQL = "SELECT 
						T.ED02_SOLICITUDID as MAESTROID,
						E.ED01_USUARIOID as USUARIO_DESTINO 
						FROM 
						e_desk.ED17_USUARIO_NOTIFICADO_SOLICITUD E
						INNER JOIN
						e_desk.ED02_SOLICITUD T on E.ED02_SOLICITUDID=T.ED02_SOLICITUDID
						WHERE
						E.ED17_LEIDO=0 and E.ED17_FECHANOTIFICACION < DATE_ADD(now(),INTERVAL -1 DAY) ";			
				
				
				$rowset = $DB->fetchAll($sSQL);
	
				foreach($rowset as $row_datosQuery)
				{
						if(trim($row_datosQuery["USUARIO_DESTINO"])!="")
						{
							$ID=trim($row_datosQuery["USUARIO_DESTINO"]);
							$matriz_notificacion["$ID"]["MAESTROID"]=trim($row_datosQuery["MAESTROID"]);
						
						}

				}
				
			
			
			
			
			
			
			    $email="";
				
				if(isset($matriz_notificacion))
				{
					//print_r($matriz_notificacion);
			
					foreach ($matriz_notificacion as $clave => $valor)
					{
						//echo $clave."<br>";
						
						if(isset($datosusuarios["$clave"]))
						{
							//echo $datosusuarios["$clave"]."<br>";
						
							if($email=="")
							   $email=$datosusuarios["$clave"];
							else   	
							   $email.=",".$datosusuarios["$clave"];
						
						}
					
					}
			
	  			}
	  
	  
	  
	  
	 	
	  
			  
				if($email!="")
				{
				
					   $subject="INTERNO - NOTIFICACIONES NO REVISADAS EN E-DESK";
					   $body="<u>Estimado Usuario</u><br><br>
					   Con fecha de hoy ".date("d/m/Y")." hemos detectado que no haz revisado todas tus notificaciones en E-DESK.<br><br>
					   Por favor ingresa a la plataforma y revisa las actividades en las que te han notificado. <br><br>
					   Atte.<br>Equipo Compumat.";
				
		
					   $RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
	
	
	
				}			

	  
	  
	  
	  
    }

    public function avisoincidenteretrasoAction()
    {
    

					$this->_helper->layout->disableLayout();
					
		
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
			
					
					
					#############################LISTADO DE DERIVADOS CON MAS DE 6 DIAS SIN RESPUESTA#######################
					#############################LISTADO DE DERIVADOS CON MAS DE 6 DIAS SIN RESPUESTA#######################
				
				
					$CUERPO="<table border='1'>";
					$CUERPO.="<tr bgcolor='#EEEEEE'>";
					$CUERPO.="<td><strong><font face='arial' size='3'>Colegio</font></strong></td>";
					$CUERPO.="<td><strong><font face='arial' size='3'>N&deg;&nbsp;Incidente</font></strong></td>";
					$CUERPO.="<td><strong><font face='arial' size='3'>Detalle Incidente</font></strong></td>";
					$CUERPO.="<td><strong><font face='arial' size='3'>Fecha</font></strong></td>";
					$CUERPO.="<td><strong><font face='arial' size='3'>D&iacute;as de demora</font></strong></td>";
					$CUERPO.="<td><strong><font face='arial' size='3'>Usuario Generador</font></strong></td>";
					$CUERPO.="<td><strong><font face='arial' size='3'>Usuario Derivado</font></strong></td>";
					$CUERPO.="<td><strong><font face='arial' size='3'>M&oacute;dulo</font></strong></td>";
					$CUERPO.="</tr>";
				
					
					
					 $sSQL= "SELECT
							 SIS03_LABORATORIOID,
							 ED03_TICKETID,
							 ED03_DETALLETICKET,
							 ED03_FECHATICKET,
							 DATEDIFF(now(),ED03_FECHATICKET) as dias,
							 ED01_USUARIOID,
							 ED03_DERIVADO,
							 SIS05_CODIGOMODULO
							 FROM
							 e_desk.ED03_TICKET
							 WHERE
							 ED03_ESTADO in ('DERIVADO','PENDIENTE') 
							 and 
							 DATEDIFF(now(),ED03_FECHATICKET) > 6 
							 ORDER BY 
							 ED03_FECHATICKET";


					$hay_sin_resolver=0;
				
				
					$rowset = $DB->fetchAll($sSQL);
	
					foreach($rowset as $row_datosQuery)
					{
							if(trim($row_datosQuery["SIS03_LABORATORIOID"])!="")
							{
				
								$hay_sin_resolver=1;
				
								$CUERPO.="<tr>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["SIS03_LABORATORIOID"])."</font></td>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["ED03_TICKETID"])."</font></td>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["ED03_DETALLETICKET"])."</font></td>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["ED03_FECHATICKET"])."</font></td>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["dias"])."</font></td>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["ED01_USUARIOID"])."</font></td>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["ED03_DERIVADO"])."</font></td>";
								$CUERPO.="<td><font face='arial' size='2'>".trim($row_datosQuery["SIS05_CODIGOMODULO"])."</font></td>";
								$CUERPO.="</tr>";

							}
	
					}
				
				
					$CUERPO.="</table>";
					
					
					//USUARIOS
					////////////////////////////
					$email="";
					
					$sSQL="SELECT
							u.ED01_USUARIOID,
							u.ED01_EMAIL
							FROM
							e_desk.ED01_USUARIO u WHERE u.SIS02_NIVELID in (2,3) ";
				
				
					$rowset = $DB->fetchAll($sSQL);
	
					foreach($rowset as $row_datosQuery)
					{
						if(trim($row_datosQuery["ED01_USUARIOID"])!="")
						{
							if($email=="")
							   $email=$row_datosQuery["ED01_EMAIL"];
							else   	
							   $email.=",".$row_datosQuery["ED01_EMAIL"];
						}
					}
					
					
					
					if($hay_sin_resolver==1)
					{
					   $subject="INTERNO - INCIDENTES NO RESUELTOS E-DESK EN 6 DIAS";
					   $body=addslashes($CUERPO);
					   $RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);
			
					}
						
	
    }

   
   
    public function avisoasistenciagendaAction()
    {
        			// action body
    	
					$this->_helper->layout->disableLayout();
					
		
					$DB = Zend_Db_Table::getDefaultAdapter();
					$config = Zend_Registry::get('config');
					$functions = new ZendExt_RutinasPhp();
			
	
	
	
					//USUARIOS
					/////////////////
					$sSQL="SELECT
							u.ED01_USUARIOID,
							u.ED01_EMAIL
							FROM
							e_desk.ED01_USUARIO u ";
				
				
					$rowset = $DB->fetchAll($sSQL);
	
					foreach($rowset as $row_datosQuery)
					{
						if(trim($row_datosQuery["ED01_USUARIOID"])!="")
						{
						
							   $ID=$row_datosQuery["ED01_USUARIOID"];
							   $matriz_usuarios["$ID"]=$row_datosQuery["ED01_EMAIL"];
						}
					}
	
	
	
	
					//ASISTENCIAS
					/////////////////
					//hasta 1 y 3 días
					$sSQL="SELECT 
							ED05_DERIVADO, 
							DATE_FORMAT(ED05_FECHAREALIZARCE, '%d-%m-%Y') as FECHAREALIZARCE, 
							ED05_ASISTENCIAID, 
							SIS03_LABORATORIOID
							FROM 
							e_desk.ED05_ASISTENCIA_TECNICA 
							WHERE
							DATEDIFF(ED05_FECHAREALIZARCE,now()) > 0 and DATEDIFF(ED05_FECHAREALIZARCE,now()) < 4 ";
			
				
					$rowset = $DB->fetchAll($sSQL);
	
					foreach($rowset as $row_datosQuery)
					{
						if(trim($row_datosQuery["ED05_ASISTENCIAID"])!="")
						{
						
							   $IDDER=$row_datosQuery["ED05_DERIVADO"];
					
							   if(isset($matriz_usuarios["$IDDER"]))
							   {
							   
							   		   $email=$matriz_usuarios["$IDDER"];
			
									   $subject="INTERNO - RECORDATORIO FUTURA ASISTENCIA TECNICA A REALIZAR - E-DESK";
									   $body="<u>Estimado Usuario</u><br><br>
									   Recuerda que en la fecha : ".$row_datosQuery["FECHAREALIZARCE"]." debes realizar la asistencia num : ".$row_datosQuery["ED05_ASISTENCIAID"]." <br><br>
									   para el colegio RBD : ".$row_datosQuery["SIS03_LABORATORIOID"].". <br><br>
									   Atte.<br>Equipo Compumat.";
								
						
									   $RES_ENVIO=$functions->envio_correos($config['desdeenvio'],$email,$subject,$body);

											

							   }
						}
					}
	
	
	
	}


}