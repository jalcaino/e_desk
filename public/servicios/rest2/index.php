<?php
include "config.php";
include "utils.php";


$dbConn =  connect($db);

/*
  listar todos los alumnos o uno solo
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['rut']))
    {
      //Mostrar un alumno
      $sql = $dbConn->prepare("SELECT rut, nombres, apellidos FROM tblacceso where rut=:rut");
      $sql->bindValue(':rut', $_GET['rut']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar todos los alumnos
      $sql = $dbConn->prepare("SELECT rut, nombres, apellidos FROM tblacceso");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}
// Crear un nuevo registro de alumno
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $rut = $input['rut'];
    $institucion = $input['institucion'];
    $nombres = $input['nombres'];
    $apellidos = $input['apellidos'];
    $detalle = $input['detallesnivelacion'];
    

    $sql = "INSERT INTO tblacceso
          (rut, institucion, nombres, apellidos, detallesnivelacion)
          VALUES
          ('$rut', '$institucion', '$nombres', '$apellidos','$detalle')";
    $statement = $dbConn->prepare($sql);

  //  var_dump(bindAllValues($statement, $input));
    //exit();
  //  bindAllValues($statement, $input);
    $statement->execute();
      header("HTTP/1.1 200 OK");
      exit();
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
  $rut = $_GET['rut'];
  $statement = $dbConn->prepare("DELETE FROM tblacceso where rut=:rut");
  $statement->bindValue(':rut', $rut);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}

//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $rut = $input['rut'];
    $fields = getParams($input);

    $sql = "
          UPDATE tblacceso
          SET $fields
          WHERE rut='$rut'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}



//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>