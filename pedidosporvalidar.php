<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/light-bootstrap-dashboard.css" rel="stylesheet"/>
<link href="css/facapolo.css" rel="stylesheet" />
<title>APOLO - Validacion</title>
<link rel="shortcut icon" href="img/ap.ico">
<body background="img/empacar.jpg" style="background-size: 100%; ">

<?php
session_start();
require_once('db.php'); 
$usid = $_SESSION["usid"];
$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  

//$cliente = $_GET['id']; 
if(!$conexion){
 die( print_r( sqlsrv_errors(), true));
 }

/*
 $query2 = "SELECT distinct sod_so_id, sod_pt_id
 FROM sod_det, so_hist
 WHERE so_status = 'AL' AND sod_status = 'x'
 AND sod_so_id = so_id";
 $registros2 = sqlsrv_query($conexion, $query2); 
*/
  $query2 = "select so_ped,so_id, so_cm_id,  cm_name, MAX (total) as total , MAX (validado) as validado
from (
SELECT  so_ped,so_id, so_cm_id,  cm_name, COUNT (sod_pt_id) as total, 0 as validado
 FROM cm_mstr, so_hist, sod_det
 WHERE so_status = 'AL' 
 AND so_cm_id = cm_id2
 AND so_id = sod_so_id
 GROUP by so_ped,so_id, so_cm_id,  cm_name
 UNION
 SELECT  so_ped,so_id, so_cm_id,  cm_name, 0 as total, COUNT (sod_pt_id) as validado
 FROM cm_mstr, so_hist, sod_det
 WHERE so_status = 'AL' 
 AND so_cm_id = cm_id2
 AND so_id = sod_so_id
 and sod_status = 'V'
 GROUP by so_ped,so_id, so_cm_id,  cm_name
 ) as tmp
 GROUP by so_ped,so_id, so_cm_id,  cm_name";

 $registros2 = sqlsrv_query($conexion, $query2); 
/*
$query1 = "UPDATE so_hist set so_us_id = '".$_SESSION["usid"]."' WHERE so_cm_id = '".$cliente."'";
sqlsrv_query($conexion, $query1);
$query2 = "SELECT sod_so_id, sod_pt_id    
FROM so_hist, sod_det 
WHERE so_status = 'x'	
AND sod_so_id = so_i
group by  so_id, so_type;
having COUNT(sod_pt_id) - COUNT(sod_status)=0;
"*/

?>

<header>
 	<img class="img-responsive img-center" src="img/apolo1.jpg">
</header>
            
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Validación de Pedidos</h4>
                        <p class="category"><?php include ("fecha.php") ?></p>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                              <th># ORDEN DE COMPRA</th>
                              <th>ALMACEN</th>
                              <th></th>
                            </thead>
                            <tbody>
                            	<?php  
                              	while($row2 = sqlsrv_fetch_object($registros2)){
                                  $estilo = "oculto";
                                  if ($row2->total == $row2->validado) {
                                    # code...
                                    $estilo = "";
                                  }
                 			            echo "<tr>";     
                                  echo "<td><a href='validarpedido.php?id=".$row2->so_id."&ord=".$row2->so_ped."'>".$row2->so_ped."</a></td>";
                                  echo "<td>".$row2->cm_name."</td>";
                                  echo "<td><a class = '".$estilo." btn btn-info' href='finalizarpedido.php?id=".$row2->so_id."'>Finalizar</a></td>";
                                  echo "</tr>";  
                             	  }  
                              ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
          <a href="logicaLogout.php" class= 'btn btn-info'> Cerrar Sesion 
            <span class='glyphicon glyphicon-log-out' aria-hidden='true'></span>
          </a>
    </div>
</section>