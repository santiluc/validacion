<?php
session_start();
require_once('db.php'); 
$ordencompra = $_GET['id'];
$articulo = $_GET['pt'];
$cantidad = $_GET['cnt'];
$numorden = $_GET['numorden'];
$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  

if(!$conexion){

 die( print_r( sqlsrv_errors(), true));

 }
 
 	$query1 = "UPDATE sod_det set sod_status = 'V', sod_qty_emb = ".$cantidad." , sod_subtotal = sod_unitprice * ".$cantidad." * (1 - (sod_discval/100)) , sod_total = ((sod_unitprice * ".$cantidad.") * (1 - (sod_discval/100))) * (1 + (sod_taxval/100)) WHERE sod_so_id = '".$ordencompra."' AND sod_pt_id = '".$articulo."'";
	sqlsrv_query($conexion, $query1);
	header("location: validarpedido.php?id=".$ordencompra."&ord=".$numorden);
?>
