<?php
session_start();
require_once('db.php'); 
$username = $_POST['username'];
$contrasena = $_POST['password'];
$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  

$cliente = $_GET['id']; 
	if(!$conexion){
 		die( print_r( sqlsrv_errors(), true));
 	}
 	$exito = 0;
	$query2 = "SELECT us_desc     
  	FROM us_mstr 
   	WHERE us_type = 'APOLO'
	AND us_pass = '".$contrasena."'"."
	AND us_id = '".$username."'";

	$registros2 = sqlsrv_query($conexion, $query2);
	while($row2 = sqlsrv_fetch_object($registros2)){
     $_SESSION["usid"] = $username;
     $exito= 1;                         
	}
	if ($exito == 1 ){
		header("Location: pedidosporvalidar.php");
	} else {
		header("Location: index.php");
	}
?>
