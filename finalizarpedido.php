<?php
session_start();
$ordencompra = $_GET['id'];
$usuario ="capptus";
$pass ="Encuentro1";
$servidor ="SERVER-PC";
$basedatos ="lucembarques";
$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  

if(!$conexion){

 die( print_r( sqlsrv_errors(), true));

 }
 
 	$query1 = "UPDATE so_hist set so_status = 'FI' WHERE so_id = '".$ordencompra."'";
	sqlsrv_query($conexion, $query1);
	
		header("Location: pedidosporvalidar.php");
?>