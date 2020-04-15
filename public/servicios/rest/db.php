<?php
/*
Author: Javed Ur Rehman
Website: https://www.allphptricks.com
*/

// Enter your Host, username, password, database below.
// I left password empty because i do not set password on localhost.
/*
$enlace =  mysql_connect("localhost","compumat","#$C0mp2008$#");

	if (!$enlace) {

		die('No pudo conectarse para reemplazar html en preguntas...<br>: '.mysql_error().'<br<<br>');

	}


	mysql_select_db("helpdesk1");
*/

	$con = mysqli_connect('localhost','compumat','#$C0mp2008$#','servicios_rest');
	if (mysqli_connect_errno()){
		echo "Failed1 to connect to MySQL: " . mysqli_connect_error();
		die();
		}


?>