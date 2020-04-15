<?php
	
	header("Content-Type:application/json");
	
	include "../includes/config.php";
	include "../includes/utils.php";

	$dbConn =  connect($db);


	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
	
    	$VAR_RBD = $_REQUEST["rbd"];
    	$VAR_TIPO = $_REQUEST["tipo"];
		$VAR_FECHA_LOCAL = $_REQUEST["fecha_local"];
		$VAR_ES_DISTRIBUIDO = $_REQUEST["es_distribuido"];
		$VAR_UNA_ACTUALIZACION = $_REQUEST["una_actualizacion"];
	
		$VAR_FECHA_PRO = date("ymdhis");

		$STATUS="OK_REG_".$VAR_TIPO;

		
		$SQL = "INSERT INTO ED19_REGISTRO_ACTUALIZACION_LOCAL(SIS03_LABORATORIOID,ED19_TIPO,ED19_ES_DISTRIBUIDO,ED19_UNA_ACTUALIZACION_POR_DIA,ED19_FECHAHORA_LOCAL,ED19_FECHAHORA_SERVIDOR_WEB) VALUES ('$VAR_RBD','$VAR_TIPO','$VAR_ES_DISTRIBUIDO','$VAR_UNA_ACTUALIZACION','$VAR_FECHA_LOCAL','$VAR_FECHA_PRO')";
	
		try {


				$statement = $dbConn->prepare($SQL);
				$statement->execute();
	   			
				
				$json_response = json_encode($STATUS);
				echo $json_response;
				exit;

	
		} catch (Exception $e) {
	
	
	
				$json_response = json_encode("ERROR INSERT:".$e->getMessage());
				echo $json_response;
				exit;

		}
	
	
	}else{
	
	
				$STATUS="METODO INCORRECTO";
				$json_response = json_encode($STATUS);
				echo $json_response;
				exit;
	
	}
?>