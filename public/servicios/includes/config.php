<?php

	/////////////LECTURA DE APPLICATION.INI CONECCION BD/////////////////
	//POR DEFECTO RED INTERNA EN CASO DE FALLA
	$nombre_carpeta=$_SERVER['DOCUMENT_ROOT'];
	$nombre_carpeta_nueva=explode("public",$nombre_carpeta);
	$nombre_fichero=$nombre_carpeta_nueva[0]."application/configs/application.ini";
	
	
	$CONT_PARAM_CONEX=0;	
	if (file_exists($nombre_fichero)) 
	{
			$names=file($nombre_fichero);
		
			foreach($names as $name)
			{
			   $pos1 = stripos($name,".host");
			   if ($pos1 !== false) 
			   {
				   $LINEA_CONEXION=trim($name);
			   	   $CONT_PARAM_CONEX++;	
			   }
				
			   $pos2 = stripos($name,".username");
			   if ($pos2 !== false) 
			   {
				   $LINEA_USUARIO=trim($name);
			   	   $CONT_PARAM_CONEX++;	
			   }
		
			   $pos3 = stripos($name,".password");
			   if ($pos3 !== false) 
			   {
				   $LINEA_PASSWORD=trim($name);
			   	   $CONT_PARAM_CONEX++;
			   }
			
				//si ya estàn los 3 paràmetros sale del for
				if($CONT_PARAM_CONEX>2) break;
			
			}


			$LINEA_CONEXION_ARREGLO=explode("=",$LINEA_CONEXION);		
			if(isset($LINEA_CONEXION_ARREGLO[1]))
			{
				$FLAG_CONEXION=trim($LINEA_CONEXION_ARREGLO[1]);
			}


			$LINEA_USUARIO_ARREGLO=explode("=",$LINEA_USUARIO);		
			if(isset($LINEA_USUARIO_ARREGLO[1]))
			{
				$FLAG_USUARIO=trim($LINEA_USUARIO_ARREGLO[1]);
			}


			$LINEA_PASSWORD_ARREGLO=explode("=",$LINEA_PASSWORD);		
			if(isset($LINEA_PASSWORD_ARREGLO[1]))
			{
				$FLAG_PASSWORD=trim($LINEA_PASSWORD_ARREGLO[1]);
			}


	}



$db = [
    'host' => $FLAG_CONEXION,
    'username' => $FLAG_USUARIO,
    'password' => $FLAG_PASSWORD,
    'db' => 'e_desk'
];







		##INICIO LISTADO DE BASES DE DATOS y tablas a actualizar con filtro de campos
		##INICIO LISTADO DE BASES DE DATOS y tablas a actualizar con filtro de campos
		##INICIO LISTADO DE BASES DE DATOS y tablas a actualizar con filtro de campos
		
		$MATRIZ_BDS["compumat_informes_min"]["alumno_indicadores_mensuales"]="id_institucion='RBD'";
		$MATRIZ_BDS["compumat_informes_min"]["alumno_indicadores_mensuales_psu"]="id_institucion='RBD'";
		$MATRIZ_BDS["compumat_informes_min"]["alumno_indicadores_quincenales"]="id_institucion='RBD'";
		$MATRIZ_BDS["compumat_informes_min"]["alumno_indicadores_quincenales_psu"]="id_institucion='RBD'";
		$MATRIZ_BDS["compumat_informes_min"]["alumno_indicadores_semanales"]="institucion='RBD'";
	

		/*
		$MATRIZ_BDS["emat"]["tbl_alumno_actividades"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_alumno_diagnostico"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_alumno_respuestas"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_alumnos"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_contenido_unidad"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_ingresos"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_instituciones"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_listas"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_plan"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_plan_autonomo"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_preguntas"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_registroip_alumno"]="id_institucion='RBD'";
		$MATRIZ_BDS["emat"]["tbl_tutores"]="id_institucion='RBD'";
		*/
		
		$MATRIZ_BDS["registromin_local"]["ev07_resultados"]=" (ev07_dIngreso > DATE_ADD(now(),INTERVAL -4 WEEK) or ev07_dTermino > DATE_ADD(now(),INTERVAL -4 WEEK)) and ev03_vcRut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["ev08_resultados_final"]="ev08_vcIdInstitucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["material_guias_respuestas"]=" fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["material_guias_resultado_alumno"]=" fecha_termino > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["minpruebas_respuestas"]=" fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["tbl_trab_dirigido"]="codigocol='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbl_trab_dirigido_eliminado"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tblacceso"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tblacceso_trab_dirigido"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tblinstituciones"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllistas"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllistas_notas"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllistas_productos_pruebas"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllistas_reprogramacion"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllistas_revision"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllistas_unidades"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllistas_unidades_historial"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tbllogin"]=" fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["tbllogin_session_multiple"]=" rut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["tblregistro"]=" (ingreso > DATE_ADD(now(),INTERVAL -4 WEEK) or termino > DATE_ADD(now(),INTERVAL -4 WEEK)) and rut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["tblregistro_detalle"]=" rut in (select rut from loc_registromin_local.tblacceso where institucion='RBD')";
		$MATRIZ_BDS["registromin_local"]["tbltutores"]="institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tblusuarios"]="id_institucion='RBD'";
		$MATRIZ_BDS["registromin_local"]["tblvisitas_tutores"]=" visita_fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and RBD='RBD'"; 
		
		$MATRIZ_BDS["registromin_psu"]["ev07_resultados"]=" (ev07_dIngreso > DATE_ADD(now(),INTERVAL -4 WEEK) or ev07_dTermino > DATE_ADD(now(),INTERVAL -4 WEEK)) and ev03_vcRut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["ev08_resultados_final"]="ev08_vcIdInstitucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["material_guias_respuestas"]=" fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["material_guias_resultado_alumno"]=" fecha_termino > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["minpruebas_respuestas"]=" fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["tbl_trab_dirigido"]="codigocol='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tbl_trab_dirigido_eliminado"]="institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tblacceso "]="institucion='RBD'";
		$MATRIZ_BDS["registromin_psu"]["tblacceso_trab_dirigido"]="institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tblinstituciones"]="institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tbllistas_productos_pruebas"]="institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tbllistas_reprogramacion"]="institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tbllistas_revision"]="institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tbllogin"]=" fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and rut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["tbllogin_session_multiple"]=" rut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["tblregistro"]=" (ingreso > DATE_ADD(now(),INTERVAL -4 WEEK) or termino > DATE_ADD(now(),INTERVAL -4 WEEK)) and rut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["tblregistro_detalle"]=" rut in (select rut from loc_registromin_psu.tblacceso where institucion='RBD')"; 
		$MATRIZ_BDS["registromin_psu"]["tbltutores"]="institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tblusuarios"]="id_institucion='RBD'"; 
		$MATRIZ_BDS["registromin_psu"]["tblvisitas_tutores"]=" visita_fecha > DATE_ADD(now(),INTERVAL -4 WEEK) and RBD='RBD'"; 
		

		##FIN LISTADO DE BASES DE DATOS y tablas a actualizar con filtro de campos
		##FIN LISTADO DE BASES DE DATOS y tablas a actualizar con filtro de campos
		##FIN LISTADO DE BASES DE DATOS y tablas a actualizar con filtro de campos


		//FERIADOS CONSTANTES
		//FERIADOS CONSTANTES
		//FERIADOS CONSTANTES
		//FERIADOS CONSTANTES
		$FERIADOS["0101"]="Ano nuevo";
		$FERIADOS["0105"]="Dia del trabajo";
		$FERIADOS["2105"]="Dia Glorias Navales";
		$FERIADOS["1809"]="Fiestas Patrias";
		$FERIADOS["1909"]="Dia Glorias Ejercito";
		$FERIADOS["1210"]="Encuentro de dos mundos";
		$FERIADOS["0111"]="Dia de todos los santos";
		$FERIADOS["0812"]="Inmaculada Concepcion";
		$FERIADOS["2512"]="Navidad";
		

?>