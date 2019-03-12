<?php

class EmatpsuoperController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function estadoactividadAction()
    {

    //revisa en que esta el alumno, si tiene un modulo o actividad inconclusa o no
        // action body
        //requiere layout activado en aplication ini
        $this->_helper->layout->disableLayout();
         @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        //echo "voy bien 1";
        //configuraci?n inicial
        $config = Zend_Registry::get('config');
        //echo "voy bien 2";
        //CONEXION

        ##INICIO determinar server de conexi?n BD
        ##############################################
        $VAR_HOST = explode(".",$_SERVER["HTTP_HOST"]);
        $VAR_HOST_ACTUAL=$VAR_HOST[0];
        if(isset($config['servidor'][$VAR_HOST_ACTUAL]))
        {
           $CONNECTOR=$config['servidor'][$VAR_HOST_ACTUAL];
           $DB = Zend_Registry::get($CONNECTOR);
        }else
            $DB = Zend_Db_Table::getDefaultAdapter();
        ##FIN determinar server de conexi?n BD
        ##############################################
        
                //echo "voy bien 3";
        

        //con parametros

        //PARAMETROS
        $S_RUT=$this->_request->getPost('festadoactividadp1');
        $S_ACTIVIDAD=$this->_request->getPost('festadoactividadp2');

        //LLEVARA REFERER
        $producto = ($this->_request->getPost('Prod') != "")?$this->_request->getPost('Prod'):"XXX";
        if(isset($config['bds'][$producto]))
            $_BASE_PRODUCTO=$config['bds'][$producto];
        else
            $_BASE_PRODUCTO=$config['bds']['defecto'];

        
        if(trim($S_RUT)!="" && trim($S_ACTIVIDAD)!="")
         {
        
                /*
                $S_ID=$this->encrypt($S_ID,$keyStr);
                $S_CLAVE=$this->encrypt($S_CLAVE,$keyStr);
                */  
                //echo "$S_ID@@@$S_CLAVE<br>";
        
        
                // si los datos est?n encriptados
                // usamos encrypt
                //$S_ID=$functions->decrypt($S_ID,$keyStr);
                //$S_CLAVE=$functions->decrypt($S_CLAVE,$keyStr);
        
                //echo "$S_ID@@@$S_CLAVE<br>";
                
                $sql = "select ultimomodulo,marca,datosreinic from ".$_BASE_PRODUCTO."tblacceso where rut = '$S_RUT' ";
                //echo ("<br>".$sql."<br>");
                $rowset = $DB->fetchAll($sql);
                //print_r($rowset);
                //echo (" marca ".$rowset["marca"]);
                $sql1 = "select puntaje as puntaje,termino as termino from ".$_BASE_PRODUCTO."tblregistro where rut = '$S_RUT' and modulo =  '$S_ACTIVIDAD' and TRIM(estado) = 'terminado' ";
                //echo ("<br>".$sql1."<br>");
                //
                $bLibre = 0;
                $sInconclusa = "";
                $sDatosAct = "";
                if (count($rowset) > 0)
                   {
                     
					  if ($rowset[0]['marca'] == 0)
                      {
                     	    						  
						    //echo("-0X-");
                            $rowset1 = $DB->fetchAll($sql1);
                            //print_r($rowset1);
                            //echo(" count ".count($rowset1));
                            //exit;
                            if (count($rowset1) > 0)
                            {
                                 //echo ("marca = 0 y rowset1 = 1");   //existe registro en tblregistro
                                 //exit;
                                $sDatosAct = trim($rowset1[0]['puntaje'])."|".trim($rowset1[0]['termino'])."|";
                            
							
							
							}
                            else
                            {
                                //echo ("marca = 0 y rowset1 = 0");
                                //exit;
                                $sDatosAct = "0|0"."|";
                            
							}
                        }
                      else   // hay una iniciada
                        {
							
        	                  //echo ("marca es 1");
    	                     $sReinicio = $rowset[0]['datosreinic'];
	                         if (stripos($sReinicio,$S_ACTIVIDAD)  === FALSE )   //  ' no  la encuentra, hay otra
                             {
								 $bLibre = 1;
								//cual es la otra ?
								 $sInconclusa =  substr($sReinicio,0,4);
                             
							 }
                          	  else // si la puede hacer , es la misma
                             {
                            
								 $bLibre = 2;
								 $sInconclusa =  $S_ACTIVIDAD;
							  	
                             }
                        }


                  }
               		
					
					
					if ($bLibre == 0)
                    {
                    	 echo($bLibre.utf8_encode("�").$sDatosAct.utf8_encode("�"));

					
					}
                	else
                    {
                     	echo($bLibre.utf8_encode("�").$sInconclusa.utf8_encode("�"));
                    }



         }
    }

    public function insrtactividadAction()
    {
        
       
	   $this->_helper->layout->disableLayout();
       @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('Inicios');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_insrtactividad($S_INICIOS,$S_PRODUCTO);
	   
	   //INICIO logs
	   $functions_logs = new ZendExt_Application_Resource_RutinasPsu();
	   $functions_logs->graba_logs("Ematpsuoper-insrtactividad",$S_PRODUCTO,$S_INICIOS."@@".$respuesta_servicio);
	   //FIN logs
	
	   
	   echo $respuesta_servicio;
		
		
	   
    }

    public function leereinicioAction()
    {

        $this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
    
	

		   
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('LeeReinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_leereinicio($S_INICIOS,$S_PRODUCTO);
	
	   //INICIO logs
	   $functions_logs = new ZendExt_Application_Resource_RutinasPsu();
	   $functions_logs->graba_logs("Ematpsuoper-leereinicio",$S_PRODUCTO,$S_INICIOS."@@".$respuesta_servicio);
	   //FIN logs
	
	
	
	
	   echo $respuesta_servicio;
	 
	
    }

    public function reinicioAction()
    {
        
		//JA INICIO MANEJO DE SESIONES	
		$compumatSession = new Zend_Session_Namespace('Compumat');
		$SESSION_ACTIVA="";
		if(isset($compumatSession->ultima_session_logeada))
		{
			$SESSION_ACTIVA=$compumatSession->ultima_session_logeada;
		}
		//JA FIN MANEJO DE SESIONES	
		
		
		
		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('Reinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_reinicio($S_INICIOS,$S_PRODUCTO,$SESSION_ACTIVA);
	   
	   
	   //INICIO logs
	   $functions_logs = new ZendExt_Application_Resource_RutinasPsu();
	   $functions_logs->graba_logs("Ematpsuoper-reinicio",$S_PRODUCTO,$S_INICIOS."@@".$respuesta_servicio);
	   //FIN logs
		   
	   
	   echo $respuesta_servicio;
		   
		
	
    }

    public function updateactividadAction()
    {
        		
		
		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        $compumatSession = new Zend_Session_Namespace('Compumat');

	
	    //reemplazo webservice
	     $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	    //con parametros
        $S_PRODUCTO = $this->_request->getPost('Prod');	
		$S_TERMINOS = $this->_request->getPost('Terminos'); // Parametros del ejercicio
        $S_FLAG_REGISTRO = $this->_request->getPost('SinR'); // No graba en tblregistro
        $S_FLAG_ADMIN = $this->_request->getPost('desdeAdmin'); // No aumenta cuenta si se cierra desde admin
        $S_FLAG_ACCESO = $this->_request->getPost('SinA'); // No actualiza ultimomodulo

        if(!isset($S_FLAG_REGISTRO)) $S_FLAG_REGISTRO = 0;
        if(!isset($S_FLAG_ADMIN)) $S_FLAG_ADMIN = 0;
        if(!isset($S_FLAG_ACCESO)) $S_FLAG_ACCESO = 0;

			
	   $respuesta_servicio = $functions->ws_updateactividad($S_TERMINOS,$S_FLAG_REGISTRO,$S_FLAG_ADMIN,$S_FLAG_ACCESO,$S_PRODUCTO);


   		//INICIO logs
		$functions_logs = new ZendExt_Application_Resource_RutinasPsu();
		$functions_logs->graba_logs("Ematpsuoper-updateactividad",$S_PRODUCTO,$S_TERMINOS."@@".$respuesta_servicio);
		//FIN logs


	    echo $respuesta_servicio;

        // Libera sesion para actualizar registro login
        unset($compumatSession->login);
        unset($compumatSession->modulo);
		

    }

    public function terminovueltatrasAction()
    {
  
  					$this->_helper->layout->disableLayout();
					@header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
				   
				
				   //reemplazo webservice
				   $functions = new ZendExt_Application_Resource_Ematpsuoper();
				   
				   $S_TERMINOS = $this->_request->getPost('Terminos');
				   $S_PRODUCTO = $this->_request->getPost('Prod');	
						
				   $respuesta_servicio = $functions->ws_terminovueltatras($S_TERMINOS,$S_PRODUCTO);
				   
				   
				    //INICIO logs
				   $functions_logs = new ZendExt_Application_Resource_RutinasPsu();
				   $functions_logs->graba_logs("Ematpsuoper-terminovueltatras",$S_PRODUCTO,$S_TERMINOS."@@".$respuesta_servicio);
				   //FIN logs
				
				   
				   
				   echo $respuesta_servicio;
    
    }

    public function cierracapituloAction()
    {
                    // action body
                    // este controlador cierra un cápitulo o una unidad de trabajo,
                    // poniendo en 2 el caracter asociado a la unidad en detallenivelacion
                    // el alumno puede estar en estado de recuperacion o en nivel, porque encualquiera
                    //de los dos puede implementarse mediante el metodo de las unidades de trabajo
                    // adicionalmente pone en CERO marca; y en BLANCO datosreinicio
                    // Este controlador NO CAMBIA NUNCA EL ESTADO DE NIVELACION.
                    //
                    $this->_helper->layout->disableLayout();
                      @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
                    //configuraci?n inicial
                    $config = Zend_Registry::get('config');
                    
                    //CONEXION

                    ##INICIO determinar server de conexi?n BD
                    ##############################################
                    $VAR_HOST = explode(".",$_SERVER["HTTP_HOST"]);
                    $VAR_HOST_ACTUAL=$VAR_HOST[0];
                    if(isset($config['servidor'][$VAR_HOST_ACTUAL]))
                    {
                       $CONNECTOR=$config['servidor'][$VAR_HOST_ACTUAL];
                       $DB = Zend_Registry::get($CONNECTOR);
                    }else
                        $DB = Zend_Db_Table::getDefaultAdapter();
                    ##FIN determinar server de conexi?n BD
                    ##############################################

                            
                    
                    //con parametros
                    $S_PARAMETROS = $this->_request->getPost('CierraCapitulo');
                                    
                    $matriz = explode("|",$S_PARAMETROS);
    
                    $Rut = $matriz[0];
                    $nTest = $matriz[1];
                    $Valor = $matriz[2];

        //LLEVARA REFERER
        $producto = ($this->_request->getPost('Prod') != "")?$this->_request->getPost('Prod'):"XXX";
        if(isset($config['bds'][$producto]))
            $_BASE_PRODUCTO=$config['bds'][$producto];
        else
            $_BASE_PRODUCTO=$config['bds']['defecto'];


                    $estado = 1;

                    //leer detalle actual
                    //preparao bd
                  
                    //leo detalle
                    $iError = 0;
                    $sSQL="select detallesnivelacion FROM $_BASE_PRODUCTO"."tblacceso where rut = '".$Rut."' ";
                                    
                    $rowset = $DB->fetchAll($sSQL);
                    if (count($rowset) > 0)  
                    {
                        $row = reset($rowset);
                        $sDetalle = $row["detallesnivelacion"];

                    }else{

                             echo "20|0|0|".$sSQL;
                             exit;
                    }



                    //fin recuperacion detalle nivelacion
                    //proceso

                    $i = $nTest-1;
                    //echo $i;
                    $detalle = str_split($sDetalle);
                    $detalle[$i] = $Valor;
                    $detalle = implode($detalle);


                  //Proceso de Actualizacion de  Campos Estado y DetalleNivelacion
                  $iError = 0;
                    
                   //JA 21/03/2017 a solicitud de HD no se realiza esta actualización 'estadonivelacion' => $estado,
                        
					
					
                  //actualizo
                  $DB->beginTransaction();
                  try {
                            $data1 = array(
                              'detallesnivelacion'  => $detalle,
                              'marca' => '0',
                              'datosreinic' => ''
                              );
                       
                             $where1['rut = ?' ] =  $Rut;
                             $n = $DB->update($_BASE_PRODUCTO.'tblacceso', $data1, $where1);
                             
                             $DB->commit();
                           
                             echo "100|".$detalle;
                      }
                      catch (Exception $e)
                      {
                              $DB->rollBack();
                              echo $e->getMessage();
                      }

                    
    }

    public function cierranivelacionAction()
    {
                    // action body
                  
                    $this->_helper->layout->disableLayout();
                      @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
                    //configuraci?n inicial
                    $config = Zend_Registry::get('config');
                    
                    //CONEXION

                    ##INICIO determinar server de conexi?n BD
                    ##############################################
                    $VAR_HOST = explode(".",$_SERVER["HTTP_HOST"]);
                    $VAR_HOST_ACTUAL=$VAR_HOST[0];
                    if(isset($config['servidor'][$VAR_HOST_ACTUAL]))
                    {
                       $CONNECTOR=$config['servidor'][$VAR_HOST_ACTUAL];
                       $DB = Zend_Registry::get($CONNECTOR);
                    }else
                        $DB = Zend_Db_Table::getDefaultAdapter();
                    ##FIN determinar server de conexi?n BD
                    ##############################################
                    
                            
                    
                    //con parametros
                    $S_PARAMETROS = $this->_request->getPost('FinNivelacion');
                                    
                    $matriz = explode("|",$S_PARAMETROS);
    
                    $Rut = $matriz[0];

        //LLEVARA REFERER
        $producto = ($this->_request->getPost('Prod') != "")?$this->_request->getPost('Prod'):"XXX";
        if(isset($config['bds'][$producto]))
            $_BASE_PRODUCTO=$config['bds'][$producto];
        else
            $_BASE_PRODUCTO=$config['bds']['defecto'];

                        $estado = 2;
        
                      //actualizo
                      $DB->beginTransaction();
                      try {
                                $data1 = array(
                                  'estadonivelacion' => $estado,
                                  'ultimomodulo'  => 'no empieza aun'
                                  );
                           
                                 $where1['rut = ?' ] =  $Rut;
                                 $n = $DB->update($_BASE_PRODUCTO.'tblacceso', $data1, $where1);
                                 
                                 $DB->commit();
                               
                                 echo "100|".$estado;
                          }
                          catch (Exception $e)
                          {
                                  $DB->rollBack();
                                  echo $e->getMessage();
                          }
                
                    
                    
    }

    public function cierramaterianivelAction()
    {
                        
            
              $this->_helper->layout->disableLayout();
               @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
              //configuraci?n inicial
              $config = Zend_Registry::get('config');
            
              //CONEXION

                ##INICIO determinar server de conexi?n BD
                ##############################################
                $VAR_HOST = explode(".",$_SERVER["HTTP_HOST"]);
                $VAR_HOST_ACTUAL=$VAR_HOST[0];
                if(isset($config['servidor'][$VAR_HOST_ACTUAL]))
                {
                   $CONNECTOR=$config['servidor'][$VAR_HOST_ACTUAL];
                   $DB = Zend_Registry::get($CONNECTOR);
                }else
                    $DB = Zend_Db_Table::getDefaultAdapter();
                ##FIN determinar server de conexi?n BD
                ##############################################
                
                    
              //con parametros
              $S_PARAMETROS = $this->_request->getPost('FinMateria');
                            
              $matriz = explode("|",$S_PARAMETROS);

              $Rut = $matriz[0];

        //LLEVARA REFERER
        $producto = ($this->_request->getPost('Prod') != "")?$this->_request->getPost('Prod'):"XXX";
        if(isset($config['bds'][$producto]))
            $_BASE_PRODUCTO=$config['bds'][$producto];
        else
            $_BASE_PRODUCTO=$config['bds']['defecto'];

                $estado = 3;
                    
              //actualizo
              $DB->beginTransaction();
              try {
                        $data1 = array(
                          'estadonivelacion'  => $estado,
                          'ultimomodulo' => 'no empieza aun'
                          );
                   
                         $where1['rut = ?' ] =  $Rut;
                         $n = $DB->update($_BASE_PRODUCTO.'tblacceso', $data1, $where1);
                         
                         $DB->commit();
                       
                         echo ("100|".$estado);
                  }
                  catch (Exception $e)
                  {
                          $DB->rollBack();
                          echo $e->getMessage();
                  }
            
    
    }

    public function terminatestAction()
    {
        // action body
                  
        $this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);

        //reemplazo webservice
        $functions = new ZendExt_Application_Resource_Ematpsuoper();

        $producto = ($this->_request->getPost('Prod') != "")?$this->_request->getPost('Prod'):"XXX";
        $S_PARAMETROS = $this->_request->getPost('TerminaTest');
        $respuesta_servicio = $functions->ws_terminatest($S_PARAMETROS,$producto);

        echo $respuesta_servicio;

    }

    public function reiniciomatAction()
    {
        
		
		//JA INICIO MANEJO DE SESIONES	
		$compumatSession = new Zend_Session_Namespace('Compumat');
		$SESSION_ACTIVA="";
		if(isset($compumatSession->ultima_session_logeada))
		{
			$SESSION_ACTIVA=$compumatSession->ultima_session_logeada;
		}
		//JA FIN MANEJO DE SESIONES	
			
		
		
		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('Reinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_reiniciomat($S_INICIOS,$S_PRODUCTO,$SESSION_ACTIVA);
	   
	   
	   //INICIO logs
	   $functions_logs = new ZendExt_Application_Resource_RutinasPsu();
	   $functions_logs->graba_logs("Ematpsuoper-reiniciomat",$S_PRODUCTO,$S_INICIOS."@@".$respuesta_servicio);
	   //FIN logs
	
	   
	   
	   echo $respuesta_servicio;
			
		
    }

    public function rellenapruebaAction()
    {
        
		// action body
    

	   	##RUTINA PARA CERRAR DIAGN�STICOS CON MAS DE 2 SEMANAS

		##LLAMADA DESDE CRON JOB DEL SERVER
	    include 'EmatpsudiagController.php';
	   
	    // action body
		$this->_helper->layout->disableLayout();
        $config = Zend_Registry::get('config');


	    ##INICIO determinar server de conexi?n BD
        ##############################################
        $VAR_HOST = explode(".", $_SERVER["HTTP_HOST"]);
        $VAR_HOST_ACTUAL = $VAR_HOST[0];
        if (isset($config['servidor'][$VAR_HOST_ACTUAL])) {
            $CONNECTOR = $config['servidor'][$VAR_HOST_ACTUAL];
            $DB = Zend_Registry::get($CONNECTOR);
        } else
            $DB = Zend_Db_Table::getDefaultAdapter();
        ##FIN determinar server de conexi?n BD
        ##############################################

		//LLEVARA REFERER
        $producto = ($this->_request->getParam('Prod') != "") ? $this->_request->getParam('Prod') : "XXX";
        if (isset($config['bds'][$producto]))
            $_BASE_PRODUCTO = $config['bds'][$producto];
        else
            $_BASE_PRODUCTO = $config['bds']['defecto'];

        // -----------------------------------------------------------

			

		############################################################
		############################################################
		 $sSQL="SELECT
				r.rut,
				r.npregunta,
				r.fecha,
				rs.ev04_vcIdPrueba,
        		LOWER(ep.codprueba) as codprueba,
				r.idprueba 
				FROM
				$_BASE_PRODUCTO "."tblacceso a
				INNER JOIN $_BASE_PRODUCTO "."minpruebas_respuestas r ON a.rut=r.rut
      			INNER JOIN e_test.pruebas ep on r.idprueba=ep.idprueba
      			LEFT JOIN $_BASE_PRODUCTO "."ev07_resultados rs ON r.rut=rs.ev03_vcRut and LOWER(ep.codprueba)=LOWER(rs.ev04_vcIdPrueba)
				WHERE ";
		
		if(isset($config['rellenapruebabasica']))
		{
			
			foreach($config['rellenapruebabasica'] as $clave => $valor) 
			{
 		   		$sSQL.=" a.curso <> '$valor' and ";
			}

		}
		
		
		$sSQL.=" r.npregunta=1 and
				rs.ev04_vcIdPrueba is null and
				DATE_ADD(r.fecha,INTERVAL 336 HOUR) < now() and ep.codprueba not like 'SEP%' and ep.codprueba not like 'D%' and ep.codprueba not like '%S' and ep.codprueba not like 'enc20%' and 
				CONCAT(a.institucion,'-',a.lista) not in (SELECT concat(fcd.institucion,'-',fcd.lista)
				FROM
				$_BASE_PRODUCTO "."filtro_cierre_diagnostico fcd) ";

				
		//year(r.fecha)=year(now()) and
		//echo "<br><br>".$sSQL."<br><br>";


		$CONT_ALUMNOS=0;
		$rowset = $DB->fetchAll($sSQL);
        foreach ($rowset as $row_datosQuery)
        {
        
			
				
			 $IDEN_PRUEBA=trim($row_datosQuery['idprueba']);	
			  
			  if(!isset($MATRIZ_PRUEBAS[$IDEN_PRUEBA]))
			  {
			  		$MATRIZ_PRUEBAS[$IDEN_PRUEBA]["codlargo"]=trim($row_datosQuery['codprueba']);	
			  		$MATRIZ_PRUEBAS[$IDEN_PRUEBA]["codcorto"]=substr(trim($row_datosQuery['codprueba']),0,2);	
			  }
			  
			  
			  $MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID["$CONT_ALUMNOS"]=$row_datosQuery['rut']."|".$row_datosQuery['idprueba'];

			  $CONT_ALUMNOS++;
        }
     	
		/*
		print_r($MATRIZ_PRUEBAS);
		echo "<br><br><br><br>";
	
		echo $sSQL."<br><br>";
		echo print_r($MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID);
		echo "<br><br>".$CONT_ALUMNOS;
		exit;
		*/
		//vamos a averigua la pregunta mayor contestada
		 
		 
		 
		 
		if(isset($MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID))
		{ 
	
				//sólo diagnósticos
				$sSQL4="SELECT
						p.idprueba,
						p.npregunta,
						p.respuesta_pregunta,
						LOWER(pp.codprueba) as codprueba  
						FROM
						e_test.preguntas_instancias p
						inner join
						e_test.pruebas pp on p.idprueba=pp.idprueba
						WHERE
						pp.codprueba not like 'SEP%' and pp.codprueba not like 'D%' and pp.codprueba not like '%S' and pp.codprueba not like 'enc20%' and pp.codprueba like 'P%'";
			
					
					$rowset4 = $DB->fetchAll($sSQL4);
					foreach ($rowset4 as $row_datosQuery4)
					{
						  $identi_resp=$row_datosQuery4['codprueba']."|".$row_datosQuery4['npregunta'];	
							
						  
						  if(!isset($MATRIZ_RESPUESTAS_CORRECTAS["$identi_resp"]))
						  {
					
						  		$MATRIZ_RESPUESTAS_CORRECTAS["$identi_resp"]=trim($row_datosQuery4['respuesta_pregunta']);
							
						  }
					}
	
			
				$sSQL2="SELECT
						r.rut,
						r.idprueba,
						max(r.npregunta) as maxipregunta,
						a.institucion,
						a.lista
						FROM
						$_BASE_PRODUCTO"."minpruebas_respuestas r
						INNER JOIN $_BASE_PRODUCTO"."tblacceso a ON r.rut=a.rut and r.codcol=a.institucion 
						WHERE
						a.curso <> '2 BASICO' and 
						CONCAT(r.rut,'|',r.idprueba) in ('".implode("','",$MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID)."') GROUP BY r.rut,r.idprueba";
				
				
				
					
					
					$rowset2 = $DB->fetchAll($sSQL2);
					foreach ($rowset2 as $row_datosQuery2)
					{
						  if(isset($MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codlargo"]))
						  {
					
						  		$identi=$row_datosQuery2['rut']."|".$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codlargo"];	
								
								$MATRIZ_MAX["$identi"]["RUT"]=$row_datosQuery2['rut'];
								$MATRIZ_MAX["$identi"]["PRUEBA"]=$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codlargo"];
								$MATRIZ_MAX["$identi"]["PRUEBACORTO"]=$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codcorto"];
								$MATRIZ_MAX["$identi"]["PREGUNTA"]=$row_datosQuery2['maxipregunta'];
								$MATRIZ_MAX["$identi"]["COLEGIO"]=$row_datosQuery2['institucion'];
								$MATRIZ_MAX["$identi"]["LISTA"]=$row_datosQuery2['lista'];
							
						  }
					}
				
		}
	
		
		
		$STRING_ACTUALIZADOS="";
		
		
		if(isset($MATRIZ_MAX))
		{
			
					foreach ($MATRIZ_MAX as $clave => $valor)
					{
			
						//define prueba
						$IDENPRO="diag".$producto;
			
			
						//si hay preguntas definidas para esta prueba	
						if(isset($config[$IDENPRO][$valor["PRUEBACORTO"]]))
						{
							//echo "p:".$config[$IDENPRO][$valor["PRUEBA"]]."|";
							
							$MAX_PREGUNTAS=$config[$IDENPRO][$valor["PRUEBACORTO"]];
							
							for($i=$valor["PREGUNTA"]+1;$i<=$MAX_PREGUNTAS;$i++)
							{
						
		
									$this->getRequest()->setPost('Prod',$producto);

								
									
									$identi_resp=trim($valor["PRUEBA"])."|".$i;	
									
									if(isset($MATRIZ_RESPUESTAS_CORRECTAS["$identi_resp"]))
										$RESPUESTA_CORRECTA=$MATRIZ_RESPUESTAS_CORRECTAS["$identi_resp"];
									else
										$RESPUESTA_CORRECTA="";
									
									$CADENA_ENVIO=trim($valor["PRUEBA"])."|".trim($valor["COLEGIO"])."|".trim($valor["LISTA"])."|".trim($valor["RUT"])."|".$i."||0|".$RESPUESTA_CORRECTA;
									
									
									$STRING_ACTUALIZADOS.=$CADENA_ENVIO."<br><br>";
									
									
									$this->getRequest()->setPost('PorPregunta',$CADENA_ENVIO);
									$a = new EmatpsudiagController($this->_request, $this->_response);
									$a->grabapreguntaAction();
								
							}//del for
							
							
						
							$this->getRequest()->setPost('Prod',$producto);
							$CADENA_ENVIO=trim($valor["RUT"])."|".trim($valor["PRUEBA"])."|";
							$this->getRequest()->setPost('CalculaResultados',$CADENA_ENVIO);
											
						
							//las pruebas
							//$las_pruebas = array("p32016","p42016","p52016","p62016","p72016","p82016","pm2016","p3f2016","p4f2016","p5f2016","p6f2016","p7f2016","p8f2016","pmf2016");
							//del año actual y el anterior cuando cambia de año
							$las_pruebas = array("p3".date("Y"),"p4".date("Y"),"p5".date("Y"),"p6".date("Y"),"p7".date("Y"),"p8".date("Y"),"pm".date("Y"),"p3f".date("Y"),"p4f".date("Y"),"p5f".date("Y"),"p6f".date("Y"),"p7f".date("Y"),"p8f".date("Y"),"pmf".date("Y"));


							if (in_array(trim($valor["PRUEBA"]),$las_pruebas))
							{
								$b = new EmatpsudiagController($this->_request, $this->_response);
							  	$b->calcdiagbasetestAction();
								$STRING_ACTUALIZADOS.=$CADENA_ENVIO."<br><br>";
							}
							else
							{
								  $b = new EmatpsudiagController($this->_request, $this->_response);
								  $b->calcdiagmedetestAction();
								  
								  $STRING_ACTUALIZADOS.=$CADENA_ENVIO."<br><br>";
						
							}
							
						}
			
			
					}
			
		
				
				echo "<br><br>ok!!!!!!!!!!!!!!!!!!!!<br><br>";
				
				

				
				
				
		}else
			echo "<br><br>Ejecutado sin datos<br><br>";





				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "From: Laboratorios <helpdesk@compumat.cl>\r\n";
				$headers .= "Return-path: helpdesk@compumat.cl\r\n";
				$today = date("d-m-Y H:i:s");
				mail("jalcaino@compumat.cl","Cierre automático diagnostico","ejecutado el $today <br><br>$STRING_ACTUALIZADOS",$headers);











    }

    public function creatutorgenericoAction()
    {


		// action body
	   	##RUTINA PARA CREAR TUTORES GENERICOS A TODOS LOS COLEGIOS
		##LLAMADA DESDE CRON JOB DEL SERVER

	    include 'EmatpsuadminController.php';



	    // action body
		$this->_helper->layout->disableLayout();
        $config = Zend_Registry::get('config');





	    ##INICIO determinar server de conexion BD
        ##############################################

        $VAR_HOST = explode(".", $_SERVER["HTTP_HOST"]);
        $VAR_HOST_ACTUAL = $VAR_HOST[0];

        if (isset($config['servidor'][$VAR_HOST_ACTUAL])) {
            $CONNECTOR = $config['servidor'][$VAR_HOST_ACTUAL];
            $DB = Zend_Registry::get($CONNECTOR);
        } else
            $DB = Zend_Db_Table::getDefaultAdapter();

        ##FIN determinar server de conexi?n BD
        ##############################################



		//LLEVARA REFERER

        $producto = ($this->_request->getParam('Prod') != "") ? $this->_request->getParam('Prod') : "XXX";
        if (isset($config['bds'][$producto]))
            $_BASE_PRODUCTO = $config['bds'][$producto];
        else
            $_BASE_PRODUCTO = $config['bds']['defecto'];

        // -----------------------------------------------------------


		//datos por defecto para tutor genèrico
		$S_RUT="98765432-5";
		$S_NOMBRE="TUTOR GENERAL";
		$S_MAIL="tutorgeneral@compumat.cl";

		$sSQL="select
				i.institucion as institucionpadre,
				a.institucion,
				t.institucion
				from
				".$_BASE_PRODUCTO."tblinstituciones i
				left join
				".$_BASE_PRODUCTO."tblacceso a on i.institucion=a.institucion and a.rut in ('".$S_RUT."')
				left join
				".$_BASE_PRODUCTO."tbltutores t on i.institucion=t.institucion and t.ruttutor in ('".$S_RUT."')
				where
				a.institucion is null and t.institucion is null
				GROUP BY
				i.institucion";




		$CONT_TUTORES=0;

		$rowset = $DB->fetchAll($sSQL);

        foreach ($rowset as $row_datosQuery)
        {

			  $MATRIZ_COLEGIOS_SIN_TUTOR_GENERICO["$CONT_TUTORES"]=$row_datosQuery['institucionpadre'];
			  $CONT_TUTORES++;

        }



		if(isset($MATRIZ_COLEGIOS_SIN_TUTOR_GENERICO))
		{

					foreach ($MATRIZ_COLEGIOS_SIN_TUTOR_GENERICO as $clave => $valor)
					{
							$S_COLEGIO=$valor;


							 try {


										echo $S_COLEGIO."|<br>";


										$this->getRequest()->setPost('Prod',$producto);
										$this->getRequest()->setPost('creauntutorp1',$S_COLEGIO);
										$this->getRequest()->setPost('creauntutorp2',$S_RUT);
										$this->getRequest()->setPost('creauntutorp3',$S_NOMBRE);
										$this->getRequest()->setPost('creauntutorp4',$S_MAIL);


										$b = new EmatpsuadminController($this->_request, $this->_response);

										$b->creauntutorAction();


    				              }
			                      catch (Exception $e)
			                      {
		                              echo $e->getMessage();

        			              }




					}//del for

		}//si hay tutores a crear


		$TUTORES_ASOCIADOS=0;
		//vamos a asociar el tutor gen{erico automáticamente a una lista
		//que no sea emat pero si simce o sep
		$sSQL="SELECT
				t.institucion,
				t.Lista,
				t.codigoLista,
				pp.prueba,
				ppp.prueba
				FROM
				".$_BASE_PRODUCTO."tbllistas t
				INNER JOIN ".$_BASE_PRODUCTO."tbllistas_productos_pruebas pp ON t.institucion=pp.institucion and t.codigoLista=pp.codigoLista and (pp.prueba='SEP' or pp.prueba='SIMCE')
				LEFT JOIN ".$_BASE_PRODUCTO."tbllistas_productos_pruebas ppp ON t.institucion=ppp.institucion and t.codigoLista=ppp.codigoLista and ppp.prueba='EMAT'
				WHERE
				(t.ruttutor is null or t.ruttutor = '') and ppp.prueba is null ";


		$rowset = $DB->fetchAll($sSQL);

        foreach ($rowset as $row_datosQuery)
        {

				$LA_INSTITUCION=$row_datosQuery['institucion'];
				$LA_LISTA=$row_datosQuery['Lista'];
				$EL_CODIGO_LISTA=$row_datosQuery['codigoLista'];


			  	$data = array('ruttutor' =>  $S_RUT);

                $where['institucion = ?' ] =  $LA_INSTITUCION;
				$where['Lista = ?' ] =  $LA_LISTA;
				$where['codigoLista = ?' ] =  $EL_CODIGO_LISTA;

                $n2 =  $DB->update($_BASE_PRODUCTO.'tbllistas', $data, $where);

				$TUTORES_ASOCIADOS++;

		}




				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "From: Laboratorios <helpdesk@compumat.cl>\r\n";
				$headers .= "Return-path: helpdesk@compumat.cl\r\n";
				$today = date("d-m-Y H:i:s");
				mail("jalcaino@compumat.cl","Creacion de tutores genericos","Un total de $CONT_TUTORES creados y $TUTORES_ASOCIADOS asociados, ejecutado el $today",$headers);



				echo "Creacion de tutores genericos . Un total de $CONT_TUTORES creados y $TUTORES_ASOCIADOS asociados, ejecutado el $today";







	

	
		
    }

    public function cambianivelbasicaAction()
    {

			//JA 15-12-2017
			//esta función una vez que todos los libros apunten a :
			//cambianivelgen será limpiada como una forma de no seguir utilizándola
			//revisar en abril del 2018



			$this->_helper->layout->disableLayout();
			@header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
			//configuraci?n inicial
			$config = Zend_Registry::get('config');
			
			//CONEXION

			##INICIO determinar server de conexi?n BD
			##############################################
			$VAR_HOST = explode(".",$_SERVER["HTTP_HOST"]);
			$VAR_HOST_ACTUAL=$VAR_HOST[0];
			if(isset($config['servidor'][$VAR_HOST_ACTUAL]))
			{
			   $CONNECTOR=$config['servidor'][$VAR_HOST_ACTUAL];
			   $DB = Zend_Registry::get($CONNECTOR);
			}else
				$DB = Zend_Db_Table::getDefaultAdapter();
			##FIN determinar server de conexi?n BD
			##############################################

					
			
			//con parametros
			$S_PARAMETROS = $this->_request->getPost('Cambianivel');
							
			$matriz = explode("|",$S_PARAMETROS);

			$Rut = $matriz[0];
			$Nivel = $matriz[1];
	
	
			//LLEVARA REFERER
			$producto = ($this->_request->getPost('Prod') != "")?$this->_request->getPost('Prod'):"XXX";
			if(isset($config['bds'][$producto]))
				$_BASE_PRODUCTO=$config['bds'][$producto];
			else
				$_BASE_PRODUCTO=$config['bds']['defecto'];


                    
			  //actualizo
			  $DB->beginTransaction();
			  try {
						$data1 = array(
						  'estadonivelacion' => $Nivel, 
						  'ultimomodulo' => 'no empieza aun'  
						 );

						 $where1['rut = ?' ] =  $Rut;
						 $n = $DB->update($_BASE_PRODUCTO.'tblacceso', $data1, $where1);
						 
						 $DB->commit();
					   
						 echo "100";
				  }
				  catch (Exception $e)
				  {
						  $DB->rollBack();
						  echo $e->getMessage();
				  }

	
    }

    public function leereiniciometaAction()
    {
        // action body
    
	
       $this->_helper->layout->disableLayout();
       @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('LeeReinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_leereiniciometa($S_INICIOS,$S_PRODUCTO);
	   
	   echo $respuesta_servicio;
		

	
    }

    public function reiniciometaAction()
    {
        // action body
    
	    //JA INICIO MANEJO DE SESIONES	
		$compumatSession = new Zend_Session_Namespace('Compumat');
		$SESSION_ACTIVA="";
		if(isset($compumatSession->ultima_session_logeada))
		{
			$SESSION_ACTIVA=$compumatSession->ultima_session_logeada;
		}
		//JA FIN MANEJO DE SESIONES	
	
		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('Reinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_reiniciometa($S_INICIOS,$S_PRODUCTO,$SESSION_ACTIVA);
	   
	   echo $respuesta_servicio;
	
	
	
    }

    public function limpiareiniciometaAction()
    {
        // action body
   		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('limpiareinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_limpiareiniciometa($S_INICIOS,$S_PRODUCTO);
	   
	   echo $respuesta_servicio;
		
    }

    public function respresulmetaAction()
    {
        // action body
   		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('resulmeta');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_respresulmeta($S_INICIOS,$S_PRODUCTO);
	   
	   echo $respuesta_servicio;
		
    }

    public function iniciaunidadAction()
    {
        // action body
   		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
      	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_UNIDAD = $this->_request->getPost('unidad');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_iniciaunidad($S_UNIDAD,$S_PRODUCTO);
	   
	   echo $respuesta_servicio;
		
    }

    public function leereiniciosepAction()
    {
        // action body
    
		$this->_helper->layout->disableLayout();
       @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('LeeReinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_leereiniciosep($S_INICIOS,$S_PRODUCTO);
	   
	   echo $respuesta_servicio;
		
	
	
    }

    public function reiniciosepAction()
    {
        // action body
    
		//JA INICIO MANEJO DE SESIONES	
		$compumatSession = new Zend_Session_Namespace('Compumat');
		$SESSION_ACTIVA="";
		if(isset($compumatSession->ultima_session_logeada))
		{
			$SESSION_ACTIVA=$compumatSession->ultima_session_logeada;
		}
		//JA FIN MANEJO DE SESIONES	
	
	
		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('Reinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_reiniciosep($S_INICIOS,$S_PRODUCTO,$SESSION_ACTIVA);
	   
	   echo $respuesta_servicio;
	
	
	
    }

    public function limpiareiniciosepAction()
    {
        // action body
		
		$this->_helper->layout->disableLayout();
        @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
       
	
	   //reemplazo webservice
	   $functions = new ZendExt_Application_Resource_Ematpsuoper();
	   
	   $S_INICIOS = $this->_request->getPost('limpiareinicio');
	   $S_PRODUCTO = $this->_request->getPost('Prod');	
			
	   $respuesta_servicio = $functions->ws_limpiareiniciosep($S_INICIOS,$S_PRODUCTO);
	   
	   echo $respuesta_servicio;
    }

    public function rellenapruebasepAction()
    {
    
	   
	    // action body
		$this->_helper->layout->disableLayout();
        $config = Zend_Registry::get('config');


	    ##INICIO determinar server de conexi?n BD
        ##############################################
        $VAR_HOST = explode(".", $_SERVER["HTTP_HOST"]);
        $VAR_HOST_ACTUAL = $VAR_HOST[0];
        if (isset($config['servidor'][$VAR_HOST_ACTUAL])) {
            $CONNECTOR = $config['servidor'][$VAR_HOST_ACTUAL];
            $DB = Zend_Registry::get($CONNECTOR);
        } else
            $DB = Zend_Db_Table::getDefaultAdapter();
        ##FIN determinar server de conexi?n BD
        ##############################################

		//LLEVARA REFERER
        $producto = ($this->_request->getParam('Prod') != "") ? $this->_request->getParam('Prod') : "XXX";
        if (isset($config['bds'][$producto]))
            $_BASE_PRODUCTO = $config['bds'][$producto];
        else
            $_BASE_PRODUCTO = $config['bds']['defecto'];

        // -----------------------------------------------------------

		
		############################################################
		############################################################
		
			 $sSQL="SELECT
					r.rut,
					r.npregunta,
					r.fecha,
					LOWER(ep.codprueba) as codprueba,
					r.idprueba,
					ep.npreguntas,
					rs.idprueba as prueba_no_terminada  
					FROM
					".$_BASE_PRODUCTO."tblacceso a
					INNER JOIN ".$_BASE_PRODUCTO."minpruebas_respuestas r ON a.rut=r.rut and r.codcol=a.institucion
					INNER JOIN e_test.pruebas ep on r.idprueba=ep.idprueba
					LEFT JOIN ".$_BASE_PRODUCTO."minpruebas_respuestas rs ON r.rut=rs.rut and r.codcol=rs.codcol and ep.npreguntas=rs.npregunta and r.idprueba=rs.idprueba
					WHERE
					r.npregunta=1 and
					rs.idprueba is null and
					year(r.fecha)=year(now()) and
					(ep.codprueba like 'SEPH0%' or
					ep.codprueba like 'SEPL0%' or
					ep.codprueba like 'SEPM0%' or
					ep.codprueba like 'SEPN0%') and
					ep.codprueba not like 'D%' and
					ep.codprueba not like '%S' and
					ep.codprueba not like 'enc20%' ";
				


//	DATE_ADD(r.fecha,INTERVAL 1 HOUR) < now() and
//DATE_ADD(r.fecha,INTERVAL 3 HOUR) < now() and


		// echo "<br><br>query1:".$sSQL."<br><br>";
		//mayor a 336 horas = 2 semanas = 14 días

		//exit;




		$CONT_ALUMNOS=0;
		$rowset = $DB->fetchAll($sSQL);
        foreach ($rowset as $row_datosQuery)
        {
        
			
				
			 $IDEN_PRUEBA=trim($row_datosQuery['idprueba']);	
			  
			  if(!isset($MATRIZ_PRUEBAS[$IDEN_PRUEBA]))
			  {
			  		$MATRIZ_PRUEBAS[$IDEN_PRUEBA]["codlargo"]=trim($row_datosQuery['codprueba']);	
			  		$MATRIZ_PRUEBAS[$IDEN_PRUEBA]["codcorto"]=substr(trim($row_datosQuery['codprueba']),0,2);	
			  		$MATRIZ_PRUEBAS[$IDEN_PRUEBA]["npreguntas"]=trim($row_datosQuery['npreguntas']);	
			 		$MATRIZ_PRUEBAS[$IDEN_PRUEBA]["idprueba"]=trim($row_datosQuery['idprueba']);	
			  }
			  
			  
			  $MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID["$CONT_ALUMNOS"]=$row_datosQuery['rut']."|".$row_datosQuery['idprueba'];

			  $CONT_ALUMNOS++;
        }
     	
		/*
		print_r($MATRIZ_PRUEBAS);
		echo "<br><br><br><br>";
	
		echo $sSQL."<br><br>";
		echo print_r($MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID);
		echo "<br><br>".$CONT_ALUMNOS;
		exit;
		*/
		
		//vamos a averigua la pregunta mayor contestada
		 
		if(isset($MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID))
		{ 
	
	
					// no se usa
					/*echo $sSQL4."<br><br>";
					print_r($MATRIZ_RESPUESTAS_CORRECTAS);
					exit;*/
	
				
				$sSQL2="SELECT
						r.rut,
						r.idprueba,
						max(r.npregunta) as maxipregunta,
						a.institucion,
						a.lista
						FROM
						$_BASE_PRODUCTO"."minpruebas_respuestas r
						INNER JOIN $_BASE_PRODUCTO"."tblacceso a ON r.rut=a.rut and r.codcol=a.institucion 
						WHERE
						CONCAT(r.rut,'|',r.idprueba) in ('".implode("','",$MATRIZ_ALUMNOS_PENDIENTES_PRUEBA_ID)."') GROUP BY r.rut,r.idprueba";
				
				
				
					//echo $sSQL2."<br><br>";
					//exit;
					
					//echo "<br><br>$sSQL2<br><br>";		
			
					
					$rowset2 = $DB->fetchAll($sSQL2);
					foreach ($rowset2 as $row_datosQuery2)
					{
						  if(isset($MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codlargo"]))
						  {
					
						  		$identi=$row_datosQuery2['rut']."|".$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codlargo"];	
								
								$MATRIZ_MAX["$identi"]["RUT"]=$row_datosQuery2['rut'];
								$MATRIZ_MAX["$identi"]["PRUEBA"]=$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codlargo"];
								$MATRIZ_MAX["$identi"]["PRUEBACORTO"]=$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["codcorto"];
								$MATRIZ_MAX["$identi"]["IDPRUEBA"]=$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["idprueba"];
								$MATRIZ_MAX["$identi"]["PREGUNTA"]=$row_datosQuery2['maxipregunta'];
								$MATRIZ_MAX["$identi"]["COLEGIO"]=$row_datosQuery2['institucion'];
								$MATRIZ_MAX["$identi"]["LISTA"]=$row_datosQuery2['lista'];
								$MATRIZ_MAX["$identi"]["NUMPREGUNTASPRUEBA"]=$MATRIZ_PRUEBAS[$row_datosQuery2['idprueba']]["npreguntas"];
							
							
						  }
					}
				
		}
	
		
		//print_r($MATRIZ_MAX);
		//exit;
		
		
		$STRING_ACTUALIZADOS="";
		
		
		if(isset($MATRIZ_MAX))
		{
			
					foreach ($MATRIZ_MAX as $clave => $valor)
					{
			
						//define prueba
						$IDENPRO=$valor["IDPRUEBA"];
						/*
						echo $config[$IDENPRO][$valor["PRUEBACORTO"]];
						exit;
						*/
						
						//si hay preguntas definidas para esta prueba	
						if(isset($MATRIZ_PRUEBAS[$IDENPRO]["npreguntas"]))
						{
						
							//echo "p:".$config[$IDENPRO][$valor["PRUEBA"]]."|";
							
							$MAX_PREGUNTAS=$MATRIZ_PRUEBAS[$IDENPRO]["npreguntas"];
							
							for($i=$valor["PREGUNTA"]+1;$i<=$MAX_PREGUNTAS;$i++)
							{
									
								
									$RESPUESTA_CORRECTA="";
									
									
									$CADENA_ENVIO=trim($valor["PRUEBA"])."|".trim($valor["COLEGIO"])."|".trim($valor["LISTA"])."|".trim($valor["RUT"])."|".$i."||1|".$RESPUESTA_CORRECTA;
									
									$STRING_ACTUALIZADOS.=$CADENA_ENVIO."<br><br>";
									
									//insertamos respuesta
									$sSQL4 = "INSERT INTO 
												".$_BASE_PRODUCTO."minpruebas_respuestas 
												(codcol, 
												lista, 
												idprueba, 
												npregunta, 
												rut, 
												respuesta, 
												fecha,
												instancia,
												respuesta_correcta) 
												VALUES 
												('".trim($valor["COLEGIO"])."',
												'".trim($valor["LISTA"])."',
												'".trim($IDENPRO)."',
												'".$i."',
												'".trim($valor["RUT"])."',
												'',
												now(),'0','cerradoxtiempo')";
					
								
									echo $sSQL4."<br><br>";
								
									$DB->getConnection();
									$DB->beginTransaction();
								
									try {
											$DB->query($sSQL4);
											$DB->commit();
										
											echo "OK insertada $CADENA_ENVIO <br>";
										
									}  catch (Exception $e) {
	
											$error = $e->getMessage();
											$DB->rollBack();
											
											echo $error."<br>";
											
										    $headers = "MIME-Version: 1.0\r\n";
											$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
											$headers .= "From: Laboratorios <helpdesk@compumat.cl>\r\n";
										  	$headers .= "Return-path: helpdesk@compumat.cl\r\n";
										  	$today = date("d-m-Y H:i:s");
										  	mail("jalcaino@compumat.cl","Error cierre automático pruebas SEP (Pregunta)","ejecutado el $today <br><br>ERROR:".$e->getMessage()."",$headers);

	
									
									}
																			
									$DB->closeConnection();
									
								
							}//del for
							
							
			
							  //update a los datos reiniciosep del alumno
							  //actualizo
							  
							  $DB->beginTransaction();
							  try {
										$data1 = array(
										  'marcasep' => '0',
										  'datosreinicsep' => ''
										  );
								   
										 $where1['rut = ?' ] =  trim($valor["RUT"]);
										 $n = $DB->update($_BASE_PRODUCTO.'tblacceso', $data1, $where1);
										 
										 $DB->commit();
									   
									   	 echo "OK actualización rut: ".trim($valor["RUT"])."<br>";
										 
									   
								  }
								  catch (Exception $e)
								  {
										  $error = $e->getMessage();
										  $DB->rollBack();
								  		
										  echo $error."<br>";	
																  
										  $headers = "MIME-Version: 1.0\r\n";
										  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
										  $headers .= "From: Laboratorios <helpdesk@compumat.cl>\r\n";
										  $headers .= "Return-path: helpdesk@compumat.cl\r\n";
										  $today = date("d-m-Y H:i:s");
										  mail("jalcaino@compumat.cl","Error cierre automático pruebas SEP (alumno)","ejecutado el $today <br><br>ERROR:".$error."",$headers);
								  
								  
								  }
								
								
								
						}
			
			
					}
			
		
					
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "From: Laboratorios <helpdesk@compumat.cl>\r\n";
				$headers .= "Return-path: helpdesk@compumat.cl\r\n";
				$today = date("d-m-Y H:i:s");
				mail("jalcaino@compumat.cl","Cierre automático pruebas SEP (OK)","ejecutado el $today <br><br>$STRING_ACTUALIZADOS",$headers);

				
				
		}else{
			
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "From: Laboratorios <helpdesk@compumat.cl>\r\n";
				$headers .= "Return-path: helpdesk@compumat.cl\r\n";
				$today = date("d-m-Y H:i:s");
				mail("jalcaino@compumat.cl","Cierre automático pruebas SEP (SIN DATOS)","ejecutado el $today <br><br>$STRING_ACTUALIZADOS",$headers);


			  }
	
	
	
    }

    
	
	
	
	
	
	
	public function cambianivelgenAction()
    {
       				// action body
			
			        $this->_helper->layout->disableLayout();
                    @header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
                    //configuraci?n inicial
                    $config = Zend_Registry::get('config');
                    
                    //CONEXION

                    ##INICIO determinar server de conexi?n BD
                    ##############################################
                    $VAR_HOST = explode(".",$_SERVER["HTTP_HOST"]);
                    $VAR_HOST_ACTUAL=$VAR_HOST[0];
                    if(isset($config['servidor'][$VAR_HOST_ACTUAL]))
                    {
                       $CONNECTOR=$config['servidor'][$VAR_HOST_ACTUAL];
                       $DB = Zend_Registry::get($CONNECTOR);
                    }else
                        $DB = Zend_Db_Table::getDefaultAdapter();
                    ##FIN determinar server de conexi?n BD
                    ##############################################




					//LLEVARA REFERER
					$producto = ($this->_request->getPost('Prod') != "")?$this->_request->getPost('Prod'):"XXX";
					if(isset($config['bds'][$producto]))
						$_BASE_PRODUCTO=$config['bds'][$producto];
					else
						$_BASE_PRODUCTO=$config['bds']['defecto'];



					  $datosMat =  $this->_request->getPost('Cambianivel');
					  $arrRefer = explode("|",$datosMat);
					 
					 if (sizeof($arrRefer) > 1) 
					 {
						$elrut=  $arrRefer[0];
						$producto = $arrRefer[1];
						$acualnivel = $arrRefer[2];    // segun producto

					 }else{
							echo "-1|Faltan datos";
					 }
				


					$inicial = array('prediag' => 0,'a-diag' => 1, 'en-diag' => 2,'nivel' => 3, 'compl' => 4);
					$emat = array('diag' => 0,'recup' => 1, 'nivel' => 2,'compl' => 3);
					$mediapsu = array('diag' => 0,'recup' => 1, 'nivel' => 2,'compl' => 3);
					
					
					switch ($producto) {
						case "inicial":
							$estadoniv = $inicial[$acualnivel];
							break;
						case "emat":
							$estadoniv = $emat[$acualnivel];
							break;
						case "mediapsu":
							$estadoniv = $mediapsu[$acualnivel];
							break;
						default:
							$estadoniv=x;
					}


                  $DB->beginTransaction();
                  try {
                            $data1 = array(
                              'estadonivelacion'  => $estadoniv,
                              'ultimomodulo' => 'no empieza aun',
                              'marca' => '0',
							  'datosreinic' => ''
							  );
                       
                             $where1['rut = ?' ] =  $elrut;
                             $n = $DB->update($_BASE_PRODUCTO.'tblacceso', $data1, $where1);
                             
                             $DB->commit();
                           
                             echo "100|".$elrut;
                      }
                      catch (Exception $e)
                      {
                              $DB->rollBack();
                              echo $elrut." ".$producto." ".$acualnivel." "."014-Error grabado respta".$e->getMessage();
                      }

	
	
	}


}